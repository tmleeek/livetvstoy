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
class Zeon_CatalogManager_Adminhtml_IndexController
    extends Mage_Adminhtml_Controller_Action
{
    protected function _initProduct()
    {
        $product = Mage::getModel('catalog/product')
            ->setStoreId($this->getRequest()->getParam('store', 0));


        if ($setId = (int) $this->getRequest()->getParam('set')) {
            $product->setAttributeSetId($setId);
        }

        if ($typeId = $this->getRequest()->getParam('type')) {
            $product->setTypeId($typeId);
        }

        $product->setData('_edit_mode', true);

        Mage::register('product', $product);

        return $product;
    }

    /**
     * Method used to display the list of all products to select featured items.
     */
    public function featuredAction()
    {

        // Init the product
        $this->_initProduct();

        // Set the active menu.
        $this->loadLayout()->_setActiveMenu('zextension/featuredproduct');

        // Set and load the block content.
        $this->_addContent(
            $this->getLayout()->createBlock('zeon_catalogmanager/adminhtml_featuredproducts')
        );

        // Render the layout.
        $this->renderLayout();
    }

    /**
     * Method used to display the list of all products to select gift card for home page option 1 items.
     */
    public function Homepageoption1Action()
    {

        // Init the product
        $this->_initProduct();

        // Set the active menu.
        $this->loadLayout()->_setActiveMenu('zextension/homepageoption1');

        // Set and load the block content.
        $this->_addContent(
            $this->getLayout()->createBlock('zeon_catalogmanager/adminhtml_Homepageoption1')
        );

        // Render the layout.
        $this->renderLayout();
    }
    /**
     * Method used to display the list of all products to select gift card for her items.
     */
    public function Homepageoption2Action()
    {

        // Init the product
        $this->_initProduct();

        // Set the active menu.
        $this->loadLayout()->_setActiveMenu('zextension/homepageoption2');

        // Set and load the block content.
        $this->_addContent(
            $this->getLayout()->createBlock('zeon_catalogmanager/adminhtml_Homepageoption2')
        );

        // Render the layout.
        $this->renderLayout();
    }

    /**
     * Method used to display the list of all products to select gift card for baby items.
     */
    public function Homepageoption3Action()
    {

        // Init the product
        $this->_initProduct();

        // Set the active menu.
        $this->loadLayout()->_setActiveMenu('zextension/homepageoption3');

        // Set and load the block content.
        $this->_addContent(
            $this->getLayout()->createBlock('zeon_catalogmanager/adminhtml_Homepageoption3')
        );

        // Render the layout.
        $this->renderLayout();
    }
    /**
     * Method used to display the list of all products to select best sellers.
     */
    public function bestAction()
    {
        // Init the product
        $this->_initProduct();

        // Set the active menu.
        $this->loadLayout()->_setActiveMenu('zextension/bestproducts');

        // Set and load the block content.
        $this->_addContent(
            $this->getLayout()->createBlock('zeon_catalogmanager/adminhtml_bestproducts')
        );

        // Render the layout.
        $this->renderLayout();
    }

    /**
     * Method used to display the list of all products to select most popular.
     */
    public function popularAction()
    {

        // Init the product
        $this->_initProduct();

        // Set the active menu.
        $this->loadLayout()->_setActiveMenu('zextension/popularproduct');

        // Set and load the block content.
        $this->_addContent(
            $this->getLayout()->createBlock('zeon_catalogmanager/adminhtml_popularproducts')
        );

        // Render the layout.
        $this->renderLayout();
    }

    public function gridAction()
    {
        // Set the block name based on the url (Featured/Best/Popular).

        $blockName = $this->getRequest()->getParam('blockname', null);
        if ($blockName) {
            $this->getResponse()
                ->setBody(
                    $this->getLayout()
                        ->createBlock('zeon_catalogmanager/'.$blockName)
                        ->toHtml()
                );
        }
    }

    /**
     * Method used to save the featured products.
     */
    public function saveFeaturedProductsAction()
    {
        $data = $this->getRequest()->getPost();
        $storeId = $this->getRequest()->getParam('store', 0);
        parse_str($data['featured_products'], $featuredProducts);
        parse_str($data['featured_products_position'], $featuredProductsPosition);
        $indexProducts = array();
        try {
            if (!empty($featuredProducts)) {
                foreach ($featuredProducts as $key => $value) {
                    Mage::getSingleton('catalog/product_action')
                        ->updateAttributes(array($key), array('featured_product'=>$value), $storeId);
                    $indexProducts[] = $key;
                    if (!$value) {
                        Mage::getSingleton('catalog/product_action')
                            ->updateAttributes(array($key), array('featured_product_position'=>''), $storeId);
                    }
                }
            }
            if (!empty($featuredProductsPosition)) {
                foreach ($featuredProductsPosition as $key => $value) {
                    $product = Mage::getModel('catalog/product')->setStore($storeId)->setStoreId($storeId)->load($key);
                    $featureValue = $product->getData('featured_product')?$product->getData('featured_product'):Null;
                    if (!in_array($key, $indexProducts)) {
                        $indexProducts[] = $key;
                    }
                    if ($featureValue && ($value !== NULL)) {
                        Mage::getSingleton('catalog/product_action')
                            ->updateAttributes(array($key), array('featured_product_position'=>$value), $storeId);
                    }
                }
            }
            /*if (!empty($indexProducts)) {
                Mage::getModel('catalog/product_flat_indexer')->updateProduct($indexProducts, null);
            }*/

            $this->_getSession()->addSuccess(
                $this->__('Featured product was successfully saved.')
            );
            $this->_redirect(
                '*/*/featured',
                array('store' => $this->getRequest()->getParam('store'))
            );
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $this->_redirect(
                '*/*/featured',
                array('store' => $this->getRequest()->getParam('store'))
            );
        }
    }

    /**
     * Method used to save the Gift products For Him.
     */
    public function saveProductsOption1Action()
    {
        $data = $this->getRequest()->getPost();
        $storeId = $this->getRequest()->getParam('store', 0);
        parse_str($data['homepage_option1'], $giftProducts);
        parse_str($data['homepage_option1_position'], $giftProductsPosition);

        $indexProducts = array();
        try {
            if (!empty($giftProducts)) {
                foreach ($giftProducts as $key => $value) {
                    Mage::getSingleton('catalog/product_action')
                        ->updateAttributes(array($key), array('homepage_option1'=>$value), $storeId);
                    $indexProducts[] = $key;
                    if (!$value) {
                        Mage::getSingleton('catalog/product_action')
                            ->updateAttributes(array($key), array('homepage_option1_position'=>''), $storeId);
                    }
                }
            }
            if (!empty($giftProductsPosition)) {
                foreach ($giftProductsPosition as $key => $value) {
                    //$product = Mage::getModel('catalog/product')->load($key);
                    $product = Mage::getModel('catalog/product')
                        ->setStore($storeId)
                        ->setStoreId($storeId)
                        ->load($key);
                    $giftForValue = $product->getData('homepage_option1')?$product->getData('homepage_option1'):Null;
                    if (!in_array($key, $indexProducts)) {
                        $indexProducts[] = $key;
                    }
                    if ($giftForValue && ($value !== NULL)) {
                        Mage::getSingleton('catalog/product_action')
                            ->updateAttributes(array($key), array('homepage_option1_position'=>$value), $storeId);
                    }
                }
            }
            /*if (!empty($indexProducts)) {
                Mage::getModel('catalog/product_flat_indexer')->updateProduct($indexProducts, null);
            }*/

            $this->_getSession()->addSuccess(
                $this->__('Gift product was successfully saved.')
            );
            $this->_redirect(
                '*/*/homepageoption1',
                array('store' => $this->getRequest()->getParam('store'))
            );
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $this->_redirect(
                '*/*/homepageoption1',
                array('store' => $this->getRequest()->getParam('store'))
            );
        }
    }


    /**
     * Method used to save the Gift products For her.
     */
    public function saveProductsOption2Action()
    {
        $data = $this->getRequest()->getPost();
        $storeId = $this->getRequest()->getParam('store', 0);
        parse_str($data['homepage_option2'], $giftProducts);
        parse_str($data['homepage_option2_position'], $giftProductsPosition);

        $indexProducts = array();
        try {
            if (!empty($giftProducts)) {
                foreach ($giftProducts as $key => $value) {
                    Mage::getSingleton('catalog/product_action')
                        ->updateAttributes(array($key), array('homepage_option2'=>$value), $storeId);
                    $indexProducts[] = $key;
                    if (!$value) {
                        Mage::getSingleton('catalog/product_action')
                            ->updateAttributes(array($key), array('homepage_option2_position'=>''), $storeId);
                    }
                }
            }
            if (!empty($giftProductsPosition)) {
                foreach ($giftProductsPosition as $key => $value) {
                    //$product = Mage::getModel('catalog/product')->load($key);
                    $product = Mage::getModel('catalog/product')
                        ->setStore($storeId)
                        ->setStoreId($storeId)
                        ->load($key);
                    $giftForValue = $product->getData('homepage_option2')?$product->getData('homepage_option2'):Null;
                    if (!in_array($key, $indexProducts)) {
                        $indexProducts[] = $key;
                    }
                    if ($giftForValue && ($value !== NULL)) {
                        Mage::getSingleton('catalog/product_action')
                            ->updateAttributes(array($key), array('homepage_option2_position'=>$value), $storeId);
                    }
                }
            }
            /*if (!empty($indexProducts)) {
                Mage::getModel('catalog/product_flat_indexer')->updateProduct($indexProducts, null);
            }*/

            $this->_getSession()->addSuccess(
                $this->__('Gift product was successfully saved.')
            );
            $this->_redirect(
                '*/*/homepageoption2',
                array('store' => $this->getRequest()->getParam('store'))
            );
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $this->_redirect(
                '*/*/homepageoption2',
                array('store' => $this->getRequest()->getParam('store'))
            );
        }
    }

    /**
     * Method used to save the Gift products For Baby.
     */
    public function saveProductsOption3Action()
    {
        $data = $this->getRequest()->getPost();
        $storeId = $this->getRequest()->getParam('store', 0);
        parse_str($data['homepage_option3'], $giftProducts);
        parse_str($data['homepage_option3_position'], $giftProductsPosition);

        $indexProducts = array();
        try {
            if (!empty($giftProducts)) {
                foreach ($giftProducts as $key => $value) {
                    Mage::getSingleton('catalog/product_action')
                        ->updateAttributes(array($key), array('homepage_option3'=>$value), $storeId);
                    $indexProducts[] = $key;
                    if (!$value) {
                        Mage::getSingleton('catalog/product_action')
                            ->updateAttributes(array($key), array('homepage_option3_position'=>''), $storeId);
                    }
                }
            }
            if (!empty($giftProductsPosition)) {
                foreach ($giftProductsPosition as $key => $value) {
                    //$product = Mage::getModel('catalog/product')->load($key);
                    $product = Mage::getModel('catalog/product')
                        ->setStore($storeId)
                        ->setStoreId($storeId)
                        ->load($key);
                    $giftForValue = $product->getData('homepage_option3')?$product->getData('homepage_option3'):Null;
                    if (!in_array($key, $indexProducts)) {
                        $indexProducts[] = $key;
                    }
                    if ($giftForValue && ($value !== NULL)) {
                        Mage::getSingleton('catalog/product_action')
                            ->updateAttributes(array($key), array('homepage_option3_position'=>$value), $storeId);
                    }
                }
            }
            /*if (!empty($indexProducts)) {
                Mage::getModel('catalog/product_flat_indexer')->updateProduct($indexProducts, null);
            }*/

            $this->_getSession()->addSuccess(
                $this->__('Gift product was successfully saved.')
            );
            $this->_redirect(
                '*/*/homepageoption3',
                array('store' => $this->getRequest()->getParam('store'))
            );
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $this->_redirect(
                '*/*/homepageoption3',
                array('store' => $this->getRequest()->getParam('store'))
            );
        }
    }

    /**
     * Method used to save the best seller products.
     */
    public function saveBestSellerProductsAction()
    {
        $data = $this->getRequest()->getPost();
        $storeId = $this->getRequest()->getParam('store', 0);
        parse_str($data['best_products'], $bestProducts);
        parse_str($data['best_products_position'], $bestProductsPosition);
        $indexProducts = array();
        try {
            if (!empty($bestProducts)) {
                foreach ($bestProducts as $key => $value) {
                    Mage::getSingleton('catalog/product_action')
                        ->updateAttributes(array($key), array('best_seller'=>$value), $storeId);
                    $indexProducts[] = $key;
                    if (!$value) {
                        Mage::getSingleton('catalog/product_action')
                            ->updateAttributes(array($key), array('best_seller_position'=>''), $storeId);
                    }
                }
            }
            if (!empty($bestProductsPosition)) {
                foreach ($bestProductsPosition as $key => $value) {
                    $product = Mage::getModel('catalog/product')->setStore($storeId)->setStoreId($storeId)->load($key);
                    $bestValue = $product->getData('best_seller')?$product->getData('best_seller'):Null;
                    if (!in_array($key, $indexProducts)) {
                        $indexProducts[] = $key;
                    }

                    if ($bestValue && ($value !== NULL)) {
                        Mage::getSingleton('catalog/product_action')
                            ->updateAttributes(array($key), array('best_seller_position'=>$value), $storeId);
                    }
                }
            }

            /*if (!empty($indexProducts)) {
                Mage::getModel('catalog/product_flat_indexer')->updateProduct($indexProducts, null);
            }*/

            $this->_getSession()->addSuccess(
                $this->__('Best seller product was successfully saved.')
            );
            $this->_redirect(
                '*/*/best',
                array('store' => $this->getRequest()->getParam('store'))
            );
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $this->_redirect(
                '*/*/best',
                array('store' => $this->getRequest()->getParam('store'))
            );
        }
    }

    /**
     * Method used to save the most popular products.
     */
    public function saveMostPopularProductsAction()
    {
        $data = $this->getRequest()->getPost();
        $storeId = $this->getRequest()->getParam('store', 0);

        parse_str($data['popular_products'], $popularProducts);


        try {
            foreach ($popularProducts as $key => $value) {
                Mage::getSingleton('catalog/product_action')
                    ->updateAttributes(array($key), array('most_popular'=>$value), $storeId);
            }

            $this->_getSession()->addSuccess(
                $this->__('Most popular product was successfully saved.')
            );
            $this->_redirect(
                '*/*/popular',
                array('store' => $this->getRequest()->getParam('store'))
            );
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $this->_redirect(
                '*/*/popular',
                array('store' => $this->getRequest()->getParam('store'))
            );
        }
    }

    protected function _validateSecretKey()
    {
        return true;
    }

    protected function _isAllowed()
    {
        $actionName = $this->getRequest()->getActionName();
        $aclKey = '';
        switch($actionName) {
            case 'best':
                $aclKey = '/zeon_catalogmanager_bestproducts';
                break;
            case 'featured':
                $aclKey = '/zeon_catalogmanager_featuredproducts';
                break;
            case 'popular':
                $aclKey = '/zeon_catalogmanager_popularproducts';
                break;
        }
        return Mage::getSingleton('admin/session')
            ->isAllowed('zextension/zeon_catalogmanager_menu'.$aclKey);
    }

    private function _getExportFileName($extension='csv', $type='featured')
    {
        $storeid = $this->getRequest()->getParam('store');

        $name = $type.'_products_';

        if ($storeid) {
            $store = Mage::getModel('core/store')->load($storeid);

            if ($store && $store->getId()) {
                return $name . $store->getName() . '.' . $extension;
            }
        }

        return $name . 'AllStores.' . $extension;
    }

    /**
     * Export stylist grid to CSV format
     */
    public function exportCsvAction()
    {
        $type = $this->getRequest()->getParam('type');
        $fileName = $this->_getExportFileName('csv', $type);
        $content  = $this->getLayout()
            ->createBlock('zeon_catalogmanager/adminhtml_'.$type.'products_grid')
            ->getCsvFile();

        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export stylist grid to XML format
     */
    public function exportXmlAction()
    {
        $type = $this->getRequest()->getParam('type');
        $fileName = $this->_getExportFileName('xml');
        $content  = $this->getLayout()
            ->createBlock('zeon_catalogmanager/adminhtml_'.$type.'products_grid')
            ->getExcelFile();

        $this->_prepareDownloadResponse($fileName, $content);
    }

}
