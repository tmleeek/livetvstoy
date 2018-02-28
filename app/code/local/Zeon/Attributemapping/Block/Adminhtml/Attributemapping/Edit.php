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
 * Do not edit or add to this file if you wish to upgrade this extension to new
 * versions in the future. If you wish to customize this extension for your
 * needs please refer to http://www.zeonsolutions.com for more information.
 *
 * @category    Fqs
 * @package     Fqs_Attributemapping
 * @copyright   Copyright (c) 2012 Zeon Solutions, Inc.
 * All Rights Reserved.(http://www.zeonsolutions.com)
 * @license     http://www.zeonsolutions.com/license/
 */

class Zeon_Attributemapping_Block_Adminhtml_Attributemapping_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Initialize option data page edit page. Set management buttons
     *
     */
    public function __construct()
    {
        $this->_objectId = 'id';
        $this->_controller = 'adminhtml_attributemapping';
        $this->_blockGroup = 'zeon_attributemapping';

        parent::__construct();

        $attributeId  = (int) $this->getRequest()->getParam('attribute_id');
        $optionId  = (int) $this->getRequest()->getParam('option_id');
        $storeId  = (int) $this->getRequest()->getParam('store', 0);
        $this->_updateButton(
            'back',
            'onclick',
            "setLocation('".$this->getUrl(
                '*/*/list/id/'.$attributeId.'/store/'.$storeId
            )."');"
        );

        $this->_updateButton(
            'save',
            'label',
            Mage::helper('zeon_attributemapping')->__('Save')
        );

        $this->_addButton(
            'save_and_edit_button',
            array(
                'label'   => Mage::helper('zeon_attributemapping')
                    ->__('Save and Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save'
            ),
            100
        );
        $this->_formScripts[] = '
            function saveAndContinueEdit() {
            editForm.submit($(\'edit_form\').action'
            .' + \'back/edit/\'); return true; }';
    }

    /**
     * Get header text for option edit page edit page
     *
     */
    public function getHeaderText()
    {
        return Mage::helper('zeon_attributemapping')->__('Attribute Data Edit');
    }

    /**
     * Get form action URL
     *
     */
    public function getFormActionUrl()
    {
        return $this->getUrl('*/*/save');
    }
}