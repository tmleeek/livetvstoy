<?php
class Celigo_Connector_Model_System_Config_Backend_Resturl extends Mage_Core_Model_Config_Data
{
	const XML_PATH_NETSUITE_EMAIL = 'connector/nsdetails/nsemail';
	const XML_PATH_NETSUITE_PASSWORD = 'connector/nsdetails/nspassword';
	const XML_PATH_NETSUITE_ROLE = 'connector/nsdetails/nsrole';
	const XML_PATH_NETSUITE_ACCOUNT = 'connector/nsdetails/nsaccountid';
	const XML_PATH_NETSUITE_ENVIRONMENT = 'connector/nsdetails/nsenvironment';
	
	const XML_PATH_RESTLET_URL = 'connector/othersettings/restleturl';
    /**
     * Change secure/unsecure base_url after use_custom_url was modified
     *
     * @return Mage_Adminhtml_Model_System_Config_Backend_Admin_Custom
     */
    public function _afterSave()
    {
	
		$storeId = ''; $websiteId = '';
		$params = Mage::app()->getRequest()->getParams();
		unset($params['key']);
		
		if (isset($params['store']) && $params['store'] != '') {
			$storeId = Mage::app()->getStore($params['store'])->getId();
		}
		
		if (isset($params['website']) && $params['website'] != '') {
			$websiteId = Mage::app()->getWebsite($params['website'])->getId();
			$websiteId = $params['website'];
		}

		$nsemail = Mage::helper('connector')->getConfigValue(self::XML_PATH_NETSUITE_EMAIL, $storeId, $websiteId);
		$nspassword = Mage::helper('connector')->getConfigValue(self::XML_PATH_NETSUITE_PASSWORD, $storeId, $websiteId);
		$nsaccount = Mage::helper('connector')->getConfigValue(self::XML_PATH_NETSUITE_ACCOUNT, $storeId, $websiteId);
		$nsrole = Mage::helper('connector')->getConfigValue(self::XML_PATH_NETSUITE_ROLE, $storeId, $websiteId);
		$nsenvironment = Mage::helper('connector')->getConfigValue(self::XML_PATH_NETSUITE_ENVIRONMENT, $storeId, $websiteId);

        $value = $this->getValue();
		
		if ($nsemail != '' && $nspassword != '' && $nsaccount != '' 
				&& $nsrole != '' && $nsenvironment != '' && $value == '') {
			try {
				$result = Mage::helper('connector/resturl')->fetchRestUrl($nsemail, $nspassword, $nsaccount, $nsrole, $nsenvironment, $storeId, $websiteId);
				 if($result === true) {
					$restleturl = Mage::helper('connector')->getConfigValue(self::XML_PATH_RESTLET_URL, $storeId, $websiteId);
					$this->setValue($restleturl);
				}
			} catch (Exception $e) {
				Mage::helper('connector')->addErrorMessageToLog($e->getMessage(), Zend_Log::ERR, $storeId, $websiteId);
			}
		}
		
        return $this;
    }
}