<?php
class Zeon_Personalize_Model_Personalize extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('personalize/personalize');
    }

    public function getPersonalizedInformation($_prodId, $_custId, $_sku = null, $_itemId = null)
    {
        $resource = Mage::getSingleton('core/resource')->getConnection('core_read');

        $tablePersonalize = Mage::getSingleton('core/resource')->getTableName('personalize');

        $select = $resource->select()
                ->from(array('s' => $tablePersonalize))
                ->where('product_id = (?)', $_prodId)
                //->orWhere('productCode = (?)', $_prodId)
                ->where('customer_id = (?)', $_custId);

        // Check, if SKU is passed in parameter then the add that in condition to fetch exact value.
        if (!empty($_sku)) {
            $select->where('sku = (?)', $_sku);
        }

        // Check, if ITEM-ID is passed in parameter then the add that in condition to fetch exact value.
        if (!empty($_itemId)) {
            $select->where('item_id = (?)', $_itemId);
        }

        $select->order(array('personalize_id DESC'))->limit(1);

        return $resource->fetchRow($select);
    }

    public function getPersonalizedByOrderId($_prodId, $_orderID, $_sku = null, $_itemId = null)
    {
        $resource = Mage::getSingleton('core/resource')->getConnection('core_read');

        $tablePersonalize = Mage::getSingleton('core/resource')->getTableName('personalize');

        $select = $resource->select()
                ->from(array('s' => $tablePersonalize))
                ->where('product_id = (?)', $_prodId)
                ->where('order_id = (?)', $_orderID);

        // Check, if SKU is passed in parameter then the add that in condition to fetch exact value.
        if (!empty($_sku)) {
            $select->where('sku = (?)', $_sku);
        }

        // Check, if ITEM-ID is passed in parameter then the add that in condition to fetch exact value.
        if (!empty($_itemId)) {
            $select->where('item_id = (?)', $_itemId);
        }

        $select->order(array('design_id DESC'))->limit(1);

        return $resource->fetchRow($select);
    }

    public function getPersonalizedDetailsByOrderId($_orderID)
    {
        $resource = Mage::getSingleton('core/resource')->getConnection('core_read');

        $tablePersonalize = Mage::getSingleton('core/resource')->getTableName('personalize');

        $select = $resource->select()
                ->from(array('s' => $tablePersonalize))
                ->where('order_id = (?)', $_orderID);

        $select->order(array('design_id DESC'))->limit(1);

        return $resource->fetchRow($select);
    }

    /**
     *  This Function is used to get the customer ID from personalize table
     *  $_custId is the customerId of the image
     */
    public function getPersonalizeByCustId($_custId)
    {
        $resource = Mage::getSingleton('core/resource')->getConnection('core_read');

        $tablePersonalize = Mage::getSingleton('core/resource')->getTableName('personalize');

        $select = $resource->select('personalize_id, productcode, design_id')
            ->from(array('s' => $tablePersonalize))
            ->where('customer_id = (?)', $_custId);
        $select->order(array('personalize_id DESC'));
        return $resource->fetchAll($select);
    }

    /**
     * Get personalize data by customer data and limit
     */
    public function getPersonalizedDetailsByCustomerId($_custId, $_limit)
    {
        $resource = Mage::getSingleton('core/resource')->getConnection('core_read');

        $tablePersonalize = Mage::getSingleton('core/resource')->getTableName('personalize');

        $select = $resource->select()
            ->from(array('s' => $tablePersonalize))
            ->where('customer_id = (?)', $_custId);
        $select->order(array('personalize_id DESC'))->limit($_limit);

        return $resource->fetchAll($select);
    }

}