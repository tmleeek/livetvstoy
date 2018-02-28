<?php

class Magalter_Crossup_Model_Ruleactions extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        parent::_construct();
        $this->_init('magalter_crossup/ruleactions');
    }
}