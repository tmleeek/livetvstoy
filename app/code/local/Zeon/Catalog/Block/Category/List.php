<?php
/**
 * Zeon Solutions
 * Catalog Module
 * The Catalog module has been overridden.
 * refer the text file for details.
 *
 * @category   Zeon
 * @package    Zeon_Catalog
 * @copyright  Copyright (c) 2008 Zeon Solutions (http://www.zeonsolutions.com/)
 * @version    1.01
 * @date       jan 29 2009 1846 IST
 */

class Zeon_Catalog_Block_Category_List extends Mage_Catalog_Block_Navigation
{
    protected $_defaultToolbarBlock = 'catalog/category_list_toolbar';
    protected $_categoryCollection;
    protected $_categoryCollectionNew;

    /**
     * Retrieve child categories of current category, if selected.
     *
     * @return Varien_Data_Tree_Node_Collection
     */
    public function getCurrentChildCategories()
    {
        if (is_null($this->_categoryCollection)) {
            // Read the current category.
            $layer = Mage::getSingleton('catalog/layer');

            /* @var $category Mage_Catalog_Model_Category */
            $this->_categoryCollection = Mage::getModel('catalog/category')
                ->getCollection();
            /* @var $collection
             * Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection */
            $this->_categoryCollection->addAttributeToSelect('url_key')
                ->addAttributeToSelect('name')
                ->addAttributeToSelect('image')
                ->addAttributeToSelect('thumbnail')
                ->addAttributeToSelect('is_anchor')
                ->addAttributeToFilter('is_active', 1);

            if ( $category   = $layer->getCurrentCategory() ) {
                $this->_categoryCollection
                    ->addIdFilter($category->getChildren());
            }

            $this->_categoryCollection->setOrder('position', 'ASC');
        }
        return $this->_categoryCollection;
    }

    protected function _beforeToHtml()
    {

        $toolbar = $this->getToolbarBlock();
        if ($orders = $this->getAvailableOrders()) {
            $toolbar->setAvailableOrders($orders);
        }
        if ($modes = $this->getModes()) {
            $toolbar->setModes($modes);
        }
        $toolbar->setCollection($this->getCurrentChildCategories());
        $this->setChild('toolbar', $toolbar);

        return parent::_beforeToHtml();
    }

    public function getToolbarBlock()
    {
        if ($blockName = $this->getToolbarBlockName()) {
            if ($block = $this->getLayout()->getBlock($blockName)) {
                return $block;
            }
        }
        $block = $this->getLayout()
            ->createBlock($this->_defaultToolbarBlock, microtime());
        return $block;
    }

    /**
     * Retrieve list toolbar HTML
     *
     * @return string
     */
    public function getToolbarHtml()
    {
        return $this->getChildHtml('toolbar');
    }

    public function getMode()
    {
        return $this->getChild('toolbar')->getCurrentMode();
    }

    /**
     * Method used to get the config setting of the passed field.
     *
     * @param String $configName
     *
     * @return Array
     */
    public function getConfigDetails($configName)
    {
        return Mage::getStoreConfig('catalog/' . $configName);
    }

}