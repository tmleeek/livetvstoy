<?php
class Zeon_Bannerslider_Model_Resource_Bannerstores_Collection
    extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('bannerslider/bannerstores');
    }
}