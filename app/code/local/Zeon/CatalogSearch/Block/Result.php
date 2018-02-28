<?php
class Zeon_CatalogSearch_Block_Result extends Mage_CatalogSearch_Block_Result
{
    /**
     * Set search available list orders
     *
     * @return Mage_CatalogSearch_Block_Result
     */
    public function setListOrders()
    {
        $category = Mage::getSingleton('catalog/layer')
            ->getCurrentCategory();
        /* @var $category Mage_Catalog_Model_Category */
        $availableOrders = $category->getAvailableSortByOptions();
        unset($availableOrders['position']);
//        $availableOrders = array_merge(array(
//            'relevance' => $this->__('Relevance')
//        ), $availableOrders);

        $this->getListBlock()
            ->setAvailableOrders($availableOrders)
            ->setDefaultDirection('desc')
            ->setSortBy('name');

        return $this;
    }

}
