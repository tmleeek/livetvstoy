<?php
class Zeon_Attributemapping_Model_Resource_Attributemapping
    extends Mage_Core_Model_Resource_Db_Abstract
{
    protected $_storeId;

    protected function _construct()
    {
        $this->_init('zeon_attributemapping/attributemapping', 'mapping_id');
        $this->_storeId = (int)Mage::app()->getStore()->getId();
        if (Mage::app()->getStore()->isAdmin()) {
            if ( Mage::app()->getRequest()->getParam('store') ) {
                $this->_storeId = Mage::app()->getRequest()->getParam('store');
            }
        }
    }

/**
     * Retrieve store for resource model
     *
     * @return int
     */
    public function getStoreId()
    {
        return $this->_storeId;
    }

    /**
     * Set store for resource model
     *
     * @param mixed $store
     * @return Mage_Catalog_Model_Resource_Product_Flat
     */
    public function setStoreId($store)
    {
        if (is_int($store)) {
            $this->_storeId = $store;
        } else {
            $this->_storeId = (int)Mage::app()->getStore($store)->getId();
        }
        return $this;
    }

    /**
     * Retrieve Flat Table name
     *
     * @param mixed $store
     * @return string
     */
    public function getFlatTableName($store = null)
    {
        if ($store === null) {
            $store = $this->getStoreId();
        }
        return $this->getTable(
            array('zeon_attributemapping/attributemapping', $store)
        );
    }

    /**
     * Retrieve main resource table name
     *
     * @return string
     */
    public function getMainTable()
    {
        return $this->getFlatTableName($this->getStoreId());
    }


}