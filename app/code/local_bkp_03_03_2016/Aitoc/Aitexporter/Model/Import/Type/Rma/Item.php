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
class Aitoc_Aitexporter_Model_Import_Type_Rma_Item implements Aitoc_Aitexporter_Model_Import_Type_Interface
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

        if (!isset($itemXml->product_sku))
        {
            Mage::getModel('aitexporter/import_error')
                ->setOrderIncrementId($orderIncrementId)
                ->setError(Mage::helper('aitexporter')->__('RMA item %s does not have sku field', $itemXpath))
                ->save();
        }
		
		$productId = Mage::getResourceModel('catalog/product')->getIdBySku((string)$itemXml->product_sku);
        if (!$productId)
        {
            Mage::getModel('aitexporter/import_error')
            ->setOrderIncrementId((string)$orderXml->fields->increment_id)
            ->setError(Mage::helper('aitexporter')->__('Product `%s` doesn\'t exists', (string)$itemXml->name))
            ->setType(Aitoc_Aitexporter_Model_Import_Error::TYPE_ERROR)
            ->save();
        }

        if (!isset($itemXml->qty_requested))
        {
            Mage::getModel('aitexporter/import_error')
                ->setOrderIncrementId($orderIncrementId)
                ->setError(Mage::helper('aitexporter')->__('RMA item %s does not have qty field', $itemXpath))
                ->save();
        }

        if (!isset($itemXml->order_item_id))
        {
            Mage::getModel('aitexporter/import_error')
                ->setOrderIncrementId($orderIncrementId)
                ->setError(Mage::helper('aitexporter')->__('RMA item %s does not have order_item_id field. It will not be imported', $itemXpath))
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
                    ->setError(Mage::helper('aitexporter')->__('RMA item %s has order_item_id value that does not correspond to any order item\'s entity_id. RMA item will not be imported', $itemXpath))
                    ->save();
            }
        }
        return $isValid;
    }

    /**
     * @param SimpleXMLElement $orderXml
     * @param string $itemXpath
     * @param array $importConfig
     * @param Mage_Core_Model_Abstract $parentItem
     */
    public function import(SimpleXMLElement $orderXml, $itemXpath, array $config, Mage_Core_Model_Abstract $parentItem = null)
    {
        /* @var $parentItem Aitoc_Aitexporter_Model_Enterprise_Rma_Rma */
        $item    = Mage::getModel('enterprise_rma/item');
        /* @var $item Enterprise_Rma_Model_Item */
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
                case 'rma_entity_id':
                    break;

                //case 'shipment_id':
                case 'order_item_id':
                    $item->setData('old_'.$field->getName(), (string)$field);
                    break;

                case 'store_id':
                    $item->setStoreId($parentItem->getStoreId());
                    break;
			    case 'product_options':
                    $productOptions = unserialize(str_replace("&quot;",'"',(string)$field));
                    if (!is_array($productOptions))
                    {
                        continue;
                    }

                    if (isset($productOptions['options']) && is_array($productOptions['options']))
                    {
                        foreach ($productOptions['options'] as & $option)
                        {
                            $option['option_id'] = 0;
                            if (!empty($option['option_sku']))
                            {
                                $optionModel = Mage::getModel('catalog/product_option')->load($option['option_sku'], 'sku');
                                if ($optionModel->getId())
                                {
                                    $option['option_id'] = $optionModel->getId();
                                }
                                else
                                {
                                    $optionValue = Mage::getModel('catalog/product_option_value')->load($option['option_sku'], 'sku');
    
                                    if ($optionValue->getId())
                                    {
                                        $option['option_id'] = (int)$optionValue->getId();
                                    }
                                }
    
                                unset($option['option_sku']);
                            }
                        }
                    }

                    $item->setProductOptions($productOptions);
                    break;

                default:
                    $item->setData($field->getName(), (string)$field);
                    break;
            }
        }
     
        if ($item->getOldOrderItemId())
        {
		    //$parentItem->addItem($item);
			$item->setRmaEntityId($parentItem->getId())
				->setStoreId($parentItem->getStoreId());

            if (!$item->getId()) 
			{
                $parentItem->addItemToImportItemsCollection($item);
				//$parentItem->getItemsCollection()->addItem($item);
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
        return false;
    }
}