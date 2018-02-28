<?php
/**
 * Orders Export and Import
 *
 * @category:    Aitoc
 * @package:     Aitoc_Aitexporter
 * @version      10.1.7
 * @license:     G0SwOduKhxI2ppsFsSxbJansCjYrTVcLKLwIiB2Xt7
 * @copyright:   Copyright (c) 2015 AITOC, Inc. (http://www.aitoc.com)
 */
class Aitoc_Aitexporter_Model_Import_Type_Shipment_Item implements Aitoc_Aitexporter_Model_Import_Type_Interface
{
  /**
     * 
     * @param SimpleXMLElement $orderXml
     * @param string $itemXpath
     * @param array $config
     * @param string $orderIncrementId
     * @return boolean is valid 
     */
    public function validate(SimpleXMLElement $orderXml, $itemXpath, array $config, $orderIncrementId = '')
    {
        $isValid = true;
        $itemXml = current($orderXml->xpath($itemXpath));

        // empty items from CSV 
        if (!trim(strip_tags($itemXml->asXML())))
        {
            return $isValid;
        }

        if (!isset($itemXml->sku))
        {
            Mage::getModel('aitexporter/import_error')
                ->setOrderIncrementId($orderIncrementId)
                ->setError(Mage::helper('aitexporter')->__('Shipment item %s does not have sku field', $itemXpath))
                ->save();
        }

        if (!isset($itemXml->qty))
        {
            Mage::getModel('aitexporter/import_error')
                ->setOrderIncrementId($orderIncrementId)
                ->setError(Mage::helper('aitexporter')->__('Shipment item %s does not have qty field', $itemXpath))
                ->save();
        }

        if (!isset($itemXml->order_item_id))
        {
            Mage::getModel('aitexporter/import_error')
                ->setOrderIncrementId($orderIncrementId)
                ->setError(Mage::helper('aitexporter')->__('Shipment item %s does not have order_item_id field. Shipment item will not be imported', $itemXpath))
                ->save();
        }
        else 
        {
            $itemFound = false;
            foreach ($itemXml->xpath('../../../../items/item') as $item)
            {
                if ((string)$item->item_id == $itemXml->order_item_id)
                {
                    $itemFound = true;
                    break;
                }
            }

            if (!$itemFound)
            {
                Mage::getModel('aitexporter/import_error')
                    ->setOrderIncrementId($orderIncrementId)
                    ->setError(Mage::helper('aitexporter')->__('Shipment item %s has order_item_id value that does not correspond to any order item\'s entity_id. Shipment item will not be imported', $itemXpath))
                    ->save();
            }
        }
        
        return $isValid;
    }

    /**
     * @param SimpleXMLElement $orderXml
     * @param string $itemXpath
     * @param array $importConfig
     * @param Mage_Sales_Model_Order_Shipment $parentItem
     */
    public function import(SimpleXMLElement $orderXml, $itemXpath, array $config, Mage_Core_Model_Abstract $parentItem = null)
    {
        /* @var $parentItem Mage_Sales_Model_Order_Shipment */
        $item    = Mage::getModel('sales/order_shipment_item');
        /* @var $item Mage_Sales_Model_Order_Shipment_Item */
        $itemXml = current($orderXml->xpath($itemXpath));
        /* @var $itemXml SimpleXMLElement */

        // empty items from CSV 
        if (!trim(strip_tags($itemXml->asXML())))
        {
            return false;
        }

        foreach ($itemXml->children() as $field)
        {
            switch ($field->getName())
            {
                case 'entity_id':
                case 'parent_id':
                    break;

                case 'order_item_id':
                    $item->setData('old_'.$field->getName(), (string)$field);
                    break;

                case 'store_id':
                    $item->setStoreId($parentItem->getStoreId());
                    break;

                case 'product_id':
                    if (!empty($itemXml->sku) || !empty($itemXml->base_sku))
                    {
                        $product = Mage::getModel('catalog/product')->loadByAttribute('sku', (string)$itemXml->sku);
            
                        if(!$product)
                        {
                            $product = Mage::getModel('catalog/product')->loadByAttribute('sku', (string)$itemXml->base_sku);
                        }
                        if ($product)
                        {
                            /* @var $product Mage_Catalog_Model_Product */
                            $item->setProductId($product->getId());
                        }
                    }
                    break;

                default:
                    $item->setData($field->getName(), (string)$field);
                    break;
            }
        }

        if ($item->getOldOrderItemId())
        {
            if(Mage::helper('aitexporter')->isPreorderEnabled())
            {
                $item->setShipment($parentItem)
                    ->setParentId($parentItem->getId())
                    ->setStoreId($parentItem->getStoreId());
        
                if (!$item->getId()) 
                {
                    $parentItem->getItemsCollection()->addItem($item);
                }
            }
            else
            {
                $parentItem->addItem($item);
            }
        }        
    }
    
    public function getXpath()
    {
        return '/items/item';
    }

    /**
     * @see Aitoc_Aitexporter_Model_Import_Type_Interface::getErrorType()
     * return string
     */
    public function getErrorType()
    {
        return Aitoc_Aitexporter_Model_Import_Error::TYPE_WARNING;
    }
}