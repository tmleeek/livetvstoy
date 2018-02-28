<?php

/**
 * Product:       Xtento_AdvancedOrderStatus (1.1.2)
 * ID:            K9G6jcW/N2xX3TyJCSEdFoTQufgDzP4CMd/PLC0RbIU=
 * Packaged:      2014-10-07T13:01:54+00:00
 * Last Modified: 2012-06-04T15:38:02+02:00
 * File:          app/code/local/Xtento/AdvancedOrderStatus/Model/System/Config/Backend/Import/Servername.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_AdvancedOrderStatus_Model_System_Config_Backend_Import_Servername extends Mage_Core_Model_Config_Data
{
    const MODULE_MESSAGE = 'The Advanced Order Status extension couldn\'t be enabled. Please make sure you are using a valid license key.';

    public function afterLoad()
    {
        $sName1 = Mage::getModel('advancedorderstatus/system_config_backend_import_server')->getFirstName();
        $sName2 = Mage::getModel('advancedorderstatus/system_config_backend_import_server')->getSecondName();
        if ($sName1 !== $sName2) {
            $this->setValue(sprintf('%s (Main: %s)', $sName1, $sName2));
        } else {
            $this->setValue(sprintf('%s', $sName1));
        }
    }
}
