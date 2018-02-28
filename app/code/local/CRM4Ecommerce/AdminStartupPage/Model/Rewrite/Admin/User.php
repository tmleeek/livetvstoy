<?php

/**
 * 
 * @category   CRM4Ecommerce
 * @package    CRM4Ecommerce_AdminStartupPage
 * @author     Philip Nguyen <philip@crm4ecommerce.com>
 * @link       http://crm4ecommerce.com
 */
class CRM4Ecommerce_AdminStartupPage_Model_Rewrite_Admin_User extends Mage_Admin_Model_User {

    public function getStartupPageUrl() {
        if (Mage::getStoreConfig('crm4ecommerce_adminstartuppage/general/status') == CRM4Ecommerce_CRMCore_Model_Option_ModuleStatus::STATUS_ENABLED) {
            $startupPage = $this->getCrm4eStartupPage();
            $aclResource = 'admin/' . $startupPage;
            if (Mage::getSingleton('admin/session')->isAllowed($aclResource)) {
                $nodePath = 'menu/' . join('/children/', explode('/', $startupPage)) . '/action';
                $url = Mage::getSingleton('admin/config')->getAdminhtmlConfig()->getNode($nodePath);
                if ($url) {
                    return $url;
                }
            }
        }
        return parent::getStartupPageUrl();
    }

}