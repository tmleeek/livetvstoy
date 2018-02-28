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
class Aitoc_Aitexporter_Model_Import_Type_Rma_Statushistory implements Aitoc_Aitexporter_Model_Import_Type_Interface
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
        $statusXml = current($orderXml->xpath($itemXpath));

        // empty items from CSV 
        if (!trim(strip_tags($statusXml->asXML())))
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
        $history   = Mage::getModel('enterprise_rma/rma_status_history');
        /* @var $history Enterprise_Rma_Model_Rma_Status_History */
        $statusXml = current($orderXml->xpath($itemXpath));
        /* @var $statusXml SimpleXMLElement */

        // empty items from CSV 
        if (!trim(strip_tags($statusXml->asXML())))
        {
            return false;
        }

        foreach ($statusXml->children() as $field)
        {
            switch ($field->getName())
            {
                case 'entity_id':
                case 'rma_entity_id':
                    break;
                
                case 'status':
                    $fieldName = $field->getName();
                    $field = (string) $field;
                    if(!Mage::helper('aitexporter')->isPreorderEnabled())
                    {
                        $field = str_replace('preorder', '', $field);
                    }
                    $history->setData($fieldName, (string)$field);
                    break;

                default:
                    $history->setData($field->getName(), (string)$field);
                    break;
            }
        }

		if (!$history->getId()) 
		{
		    $parentItem->addItemToImportStatusHistoryCollection($history);
		}
    }

    public function getXpath()
    {
        return '/statuseshistory/statushistory';
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