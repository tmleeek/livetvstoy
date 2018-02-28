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
class Aitoc_Aitexporter_Model_Rewrite_SalesModelMysql4OrderShipmentComment extends Mage_Sales_Model_Mysql4_Order_Shipment_Comment
{
	/**
	 * To save correct create and update dates.
	 * 
	 * @override
	 */
	protected function _prepareDataForSave(Mage_Core_Model_Abstract $object)
	{
		if(Mage::registry('current_import'))
		{
            if(version_compare(Mage::getVersion(), '1.11.0.0', '>=')) {
                return Mage_Core_Model_Resource_Db_Abstract::_prepareDataForSave($object);
            } else {
			    return Mage_Core_Model_Mysql4_Abstract::_prepareDataForSave($object);
            }
		}
		else
		{
			return parent::_prepareDataForSave($object);
		}
	}
}