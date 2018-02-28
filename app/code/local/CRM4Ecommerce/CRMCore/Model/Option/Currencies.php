<?php

/**
 * The array params for config module
 * 
 * @category   CRM4Ecommerce
 * @package    CRM4Ecommerce_ZohoSynchronization
 * @author     Philip Nguyen <philip@crm4ecommerce.com>
 * @link       http://crm4ecommerce.com
 */
class CRM4Ecommerce_CRMCore_Model_Option_Currencies extends CRM4Ecommerce_CRMCore_Model_Option_Abstract {

    public function toOptionArray() {
        static $currencies = array();
        if (!count($currencies)) {
            $_currencies = Mage::getSingleton('adminhtml/system_config_source_currency')->toOptionArray(false);
            $_allowed_currencies = Mage::getModel('core/config_data')->getCollection()
                    ->addFieldToFilter('path','currency/options/allow');
            $allowed_currencies = '';
            foreach($_allowed_currencies as $config) {
                $allowed_currencies .= $config->getValue() . ',';
            }
            if ($allowed_currencies == '') {
                $allowed_currencies = Mage::app()->getStore()->getBaseCurrencyCode();
            }
            $allowed_currencies = explode(',', $allowed_currencies);
            sort($allowed_currencies);
            foreach ($allowed_currencies as $currency_code) {
                foreach ($_currencies as $currency) {
                    if ($currency_code == $currency['value'] && !key_exists($currency['value'], $currencies)) {
                        $currencies[$currency['value']] = $currency['label'] . ' (' . $currency['value'] . ')';
                    }
                }
            }
        }
        return $currencies;
    }

}