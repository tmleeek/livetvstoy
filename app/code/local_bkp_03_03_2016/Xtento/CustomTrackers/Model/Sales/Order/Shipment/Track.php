<?php

/**
 * Product:       Xtento_CustomTrackers (1.5.1)
 * ID:            m4hoQjDDnX8//zqNPrqJhIIo2pzblxzZ7ZxeXyh8i9M=
 * Packaged:      2014-12-30T05:54:31+00:00
 * Last Modified: 2012-03-01T17:20:44+01:00
 * File:          app/code/local/Xtento/CustomTrackers/Model/Sales/Order/Shipment/Track.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_CustomTrackers_Model_Sales_Order_Shipment_Track extends Mage_Sales_Model_Order_Shipment_Track
{
    /**
     * Retrieve detail for shipment track
     *
     * @return string
     */
    public function getNumberDetail()
    {
        /*
         * The following modification was added to be able to replace the ZIP code and other details in the tracking URL.
         */
        Mage::register('xt_current_shipment', $this, true); // This is actually the track. But this works for us. And as only we're using it, it's fine.
        /*
         * End of modification
         */
        $carrierInstance = Mage::getSingleton('shipping/config')->getCarrierInstance($this->getCarrierCode());
        if (!$carrierInstance) {
            $custom['title'] = $this->getTitle();
            $custom['number'] = $this->getNumber();
            return $custom;
        } else {
            $carrierInstance->setStore($this->getStore());
        }

        if (!$trackingInfo = $carrierInstance->getTrackingInfo($this->getNumber())) {
            return Mage::helper('sales')->__('No detail for number "%s"', $this->getNumber());
        }

        return $trackingInfo;
    }
}
