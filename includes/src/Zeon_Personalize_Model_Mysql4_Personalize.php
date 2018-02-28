<?php
class Zeon_Personalize_Model_Mysql4_Personalize extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('personalize/personalize', 'personalize_id');
    }   
}