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
class Aitoc_Aitexporter_Model_Export_Order extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();

        $this->_init('aitexporter/export_order');
    }

    public function isOrdersExported($profile_id = 0)
    {
        $exportedOrders = $this->getCollection()
            ->loadByProfileId($profile_id);

        return sizeof($exportedOrders) == 1;
    } 
    
    public function assignOrders(Aitoc_Aitexporter_Model_Export $export)
    {
        $this->getResource()->assignOrders($export);

        return $this;
    }
}