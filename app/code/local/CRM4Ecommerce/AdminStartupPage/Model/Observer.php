<?php

/**
 * 
 * @category   CRM4Ecommerce
 * @package    CRM4Ecommerce_AdminStartupPage
 * @author     Philip Nguyen <philip@crm4ecommerce.com>
 * @link       http://crm4ecommerce.com
 */
class CRM4Ecommerce_AdminStartupPage_Model_Observer {

    public function adminhtmlControllerActionPredispatch($observer) {
        if (Mage::getStoreConfig('crm4ecommerce_adminstartuppage/general/status') == CRM4Ecommerce_CRMCore_Model_Option_ModuleStatus::STATUS_ENABLED) {
            $controller = $observer->getControllerAction();
            $module_name = $controller->getRequest()->getModuleName();
            $controller_name = $controller->getRequest()->getControllerName();
            $action_name = $controller->getRequest()->getActionName();
            $admin_router_key = Mage::getStoreConfig('crm4ecommerce_adminstartuppage/settings/admin_ruoter_key');
            if ($admin_router_key == '') {
                $admin_router_key = Mage::helper('adminstartuppage')->getDefauleConfig('crm4ecommerce_adminstartuppage/settings/admin_ruoter_key');
            }
            if (isset($_SESSION['startup']) && $_SESSION['startup']) {
                $user = Mage::getSingleton('admin/session');
                $startuppage = $user->getStartupPageUrl();
                $startuppage = explode('/', $startuppage);
                if (!isset($startuppage[2])) {
                    $startuppage[2] = 'index';
                }
                if (!isset($startuppage[1])) {
                    $startuppage[1] = 'index';
                }
                if (!(($module_name == $startuppage[0] || $module_name == $admin_router_key) && $controller_name == $startuppage[1] && $action_name == $startuppage[2])) {
                    $_SESSION['startup'] = false;
                    header("Location: " . Mage::getBaseUrl() . $admin_router_key);
                    die();
                }
            }
            if ($module_name == $admin_router_key && $action_name == 'save') {
                switch ($controller_name) {
                    case 'system_account':
                    case 'permissions_user':
                        if (!Mage::helper('adminstartuppage')->isRegistered()) {
                            Mage::getSingleton('adminhtml/session')->addError(
                                    Mage::helper('adminstartuppage')->__('Please register to use extension <u>%s</u>', 'CRM4Ecommerce Admin Startup Page')
                            );
                        } else if (Mage::getSingleton('admin/session')->isAllowed(CRM4Ecommerce_AdminStartupPage_Helper_Data::ACL_CHANGE_STARTUP_PAGE)) {
                            $userId = $controller->getRequest()->getParam('user_id');
                            $user = Mage::getModel("admin/user")->load($userId);
                            if ((int) $user->getId() > 0) {
                                $coreResource = Mage::getSingleton('core/resource');
                                $sql = "UPDATE `" . $coreResource->getTableName('admin_user')
                                        . "` SET `crm4e_startup_page_usedefault` = '" . ($controller->getRequest()->getParam('crm4e_startup_page_usedefault') == true ? '1' : '0') . "'"
                                        . ", `crm4e_startup_page` = '" . $controller->getRequest()->getParam('crm4e_startup_page', '') . "'"
                                        . " WHERE `user_id` = " . $user->getId();
                                $write = $coreResource->getConnection('core_write');
                                $write->query($sql);
                            }
                        }
                        break;
                }
            }
        }
        return $this;
    }

    /**
     *
     * @param type $observer 
     */
    public function adminLogin($observer) {
        if (Mage::getStoreConfig('crm4ecommerce_adminstartuppage/general/status') == CRM4Ecommerce_CRMCore_Model_Option_ModuleStatus::STATUS_ENABLED) {
            try {
                Mage::helper('adminstartuppage')->register();
            } catch (Exception $e) {
                if (method_exists(Mage::app(), 'getCacheInstance')) {
                    Mage::app()->getCacheInstance()->cleanType('config');
                } else {
                    Mage::app()->cleanCache('config');
                }
            }
            $_SESSION['startup'] = true;
        }
        return $this;
    }

    /**
     *
     * @param type $observer 
     */
    public function configChanged($observer) {
        try {
            if (is_null($observer->getWebsite()) && is_null($observer->getStore())) {
                Mage::helper('adminstartuppage')->register();
                if (method_exists(Mage::app(), 'getCacheInstance')) {
                    Mage::app()->getCacheInstance()->cleanType('config');
                } else {
                    Mage::app()->cleanCache('config');
                }
            }
        } catch (Exception $e) {
            if (method_exists(Mage::app(), 'getCacheInstance')) {
                Mage::app()->getCacheInstance()->cleanType('config');
            } else {
                Mage::app()->cleanCache('config');
            }
            throw $e;
        }
        return $this;
    }

}