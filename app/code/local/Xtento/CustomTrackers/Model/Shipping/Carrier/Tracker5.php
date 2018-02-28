<?php

/**
 * Product:       Xtento_CustomTrackers (1.5.1)
 * ID:            m4hoQjDDnX8//zqNPrqJhIIo2pzblxzZ7ZxeXyh8i9M=
 * Packaged:      2014-12-30T05:54:31+00:00
 * Last Modified: 2012-02-10T00:37:18+01:00
 * File:          app/code/local/Xtento/CustomTrackers/Model/Shipping/Carrier/Tracker5.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_CustomTrackers_Model_Shipping_Carrier_Tracker5 extends Xtento_CustomTrackers_Model_Shipping_Carrier_Abstract implements Mage_Shipping_Model_Carrier_Interface
{
    protected $_code = 'tracker5';

    public function getAllowedMethods()
    {
        return array($this->_code => $this->getConfigData('name'));
    }

    public function isTrackingAvailable()
    {
        return parent::isTrackingAvailable();
    }
}