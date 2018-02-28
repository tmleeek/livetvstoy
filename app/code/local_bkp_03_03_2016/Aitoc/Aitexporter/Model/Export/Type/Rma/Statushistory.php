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
class Aitoc_Aitexporter_Model_Export_Type_Rma_Statushistory implements Aitoc_Aitexporter_Model_Export_Type_Interface
{
    /**
     * 
     * @param SimpleXMLElement $rmaXml
     * @param MEnterprise_Rma_Model_Rma $rma
     * @param Varien_Object $exportConfig
     */
    public function prepareXml(SimpleXMLElement $rmaXml, Mage_Core_Model_Abstract $rma, Varien_Object $exportConfig)
    {
        if (empty($exportConfig['entity_type']['rma']['rma_statushistory']))
        {
            return false;
        }

        $statusesHistoryXml = $rmaXml->addChild('statuseshistory');

		$rmaStatusHistoryCollection = Mage::getModel('enterprise_rma/rma_status_history')->getCollection()->addFieldToFilter('rma_entity_id', $rma->getId());
        foreach ($rmaStatusHistoryCollection as $statusHistory)
        {
            $statusHistoryXml = $statusesHistoryXml->addChild('statushistory');
            
            foreach($statusHistory->getData() as $field => $value)
            {
                $statusHistoryXml->addChild($field, $value);
            }
        }
    }
}