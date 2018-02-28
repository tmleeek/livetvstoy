<?php

/**
 * 
 * @category   CRM4Ecommerce
 * @package    CRM4Ecommerce_AdminStartupPage
 * @author     Philip Nguyen <philip@crm4ecommerce.com>
 * @link       http://crm4ecommerce.com
 */
class CRM4Ecommerce_AdminStartupPage_Helper_Data extends Mage_Core_Helper_Abstract {

    const ACL_CHANGE_STARTUP_PAGE = 'admin/system/myaccount/change_startup_page';
    const ACL_CLEAR_REGISTER_INFORMATION = 'admin/crm4ecommerce/adminstartuppage/clear_register';
    const MODULE_KEY = 'AdminStartupPage';

    public function clearAllRegisterInformation() {
        return Mage::helper('crmcore')->clearAllRegisterInformation(CRM4Ecommerce_AdminStartupPage_Helper_Data::MODULE_KEY);
    }

    public function getVersion() {
        return Mage::helper('crmcore')->getModuleVersion(CRM4Ecommerce_AdminStartupPage_Helper_Data::MODULE_KEY);
    }

    public function getDefauleConfig($key) {
        $xmlPath = Mage::getBaseDir() . DS . 'app' . DS . 'code' . DS . 'local' . DS . 'CRM4Ecommerce' . DS . 'AdminStartupPage' . DS . 'etc' . DS . 'config.xml';
        $xmlObj = new Varien_Simplexml_Config($xmlPath);
        return (string) $xmlObj->getNode('default/' . $key);
    }

