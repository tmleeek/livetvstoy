<?php
/**
 * Zeon Solutions, Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Zeon Solutions License
 * that is bundled with this package in the file LICENSE_ZE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.zeonsolutions.com/license/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zeonsolutions.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to
 * versions in the future. If you wish to customize this extension for your
 * needs please refer to http://www.zeonsolutions.com for more information.
 *
 * @category    Fqs
 * @package     Fqs_Attributemapping
 * @copyright   Copyright (c) 2012 Zeon Solutions, Inc.
 * All Rights Reserved.(http://www.zeonsolutions.com)
 * @license     http://www.zeonsolutions.com/license/
 */

class Zeon_Attributemapping_Block_Adminhtml_Attributemapping
    extends Mage_Adminhtml_Block_Widget_Container
{
    /**
     * Set template
     */
    public function __construct()
    {
        parent::__construct();
        $this->removeButton('add');
        $this->setTemplate('zeon/attributemapping/grid.phtml');
    }

    /**
     * get store data
     */
    protected function _getStoreId()
    {
        $defaultStore = $storeId = Mage::helper('zeon_attributemapping')
            ->getTopStore();
        $storeId = (int) $this->getRequest()->getParam('store', $defaultStore);
        return $storeId;
    }

    /**
     * Prepare button and grid
     *
     * @return Mage_Adminhtml_Block_Catalog_Product
     */
    protected function _prepareLayout()
    {
        $attributeId  = (int) $this->getRequest()->getParam('id');
        if ($attributeId) {
            $this->_addButton(
                'back',
                array(
                    'label'     => Mage::helper('zeon_attributemapping')
                        ->__('Back'),
                    'onclick'   => "setLocation('"
                        .$this->getUrl('/index/')."');",
                    'class'        => "back"
                )
            );
            $attributeInfo = Mage::getModel('eav/entity_attribute')
                ->load($attributeId);
            $this->_addButton(
                'add',
                array(
                    'label'     => Mage::helper('zeon_attributemapping')->__(
                        'Add Option For '.$attributeInfo
                        ->getData('frontend_label')
                    ),
                    'onclick' => "openMyPopup('".$this
                        ->getUrl(
                            '/index/addoptions/',
                            array(
                                'id'=>$attributeId,
                                'store'=>$this->_getStoreId()
                            )
                        )."', 'Add Options For ".$attributeInfo
                            ->getData('frontend_label')."')",
                    'class'        => "add"
                )
            );
        } else {
            $this->_addButton(
                'back',
                array(
                    'label'     => Mage::helper('zeon_attributemapping')
                        ->__('Back'),
                    'onclick'   => "setLocation('".$this
                        ->getUrl('/catalog_product_attribute')."');",
                    'class'     => "back"
                )
            );
        }

        return parent::_prepareLayout();
    }

    /**
     *
     * set page header title
     */
    public function getGridHeader()
    {
        return Mage::helper('zeon_attributemapping')
            ->__('Manage Attribute Labels');
    }

    /**
     * Render grid
     *
     * @return string
     */
    public function getGridHtml()
    {
        return $this->getChildHtml('attribue_value.grid');
    }

    /**
     * Check whether it is single store mode
     *
     * @return bool
     */
    public function isSingleStoreMode()
    {
        if (!Mage::app()->isSingleStoreMode()) {
               return false;
        }
        return true;
    }
}