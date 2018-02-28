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
class Zeon_Attributemapping_Block_View extends Mage_Core_Block_Template
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
        $charId  = $this->getRequest()->getParam('char', 0);
        $charId = preg_replace("/[^a-zA-Z]+/", "", $charId);
        $this->getRequest()->setParam('char', $charId);
        $attribute = $this->getAttributeData();

        $title = $this->__("All ".$attribute->getFrontendLabel().'s');
        if ($charId) {
            $title = $this->__("All '".strtoupper($charId)."' ".$attribute->getFrontendLabel().'s');
        }

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
            $charId  = $this->getRequest()->getParam('char', 0);
            $condition = '';
            if ($charId) {
                $condition .= ' AND IF (opt1.value_id > 0, opt1.value, opt0.value ) LIKE \''.$charId.'%\' ';
            }
            $condition .= 'ORDER BY value ASC';
            $attId = $this->getAttributeData()->getId();
            $store = $this->getStore()->getId();
            $sql = Mage::getSingleton('zeon_attributemapping/attributemapping')
                ->getCollection()
                ->getAttributeData($attId, $store, $condition);


                //echo $sql; exit;

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
            $code = Mage::helper('zeon_attributemapping')
                ->getConfigData('front_scroller/slider_attribute');
            $this->_attribute = Mage::getModel('eav/entity_attribute')
                ->loadByCode('catalog_product', $code);
        }
        return $this->_attribute;
    }

    /**
     *
     * get all populer active attributes
     */
    public function getPopularAttributeList()
    {
        $read = Mage::getSingleton('core/resource')
            ->getConnection('core_read');
        $condition = 'AND att_table.populer_character = \'1\''
            . ' ORDER BY att_table.populer_position ASC';
        $attId = $this->getAttributeData()->getId();
        $store = $this->getStore()->getId();
        $sql = Mage::getSingleton('zeon_attributemapping/attributemapping')
            ->getCollection()
            ->getAttributeData($attId, $store, $condition);
        $popularCharacters = $read->fetchAll($sql);
        return $popularCharacters;
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
