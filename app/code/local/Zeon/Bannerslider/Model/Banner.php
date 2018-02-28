<?php
class Zeon_Bannerslider_Model_Banner extends Mage_Core_Model_Abstract
{
    protected $_storeId = null;

    public function _construct()
    {
        parent::_construct();
        if ($storeId = Mage::app()->getStore()->getId()) {
            $this->setStoreId($storeId);
        }
        $this->_init('bannerslider/banner');
    }

    public function getStoreId()
    {
        return $this->_storeId;
    }

    public function setStoreId($id)
    {
        $this->_storeId = $id;
        return $this;
    }

    //info multistore
    public function load($id, $field = null)
    {
        parent::load($id, $field);
        if ($this->getStoreId()) {
            $this->getMultiStoreValue();
        }
        return $this;
    }

}