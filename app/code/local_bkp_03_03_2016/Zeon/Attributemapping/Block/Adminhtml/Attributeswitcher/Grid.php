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

class Zeon_Attributemapping_Block_Adminhtml_Attributeswitcher_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set defaults
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('attMappingGrid');
        $this->setDefaultSort('sort_order');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        //$this->setUseAjax(true);
        $this->setVarNameFilter('amapping_filter');
    }

    /**
     * get store data
     */
    protected function _getStore()
    {
        $storeId = Mage::helper('zeon_attributemapping')->getTopStore();
        return Mage::app()->getStore($storeId);
    }

    /**
     * Instantiate and prepare collection
     *
     * @return Fqs_Attributemapping_Block_Adminhtml_Attributemapping_Grid
     */
    protected function _prepareCollection()
    {
        $attributeValueTable = Mage::getSingleton('core/resource')
            ->getTableName('eav_attribute');
        $entityTable = Mage::getSingleton('core/resource')
            ->getTableName('eav_entity_type');
        $catalogEntityTable = Mage::getSingleton('core/resource')
            ->getTableName('catalog_eav_attribute');
        $attributes = Mage::getModel('eav/entity_attribute')
            ->getCollection();
        $attributes->getSelect()->join(
            array('entitytype' => $entityTable),
            'entitytype.entity_type_id  = main_table.entity_type_id '
            . ' AND entitytype.entity_type_code = "catalog_product"'
            . ' AND ( main_table.frontend_input = '
            . '"select" OR main_table.frontend_input = "multiselect")'
        );
        $attributes->getSelect()->join(
            array('catalogeav' => $catalogEntityTable),
            'catalogeav.attribute_id  = main_table.attribute_id'
            . ' AND ( catalogeav.is_filterable = 1 '
            . ' OR catalogeav.is_filterable_in_search = 1)'
        );

        $this->setCollection($attributes);

        return parent::_prepareCollection();
    }

    /**
     * Define grid columns
     */
    protected function _prepareColumns()
    {
        $store = $this->_getStore();

        $this->addColumn(
            'attribute_id',
            array(
                'header'=> Mage::helper('zeon_attributemapping')
                    ->__('Attribute Id'),
                'type'  => 'number',
                'width' => '1',
                'index' => 'attribute_id',
            )
        );

        $this->addColumn(
            'frontend_label',
            array(
                'header' => Mage::helper('zeon_attributemapping')
                    ->__('Attribute Label'),
                'type'   => 'text',
                'index'  => 'frontend_label',
            )
        );

        $this->addColumn(
            'attribute_code',
            array(
                'header' => Mage::helper('zeon_attributemapping')
                     ->__('Attribute Code'),
                'type'   => 'text',
                'index'  => 'attribute_code',
            )
        );


        return parent::_prepareColumns();
    }
    /**
     * Grid row URL getter
     */
    public function getRowUrl($row)
    {
        return $this->getUrl(
            '*/*/list/id/',
            array(
                'id' => $row->getAttributeId(),
                'store' => $this->_getStore()->getId()
            )
        );
    }

}