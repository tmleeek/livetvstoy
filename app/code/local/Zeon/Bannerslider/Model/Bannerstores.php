<?php
class Zeon_Bannerslider_Model_Bannerstores extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('bannerslider/bannerstores');
    }
}