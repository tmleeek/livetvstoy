<?php
class Zeon_Personalize_Block_View extends Mage_Core_Block_Template
{
    public function __construct()
    {
        parent::__construct();
    }

    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

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
            $this->_productCollection = $layer->getProductCollection();

            $this->prepareSortableFieldsByCategory($layer->getCurrentCategory());

            if ($origCategory) {
                $layer->setCurrentCategory($origCategory);
            }
        }

        return $this->_productCollection;
    }

    /**
     * Function to get attribute labels of all the selected product options.
     *
     * @return Array
     */
    public function getAttributeLabels()
    {
        // Var to store the POSTed data.
        $_postData = $this->getRequest()->getParams();

        // Create the product model object.
        $_productModel = Mage::getSingleton('catalog/product');

        // Var to store the attribute labels.
        $_labels = array();

        // Loop on all the super attribute.
        if (isset($_postData['super_attribute']) && is_array($_postData['super_attribute'])) {
            foreach ($_postData['super_attribute'] as $_attrId => $_attrValue) {
                $_attributeCode = Mage::getSingleton('eav/entity_attribute')
                    ->load($_attrId)
                    ->getAttributeCode();

                // Get the attribute
                $_attribute = $_productModel->getResource()->getAttribute($_attributeCode);

                //
                if ($_attribute->usesSource()) {
                    $_labels[] = $_attribute
                        ->getSource()
                        ->getOptionText($_postData['super_attribute'][$_attribute->getId()])
                        ;
                }
            }
        }

        // Return the labels.
        return $_labels;
    }

}