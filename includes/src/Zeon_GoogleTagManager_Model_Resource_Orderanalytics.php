<?php

/**
 * zeonsolutions inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://shop.zeonsolutions.com/license-enterprise.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * This package designed for Magento ENTERPRISE edition
 * =================================================================
 * zeonsolutions does not guarantee correct work of this extension
 * on any other Magento edition except Magento ENTERPRISE edition.
 * zeonsolutions does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   Zeon
 * @package    Zeon_GoogleTagManager
 * @version    0.0.1
 * @copyright  @copyright Copyright (c) 2013 zeonsolutions.Inc. (http://www.zeonsolutions.com)
 * @license    http://shop.zeonsolutions.com/license-enterprise.txt
 */
class Zeon_GoogleTagManager_Model_Resource_Orderanalytics extends Mage_Core_Model_Resource_Db_Abstract {

    /**
     * Constructor
     * @see Varien_Object::_construct()
     */
    protected function _construct() {
        $this->_init('zeon_googletagmanager/sales', 'analytics_id');
    }

    /**
     * 
     * Get Order Analytics
     * @param Int $orderId
     * @return Array OrderAnalytics
     */
    public function getOrderAnalytics($orderId = null) {
        if (!is_null($orderId) && 0 < $orderId) {
            $adapter = $this->_getReadAdapter();
            $select = $adapter->select()
                    ->from($this->getTable('zeon_googletagmanager/sales'))
                    ->where('order_id=:order_id');
            $data = $adapter->fetchRow($select, array('order_id' => $orderId));
            if ($data && $data['order_id'] > 0) {
                $rowObj = new Varien_Object();
                $rowObj->setData($data);
                return $rowObj;
            }
        }
        return false;
    }

}