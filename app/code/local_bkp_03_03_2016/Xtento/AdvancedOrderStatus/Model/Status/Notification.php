<?php

/**
 * Product:       Xtento_AdvancedOrderStatus (1.1.2)
 * ID:            K9G6jcW/N2xX3TyJCSEdFoTQufgDzP4CMd/PLC0RbIU=
 * Packaged:      2014-10-07T13:01:54+00:00
 * Last Modified: 2012-12-24T21:16:38+01:00
 * File:          app/code/local/Xtento/AdvancedOrderStatus/Model/Status/Notification.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_AdvancedOrderStatus_Model_Status_Notification extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('advancedorderstatus/status_notification');
    }

    public function getNotifications($statusCode)
    {
        $notifications = array();
        $notificationCollection = $this->getCollection()
            ->addFieldToFilter('status_code', $statusCode);
        foreach ($notificationCollection as $notification) {
            $notifications[$notification->getStoreId()] = $notification->getTemplateId();
        }
        return $notifications;
    }
}