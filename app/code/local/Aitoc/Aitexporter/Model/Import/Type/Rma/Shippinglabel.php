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
class Aitoc_Aitexporter_Model_Import_Type_Rma_Shippinglabel implements Aitoc_Aitexporter_Model_Import_Type_Interface
{
    private $_historyPool = array();

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
        $isValid   = true;
        $shippingLabelXml = current($orderXml->xpath($itemXpath));

        // empty items from CSV 
        if (!trim(strip_tags($shippingLabelXml->asXML())))
        {
            return $isValid;
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
        $shippingLabel   = Mage::getModel('enterprise_rma/shipping');
        /* @var $history Enterprise_Rma_Model_Shipping */
        $shippingLabelXml = current($orderXml->xpath($itemXpath));
        /* @var $statusXml SimpleXMLElement */

        // empty items from CSV 
        if (!trim(strip_tags($shippingLabelXml->asXML())))
        {
            return false;
        }

        foreach ($shippingLabelXml->children() as $field)
        {
            switch ($field->getName())
            {
                case 'entity_id':
                case 'rma_entity_id':
                    break;
                
                default:
                    $shippingLabel->setData($field->getName(), (string)$field);
                    break;
            }
        }

        		
		if (!$shippingLabel->getId()) 
			{
                $parentItem->addItemToImportShippingLabelCollection($shippingLabel);
				//$parentItem->getItemsCollection()->addItem($item);
            }
    }

    public function getXpath()
    {
        return '/shippinglabels/shippinglabel';
    }

    /**
     * @see Aitoc_Aitexporter_Model_Import_Type_Interface::getErrorType()
     * @return string
     */
    public function getErrorType()
    {
        return false;
    }
}