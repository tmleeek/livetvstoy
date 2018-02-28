<?php
class Zeon_Productdetail_Model_Resource_Review_Collection
    extends Mage_Review_Model_Resource_Review_Collection
{
    /**
     * init select
     *
     * @return Mage_Review_Model_Resource_Review_Product_Collection
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->getSelect()
            ->join(
                array('all_detail' => $this->_reviewDetailTable),
                'main_table.review_id = all_detail.review_id',
                array('location')
            );
        return $this;
    }
}