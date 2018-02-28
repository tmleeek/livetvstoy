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

class Zeon_Attributemapping_Block_Adminhtml_Addoptions_Grid
    extends Mage_Eav_Block_Adminhtml_Attribute_Edit_Options_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('zeon/attributemapping/options.phtml');
    }
    public function getAttributeObject()
    {
        $attributeId  = (int) $this->getRequest()->getParam('id');
        return Mage::getModel('eav/entity_attribute')->load($attributeId);
    }

   /**
     * get store data
     */
    protected function _getStore()
    {
        $defaultStore = $storeId = Mage::helper('zeon_attributemapping')
            ->getTopStore();
        $storeId = (int) $this->getRequest()->getParam('store', $defaultStore);
        return Mage::app()->getStore($storeId);
    }

    public function getOptionId()
    {
        return (int) $this->getRequest()->getParam('optionid');
    }

    public function getOptionValues()
    {
        $optionId  = $this->getOptionId();
        if ($optionId) {
            $attributeType = $this->getAttributeObject()->getFrontendInput();
            $defaultValues = $this->getAttributeObject()->getDefaultValue();
            if ($attributeType == 'select' || $attributeType == 'multiselect') {
                $defaultValues = explode(',', $defaultValues);
            } else {
                $defaultValues = array();
            }


            $values = $this->getData('option_values');
            if (is_null($values)) {
                $values = array();
                $optionCollection = Mage::getResourceModel(
                    'eav/entity_attribute_option_collection'
                )->setAttributeFilter($this->getAttributeObject()->getId())
                    ->setPositionOrder('desc', true)
                    ->load();

                foreach ($optionCollection as $option) {
                    $value = array();
                    if ($option->getId() == $optionId) {
                        if (in_array($option->getId(), $defaultValues)) {
                            $value['checked'] = 'checked="checked"';
                        } else {
                            $value['checked'] = '';
                        }

                        $value['intype'] = 'checkbox';
                        $value['id'] = $option->getId();
                        $value['sort_order'] = $option->getSortOrder();
                        foreach ($this->getStores() as $store) {
                            $storeValues = $this->getStoreOptionValues(
                                $store->getId()
                            );
                            if (isset($storeValues[$option->getId()])) {
                                $value['store'.$store->getId()]
                                    = htmlspecialchars(
                                        $storeValues[$option->getId()]
                                    );
                            } else {
                                $value['store'.$store->getId()] = '';
                            }
                        }
                        $values[] = new Varien_Object($value);
                    }
                    $this->setData('option_values', $values);
                }
            }

            return $values;
        }
        return false;
    }
}