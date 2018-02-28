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
class Aitoc_Aitexporter_Model_Export_Type_Invoice_Item implements Aitoc_Aitexporter_Model_Export_Type_Interface
{
    /**
     * 
     * @param SimpleXMLElement $invoiceXml
     * @param Mage_Sales_Model_Order_Invoice $invoice
     * @param Varien_Object $exportConfig
     */
    public function prepareXml(SimpleXMLElement $invoiceXml, Mage_Core_Model_Abstract $invoice, Varien_Object $exportConfig)
    {
        /* @var $invoice Mage_Sales_Model_Order_Invoice */

        $invoiceItemsXml = $invoiceXml->addChild('items');

        foreach ($invoice->getItemsCollection() as $invoiceItem)
        {
            $invoiceItemXml = $invoiceItemsXml->addChild('item');
            
            foreach($invoiceItem->getData() as $field => $value)
            {
                $invoiceItemXml->addChild($field, (string)$value);
            }
        }
    }
}