<?php

class Magalter_Crossup_Helper_Data extends Mage_Core_Helper_Abstract
{

    const MYSQL_ZEND_DATE_TIME_FROMAT = 'yyyy-MM-dd HH:mm:ss';
    const MYSQL_DATE_TIME_FROMAT = 'Y-m-d H:i:s';
    const FLAG_UPLOAD_DIR = 'magalter_crossup';

    public static function getCustomerGroup()
    {
        $customerSession = Mage::getSingleton('customer/session');

        if ($customerSession->isLoggedIn()) {

            return $customerSession->getCustomer()->getGroupId();
        }

        return 0;
    }
    
    public function isEe()
    {
        return Mage::getConfig()->getNode('modules/Enterprise_Enterprise/version'); 
    }

    public function isEnabled()
    {
        return Mage::getStoreConfig('magalter_crossup/configuration/enable')
                && !Mage::getStoreConfig('advanced/modules_disable_output/Magalter_Crossup');
    }

    public function setMediaUrl($rule)
    {
        if (!$rule->getId() || !$rule->getMedia()) {
            return;
        }
        $rule->setMedia(
                Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) .
                self::FLAG_UPLOAD_DIR .
                "/{$rule->getId()}/{$rule->getMedia()}"
        );
    }

    public function getMediaUrl($rule)
    {
        if (!$rule->getId() || !$rule->getMedia()) {
            return;
        }

        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) .
                self::FLAG_UPLOAD_DIR .
                "/{$rule->getId()}/{$rule->getMedia()}";
    }

    public function getUploadDir($path = null)
    {
        $options = Mage::getModel('core/config_options');
        return $options->getMediaDir() . DS . self::FLAG_UPLOAD_DIR . DS . $path;
    }

    public function removeRecursive($dir)
    {
        $sep = DS;
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != '.' && $object != '..') {
                    if (is_dir("{$dir}$sep{$object}")) {
                        $this->removeRecursive("{$dir}{$sep}{$object}");
                    }
                    else
                        @unlink("{$dir}{$sep}{$object}");
                }
            }
            return rmdir($dir);
        }
    }

    public function notVisibleAllowed()
    {
        return Mage::getStoreConfig('magalter_crossup/configuration/not_visible');
    }

}