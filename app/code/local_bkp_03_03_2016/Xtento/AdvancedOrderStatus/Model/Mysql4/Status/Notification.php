<?php

/**
 * Product:       Xtento_AdvancedOrderStatus (1.1.2)
 * ID:            K9G6jcW/N2xX3TyJCSEdFoTQufgDzP4CMd/PLC0RbIU=
 * Packaged:      2014-10-07T13:01:54+00:00
 * Last Modified: 2012-12-25T18:07:56+01:00
 * File:          app/code/local/Xtento/AdvancedOrderStatus/Model/Mysql4/Status/Notification.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_AdvancedOrderStatus_Model_Mysql4_Status_Notification extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('advancedorderstatus/status_notification', 'notification_id');
    }

    public function removeNotifications($statusCode)
    {
        Mage::getSingleton('core/resource')->getConnection('core_write')->query('DELETE FROM ' . $this->getTable('advancedorderstatus/status_notification') . ' WHERE status_code = "' . $statusCode . '"');
    }
}