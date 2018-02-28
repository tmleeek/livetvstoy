<?php

/**
 * BuyRequest JSON model observer
 *
 * @category    Zeon
 * @package     Zeon_ProductOptionsJson
 * @author      Blake Bauman <blake.bauman@zeonsolutions.com>
 */
class Zeon_ProductOptionsJson_Model_Observer extends Mage_Core_Model_Abstract
{

    /**
     * Converts attributes and custom options to JSON
     * Save JSON object to line item order level
     *
     * @param   Varien_Event_Observer $observer
     * @return  Zeon_ProductOptionsJson_Model_Observer
     */
    public function saveProductOptionsJson(Varien_Event_Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $items = $order->getAllItems();

        foreach ($items as $item) {
            $options = (array)$item->getProductOptions();

            if (isset($options['attributes_info']) || isset($options['options'])) {
                $converted = Mage::helper('zeon_productoptionsjson')->process($options);
                if ($converted) {
                    $item->setProductOptionsJson(Mage::helper('core')->jsonEncode($converted));
                }
            }
        }

        return $this;
    }
}