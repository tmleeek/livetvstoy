<?php

class Zeon_Catalog_Block_Product_List extends Mage_Catalog_Block_Product_List
{

    /**
     * Retrieve loaded category collection
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected function _getProductCollection()
    {
        if (is_null($this->_productCollection)) {
            $layer = $this->getLayer();
            /* @var $layer Mage_Catalog_Model_Layer */
            if ($this->getShowRootCategory()) {
                $this->setCategoryId(Mage::app()->getStore()->getRootCategoryId());
            }

            // if this is a product view page
            if (Mage::registry('product')) {
                // get collection of categories this product is associated with
                $categories = Mage::registry('product')->getCategoryCollection()
                    ->setPage(1, 1)
                    ->load();
                // if the product is associated with any category
                if ($categories->count()) {
                    // show products from this category
                    $this->setCategoryId(current($categories->getIterator()));
                }
            }

            $origCategory = null;
            if ($this->getCategoryId()) {
                $category = Mage::getModel('catalog/category')->load($this->getCategoryId());
                if ($category->getId()) {
                    $origCategory = $layer->getCurrentCategory();
                    $layer->setCurrentCategory($category);
                    $this->addModelTags($category);
                }
            }
            //added for review summary
            $productCollection = $layer->getProductCollection();
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

            $this->_productCollection = $productCollection;

            $this->prepareSortableFieldsByCategory($layer->getCurrentCategory());

            if ($origCategory) {
                $layer->setCurrentCategory($origCategory);
            }
        }

        return $this->_productCollection;
    }
}