<?php
class Zeon_Bannerslider_Model_Resource_Banner
    extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('bannerslider/banner', 'banner_id');
    }

    public function getListBannerOfBlock($block)
    {
        try {
            $randomise = $block->getSortType() ? false : true;

            $today = date("Y-m-d");
            $select = $this->_getReadAdapter()->select()
                    ->from(
                        $this->getTable('banner'),
                        array('*', $randomise ? 'Rand() as order' : '')
                    )
                    ->where('bannerslider_id=?', $block->getId())
                    ->where('status=?', 1)
                    ->where('start_time <= ?', $today)
                    ->where('end_time >= ?', $today)
                    ->order("order", "ASC");

            $items = $this->_getReadAdapter()->fetchAll($select);
            //Zend_debug::dump($items);
            return $items;
        } catch (Exception $e) {

            return null;
        }
    }

}