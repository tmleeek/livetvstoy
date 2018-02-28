<?php
/**
 * Created by PhpStorm.
 * User: piyush.sahu
 * Date: 5/23/14
 * Time: 10:48 AM
 */
class Zeon_Presonalize_Model_Resource_Eav_Mysql4_Product_Collection
    extends Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
{

    /**
     * Get SQL for get record count
     *
     * @return Varien_Db_Select
     */
    public function getSelectCountSql()
    {
        $this->_renderFilters();

        $countSelect = clone $this->getSelect();
        $countSelect->reset(Zend_Db_Select::ORDER);
        $countSelect->reset(Zend_Db_Select::LIMIT_COUNT);
        $countSelect->reset(Zend_Db_Select::LIMIT_OFFSET);
        $countSelect->reset(Zend_Db_Select::COLUMNS);

        $countSelect->columns('COUNT(DISTINCT e.entity_id)');
        $countSelect->resetJoinLeft();

        //  $countSelect->columns('COUNT(*)'); // original code
        if(count($this->getSelect()->getPart(Zend_Db_Select::GROUP)) > 0) { // handles group by. from stackoverflow
            $countSelect->reset(Zend_Db_Select::GROUP);
            //$countSelect->distinct(true);
            $group = $this->getSelect()->getPart(Zend_Db_Select::GROUP);
            $countSelect->reset(Zend_Db_Select::COLUMNS);
            $countSelect->columns(new Zend_Db_Expr("COUNT(DISTINCT ".implode(", ", $group).")"));
            $countSelect->columns(new Zend_Db_Expr('COUNT(DISTINCT e.entity_id)'));
        } else { // if no group by, handle the original way
            $countSelect->columns('COUNT(*)');
        }
        return $countSelect;
    }
}