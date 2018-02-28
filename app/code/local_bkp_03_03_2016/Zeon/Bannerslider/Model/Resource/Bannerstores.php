<?php
class Zeon_Bannerslider_Model_Resource_Bannerstores
    extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('bannerslider/bannerstores', 'id');
    }
}