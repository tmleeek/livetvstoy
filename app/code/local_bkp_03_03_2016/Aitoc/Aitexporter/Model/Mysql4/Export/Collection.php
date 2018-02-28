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
class Aitoc_Aitexporter_Model_Mysql4_Export_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('aitexporter/export');
    }

    public function loadLastCronExport($storeId)
    {
        $this->addFieldToFilter('is_cron', 1)
            ->addFieldToFilter('store_id', $storeId)
            ->setOrder('export_id', 'DESC');
        
        $this->getSelect()
            ->limit(1, 0);
        
        return $this;
    }
    
}