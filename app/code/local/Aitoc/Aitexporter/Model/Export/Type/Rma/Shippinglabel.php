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
class Aitoc_Aitexporter_Model_Export_Type_Rma_Shippinglabel implements Aitoc_Aitexporter_Model_Export_Type_Interface
{
    /**
     * 
     * @param SimpleXMLElement $rmaXml
     * @param MEnterprise_Rma_Model_Rma $rma
     * @param Varien_Object $exportConfig
     */
    public function prepareXml(SimpleXMLElement $rmaXml, Mage_Core_Model_Abstract $rma, Varien_Object $exportConfig)
    {
        if (empty($exportConfig['entity_type']['rma']['rma_shippinglabel']))
        {
            return false;
        }

        $shippingLabelsXml = $rmaXml->addChild('shippinglabels');

		$rmaShippingLabelsCollection = Mage::getModel('enterprise_rma/shipping')->getCollection()->addFieldToFilter('rma_entity_id', $rma->getId());
        foreach ($rmaShippingLabelsCollection as $shippingLabel)
        {
            $shippingLabelXml = $shippingLabelsXml->addChild('shippinglabel');
            
            foreach($shippingLabel->getData() as $field => $value)
            {
                $shippingLabelXml->addChild($field, $value);
            }
        }
    }
}