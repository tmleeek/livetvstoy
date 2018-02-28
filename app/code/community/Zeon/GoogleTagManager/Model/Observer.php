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
class Zeon_GoogleTagManager_Model_Observer {

    public function setOrderData(Varien_Event_Observer $observer) {
        $orderIds = $observer->getData('order_ids');
        if (empty($orderIds) || !is_array($orderIds)) {
            return;
        } else {
            Mage::getSingleton('customer/session')->setOrderIds($orderIds);
        }
    }

    /**
     * 
     * Collect Google Analytics Data for the Order
     * and Save to Order analytics Table
     * @param Object $observer
     */
    public function collectData($observer) {
        if (!Mage::helper('zeon_googletagmanager')->isOrderAnalyticsEnabled()) {
            return;
        }

        try {
            $order = $observer->getEvent()->getOrder();
            $incrementId = $order->getIncrementId();
            $orderId = $order->getId();
            if (0 < $orderId) {
                Mage::log(print_r(Mage::getModel('core/cookie'), 1), 1, 'cookie1.txt', 1);
                Mage::log(print_r($_COOKIE, 1), 1, 'cookie2.txt', 1);
                $orderanalyticsModel = Mage::getModel('zeon_googletagmanager/orderanalytics');
                $orderanalyticsModel->setAnalyticsId()
                        ->setOrderId($orderId)
                        ->setOrderIncrementId($incrementId)
                        ->setCustomerEmail($order->getCustomerEmail())
                        ->setCustomerName($order->getCustomerFirstname() . ' ' . $order->getCustomerLastname())
                        ->setRemoteIp($order->getRemoteIp())
                        ->setUtma(Mage::getModel('core/cookie')->get('__utma'))
                        ->setUtmb(Mage::getModel('core/cookie')->get('__utmb'))
                        ->setUtmc(Mage::getModel('core/cookie')->get('__utmc'))
                        ->setUtmz(Mage::getModel('core/cookie')->get('__utmz'))
                        ->setUtmv(Mage::getModel('core/cookie')->get('__utmv'))
                        ->setAdditionalcookies(serialize(Mage::helper('zeon_googletagmanager')->getAdditionalCookiesValues()))
                        ->save();
            }
        } catch (Exception $e) {
            //Log the exception in query if any
            Mage::log('GA_ORDERANALYTICS', $e);
        }
        return;
    }

}
