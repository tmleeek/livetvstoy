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
class Aitoc_Aitexporter_Model_Export_Type_Rma extends Aitoc_Aitexporter_Model_Export_Type_Complex implements Aitoc_Aitexporter_Model_Export_Type_Interface
{
    /**
     * 
     * @param SimpleXMLElement $orderXml
     * @param Mage_Sales_Model_Order $order
     * @param Varien_Object $exportConfig
     */
    public function prepareXml(SimpleXMLElement $orderXml, Mage_Core_Model_Abstract $order, Varien_Object $exportConfig)
    {
        /* @var $order Mage_Sales_Model_Order */

        if(empty($exportConfig['entity_type']['rma']['rma']))
        {
            return false;
        }

        $rmasXml = $orderXml->addChild('rmas');
		
		$rmaCollection = Mage::getModel('enterprise_rma/rma')->getCollection()->addFieldToFilter('order_id', $order->getId());
		foreach($rmaCollection as $rma)
		{
		    $rmaXml = $rmasXml->addChild('rma');
			
			foreach ($rma->getData() as $field => $value)
            {
                switch ($field)
                {
                    case 'increment_id':
                        break;

                    default:
                        $rmaXml->addChild($field, $value);
                        break;
                }
            }
			
			$rmaXml->addChild('increment_id', $rma->getIncrementId());
            $rmaXml->addChild('increment_prefix', $this->_getIncrementPrefix($order, 'rma_item'));
                        
            $this->_exportChildData($rmaXml, $rma, $exportConfig);
		}
    }

    /**
     * 
     * @see Aitoc_Aitexporter_Model_Export_Type_Complex::getChildTypes()
     * @param Varien_Object $exportConfig
     * @return array
     */
    public function getChildTypes(Varien_Object $exportConfig)
    {
        $entityTypes = $exportConfig->getEntityType() ? $exportConfig->getEntityType() : array();
        $childTypes  = isset($entityTypes['rma']) ? $entityTypes['rma'] : array();

        if (isset($childTypes['rma']))
        {
            unset($childTypes['rma']);
        }

        return $childTypes;
    }
}