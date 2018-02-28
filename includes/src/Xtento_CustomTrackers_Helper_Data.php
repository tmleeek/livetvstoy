<?php

/**
 * Product:       Xtento_CustomTrackers (1.5.1)
 * ID:            m4hoQjDDnX8//zqNPrqJhIIo2pzblxzZ7ZxeXyh8i9M=
 * Packaged:      2014-12-30T05:54:31+00:00
 * Last Modified: 2013-09-06T12:01:12+02:00
 * File:          app/code/local/Xtento/CustomTrackers/Helper/Data.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_CustomTrackers_Helper_Data extends Mage_Core_Helper_Abstract
{
    const EDITION = 'EE';

    public function getModuleEnabled()
    {
        if (!Mage::getStoreConfigFlag('customtrackers/general/enabled')) {
            return 0;
        }
        $moduleEnabled = Mage::getModel('core/config_data')->load('customtrackers/general/' . str_rot13('frevny'), 'path')->getValue();
        if (empty($moduleEnabled) || !$moduleEnabled || (0x28 !== strlen(trim($moduleEnabled)))) {
            return 0;
        }
        return true;
    }

    /*
    Get tracking url for track
    */
    public function getTrackingUrl($trackingItem, $storeId = 0, $shipment = false)
    {
        if (!$trackingItem) {
            return '';
        }
        //$carrierTitle = $trackingItem->getTitle();
        $trackCarrierCode = $trackingItem->getCarrierCode();

        if (Mage::helper('xtcore/utils')->mageVersionCompare(Mage::getVersion(), '1.6.0.0', '>=')) {
            $trackingNumber = $trackingItem->getTrackNumber();
        } else {
            $trackingNumber = $trackingItem->getNumber();
        }

        if ($trackCarrierCode == '') {
            return '';
        }

        // In case the XTENTO Custom Carrier Trackers extension is installed, make sure no disabled carriers are used here.
        $disabledCarriers = explode(",", Mage::getStoreConfig('customtrackers/general/disabled_carriers'));

        $trackingCarriers = Mage::getSingleton('shipping/config')->getAllCarriers($storeId);
        foreach ($trackingCarriers as $carrierCode => $carrierConfig) {
            if ($carrierConfig->isTrackingAvailable() && preg_match("/Custom/", $carrierConfig->getConfigData('name'))) {
                //if ($carrierTitle == $carrierConfig->getConfigData('title')) {
                if ($carrierCode == $trackCarrierCode) {
                    if (in_array($carrierCode, $disabledCarriers)) {
                        continue;
                    }
                    $shipmentDate = new Zend_Date();
                    $orderIncrementId = '';
                    $firstname = '';
                    $lastname = '';
                    $countryCode = '';
                    if ($shipment->getId()) {
                        if ($shipment->getCreatedAt()) {
                            $shipmentDate = new Zend_Date();
                            $shipmentDate->setTimezone(Mage_Core_Model_Locale::DEFAULT_TIMEZONE);
                            $shipmentDate->set($shipment->getCreatedAt(), Varien_Date::DATETIME_INTERNAL_FORMAT);
                            $shipmentDate->setLocale(Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_LOCALE));
                            $shipmentDate->setTimezone(Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_TIMEZONE));
                        }
                        if ($shipment->getOrder()) {
                            $orderIncrementId = $shipment->getOrder()->getIncrementId();
                            if ($shipAddress = $shipment->getOrder()->getShippingAddress()) {
                                $firstname = $shipAddress->getFirstname();
                                $lastname = $shipAddress->getLastname();
                                $countryCode = $shipAddress->getCountryId();
                            }
                        }
                    }
                    return preg_replace(
                        array("/#TRACKINGNUMBER#/i", "/#ZIP#/i", "/#d#/i", "/#m#/i", "/#y#/", "/#Y#/", "/#ORDERNUMBER#/i", "/#FIRSTNAME#/i", "/#LASTNAME#/i", "/#COUNTRYCODE#/i"),
                        array(urlencode($trackingNumber), urlencode($this->getShippingPostcode($shipment)), $shipmentDate->get('dd'), $shipmentDate->get('MM'), $shipmentDate->get('yy'), $shipmentDate->get('y'), urlencode($orderIncrementId), urlencode($firstname), urlencode($lastname), urlencode($countryCode)),
                        $carrierConfig->getConfigData('url')
                    );
                }
            }
        }
        return '';
    }

    /*
    * Get recipients postcode for current shipment/order
    */
    public function getShippingPostcode($shipment = false)
    {
        $postcode = '';

        if (!$shipment) {
            $shipment = Mage::registry('xt_current_shipment');
        }
        if ($shipment && $shipment->getId() && $shipment->getOrderId()) {
            $order = Mage::getModel('sales/order')->load($shipment->getOrderId());
            if ($order && $order->getId()) {
                $shippingAddress = $order->getShippingAddress();
                if ($shippingAddress) {
                    $postcode = $shippingAddress->getPostcode();
                }
            }
        }
        return $postcode;
    }
}