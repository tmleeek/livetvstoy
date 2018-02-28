<?php
class Zeon_Sales_Model_Observer
{
    public function update_configigurable_price(Varien_Event_Observer $observer)
    {
        $params = Mage::app()->getRequest()->getParams();
        if (isset($params['child_product']) && 0 < intval($params['child_product'])
            && $params['product'] != $params['child_product']) {
            $item = $observer->getQuoteItem();
            $childProd = Mage::getModel('catalog/product')->load($params['child_product']);
            $childProdPrice = $childProd->getPrice();
            $finalPrice = $childProd->getFinalPrice();
            if ($childProdPrice != $finalPrice) {
                $childProdPrice = $finalPrice;
            }
            $item->setOriginalCustomPrice($childProdPrice);
        }
        return $this;
    }

    public function checkoutCartProductAddAfter(Varien_Event_Observer $observer)
    {
        $action = Mage::app()->getFrontController()->getAction();
        if (in_array($action->getFullActionName(), array('sales_order_reorder', 'adminhtml_sales_order_create_reorder'))
        ) {
            $item = $observer->getQuoteItem();
            $product = $observer->getQuoteItem()->getProduct();
            if ($product->getParentId() && $product->getParentId() != null ) {
                $parentItem = ( $item->getParentItem() ? $item->getParentItem() : '0' );
                $childProd = Mage::getModel('catalog/product')->load($product->getId());
                $childProdPrice = $childProd->getPrice();
                $finalPrice = $childProd->getFinalPrice();
                if ($childProdPrice != $finalPrice) {
                    $childProdPrice = $finalPrice;
                }
                $parentItem->setCustomPrice($childProdPrice);
                $parentItem->setOriginalCustomPrice($childProdPrice);
            }
        }
    }
    /**
     * Event: sales_order_invoice_pay
     *
     * Programatically Check old status and new status for order and
     * prevent status change if old status is 'pending_fulfillment' and new status is 'processing'
     * when the payment authorization occurs because our flow is
     * Pending -> Processing -> Pending Fullfillment -> Complete
     */
    public function preventOldStatus(Varien_Event_Observer $observer)
    {
        $invoice = $observer->getEvent()->getInvoice();
        /* @var $invoice Mage_Sales_Model_Order_Invoice */
        $order = $invoice->getOrder();
        /* @var $order Mage_Sales_Model_Order */
        //$order    = $observer->getEvent()->getOrder();

        if (!$order->getId()) {
            //order not saved in the database
            return $this;
        }

        if ($order->getState() == Mage_Sales_Model_Order::STATE_PROCESSING) {
            $oldStatus = $order->getOrigData('status');
            $newStatus = $order->getData('status');
            Mage::log('Old:'.$oldStatus.' New:'.$newStatus.' - Order#'.$order->getId(), null, 'orderStatus.log', true);
            if ($oldStatus == 'pending_fulfillment' && $newStatus == Mage_Sales_Model_Order::STATE_PROCESSING) {
                try {
                    $order->setStatus($oldStatus);
                    $order->addStatusHistoryComment('Pending Fulfillment Status Preserved', 'pending_fulfillment');
                    //$order->save();
                    Mage::log('Status Preserved for Order Id'.$order->getId(), null, 'orderStatus.log', true);
                } catch (Exception $e) {
                    Mage::logException($e);
                }
            }
        }
        return $this;
    }
}