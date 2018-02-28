<?php

/**
 * Product:       Xtento_AdvancedOrderStatus (1.1.2)
 * ID:            K9G6jcW/N2xX3TyJCSEdFoTQufgDzP4CMd/PLC0RbIU=
 * Packaged:      2014-10-07T13:01:54+00:00
 * Last Modified: 2013-01-14T16:12:08+01:00
 * File:          app/code/local/Xtento/AdvancedOrderStatus/Helper/Data.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_AdvancedOrderStatus_Helper_Data extends Mage_Core_Helper_Abstract
{
    const EDITION = 'EE';

    public function getModuleEnabled()
    {
        if (!Mage::getStoreConfigFlag('advancedorderstatus/general/enabled')) {
            return 0;
        }
        $moduleEnabled = Mage::getModel('core/config_data')->load('advancedorderstatus/general/' . str_rot13('frevny'), 'path')->getValue();
        if (empty($moduleEnabled) || !$moduleEnabled || (0x28 !== strlen(trim($moduleEnabled)))) {
            return 0;
        }
        if (!Mage::registry('moduleString')) {
            Mage::register('moduleString', 'false', true);
        }
        return true;
    }
}