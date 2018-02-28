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
class Aitoc_Aitexporter_Model_Export_Type_Rma_Item implements Aitoc_Aitexporter_Model_Export_Type_Interface
{
    protected $_rmaItemFields = array();
    /**
     * 
     * @param SimpleXMLElement $rmaXml
     * @param Enterprise_Rma_Model_Rma $rma
     * @param Varien_Object $exportConfig
     */
    public function prepareXml(SimpleXMLElement $rmaXml, Mage_Core_Model_Abstract $rma, Varien_Object $exportConfig)
    {
        $rmaItemsXml = $rmaXml->addChild('items');
				
		$rmaItemsCollection = Mage::getResourceModel('enterprise_rma/item_collection')
                ->setOrderFilter($rma->getId());
		
		$fields = $this->getRmaItemFields();
				
		foreach ($rmaItemsCollection as $rmaItem)
        {
		    $rmaItem->load($rmaItem->getId());
		
            $rmaItemXml = $rmaItemsXml->addChild('item');
            
            //foreach($rmaItem->getData() as $field => $value)
            //{
            //    $rmaItemXml->addChild($field, (string)$value);
            //}
			
			foreach($fields as $field)
			{
			    $childValue = $rmaItem->getData($field);
				$rmaItemXml->addChild($field, $childValue);				
			}
        }
    }
	
	public function getRmaItemFields()
	{
	    if(count($this->_rmaItemFields) > 0)
		    return $this->_rmaItemFields;
			
		$collection = Mage::getResourceModel('enterprise_rma/item_collection')
                ->addAttributeToSelect('*');
        $collection->getSelect()
            ->limit(1,0);

        $fields = $collection->getData();
		
    	$fields = array_keys($fields[0]);

        $this->_rmaItemFields = array_unique(array_merge($fields, Mage::helper('aitexporter')->getEntityFields('rma_item')));	
		
		return $this->_rmaItemFields;
	}
}