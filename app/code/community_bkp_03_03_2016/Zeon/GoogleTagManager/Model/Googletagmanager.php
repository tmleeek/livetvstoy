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
class Zeon_GoogleTagManager_Model_Googletagmanager extends Mage_Core_Model_Abstract {

    const XML_PATH_ECOMMERCE_TRANSACTIONS = 'zeon_googletagmanager/general/ecommerce_transactions';

    /**
     * Add transaction data to the data layer?
     *
     * @return bool
     */
    public function isEcommerceTransactionsEnabled() {
        return Mage::getStoreConfig(self::XML_PATH_ECOMMERCE_TRANSACTIONS);
    }

    /**
     * get Transaction data for the data layer?
     *
     * @return array
     */
    public function getTransactionData($orderIds) {
        if (!empty($orderIds) && $this->isEcommerceTransactionsEnabled()) {
            $collection = Mage::getResourceModel('sales/order_collection')->addFieldToFilter('entity_id', array('in' => $orderIds));
            $data = array();

            $i = 0;
            $products = array();

            foreach ($collection as $order) {
                if ($i == 0) {
                    // Build all fields for first order.
                    $data = array(
                        'transactionId' => $order->getIncrementId(),
                        'transactionDate' => date("Y-m-d"),
                        'transactionTotal' => round($order->getBaseGrandTotal(), 2),
                        'transactionShipping' => round($order->getBaseShippingAmount(), 2),
                        'transactionTax' => round($order->getBaseTaxAmount(), 2),
                        'transactionPaymentType' => $order->getPayment()->getMethodInstance()->getTitle(),
                        'transactionCurrency' => $order->getOrderCurrencyCode(),
                        'transactionShippingMethod' => $order->getShippingCarrier()->getCarrierCode(),
                        'transactionPromoCode' => $order->getCouponCode(),
                        'transactionProducts' => array()
                    );
                } else {
                    // For subsequent orders, append to order ID, totals and shipping method.
                    $data['transactionId'] .= '|' . $order->getIncrementId();
                    $data['transactionTotal'] += $order->getBaseGrandTotal();
                    $data['transactionShipping'] += $order->getBaseShippingAmount();
                    $data['transactionTax'] += $order->getBaseTaxAmount();
                    $data['transactionShippingMethod'] .= '|' . $order->getShippingCarrier()->getCarrierCode();
                }

                // Build products array.
                foreach ($order->getAllVisibleItems() as $item) {
                    $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $item->getSku());
                    $product_categories = $product->getCategoryIds();
                    $categories = array();
                    foreach ($product_categories as $category) {
                        $categories[] = Mage::getModel('catalog/category')->load($category)->getName();
                    }
                    if (empty($products[$item->getSku()])) {
                        // Build all fields the first time we encounter this item.
                        $products[$item->getSku()] = array(
                            'name' => Mage::helper('zeon_googletagmanager')->jsQuoteEscape(Mage::helper('core')->escapeHtml($item->getName())),
                            'sku' => Mage::helper('zeon_googletagmanager')->jsQuoteEscape(Mage::helper('core')->escapeHtml($item->getSku())),
                            'category' => implode('|', $categories),
                            'price' => (double) number_format($item->getBasePrice(), 2, '.', ''),
                            'quantity' => (int) $item->getQtyOrdered()
                        );
                    } else {
                        // If we already have the item, update quantity.
                        $products[$item->getSku()]['quantity'] += (int) $item->getQtyOrdered();
                    }
                }

                $i++;
            }

            // Push products into main data array.
            foreach ($products as $product) {
                $data['transactionProducts'][] = $product;
            }

            // Trim empty fields from the final output.
            foreach ($data as $key => $value) {
                if (!is_numeric($value) && empty($value))
                    unset($data[$key]);
            }

            return $data;
        } else {
            return array();
        }
    }

    /**
     * get Transaction data for the data layer?
     *
     * @return array
     */
    public function getVisitorData() {
        $data = array();
        $customer = Mage::getSingleton('customer/session');

        // visitorId
        if ($customer->getCustomerId())
            $data['visitorId'] = (string) $customer->getCustomerId();

        // visitorLoginState
        $data['visitorLoginState'] = ($customer->isLoggedIn()) ? 'Logged in' : 'Logged out';

        // visitorType
        $data['visitorType'] = (string) Mage::getSingleton('customer/group')->load($customer->getCustomerGroupId())->getCode();

        // visitorExistingCustomer / visitorLifetimeValue
        $orders = Mage::getResourceModel('sales/order_collection')->addFieldToSelect('*')->addFieldToFilter('customer_id', $customer->getId());
        $ordersTotal = 0;
        foreach ($orders as $order) {
            $ordersTotal += $order->getGrandTotal();
        }
        $data['visitorLifetimeValue'] = round($ordersTotal, 2);
        $data['visitorExistingCustomer'] = ($ordersTotal > 0) ? 'Yes' : 'No';

        return $data;
    }

}

?>
