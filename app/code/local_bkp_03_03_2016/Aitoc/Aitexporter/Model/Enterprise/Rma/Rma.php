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
class Aitoc_Aitexporter_Model_Enterprise_Rma_Rma extends Enterprise_Rma_Model_Rma
{
    protected $_shippingLabels = array();
	protected $_statusesHistoryForImport = array();
	
    public function addItemToImportItemsCollection(Enterprise_Rma_Model_Item $item)
    {
        $this->_items[] = $item;
    }
	
	public function addItemToImportShippingLabelCollection(Enterprise_Rma_Model_Shipping $item)
    {
        $this->_shippingLabels[] = $item;
    }
	
	public function addItemToImportStatusHistoryCollection(Enterprise_Rma_Model_Rma_Status_History $item)
    {
        $this->_statusesHistoryForImport[] = $item;
    }
	
	public function getShippingLabelCollection()
	{
	    return $this->_shippingLabels;
	}
	
	public function getStatusHistoryCollection()
	{
	    return $this->_statusesHistoryForImport;
	}
}