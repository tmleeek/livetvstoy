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
class Zeon_Catalog_Block_Category_List_Toolbar
    extends Mage_Page_Block_Html_Pager
{
    protected $_modeVarName         = 'c_mode';
    protected $_availableMode       = array();
    protected $_enableViewSwitcher  = true;
    protected $_isExpanded          = true;

    protected function _construct()
    {
        parent::_construct();

        switch (
            Mage::getStoreConfig('catalog/category_list/category_display_mode')
        ) {
            case 'grid':
                $this->_availableMode = array('grid' => $this->__('Grid'));
                break;

            case 'list':
                $this->_availableMode = array('list' => $this->__('List'));
                break;

            case 'grid-list':
                $this->_availableMode = array(
                    'grid' => $this->__('Grid'),
                    'list' =>  $this->__('List')
                );
                break;

            case 'list-grid':
                $this->_availableMode = array(
                    'list' => $this->__('List'),
                    'grid' => $this->__('Grid')
                );
                break;
        }
        $this->setPageVarName('c_p');
        $this->setLimitVarName('c_limit');
        $this->setTemplate('catalog/category/list/toolbar.phtml');
    }


    public function setCollection($collection)
    {
        parent::setCollection($collection);
        if ($this->getCurrentOrder()) {
            $this->getCollection()
                ->setOrder(
                    $this->getCurrentOrder(),
                    $this->getCurrentDirection()
                );
        }
        return $this;
    }

    public function getModeVarName()
    {
        return $this->_modeVarName;
    }


    public function getCurrentMode()
    {
        $mode = $this->getRequest()->getParam($this->getModeVarName());
        if ($mode) {
            Mage::getSingleton('catalog/session')->setDisplayMode($mode);
        } else {
            $mode = Mage::getSingleton('catalog/session')->getDisplayMode();
        }

        if ($mode && isset($this->_availableMode[$mode])) {
            return $mode;
        }
        return current(array_keys($this->_availableMode));
    }

    public function isModeActive($mode)
    {
        return $this->getCurrentMode() == $mode;
    }

    public function getModes()
    {
        return $this->_availableMode;
    }

    public function setModes($modes)
    {
        if (!isset($this->_availableMode)) {
            $this->_availableMode = $modes;
        }
        return $this;
    }

    public function getModeUrl($mode)
    {
        return $this->getPagerUrl(array($this->getModeVarName()=>$mode));
    }

    public function disableViewSwitcher()
    {
        $this->_enableViewSwitcher = false;
        return $this;
    }

    public function enableViewSwitcher()
    {
        $this->_enableViewSwitcher = true;
        return $this;
    }

    public function isEnabledViewSwitcher()
    {
        return $this->_enableViewSwitcher;
    }

    public function disableExpanded()
    {
        $this->_isExpanded = false;
        return $this;
    }

    public function enableExpanded()
    {
        $this->_isExpanded = true;
        return $this;
    }

    public function isExpanded()
    {
        return $this->_isExpanded;
    }

    public function getDefaultPerPageValue()
    {
        if ($this->getCurrentMode() == 'list') {
            if ($default = $this->getDefaultListPerPage()) {
                return $default;
            }
            return Mage::getStoreConfig(
                'catalog/category_list/category_list_per_page'
            );
        } else if ($this->getCurrentMode() == 'grid') {
            if ($default = $this->getDefaultGridPerPage()) {
                return $default;
            }
            return Mage::getStoreConfig(
                'catalog/category_list/category_grid_per_page'
            );
        }
        return 0;
    }

    public function addPagerLimit($mode, $value, $label='')
    {
        if (!isset($this->_availableLimit[$mode])) {
            $this->_availableLimit[$mode] = array();
        }
        $this->_availableLimit[$mode][$value] = empty($label) ? $value : $label;
        return $this;
    }

    public function getAvailableLimit()
    {
        if ($this->getCurrentMode() == 'list') {
            if (isset($this->_availableLimit['list'])) {
                return $this->_availableLimit['list'];
            }
            $perPageValues = (string)Mage::getStoreConfig(
                'catalog/category_list/category_list_per_page_values'
            );
            $perPageValues = explode(',', $perPageValues);
            $perPageValues = array_combine($perPageValues, $perPageValues);
            return ($perPageValues + array('all'=>$this->__('All')));
        } elseif ($this->getCurrentMode() == 'grid') {
            if (isset($this->_availableLimit['grid'])) {
                return $this->_availableLimit['grid'];
            }
            $perPageValues = (string)Mage::getStoreConfig(
                'catalog/category_list/category_grid_per_page_values'
            );
            $perPageValues = explode(',', $perPageValues);
            $perPageValues = array_combine($perPageValues, $perPageValues);
            return ($perPageValues + array('all'=>$this->__('All')));
        }
        return parent::getAvailableLimit();
    }

    public function getLimit()
    {
        $limits = $this->getAvailableLimit();
        $limit = $this->getRequest()->getParam($this->getLimitVarName());

        if ($limit && isset($limits[$limit])) {
            Mage::getSingleton('catalog/session')->setLimitPage($limit);
        } else {
            $limit = Mage::getSingleton('catalog/session')->getLimitPage();
        }
        if (isset($limits[$limit])) {
            return $limit;
        }
        if ($limit = $this->getDefaultPerPageValue()) {
            if (isset($limits[$limit])) {
                return $limit;
            }
        }

        $limits = array_keys($limits);
        return $limits[0];
    }
}
