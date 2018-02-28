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
class Zeon_GoogleTagManager_Model_Orderanalytics extends Mage_Core_Model_Abstract {

    /**
     * Constructor
     * @see Varien_Object::_construct()
     */
    protected function _construct() {
        parent::_construct();
        $this->_init('zeon_googletagmanager/orderanalytics');
    }

    /**
     * 
     * Get Order Analytics
     * @param Int $orderId
     * @return Array OrderAnalytics
     */
    public function getOrderAnalytics($orderId = null) {
        return $this->getResource()->getOrderAnalytics($orderId);
    }

}