    public function register() {
        $current_modulestatus = Mage::getStoreConfig('crm4ecommerce_adminstartuppage/general/status');
        $siteurl = Mage::getStoreConfig('web/unsecure/base_url', 0);
        $siteurl = explode('/index.php', $siteurl);
        $siteurl = rtrim($siteurl[0], '/') . '/';
        $serialkey = Mage::getStoreConfig('crm4ecommerce_adminstartuppage/general/serial');
        $sitename = Mage::getStoreConfig('general/store_information/name');
        $sitedescription = Mage::getStoreConfig('design/head/default_description');
        $helper = Mage::helper('crmcore');

        $parameter = array();
        $parameter = $helper->setParameter('serialkey', trim($serialkey), $parameter);
        $parameter = $helper->setParameter('sitename', $sitename, $parameter);
        $parameter = $helper->setParameter('siteurl', $siteurl, $parameter);
        $parameter = $helper->setParameter('moduleversion', $this->getVersion(), $parameter);
        $parameter = $helper->setParameter('sitedescription', $sitedescription . "\nMagento Version: " . Mage::getVersion(), $parameter);
        $parameter = $helper->setParameter('product', 'ADMIN-STARTUP-PAGE', $parameter);

        $response = null;
        try {
            $response = $helper->sendCurlRequest('http://www.crm4ecommerce.com/index.php/serialkeyshowcase/register/', $parameter);
        } catch (Exception $e) {
            return $this;
        }

        $json = json_decode($response);
        if (!$json) {
            return $this;
        }
        $json = $json->melonkat;
        $scope = 'default';
        $scopeId = 0;

        if (isset($json->error) && $json->error) {
            Mage::getSingleton('core/config')->saveConfig(
                    'crm4ecommerce_adminstartuppage/general/serial_infor', strip_tags($json->error), $scope, $scopeId
            );
            Mage::getSingleton('core/config')->saveConfig(
                    'crm4ecommerce_adminstartuppage/e', '', $scope, $scopeId
            );
            Mage::getSingleton('core/config')->saveConfig(
                    'crm4ecommerce_adminstartuppage/u', '', $scope, $scopeId
            );
            Mage::getSingleton('core/config')->saveConfig(
                    'crm4ecommerce_adminstartuppage/general/status', CRM4Ecommerce_CRMCore_Model_Option_ModuleStatus::STATUS_DISABLED, $scope, $scopeId
            );
            throw new Exception($json->error);
        } else {
            if (isset($json->serialkey->expiredate) && $json->serialkey->expiredate != '' && strcmp(now(), $json->serialkey->expiredate) > 0) {
                Mage::getSingleton('core/config')->saveConfig(
                        'crm4ecommerce_adminstartuppage/general/status', CRM4Ecommerce_CRMCore_Model_Option_ModuleStatus::STATUS_EXPIRED, $scope, $scopeId
                );
                Mage::getSingleton('core/config')->saveConfig(
                        'crm4ecommerce_adminstartuppage/general/serial_infor', Mage::helper('adminstartuppage')->__("Serial Key %s was expired. Let contact with CRM4Ecommerce Support Department to get more details.", trim($serialkey)), $scope, $scopeId
                );
                throw new Exception(Mage::helper('adminstartuppage')->__("So sorry, Serial Key <u>%s</u> was expired.<br/>Let contact with <a href=\"mailto:support@crm4ecommerce.com\">CRM4Ecommerce Support Department</a> to get more details. Thanks for your using.", trim($serialkey)));
            }
            $edition = md5($json->serialkey->edition);
            if (!Mage::helper('crmcore')->isCommunity()
                    && $edition == '94e94133f4bdc1794c6b647b8ea134d0') {
                $error = Mage::helper('adminstartuppage')->__('Sorry, your Magento is not Community edition, so, you can\'t use Magento Admin Startup Page Community edition. Please <a target="_blank" href="http://www.crm4ecommerce.com/magento-admin-startup-page.html">purchase</a> Magento Admin Startup Page Enterprise edition.');
                Mage::getSingleton('core/config')->saveConfig(
                        'crm4ecommerce_adminstartuppage/general/serial_infor', strip_tags($error), $scope, $scopeId
                );
                Mage::getSingleton('core/config')->saveConfig(
                        'crm4ecommerce_adminstartuppage/e', '', $scope, $scopeId
                );
                Mage::getSingleton('core/config')->saveConfig(
                        'crm4ecommerce_adminstartuppage/u', '', $scope, $scopeId
                );
                Mage::getSingleton('core/config')->saveConfig(
                        'crm4ecommerce_adminstartuppage/general/status', CRM4Ecommerce_CRMCore_Model_Option_ModuleStatus::STATUS_DISABLED, $scope, $scopeId
                );
                throw new Exception($error);
            } else {
                Mage::getSingleton('core/config')->saveConfig(
                        'crm4ecommerce_adminstartuppage/general/serial_infor', $json->serialkey->edition . ' - ' . $json->serialkey->type, $scope, $scopeId
                );
                $old_edition = Mage::getStoreConfig('crm4ecommerce_adminstartuppage/e');
                Mage::getSingleton('core/config')->saveConfig(
                        'crm4ecommerce_adminstartuppage/e', base64_encode($serialkey . $edition), $scope, $scopeId
                );

                $siteurl = str_replace('/index.php/', '/', $siteurl);
                $siteurl = str_replace('/index.php', '/', $siteurl);
                Mage::getSingleton('core/config')->saveConfig(
                        'crm4ecommerce_adminstartuppage/u', sha1($siteurl), $scope, $scopeId
                );
                Mage::getSingleton('core/config')->saveConfig(
                        'crm4ecommerce_adminstartuppage/general/start_date', Mage::app()->getLocale()->date($json->serialkey->startdate, Varien_Date::DATETIME_INTERNAL_FORMAT)->toString(), $scope, $scopeId
                );

                if (isset($json->serialkey->expiredate) && $json->serialkey->expiredate != '') {
                    Mage::getSingleton('core/config')->saveConfig(
                            'crm4ecommerce_adminstartuppage/general/expire_date', Mage::app()->getLocale()->date($json->serialkey->expiredate, Varien_Date::DATETIME_INTERNAL_FORMAT)->toString(), $scope, $scopeId
                    );
                } else {
                    Mage::getSingleton('core/config')->saveConfig(
                            'crm4ecommerce_adminstartuppage/general/expire_date', Mage::helper('crmcore')->__('None'), $scope, $scopeId
                    );
                }
                if (is_null($current_modulestatus) || (int) $current_modulestatus == CRM4Ecommerce_CRMCore_Model_Option_ModuleStatus::STATUS_EXPIRED) {
                    Mage::getSingleton('core/config')->saveConfig(
                            'crm4ecommerce_adminstartuppage/general/status', CRM4Ecommerce_CRMCore_Model_Option_ModuleStatus::STATUS_DISABLED, $scope, $scopeId);
                }
                if ($old_edition != base64_encode($serialkey . $edition)) {
                    Mage::getSingleton('adminhtml/session')
                            ->addSuccess(Mage::helper('adminstartuppage')->__('Thank you for registering for url <u>%s</u>.', $siteurl));
                }
            }
        }
    }

    public function isRegistered() {
        static $rs = null;
        if (is_null($rs)) {
            $serialkey = Mage::helper('crmcore')->getStoreConfig('crm4ecommerce_adminstartuppage/general/serial');
            $config = Mage::helper('crmcore')->getStoreConfig('crm4ecommerce_adminstartuppage/e');
            if ($config != base64_encode($serialkey . '306f14e5ed6e4f8b237eaa58359fa3b2') &&
                    $config != base64_encode($serialkey . '4d4afda25a3f52041ee1b569157130b8')) {
                $rs = false;
            } else {
                $rs = true;
            }
        }
        return $rs;
    }

}