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

}