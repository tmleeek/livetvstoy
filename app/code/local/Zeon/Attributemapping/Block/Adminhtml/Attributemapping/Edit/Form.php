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

class Zeon_Attributemapping_Block_Adminhtml_Attributemapping_Edit_Form
    extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }
    /**
     * Prepare form before rendering HTML
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        //get request parameters
        $attributeId        = $this->getRequest()->getParam('attribute_id');
        $optionId            = $this->getRequest()->getParam('option_id');
        $store = $this->_getStore();
        $storeId            = $store->getId();

        if (Mage::getSingleton('adminhtml/session')->getFormData()) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            Mage::getSingleton('adminhtml/session')->getFormData(null);
        } elseif (Mage::registry('zeon_attributemapping')) {
            $data = Mage::registry('zeon_attributemapping')->getData();
        } else {
            $data = array();
        }

        $form = new Varien_Data_Form(
            array(
                'id' => 'edit_form',
                'action' => $this->getData('action'),
                'method' => 'post',
                'enctype' => 'multipart/form-data'
            )
        );

        $htmlIdPrefix = 'attributedata_information_';
        $form->setHtmlIdPrefix($htmlIdPrefix);
        $fieldsetHtmlClass = 'fieldset-wide';
        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig();

        $sortContents = isset($data['short_description']) ?
                            $data['short_description'] : '' ;
        $contents = isset($data['description']) ? $data['description'] : '' ;

        $form->setUseContainer(true);
        $this->setForm($form);

        //get attribute and option name
        $attributeValueTable = Mage::getSingleton('core/resource')
            ->getTableName('eav_attribute_option_value');
        $optionData = Mage::getModel('eav/entity_attribute_option')
            ->getCollection()
            ->addFieldToFilter('main_table.attribute_id', $attributeId)
            ->addFieldToFilter('main_table.option_id', $optionId)
            ->join(
                'attribute',
                'attribute.attribute_id=main_table.attribute_id',
                array('attribute_code', 'frontend_label')
            );
        $optionData->getSelect()
                ->join(
                    array('valuedata' => $attributeValueTable),
                    'valuedata.option_id = main_table.option_id',
                    array('value_data'=>'valuedata.value')
                );
        $optionData = $optionData->getData();
        $optionData = $optionData[0];

        //form start
        $fieldset = $form->addFieldset(
            'base_fieldset',
            array(
                'legend' => Mage::helper('zeon_attributemapping')
                    ->__('LandingPage information'),
                'class'  => $fieldsetHtmlClass,
            )
        );

        //set data for new record
        if (!$data) {
            $data = array(
                'attribute_id' => $attributeId,
                'option_id' => $optionId,
                'url_key' => strtolower($optionData['value_data'])
            );
        }
        $data['store'] = $storeId;

        $fieldset->addField(
            'attribute_id',
            'hidden',
            array(
                'name'     => 'attribute_id',
                'value'    => $attributeId
            )
        );

        $fieldset->addField(
            'option_id',
            'hidden',
            array(
                'name'     => 'option_id',
                'value'    => $optionId,
            )
        );

        $fieldset->addField(
            'store',
            'hidden',
            array(
                'name'     => 'store',
                'value'    => $storeId,
            )
        );

        $fieldset->addField(
            'note',
            'note',
            array(
                'label'    => Mage::helper('zeon_attributemapping')
                    ->__('Attribute: Option'),
                'text'     => $optionData['frontend_label'] . ': '
                    .$optionData['value_data'] ,
            )
        );

        if ($store->getId() > 0) {
            $storeName = $store->getName();
        } else {
            $storeName = $this->__('All Store Views');
        }

        $fieldset->addField(
            'store_name',
            'note',
            array(
                'label'    => Mage::helper('zeon_attributemapping')
                    ->__('Store'),
                'text'     => $storeName,
            )
        );

        $fieldset->addField(
            'option_status',
            'select',
            array(
                'label'     => Mage::helper('zeon_attributemapping')
                    ->__('Status'),
                'name'      => 'option_status',
                'values'    => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('zeon_attributemapping')
                            ->__('Enabled'),
                    ),
                    array(
                        'value' => 2,
                        'label' => Mage::helper('zeon_attributemapping')
                            ->__('Disabled'),
                    )
                )
            )
        );

        $fieldset->addField(
            'url_key',
            'text',
            array(
                'label'    => Mage::helper('zeon_attributemapping')
                    ->__('Url Key'),
                'name'     => 'url_key',
                'required' => true,
            )
        );

        $fieldset->addField(
            'description',
            'editor',
            array(
                'name'               => 'description',
                'value'              => (isset($contents) ? $contents : ''),
                'label'              => Mage::helper('zeon_attributemapping')
                    ->__('Description'),
                'config'             => Mage::getSingleton('cms/wysiwyg_config')
                    ->getConfig(),
                'wysiwyg'            => true,
                'container_id'       => 'description',
            )
        );

        $fieldset->addField(
            'page_background_image',
            'image',
            array(
                'label' => Mage::helper('zeon_attributemapping')
                    ->__('Banner Image'),
                'name'  => 'page_background_image',
                'after_element_html' => '<p class="note">
                    Banner image to display
                    </p>',
            )
        );
        
        $afterElementHtml = '<p class="nm"><small><font color="red">' . ' Must be a valid URL! ' . '</font></small></p>';
        $fieldset->addField(
            'dest_url',
            'text',
            array(
                'label'    => Mage::helper('zeon_attributemapping')
                    ->__('Destination URL'),
                'name'     => 'dest_url',
                'required' => true,
                'tooltip'  => "Should be a valid URL",
                'hint'     => "http://",
                'after_element_html' => $afterElementHtml,
            )
        );

        $fieldset->addField(
            'page_banner_left_image',
            'image',
            array(
                'label' => Mage::helper('zeon_attributemapping')
                    ->__('Left Banner Image'),
                'name'  => 'page_banner_left_image',
                'after_element_html' => '<p class="note">
                    Left Banner image to display
                    </p>',
            )
        );

        // add slider enable and images details
        $fieldset = $form->addFieldset(
            'slider_details',
            array(
                'legend' => Mage::helper('zeon_attributemapping')
                    ->__('Slider Information'),
                'class'  => $fieldsetHtmlClass,
            )
        );

        $fieldset->addField(
            'display_in_slider',
            'select',
            array(
                'label'     => Mage::helper('zeon_attributemapping')
                    ->__('Status'),
                'name'      => 'display_in_slider',
                'values'    => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('zeon_attributemapping')
                            ->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('zeon_attributemapping')
                            ->__('Disabled'),
                    )
                )
            )
        );

        $fieldset->addField(
            'logo_image',
            'image',
            array(
                'label' => Mage::helper('zeon_attributemapping')
                    ->__('Character Image'),
                'name'  => 'logo_image',
                'after_element_html' => '<p class="note">
                    Character image to display
                    </p>',
            )
        );

        $fieldset->addField(
            'slider_image',
            'image',
            array(
                'label' => Mage::helper('zeon_attributemapping')
                    ->__('Character Slider Image'),
                'name'  => 'slider_image',
                'after_element_html' => '<p class="note">
                    Character slider image to display
                    </p>',
            )
        );

        $fieldset->addField(
            'sort_order',
            'text',
            array(
                'name'     => 'sort_order',
                'label'    => Mage::helper('zeon_attributemapping')
                    ->__('Slider Image Sort Order')
            )
        );

        /* Most populer character settings */
        /*$fieldset->addField(
            'populer_character',
            'select',
            array(
                'label'     => Mage::helper('zeon_attributemapping')
                    ->__('Enable As Popular'),
                'name'      => 'populer_character',
                'values'    => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('zeon_attributemapping')
                            ->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('zeon_attributemapping')
                            ->__('Disabled'),
                    )
                )
            )
        );

        $fieldset->addField(
            'populer_position',
            'text',
            array(
                'name'     => 'populer_position',
                'label'    => Mage::helper('zeon_attributemapping')
                    ->__('Popular Character Sort Order')
            )
        );*/

        // add meta information fieldset
        $fieldset = $form->addFieldset(
            'meta_information',
            array(
                'legend' => Mage::helper('zeon_attributemapping')
                    ->__('Meta Information'),
                'class'  => $fieldsetHtmlClass,
            )
        );

        $fieldset->addField(
            'meta_title',
            'text',
            array(
                'name'     => 'meta_title',
                'label'    => Mage::helper('zeon_attributemapping')
                    ->__('Meta Title')
            )
        );

        $fieldset->addField(
            'meta_keywords',
            'textarea',
            array(
                'name'     => 'meta_keywords',
                'label'    => Mage::helper('zeon_attributemapping')
                    ->__('Meta Keywords')
            )
        );

        $fieldset->addField(
            'meta_description',
            'textarea',
            array(
                'name'     => 'meta_description',
                'label'    => Mage::helper('zeon_attributemapping')
                    ->__('Meta Description')
            )
        );

         $form->setValues($data);

        return parent::_prepareForm();
    }

    /**
     * get store data
     */
    protected function _getStore()
    {
        $defaultStore = Mage::helper('zeon_attributemapping')->getTopStore();
        $storeId = (int) $this->getRequest()->getParam('store', $defaultStore);
        return Mage::app()->getStore($storeId);
    }
}