<?php

class Magalter_Upsells_Model_Mysql4_Templates extends Mage_Core_Model_Mysql4_Abstract {

    protected function _construct() {

        $this->_init('magalter_upsells/templates', 'template_id');
    }

}