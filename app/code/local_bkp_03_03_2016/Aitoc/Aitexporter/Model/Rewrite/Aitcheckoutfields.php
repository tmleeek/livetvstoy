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
class Aitoc_Aitexporter_Model_Rewrite_Aitcheckoutfields extends Aitoc_Aitcheckoutfields_Model_Aitcheckoutfields
{
    public function saveCustomOrderData($iOrderId, $sPageType)
    {
        if (!$iOrderId OR !$sPageType) return false;

        if($steps = $this->getCheckoutAtrributeList(1, 1, $sPageType, true))
        {
            foreach($steps as $stepPlaceholders)
            {
                foreach($stepPlaceholders as $placeholderFields)
                {
                    foreach(array_keys($placeholderFields) as $fieldId)
                    {
                        $this->_saveData($this->_sCustomAttrTable, $iOrderId, $sPageType, $fieldId, false);
                    }
                }
            }
        }
        
        $order = Mage::getModel('sales/order')->load($iOrderId);
        Mage::dispatchEvent('aitcheckoutfields_aitexporter', array('order' => $order));
        
    }
}