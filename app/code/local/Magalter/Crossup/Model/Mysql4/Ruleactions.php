<?php

class Magalter_Crossup_Model_Mysql4_Ruleactions extends Mage_Core_Model_Mysql4_Abstract
{

    protected function _construct()
    {
        $this->_init('magalter_crossup/rule_actions', 'action_id');
    }

    public function getActionProducts($ruleId, $storeId)
    {
        $select = $this->_getReadAdapter()->select()
                ->from($this->getMainTable(), array('product_ids'))
                ->where('rule_id = ?', $ruleId)
                ->where('store_id = ?', $storeId);

        return $this->_getReadAdapter()->fetchCol($select);
    }

}