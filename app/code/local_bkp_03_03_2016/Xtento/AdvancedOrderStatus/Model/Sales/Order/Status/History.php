<?php

/**
 * Product:       Xtento_AdvancedOrderStatus (1.1.2)
 * ID:            K9G6jcW/N2xX3TyJCSEdFoTQufgDzP4CMd/PLC0RbIU=
 * Packaged:      2014-10-07T13:01:54+00:00
 * Last Modified: 2014-09-08T15:37:13+02:00
 * File:          app/code/local/Xtento/AdvancedOrderStatus/Model/Sales/Order/Status/History.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_AdvancedOrderStatus_Model_Sales_Order_Status_History extends Mage_Sales_Model_Order_Status_History
{
    public function setIsCustomerNotified($flag = null)
    {
        if (Mage::registry('advancedorderstatus_notifications') !== NULL && Mage::registry('advancedorderstatus_notified')) {
            $flag = 1;
        }
        return parent::setIsCustomerNotified($flag);
    }
}