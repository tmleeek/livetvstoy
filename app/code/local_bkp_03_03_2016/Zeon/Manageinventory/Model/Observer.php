<?php
/**
 * Created by PhpStorm.
 * User: aniket.nimje
 * Date: 9/5/14
 * Time: 11:56 AM
 */
class Zeon_Manageinventory_Model_Observer extends Mage_Core_Model_Abstract
{
    /**
     * Function to lock product attributes in admin panel on edit action
     *
     * @param Varien_Object $observer
     *
     * @return void
     */
    public function lockProductInventoryAttributes($observer)
    {
        $allow = Mage::getSingleton('admin/session')
            ->isAllowed('admin/catalog/products/enable_inventory_edit');
        if (!$allow) {
            $product = $observer->getEvent()->getProduct();
            if ($product instanceof Mage_Catalog_Model_Product) {
                //making inventory tab's attribute read only.
                $product->setInventoryReadonly(true);
            }
        }
    }

    /**
     * Function to perform action before product save
     *
     * @param Varien_Object $observer
     *
     * @return void
     */
    public function catalogProductSaveBefore($observer)
    {
        $allow = Mage::getSingleton('admin/session')
            ->isAllowed('admin/catalog/products/enable_inventory_edit');
        if (!$allow) {
            $product = $observer->getProduct();
            $product->unsetData('stock_data');
            Mage::log($product->getData(), null, 'product-data.log');
        }
    }
}