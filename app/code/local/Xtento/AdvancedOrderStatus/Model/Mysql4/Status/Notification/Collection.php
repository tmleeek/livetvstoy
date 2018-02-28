<?php

/**
 * Product:       Xtento_AdvancedOrderStatus (1.1.2)
 * ID:            K9G6jcW/N2xX3TyJCSEdFoTQufgDzP4CMd/PLC0RbIU=
 * Packaged:      2014-10-07T13:01:54+00:00
 * Last Modified: 2012-06-04T23:39:55+02:00
 * File:          app/code/local/Xtento/AdvancedOrderStatus/Model/Mysql4/Status/Notification/Collection.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_AdvancedOrderStatus_Model_Mysql4_Status_Notification_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('advancedorderstatus/status_notification');
    }
}