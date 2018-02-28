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
class Zeon_CatalogManager_Block_Adminhtml_Popularproducts_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('popular_products');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);

        $this->setRowClickCallback('PopularRowClick');
    }

    public function getProduct()
    {
        return Mage::registry('product');
    }

    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    protected function _addColumnFilterToCollection($column)
    {
        if ($this->getCollection()) {
            if ($column->getId() == 'websites') {
                $this->getCollection()->joinField(
                    'websites',
                    'catalog/product_website',
                    'website_id',
                    'product_id=entity_id',
                    null,
                    'left'
                );
            }
        }


        if ($column->getId() == "popular") {
            $productIds = $this->_getSelectedProducts();

            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()
                    ->addFieldToFilter(
                        'entity_id',
                        array('in' => $productIds)
                    );
            } elseif (!empty($productIds)) {
                $this->getCollection()
                    ->addFieldToFilter(
                        'entity_id',
                        array('nin' => $productIds)
                    );
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }

        return $this;
    }

    protected function _prepareCollection()
    {
        $store = $this->_getStore();

        $collection = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect('name')
                ->addAttributeToSelect('sku')
                ->addAttributeToSelect('most_popular')
                ->addAttributeToSelect('type_id')
                ->addAttributeToFilter(
                    'visibility', array('nin' => array(1, 3))
                );


        if ($store->getId()) {
            //$collection->setStoreId($store->getId());
            $collection->addStoreFilter($store);
            $collection->joinAttribute(
                'custom_name',
                'catalog_product/name',
                'entity_id',
                null,
                'inner',
                $store->getId()
            );
            $collection->joinAttribute(
                'status',
                'catalog_product/status',
                'entity_id',
                null,
                'inner',
                $store->getId()
            );
            $collection->joinAttribute(
                'visibility',
                'catalog_product/visibility',
                'entity_id',
                1,
                'inner',
                $store->getId()
            );
            $collection->joinAttribute(
                'price',
                'catalog_product/price',
                'entity_id',
                null,
                'left',
                $store->getId()
            );
        } else {
            $collection->addAttributeToSelect('price');
            $collection->addAttributeToSelect('status');
            $collection->addAttributeToSelect('visibility');
        }

        $actionName = $this->getRequest()->getActionName();

        if ($actionName == 'exportCsv' || $actionName == 'exportXml') {
            $collection->addAttributeToFilter(
                'most_popular', array('eq' => true)
            );
        }


        $this->setCollection($collection);

        parent::_prepareCollection();

        $this->getCollection()->addWebsiteNamesToResult();

        return $this;
    }

    protected function _prepareColumns()
    {
        $actionName = $this->getRequest()->getActionName();
        $helper     = Mage::helper('zeon_catalogmanager');

        if ($actionName != 'exportCsv' && $actionName != 'exportXml') {
            $this->addColumn(
                'most_popular',
                array(
                    'header_css_class' => 'a-center',
                    'type'             => 'checkbox',
                    'name'             => 'most_popular',
                    'values'           => $this->_getSelectedProducts(),
                    'align'            => 'center',
                    'index'            => 'entity_id'
                )
            );
        }

        $this->addColumn(
            'entity_id',
            array(
                'header'   => $helper->__('ID'),
                'sortable' => true,
                'width'    => '60',
                'index'    => 'entity_id',
                'type'     => 'number'
            )
        );

        $renderer = 'adminhtml_popularproducts_renderer_name';
        $this->addColumn(
            'name',
            array(
                'header'   => $helper->__('Name'),
                'index'    => 'name',
                'renderer' => 'zeon_catalogmanager/' . $renderer,
            )
        );

        $this->addColumn(
            'type',
            array(
                'header'  => $helper->__('Type'),
                'width'   => '60px',
                'index'   => 'type_id',
                'type'    => 'options',
                'options' => Mage::getSingleton('catalog/product_type')
            ->getOptionArray(),
            )
        );

        $this->addColumn(
            'sku',
            array(
                'header' => $helper->__('SKU'),
                'width'  => '140',
                'index'  => 'sku'
            )
        );

        $renderer = 'adminhtml_popularproducts_renderer_visibility';
        $this->addColumn(
            'visibility',
            array(
                'header'   => $helper->__('Visibility'),
                'width'    => '140',
                'index'    => 'visibility',
                'filter'   => false,
                'renderer' => 'zeon_catalogmanager/' . $renderer,
            )
        );

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn(
                'websites',
                array(
                    'header'   => $helper->__('Websites'),
                    'width'    => '100px',
                    'sortable' => false,
                    'index'    => 'websites',
                    'type'     => 'options',
                    'options'  => Mage::getModel('core/website')
                ->getCollection()->toOptionHash(),
                )
            );
        }

        $store = $this->_getStore();
        $this->addColumn(
            'price',
            array(
                'header'        => $helper->__('Price'),
                'type'          => 'price',
                'currency_code' => $store->getBaseCurrency()->getCode(),
                'index'         => 'price',
            )
        );

        $this->addExportType(
            '*/*/exportCsv/type/popular',
            $helper->__('CSV')
        );
        $this->addExportType(
            '*/*/exportXml/type/popular',
            $helper->__('Excel XML')
        );

        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true, 'blockname'=>'adminhtml_popularproducts_grid'));
    }

    protected function _getSelectedProducts($json = false)
    {
        $temp = $this->getRequest()->getPost('popular_ids');
        $store = $this->_getStore();

        if ($temp) {
            parse_str($temp, $popularIds);
        }

        $_prod = Mage::getModel('catalog/product')->getCollection()
                ->joinAttribute(
                    'most_popular',
                    'catalog_product/most_popular',
                    'entity_id',
                    null,
                    'left',
                    $store->getId()
                )
                ->addAttributeToFilter('most_popular', '1');

        $products = $_prod->getColumnValues('entity_id');
        $selectedProducts = array();


        if ($json == true) {
            foreach ($products as $key => $value) {
                $selectedProducts[$value] = '1';
            }
            return Zend_Json::encode($selectedProducts);
        } else {

            foreach ($products as $key => $value) {
                if ((isset($popularIds[$value])) &&
                    ($popularIds[$value] == 0)) {
                } else {
                    $selectedProducts[$value] = '0';
                }
            }

            if (isset($popularIds)) {
                foreach ($popularIds as $key => $value) {
                    if ($value == 1) {
                        $selectedProducts[$key] = '0';
                    }
                }
            }
            return array_keys($selectedProducts);
        }

        return $products;
    }

    //add javascript before/after grid html
    protected function _afterToHtml($html)
    {
        return $this->_prependHtml() .
            parent::_afterToHtml($html) .
            $this->_appendHtml();
    }

    private function _prependHtml()
    {
        $gridName = $this->getJsObjectName();

        $html = <<<EndHTML
		<script type="text/javascript">
		//<![CDATA[

    categoryForm = new varienForm('popular_edit_form');
	categoryForm.submit= function (url) {

	this._submit();
        return true;
    };

    document.observe("dom:loaded", function() {
        isCheckboxChecked();
    });

    function isCheckboxChecked()
    {
        var everycheckbox = $$("#popular_products_table tbody input.checkbox");
        everycheckbox.each(function(element, index) {
            element.onclick = function(e)
            {
                var val = element.value;
                if (element.checked) {
                    element.checked = false;
                    checkBoxes.set(val, 0);
                } else {
                    element.checked = "checked";
                    checkBoxes.set(val, 1);
                }
            }
        });
    }

    function categorySubmit(url)
    {
    	var params = {};
        var fields = $('popular_edit_form').getElementsBySelector(
            'input', 'select'
        );

        categoryForm.submit();
    }

    function PopularRowClick(grid, event)
    {
        isCheckboxChecked();
        var trElement = Event.findElement(event, 'tr');
    	var isInput   = Event.element(event).tagName == 'INPUT';

    	var checkbox = Element.getElementsBySelector(
            trElement,
            'input.checkbox'
        ).first();

        if(!checkbox) return;

        var val = checkbox.value;

        if (checkbox.checked) {
            checkbox.checked = false;
            checkBoxes.set(val, 0);
        } else {
            checkbox.checked = "checked";
            checkBoxes.set(val, 1);
        }

		$("in_popular_products").value = checkBoxes.toQueryString();
	   	$gridName.reloadParams = {'popular_ids':checkBoxes.toQueryString()};
    }

    /**
     * The following code will execute if user navigate to pages
     * and check/uncheck the checkbox.
     */
    var everycheckbox = $$("#popular_products_table tbody input.checkbox");
    everycheckbox.each(function(element, index) {
        element.onclick = function(e)
        {
            var val = element.value;
            if (element.checked) {
                element.checked = false;
                checkBoxes.set(val, 0);
            } else {
                element.checked = "checked";
                checkBoxes.set(val, 1);
            }
        }
    });

//]]>

        </script>
EndHTML;

        return $html;
    }

    private function _appendHtml()
    {
        $html = '<script type="text/javascript">
		var checkBoxes = $H();

		var checkbox_all = $$("#popular_products_table thead input.checkbox").
            first();
        var everycheckbox = $$("#popular_products_table tbody input.checkbox");

		checkbox_all.observe("click", function(event) {

		if (checkbox_all.checked) {
            everycheckbox.each(function(element, index) {
                checkBoxes.set(element.value, 1)
            });
        } else {
            everycheckbox.each(function(element, index) {
                checkBoxes.set(element.value, 0)
            });
        }
    	$("in_popular_products").value = checkBoxes.toQueryString();
		});

        </script>';

        return $html;
    }

}