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
 * @category    Zeon
 * @package     Zeon_Attributemapping
 * @copyright   Copyright (c) 2012 Zeon Solutions, Inc.
 * All Rights Reserved.(http://www.zeonsolutions.com)
 * @license     http://www.zeonsolutions.com/license/
 */

/**
 * Zeon Attributemapping Model System Config Source Product Attributes
 *
 * @author      Zeon Team <vidisha.ganjre@zeonsolutions.com>
 */
class Zeon_Attributemapping_Model_System_Config_Source_Attributes
{
    /**
     * get product attribute listing
     */
    public function toOptionArray()
    {
        $options = array();
        $entityTypeId = Mage::getModel('eav/entity_type')
            ->loadByCode('catalog_product')->getEntityTypeId();
        $attributes = Mage::getModel('eav/entity_attribute')->getCollection()
            ->addFilter('entity_type_id', $entityTypeId)
            ->setOrder('frontend_label', 'ASC');

        $attributes->getSelect()
            ->joinInner(
                array(
                'eav_attribute_additional' =>
                    Mage::getSingleton('core/resource')
                    ->getTableName('catalog/eav_attribute')),
                'main_table.attribute_id =
                    eav_attribute_additional.attribute_id',
                null
            );
        $attributes->getSelect()->where(
            'eav_attribute_additional.is_filterable <> 0'
        );

        foreach ($attributes as $attribute) {
            $item = array();
            $item['value'] = $attribute->getAttributeCode();
            if ($attribute->getFrontendLabel()) {
                $item['label'] = $attribute->getFrontendLabel();
            } else {
                $item['label'] = $attribute->getAttributeCode();
            }
            $options[] = $item;
        }

        return $options;
    }
}