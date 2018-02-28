<?php
class Zeon_Bannerslider_Block_Bannerslider extends Mage_Core_Block_Template
{
    public function getAllBanners()
    {
        $storeId = Mage::app()->getStore()->getStoreId();
        $collection = Mage::getModel('bannerslider/banner')->getCollection();
        $collection->addFieldToFilter('stores.store_id', array($storeId, 0))
            ->addFieldToFilter('main_table.status', '1')
            ->addfieldtofilter(
                'main_table.start_time',
                array(
                    array('to' => Mage::getModel('core/date')->gmtDate()),
                    array('main_table.start_time', 'null'=>'')
                )
            )
            ->addfieldtofilter(
                'main_table.end_time',
                array(
                    array('gteq' => Mage::getModel('core/date')->gmtDate()),
                    array('main_table.end_time', 'null'=>'')
                )
            );
        $collection->getSelect()
            ->order('main_table.sort_order ASC')
            ->group('main_table.banner_id')
            ->joinLeft(
                array(
                    'stores' => 'bannerslider_banner_stores'
                ),
                'stores.banner_id = main_table.banner_id',
                array()
            );
        $completeData = $collection->getData();
        $bannerData = array();
        foreach ($completeData as $data) {
            $bannerData[] = $data;
        }
        return $bannerData;
    }

}