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
 * newer versions in the future. If you wish to customize this extension for
 * your needs please refer to http://www.zeonsolutions.com for more information.
 *
 * @category    Zeon
 * @package     Zeon_CatalogManager
 * @author      Suhas Dhoke <suhas.dhoke@zeonsolutions.com>
 * @copyright   Copyright (c) 2014 Zeon Solutions, Inc. All Rights Reserved.
 *              (http://www.zeonsolutions.com)
 * @license     http://www.zeonsolutions.com/license/
 */
class Zeon_CatalogManager_Block_Adminhtml_Homepageoption2
    extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    /**
     * Constructor of the class.
     */
    public function __construct()
    {
        parent::__construct();

        $helper = Mage::helper('zeon_catalogmanager');

        $this->_blockGroup = 'zeon_catalogmanager';
        $this->_controller = 'adminhtml_homepageoption2';
        $this->_headerText =$helper->__('Homepage - Custom Option 2');

        $this->_removeButton('add');
        $this->_addButton(
            'save',
            array(
                'label'   => $helper->__('Save Homepage - Custom Option 2'),
                'onclick' => 'categorySubmit(\'' . $this->getSaveUrl() . '\')',
                'class'   => 'Save',
            )
        );
    }

    /**
     * Get the featured product save url.
     *
     * @return url
     */
    public function getSaveUrl()
    {
        return $this->getUrl(
            '*/*/saveProductsOption2',
            array('store' => $this->getRequest()->getParam('store'))
        );
    }

    protected function _afterToHtml($html)
    {
        return $this->_prependHtml() .
               parent::_afterToHtml($html) .
               $this->_appendHtml();
    }

    private function _prependHtml()
    {
        $html = '
    	<form id="gift_edit_form" action="' . $this->getSaveUrl() . '"
            method="post" enctype="multipart/form-data">
    	<input name="form_key" type="hidden"
            value="' . $this->getFormKey() . '" />
    		<div class="no-display">
        		<input type="hidden" name="homepage_option2" id="homepage_option2" value="" />
                <input type="hidden" name="homepage_option2_position" id="homepage_option2_position" value="" />
    		</div>
		</form>
    	';

        return $html;
    }

    private function _appendHtml()
    {
        $html = '';
        return $html;
    }

    public function getHeaderHtml()
    {
        return '<h3 style="background-image:
            url('. $this->getSkinUrl('images/product_rating_full_star.gif').');"
            class="' . $this->getHeaderCssClass() . '">' .
            $this->getHeaderText() . '</h3>';
    }

    protected function _prepareLayout()
    {
        $this->setChild(
            'store_switcher',
            $this->getLayout()
                ->createBlock('adminhtml/store_switcher', 'store_switcher')
                ->setUseConfirm(false)
        );
        return parent::_prepareLayout();
    }

    public function getGridHtml()
    {
        return $this->getChildHtml('store_switcher') .
               $this->getChildHtml('grid');
    }
}