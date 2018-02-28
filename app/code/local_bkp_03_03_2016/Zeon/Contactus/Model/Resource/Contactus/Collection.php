<?php
class Zeon_Contactus_Model_Resource_Contactus_Collection
    extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('contactus/contactus');
    }
}