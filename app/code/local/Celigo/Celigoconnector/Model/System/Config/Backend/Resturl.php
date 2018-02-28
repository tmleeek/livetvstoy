<?php

class Celigo_Celigoconnector_Model_System_Config_Backend_Resturl extends Mage_Core_Model_Config_Data {
    const XML_PATH_NETSUITE_EMAIL = 'celigoconnector/nsdetails/nsemail';
    const XML_PATH_NETSUITE_PASSWORD = 'celigoconnector/nsdetails/nspassword';
    const XML_PATH_NETSUITE_ROLE = 'celigoconnector/nsdetails/nsrole';
    const XML_PATH_NETSUITE_ACCOUNT = 'celigoconnector/nsdetails/nsaccountid';
    const XML_PATH_NETSUITE_ENVIRONMENT = 'celigoconnector/nsdetails/nsenvironment';
    const XML_PATH_RESTLET_URL = 'celigoconnector/othersettings/restleturl';
    const LOG_FILENAME = 'celigo-connector-adminhtml.log';
    /**
     * Change secure/unsecure base_url after use_custom_url was modified
     *
     * @return Mage_Adminhtml_Model_System_Config_Backend_Admin_Custom
     */
    public function _afterSave() {
        $storeId = '';
        $websiteId = '';
        $params = Mage::app()->getRequest()->getParams();
        unset($params['key']);
        if (isset($params['store']) && $params['store'] != '') {
            $storeId = Mage::app()->getStore($params['store'])->getId();
        }
        if (isset($params['website']) && $params['website'] != '') {
            $websiteId = Mage::app()->getWebsite($params['website'])->getId();
            $websiteId = $params['website'];
        }
        $nsemail = Mage::helper('celigoconnector')->getConfigValue(self::XML_PATH_NETSUITE_EMAIL, $storeId, $websiteId);
        $nspassword = Mage::helper('celigoconnector')->getConfigValue(self::XML_PATH_NETSUITE_PASSWORD, $storeId, $websiteId);
        $nsaccount = Mage::helper('celigoconnector')->getConfigValue(self::XML_PATH_NETSUITE_ACCOUNT, $storeId, $websiteId);
        $nsrole = Mage::helper('celigoconnector')->getConfigValue(self::XML_PATH_NETSUITE_ROLE, $storeId, $websiteId);
        $nsenvironment = Mage::helper('celigoconnector')->getConfigValue(self::XML_PATH_NETSUITE_ENVIRONMENT, $storeId, $websiteId);
        $value = $this->getValue();
        if ($nsemail != '' && $nspassword != '' && $nsaccount != '' && $nsrole != '' && $nsenvironment != '' && $value == '') {
            try {
                $result = Mage::helper('celigoconnector/resturl')->fetchRestUrl($nsemail, $nspassword, $nsaccount, $nsrole, $nsenvironment, $storeId, $websiteId);
                if ($result === true) {
                    $restleturl = Mage::helper('celigoconnector')->getConfigValue(self::XML_PATH_RESTLET_URL, $storeId, $websiteId);
                    $this->setValue($restleturl);
                }
            }
            catch(Exception $e) {
                Mage::helper('celigoconnector/celigologger')->error('errormsg="' . $e->getMessage() . '"', self::LOG_FILENAME);
            }
        }
        
        return $this;
    }
}
