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

class Zeon_Attributemapping_Block_Adminhtml_Attributemapping_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{
    private $_attributeCode = '';
    /**
     * Set defaults
     */
    public function __construct()
    {
        $this->setId('attMappingGrid');
        $this->setDefaultSort('value_data');
        $this->setDefaultDir('ASC');
        parent::__construct();
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
    /**
     * get attribute code
     */
    protected function _getAttributeCode()
    {
        if ($this->_attributeCode == "") {
            $attributeId  = (int) $this->getRequest()->getParam('id');
            $this->_attributeCode = Mage::getModel('eav/entity_attribute')
                ->load($attributeId)->getAttributeCode();
        }
        return $this->_attributeCode;
    }
    /**
     * Instantiate and prepare collection
     *
     * @return Fqs_Attributemapping_Block_Adminhtml_Attributemapping_Grid
     */
    protected function _prepareCollection()
    {
        $attributes = array();
        $store = $this->_getStore();
        $attributeId  = (int) $this->getRequest()->getParam('id');

        if ($attributeId && $store->getId()) {
            $attributeValueTable = Mage::getSingleton('core/resource')
                ->getTableName('eav_attribute_option_value');
            $attributeDataTable = Mage::getSingleton('core/resource')
                ->getTableName(
                    array(
                        'zeon_attributemapping/attributemapping',
                        $store->getId()
                    )
                );
            $attributes = Mage::getModel('eav/entity_attribute_option')
                ->getCollection();

            $attributes->getSelect()->join(
                array('valuedata' => $attributeValueTable),
                'valuedata.option_id = main_table.option_id '
                . ' AND valuedata.store_id = "0"',
                array('value_data'=>'valuedata.value')
            );

                if ($store->getId()) {
                    $attributes->getSelect()->joinLeft(
                        array('value_alldata' => $attributeValueTable),
                        'value_alldata.option_id = valuedata.option_id '
                        . ' AND value_alldata.store_id = "'.$store->getId().'"',
                        array(
                            'value_data'=> " IF (value_alldata.value_id > 0,"
                            . " value_alldata.value, valuedata.value ) "
                        )
                    );
                }

                $attributes->getSelect()->joinLeft(
                    array('att_data' => $attributeDataTable),
                    'att_data.attribute_id = main_table.attribute_id '
                    . ' AND att_data.option_id = main_table.option_id ',
                    array(
                        'mapping_id' => 'att_data.mapping_id',
                        'option_status' => 'att_data.option_status',
                        'url_key' => 'att_data.url_key',
                    )
                );

                $attributes->getSelect()->where(
                    'main_table.attribute_id = "?"', $attributeId
                );
        } else {
            echo $this->__('Please select proper attribute.');
        }

        $this->setCollection($attributes);

        return parent::_prepareCollection();
    }

    /**
     * Sets filters
     *
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($this->getCollection() && $column->getFilter()->getValue()) {

            if ($column->getId() == 'option_id') {
                $values = $column->getFilter()->getValue();

                $this->getCollection()->getSelect()
                ->where(
                    " (main_table.option_id >= '".$values['from']."'"
                    . "AND main_table.option_id <= '".$values['to']."') "
                );

                return $this;
            }

            if ($column->getId() == 'value_data') {
                $store = $this->_getStore();
                if ($store->getId()) {

                    $this->getCollection()->getSelect()
                    ->where(
                        " IF (value_alldata.value_id > 0,
                        value_alldata.value, valuedata.value ) LIKE '%"
                        .addslashes($column->getFilter()->getValue())."%'"
                    );

                } else {
                    $this->getCollection()->getSelect()
                    ->where(
                        " valuedata.value LIKE '%"
                        .addslashes($column->getFilter()->getValue())."%'"
                    );
                }

                return $this;
            }
        }

        return parent::_addColumnFilterToCollection($column);
    }

    /**
     * Define grid columns
     */
    protected function _prepareColumns()
    {
        $store = $this->_getStore();
        $this->addColumn(
            'option_id',
            array(
                'header'=> Mage::helper('zeon_attributemapping')->__('ID'),
                'type'  => 'number',
                'width' => '1',
                'index' => 'option_id',
            )
        );

        $this->addColumn(
            'value_data',
            array(
                'header' => Mage::helper('zeon_attributemapping')
                    ->__('Option Label'),
                'type'   => 'text',
                'index'  => 'value_data',
                'renderer'  =>
                    'zeon_attributemapping/adminhtml_grid_renderer_editoption',
            )
        );

        $this->addColumn(
            'option_status',
            array(
                'header' => Mage::helper('zeon_attributemapping')->__('Status'),
                'index'  => 'option_status',
                'type'      => 'options',
                'filter_index'=>'att_data.option_status',
                'options'   => array(
                    1 => 'Enable',
                    2 => 'Disable',
                 ),
            )
        );

        $this->addColumn(
            'url_key',
            array(
                'header' => Mage::helper('zeon_attributemapping')
                    ->__('Url Key'),
                'type'   => 'text',
                'index'  => 'url_key',
                'filter_index'=>'att_data.url_key',
            )
        );

        $this->addColumn(
            'action',
            array(
                'header'  => Mage::helper('zeon_attributemapping')
                    ->__('Action'),
                'width'   => '50',
                'type'    => 'action',
                'align'   => 'center',
                'getter'  => 'getOptionId',
                'actions' => array(
                     array(
                        'caption' => Mage::helper('zeon_attributemapping')
                            ->__('Edit'),
                        'url'     => array(
                            'base'      => '*/*/edit',
                            'params'    =>array(
                                'attribute_id' => $this->getRequest()
                                     ->getParam('id'),
                                'store' => $store->getId()
                            )
                        ),
                        'field'   => 'option_id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
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
            '*/*/edit',
            array(
                'attribute_id' => $row->getAttributeId(),
                'store' => $this->_getStore()->getId(),
                'option_id' => $row->getOptionId()
            )
        );
    }

}