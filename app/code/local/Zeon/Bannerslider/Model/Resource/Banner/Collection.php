<?php
class Zeon_Bannerslider_Model_Resource_Banner_Collection
    extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected $_storeId = null;
    protected $_addedTable = array();

    public function _construct()
    {
        parent::_construct();
        if ($storeId = Mage::app()->getStore()->getId()) {
            $this->setStoreId($storeId);
        }
        $this->_init('bannerslider/banner');
    }

    public function setStoreId($value)
    {
        $this->_storeId = $value;
        return $this;
    }

    public function getStoreId()
    {
        return $this->_storeId;
    }

    //multi store
    protected function _afterLoad()
    {
        parent::_afterLoad();
        if ($storeId = $this->getStoreId()) {
            foreach ($this->_items as $item) {
                $item->setStoreId($storeId)->getMultiStoreValue();
            }
        }
        return $this;
    }

}