<?php
/**
 * Zeon
 * Characters module
 *
 * @category   Zeon
 * @package    Zeon_Characters
 * @copyright  Copyright (c) 2008 Zeon Solutions.
 * @license    http://www.opensource.org/licenses/gpl-3.0.html GNU General Public License version 3
 */
class Zeon_Attributemapping_Block_Optionlist extends Mage_Core_Block_Template
{

    protected $_attribute = null;
    protected $_attributeList = null;
    protected $_store = null;

    protected function _construct()
    {
        parent::_construct();
    }

    /**
     * Prepare layout
     *
     * @return Mage_CatalogSearch_Block_Result
     */
    protected function _prepareLayout()
    {
        $attribute = $this->getAttributeData();
        $title = $this->__("All ".$attribute->getFrontendLabel().'s');
        // add Home breadcrumb
        if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbs->addCrumb(
                'home',
                array(
                    'label' => $this->__('Home'),
                    'title' => $this->__('Go to Home Page'),
                    'link'  => Mage::getBaseUrl()
                )
            )->addCrumb(
                'attribute',
                array(
                    'label' => $title,
                    'title' => $title
                )
            );
        }
        $this->getLayout()->getBlock('head')->setTitle($title);

        return parent::_prepareLayout();
    }

    /**
     *
     * set page title
     */
    public function setPageTitle()
    {
        $attribute = $this->getAttributeData();
        $title = $this->__("All ".$attribute->getFrontendLabel().'s');
        return $title;
    }

    /**
     *
     * get all active attributes
     */
    public function getAttributeList()
    {
        if (is_null($this->_attributeList)) {
            $read = Mage::getSingleton('core/resource')
                ->getConnection('core_read');
            $condition = 'ORDER BY value ASC';
            $attId = $this->getAttributeData()->getId();
            $store = $this->getStore()->getId();
            $sql = Mage::getSingleton('zeon_attributemapping/attributemapping')
                ->getCollection()
                ->getAttributeData($attId, $store, $condition);
            $this->_attributeList = $read->fetchAll($sql);
        }
        return $this->_attributeList;
    }

    /**
     *
     * set attribute data
     */
    public function getAttributeData()
    {
        if (!$this->_attribute) {
            $attributeId  = $this->getRequest()->getParam('id');
            $this->_attribute = Mage::getModel('eav/entity_attribute')
                ->load($attributeId);
        }
        return $this->_attribute;
    }

    /**
     *
     * set store data
     */
    public function getStore()
    {
        if (!$this->_store) {
            $this->_store = Mage::app()->getStore();
        }
        return $this->_store;
    }
}
