<?php
/**
 * Zeon Attribute Mapping module
 *
 * @category   Zeon
 * @package    Zeon_Attributemapping
 * @copyright  Copyright (c) 2008 Zeon Solutions.
 * @license    http://www.opensource.org/licenses/gpl-3.0.html GNU General
 * Public License version 3
 */
class Zeon_Attributemapping_Block_Productlist
    extends Mage_Catalog_Block_Product_List
{
    /**
     * Retrieve character product collection
     *
     * @return attribute product collection
     */
    protected function _getProductCollection()
    {
        if (is_null($this->_productCollection)) {
            $option = $this->getOptionData();
            $attributeCode = $option['attribute_code'];
            $layer = $this->getLayer();
            $layer->getCurrentCategory()->setIsAnchor(1);
            $productCollection = $layer->getProductCollection();
            //added for review summary
            $storeId = Mage::app()->getStore()->getStoreId();
            $reviewSummaryTable   =  Mage::getSingleton('core/resource')->getTableName('review/review_aggregate');
            $productCollection->getSelect()
                ->joinLeft(
                    array('review' => $reviewSummaryTable),
                    "e.entity_id = review.entity_pk_value AND review.store_id = ".$storeId,
                    array(
                        'review.primary_id as review_id', 'review.reviews_count as review_count',
                        'review.rating_summary as review_summary',
                        'review.store_id as rev_store_id'
                    )
                );
            if ($option['input_type'] == 'multiselect') {
                $products = $productCollection
                    ->addAttributeToFilter(
                        $attributeCode,
                        array("finset"=>array($option['value']))
                    );
            } else {
                $products = $productCollection
                    ->addAttributeToFilter(
                        $attributeCode,
                        array('eq' =>$option['value'])
                    );
            }
            $this->_productCollection = $products;
        }

        return $this->_productCollection;
    }

    public function isPoptropicaCharacter()
    {
        return Mage::registry('isPoptropicaCharacter');
    }

}
