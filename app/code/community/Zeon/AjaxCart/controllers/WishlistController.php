<?php

/**
 * zeonsolutions inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://shop.zeonsolutions.com/license-enterprise.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * This package designed for Magento ENTERPRISE edition
 * =================================================================
 * zeonsolutions does not guarantee correct work of this extension
 * on any other Magento edition except Magento ENTERPRISE edition.
 * zeonsolutions does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   Zeon
 * @package    Zeon_AjaxCart
 * @version    0.0.1
 * @copyright  @copyright Copyright (c) 2013 zeonsolutions.Inc. (http://www.zeonsolutions.com)
 * @license    http://shop.zeonsolutions.com/license-enterprise.txt
 */
class Zeon_AjaxCart_WishlistController extends Mage_Core_Controller_Front_Action {

    const XML_PATH_ENABLED = 'zeon_ajaxcart/general/is_enabled';

    /**
     * If true, authentication in this controller (wishlist) could be skipped
     *
     * @var bool
     */
    protected $_skipAuthentication = false;

    /**
     * Action list where need check enabled cookie
     *
     * @var array
     */
    protected $_cookieCheckActions = array('add');

    /**
     * Filter to convert localized values to internal ones
     * @var Zend_Filter_LocalizedToNormalized
     */
    protected $_localFilter = null;

    /**
     * Processes localized qty (entered by user at frontend) into internal php format
     *
     * @param string $qty
     * @return float|int|null
     */
    protected function _processLocalizedQty($qty) {
        if (!$this->_localFilter) {
            $this->_localFilter = new Zend_Filter_LocalizedToNormalized(array('locale' => Mage::app()->getLocale()->getLocaleCode()));
        }
        $qty = $this->_localFilter->filter($qty);
        if ($qty < 0) {
            $qty = null;
        }
        return $qty;
    }

    public function preDispatch() {
        parent::preDispatch();

        $response = array();

        if (!Mage::getStoreConfigFlag(self::XML_PATH_ENABLED)) {
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
            $response["message"] = $this->__('Cannot create wishlist. Please Enable the AjaxCart extension.');
            return;
        }

        if (!$this->_skipAuthentication && !Mage::getSingleton('customer/session')->isLoggedIn()) {
            $this->setFlag('', 'no-dispatch', true);
            if ($this->getRequest()->getActionName() == 'add') {
                $params = $this->getRequest()->getParams();
                Mage::getSingleton( 'customer/session' )
                    ->setBeforeAuthUrl(
                        Mage::getUrl(
                            'wishlist/index/add',
                            array(
                                'product' => $params['product'],
                                'form_key' => $params['form_key']
                            )
                        )
                    );
            }
            $response["loginRequired"] = 1;
            $response["message"] = $this->__('Login required to create the wishlist.');
            ;
        }
        if (!Mage::getStoreConfigFlag('wishlist/general/active')) {
            $response["message"] = $this->__('Cannot create wishlist.');
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
    }

    /**
     * Set skipping authentication in actions of this controller (wishlist)
     *
     * @return Mage_Wishlist_IndexController
     */
    public function skipAuthentication() {
        $this->_skipAuthentication = true;
        return $this;
    }

    /**
     * Retrieve wishlist object
     * @param int $wishlistId
     * @return Mage_Wishlist_Model_Wishlist|bool
     */
    protected function _getWishlist($wishlistId = null)
    {
        $wishlist = Mage::registry('wishlist');
        if ($wishlist) {
            return $wishlist;
        }

        try {
            if (!$wishlistId) {
                $wishlistId = $this->getRequest()->getParam('wishlist_id');
            }
            $customerId = Mage::getSingleton('customer/session')->getCustomerId();
            /* @var Mage_Wishlist_Model_Wishlist $wishlist */
            $wishlist = Mage::getModel('wishlist/wishlist');
            if ($wishlistId) {
                $wishlist->load($wishlistId);
            } else {
                $wishlist->loadByCustomer($customerId, true);
            }

            if (!$wishlist->getId() || $wishlist->getCustomerId() != $customerId) {
                $wishlist = null;
                Mage::throwException(
                    Mage::helper('wishlist')->__("Requested wishlist doesn't exist")
                );
            }

            Mage::register('wishlist', $wishlist);
        } catch (Mage_Core_Exception $e) {
            Mage::getSingleton('wishlist/session')->addError($e->getMessage());
            return false;
        } catch (Exception $e) {
            Mage::getSingleton('wishlist/session')->addException($e,
                Mage::helper('wishlist')->__('Wishlist could not be created.')
            );
            return false;
        }

        return $wishlist;
    }

    /**
     * Adding new item
     */
    public function addAction() {
        $response = array();

        $session = Mage::getSingleton('customer/session');
        $wishlist = $this->_getWishlist();
        if (!$wishlist) {
            $response["message"] = $this->__('Cannot create wishlist.');
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
            return;
        }

        $productId = (int) $this->getRequest()->getParam('product');
        if (!$productId) {
            $response["message"] = $this->__('Cannot add the item to wishlist.');
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
            return;
        }

        $product = Mage::getModel('catalog/product')->load($productId);
        if (!$product->getId() || !$product->isVisibleInCatalog()) {
            $response["message"] = $this->__('Cannot specify product.');
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
            return;
        }

        try {
            $requestParams = $this->getRequest()->getParams();
            $buyRequest = new Varien_Object($requestParams);

            $result = $wishlist->addNewItem($product, $buyRequest);
            if (is_string($result)) {
                $response["message"] = $result;
            }
            $wishlist->save();

            Mage::dispatchEvent(
                    'wishlist_add_product', array(
                'wishlist' => $wishlist,
                'product' => $product,
                'item' => $result
                    )
            );

            Mage::helper('wishlist')->calculate();

            $message = $this->__('%1$s has been added to your wishlist.', $product->getName());
            $response["message"] = $message;

            //Get Layout update content
            $layout = $this->getLayout();
            $layout->getUpdate()
                    ->addHandle('default')
                    ->addHandle('customer_logged_in')
                    ->load();
            $layout->generateXml()->generateBlocks();
            $header = $layout->getBlock('header')->toHtml();
            if (!Mage::getConfig()->getNode('modules/Enterprise_PageCache/active')) {
                $response["header"] = preg_replace("#<div class=\"nav-container\">(.*?)</div>#is", "", trim($header));
            } else {
                $response["header"] = trim($header);
            }

            $sidebar = $this->getLayout()->createBlock('wishlist/customer_sidebar')
                            ->setTemplate('zeon/ajaxcart/wishlist/sidebar.phtml')->toHtml();

            $response["sidebar"] = str_replace('<div id="block-wishlist" class="block block-wishlist">', '', rtrim($sidebar, '</div>'));
            $response["block_id"] = 'block-wishlist';
            $customerId = Mage::getSingleton('customer/session')->getCustomerId();
            if ($customerId) {
                $wishlistCollection = Mage::getModel('wishlist/wishlist')->getCollection()
                ->filterByCustomerId($customerId);
                $limit = Mage::helper('enterprise_wishlist')->getWishlistLimit();
                if (Mage::helper('enterprise_wishlist')->isWishlistLimitReached($wishlistCollection)) {
                    $response["wishlistlimit"] = $limit;
                }
            }
        } catch (Mage_Core_Exception $e) {
            $message = $this->__('An error occurred while adding item to wishlist: %s', $e->getMessage());
            $response["message"] = $message;
        } catch (Exception $e) {
            $message = $this->__('An error occurred while adding item to wishlist: %s', $e->getMessage());
            $response["message"] = $message;
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
    }

    /**
     * Action to accept new configuration for a wishlist item
     */
    public function updateItemOptionsAction() {
        $response = array();

        $session = Mage::getSingleton('customer/session');
        $wishlist = $this->_getWishlist();
        if (!$wishlist) {
            $response["message"] = $this->__('Cannot create wishlist.');
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
            return;
        }

        $productId = (int) $this->getRequest()->getParam('product');
        if (!$productId) {
            $response["message"] = $this->__('Cannot add the item to wishlist.');
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
            return;
        }

        $product = Mage::getModel('catalog/product')->load($productId);
        if (!$product->getId() || !$product->isVisibleInCatalog()) {
            $response["message"] = $this->__('Cannot specify product.');
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
            return;
        }

        try {
            $id = (int) $this->getRequest()->getParam('id');
            $buyRequest = new Varien_Object($this->getRequest()->getParams());

            $wishlist->updateItem($id, $buyRequest)
                    ->save();

            Mage::helper('wishlist')->calculate();
            Mage::dispatchEvent('wishlist_update_item', array(
                'wishlist' => $wishlist, 'product' => $product, 'item' => $wishlist->getItem($id))
            );

            Mage::helper('wishlist')->calculate();

            $message = $this->__('%1$s has been updated in your wishlist.', $product->getName());
            $response["message"] = $message;
            $response["wishlist_item_id"] = $wishlist->getLastAddedItemId();

            //Get Layout update content
            $layout = $this->getLayout();
            $layout->getUpdate()
                    ->addHandle('default')
                    ->addHandle('customer_logged_in')
                    ->load();
            $layout->generateXml()->generateBlocks();
            $header = $layout->getBlock('header')->toHtml();
            if (!Mage::getConfig()->getNode('modules/Enterprise_PageCache/active')) {
                $response["header"] = preg_replace("#<div class=\"nav-container\">(.*?)</div>#is", "", trim($header));
            } else {
                $response["header"] = trim($header);
            }
        } catch (Mage_Core_Exception $e) {
            $message = $e->getMessage();
            $response["message"] = $message;
        } catch (Exception $e) {
            $message = $this->__('An error occurred while updating wishlist.');
            $response["message"] = $message;
            Mage::logException($e);
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
    }

    /**
     * Add wishlist item to shopping cart and remove from wishlist
     *
     * If Product has required options - item removed from wishlist and redirect
     * to product view page with message about needed defined required options
     *
     */
    public function cartAction() {
        $response = array();

        $wishlist = $this->_getWishlist();
        if (!$wishlist) {
            $response["message"] = $this->__('Cannot create wishlist.');
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
            return;
        }

        $itemId = (int) $this->getRequest()->getParam('item');
        $this->getRequest()->setParam('product', $itemId);

        /* @var $item Mage_Wishlist_Model_Item */
        $item = Mage::getModel('wishlist/item')->load($itemId);

        if (!$item->getId() || $item->getWishlistId() != $wishlist->getId()) {
            $response["message"] = $this->__('Cannot add item to shopping cart.');
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
            return;
        }

        // Set qty
        $qty = $this->getRequest()->getParam('qty');
        if (is_array($qty)) {
            if (isset($qty[$itemId])) {
                $qty = $qty[$itemId];
            } else {
                $qty = 1;
            }
        }
        $qty = $this->_processLocalizedQty($qty);
        if ($qty) {
            $item->setQty($qty);
        }

        /* @var $session Mage_Wishlist_Model_Session */
        $session = Mage::getSingleton('wishlist/session');
        $cart = Mage::getSingleton('checkout/cart');

        try {
            $options = Mage::getModel('wishlist/item_option')->getCollection()
                    ->addItemFilter(array($itemId));
            $item->setOptions($options->getOptionsByItem($itemId));

            $buyRequest = Mage::helper('catalog/product')->addParamsToBuyRequest(
                    $this->getRequest()->getParams(), array('current_config' => $item->getBuyRequest())
            );

            $item->mergeBuyRequest($buyRequest);
            $item->addToCart($cart, true);
            $cart->save()->getQuote()->collectTotals();
            $wishlist->save();

            Mage::helper('wishlist')->calculate();

            $message = $this->__('%1$s has been added in your shopping cart.', $item->getProduct()->getName());
            $response["message"] = $message;

            //Get Layout update content
            $layout = $this->getLayout();
            $layout->getUpdate()
                    ->addHandle('default')
                    ->addHandle('customer_logged_in')
                    ->addHandle('wishlist_index_index')
                    ->load();
            $layout->generateXml()->generateBlocks();
            $header = $layout->getBlock('header')->toHtml();
            $content = $layout->getBlock('content')->toHtml();
            if (!Mage::getConfig()->getNode('modules/Enterprise_PageCache/active')) {
                $response["header"] = preg_replace("#<div class=\"nav-container\">(.*?)</div>#is", "", trim($header));
            } else {
                $response["header"] = trim($header);
            }
            $response["content"] = trim($content);

            $sidebar = $this->getLayout()->createBlock('wishlist/customer_sidebar')
                            ->setTemplate('zeon/ajaxcart/wishlist/sidebar.phtml')->toHtml();

            $response["sidebar"] = str_replace('<div id="block-wishlist" class="block block-wishlist">', '', rtrim($sidebar, '</div>'));
            $response["block_id"] = 'block-wishlist';
        } catch (Mage_Core_Exception $e) {
            if ($e->getCode() == Mage_Wishlist_Model_Item::EXCEPTION_CODE_NOT_SALABLE) {
                $message = Mage::helper('wishlist')->__('This product(s) is currently out of stock');
            } else if ($e->getCode() == Mage_Wishlist_Model_Item::EXCEPTION_CODE_HAS_REQUIRED_OPTIONS) {
                $message = $e->getMessage();
            } else {
                $message = $e->getMessage();
            }
            $response["message"] = $message;
            $response['additional'] = Mage::helper('wishlist')->__('<a href="%s">Click here</a> to view product.', $item->getProduct()->getUrl());
        } catch (Exception $e) {
            $message = $this->__('Cannot add item to shopping cart.');
            $response["message"] = $message;
            Mage::logException($e);
        }
        Mage::helper('wishlist')->calculate();
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
    }

    /**
     * Add all items from wishlist to shopping cart
     *
     */
    public function allcartAction() {
        $wishlist = $this->_getWishlist();
        if (!$wishlist) {
            $response["message"] = $this->__('Cannot create wishlist.');
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
            return;
        }
        $isOwner = $wishlist->isOwner(Mage::getSingleton('customer/session')->getCustomerId());

        $messages = array();
        $addedItems = array();
        $notSalable = array();
        $hasOptions = array();

        $cart = Mage::getSingleton('checkout/cart');
        $collection = $wishlist->getItemCollection()
                ->setVisibilityFilter();

        $qtys = $this->getRequest()->getParam('qty');
        foreach ($collection as $item) {
            /** @var Mage_Wishlist_Model_Item */
            try {
                $item->unsProduct();

                // Set qty
                if (isset($qtys[$item->getId()])) {
                    $qty = $this->_processLocalizedQty($qtys[$item->getId()]);
                    if ($qty) {
                        $item->setQty($qty);
                    }
                }

                // Add to cart
                if ($item->addToCart($cart, $isOwner)) {
                    $addedItems[] = $item->getProduct();
                }
            } catch (Mage_Core_Exception $e) {
                if ($e->getCode() == Mage_Wishlist_Model_Item::EXCEPTION_CODE_NOT_SALABLE) {
                    $notSalable[] = $item;
                } else if ($e->getCode() == Mage_Wishlist_Model_Item::EXCEPTION_CODE_HAS_REQUIRED_OPTIONS) {
                    $hasOptions[] = $item;
                } else {
                    $messages[] = $this->__('%s for "%s".', trim($e->getMessage(), '.'), $item->getProduct()->getName());
                }
            } catch (Exception $e) {
                Mage::logException($e);
                $messages[] = Mage::helper('wishlist')->__('Cannot add the item to shopping cart.');
            }
        }

        if ($notSalable) {
            $products = array();
            foreach ($notSalable as $item) {
                $products[] = '"' . $item->getProduct()->getName() . '"';
            }
            $messages[] = Mage::helper('wishlist')->__('Unable to add the following product(s) to shopping cart: %s.', join(', ', $products));
        }

        if ($hasOptions) {
            $products = array();
            foreach ($hasOptions as $item) {
                $products[] = '"' . $item->getProduct()->getName() . '"';
            }
            $messages[] = Mage::helper('wishlist')->__('Product(s) %s have required options. Each of them can be added to cart separately only.', join(', ', $products));
        }

        if ($messages) {
            $isMessageSole = (count($messages) == 1);
            if ($isMessageSole && count($hasOptions) == 1) {
                $item = $hasOptions[0];
                if ($isOwner) {
                    $item->delete();
                }
                $response["error"] = Mage::helper('wishlist')->__('%s have required options.', $item->getProduct()->getName());
            } else {
                $response["error"] = implode("\n", $messages);
            }
        }

        if ($addedItems) {
            // save wishlist model for setting date of last update
            try {
                $wishlist->save();
            } catch (Exception $e) {
                $response["error"] = $this->__('Cannot update wishlist');
            }

            $products = array();
            foreach ($addedItems as $product) {
                $products[] = '"' . $product->getName() . '"';
            }

            $response["message"] = Mage::helper('wishlist')->__('%d product(s) have been added to shopping cart: %s.', count($addedItems), join(', ', $products));
        }
        // save cart and collect totals
        $cart->save()->getQuote()->collectTotals();
        $wishlist->save();
        Mage::helper('wishlist')->calculate();

        //Get Layout update content
        $layout = $this->getLayout();
        $layout->getUpdate()
                ->addHandle('default')
                ->addHandle('customer_logged_in')
                ->addHandle('wishlist_index_index')
                ->load();
        $layout->generateXml()->generateBlocks();
        $header = $layout->getBlock('header')->toHtml();
        $content = $layout->getBlock('content')->toHtml();
        if (!Mage::getConfig()->getNode('modules/Enterprise_PageCache/active')) {
            $response["header"] = preg_replace("#<div class=\"nav-container\">(.*?)</div>#is", "", trim($header));
        } else {
            $response["header"] = trim($header);
        }
        $response["content"] = trim($content);
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
    }

    /**
     * Remove item
     */
    public function removeAction() {
        $response = array();
        $wishlist = $this->_getWishlist();
        $id = (int) $this->getRequest()->getParam('item');
        $item = Mage::getModel('wishlist/item')->load($id);

        if ($item->getWishlistId() == $wishlist->getId()) {
            try {
                $item->delete();
                $wishlist->save();
                $message = $this->__('Item was removed from your wishlist.');
                $response["message"] = $message;

                //Get Layout update content
                $layout = $this->getLayout();
                if (Mage::getSingleton('customer/session')->isLoggedIn()) {
                    $layout->getUpdate()
                            ->addHandle('default')
                            ->addHandle('customer_logged_in')
                            ->addHandle('wishlist_index_index')
                            ->load();
                } else {
                    $layout->getUpdate()
                            ->addHandle('default')
                            ->addHandle('customer_logged_out')
                            ->addHandle('wishlist_index_index')
                            ->load();
                }
                $layout->generateXml()->generateBlocks();
                $header = $layout->getBlock('header')->toHtml();
                $content = $layout->getBlock('content')->toHtml();
                if (!Mage::getConfig()->getNode('modules/Enterprise_PageCache/active')) {
                    $response["header"] = preg_replace("#<div class=\"nav-container\">(.*?)</div>#is", "", trim($header));
                } else {
                    $response["header"] = trim($header);
                }
                $response["content"] = trim($content);
            } catch (Mage_Core_Exception $e) {
                $message = $this->__('An error occurred while deleting the item from wishlist: %s', $e->getMessage());
                $response["message"] = $message;
                Mage::logException($e);
            } catch (Exception $e) {
                $message = $this->__('An error occurred while deleting the item from wishlist.');
                $response["message"] = $message;
                Mage::logException($e);
            }
        }

        Mage::helper('wishlist')->calculate();

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
    }

    /**
     * Update wishlist item comments
     */
    public function updateAction() {
        $response = array();
        $post = $this->getRequest()->getPost();
                //print_r($post);
        if ($post && isset($post['qty'])) {
            $wishlist = $this->_getWishlist();
            $wishlistId = $post['wishlist_id'];
            $itemId = $post['item_id'];
            $updatedItems = 0;
            $qty = $post['qty'];

            //foreach ($post['qty'] as $itemId => $description) {
            $item = Mage::getModel('wishlist/item')->load($itemId);
            //print_r((int)$item->getQty());
            if ((int)$item->getQty() != $qty) {

                    $qty = $post['qty'];
                    if (isset($qty)) {
                        $qty = $this->_processLocalizedQty($qty);
                    }
                    if (is_null($qty)) {
                        $qty = $qty;
                        if (!$qty) {
                            $qty = 1;
                        }
                    } elseif (0 == $qty) {
                        try {
                            $item->delete();
                        } catch (Exception $e) {
                            Mage::logException($e);
                            $response["message"] = $this->__('Can\'t delete item from wishlist');
                        }
                    }

                    try {
                        $item->setQty($qty)
                                ->save();
                        $updatedItems++;
                    } catch (Exception $e) {
                        $response["message"] = $this->__('Can\'t save description %s', Mage::helper('core')->htmlEscape($description));
                        Mage::logException($e);
                    }
                //}

                // save wishlist model for setting date of last update
                if ($updatedItems) {
                    try {
//
                        $message = $this->__('Wishlist was updated successfully.');
                        $response["message"] = $message;

                        $layout = $this->getLayout();
                        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
                            $layout->getUpdate()
                                    ->addHandle('default')
                                    ->addHandle('customer_logged_in')
                                    ->addHandle('wishlist_index_index')
                                    ->load();
                        } else {
                            $layout->getUpdate()
                                    ->addHandle('default')
                                    ->addHandle('customer_logged_out')
                                    ->addHandle('wishlist_index_index')
                                    ->load();
                        }
                        $layout->generateXml()->generateBlocks();
                        $header = $layout->getBlock('header')->toHtml();
                        $content = $layout->getBlock('content')->toHtml();
                        if (!Mage::getConfig()->getNode('modules/Enterprise_PageCache/active')) {
                            $response["header"] = preg_replace("#<div class=\"nav-container\">(.*?)</div>#is", "", trim($header));
                        } else {
                            $response["header"] = trim($header);
                        }
                        $response["content"] = trim($content);
                    } catch (Exception $e) {
                        $response["message"] = $this->__('Can\'t update wishlist');
                        Mage::logException($e);
                    }
                }
            } else {
                $response["message"] = $this->__('Already up-to-date');
            }
        } else {
            $response["message"] = $this->__('Error occured! Please try again!');
        }
        //print_r($response);
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
    }

    /************** For Move / copy code **************/

    /**
     * Move item to given wishlist.
     * Check whether item belongs to one of customer's wishlists
     *
     * @param Mage_Wishlist_Model_Item $item
     * @param Mage_Wishlist_Model_Wishlist $wishlist
     * @param Mage_Wishlist_Model_Resource_Wishlist_Collection $customerWishlists
     * @param int $qty
     * @throws InvalidArgumentException|DomainException
     */
    protected function _moveItem(
        Mage_Wishlist_Model_Item $item,
        Mage_Wishlist_Model_Wishlist $wishlist,
        Mage_Wishlist_Model_Resource_Wishlist_Collection $customerWishlists,
        $qty = null
    ) {
        if (!$item->getId()) {
            throw new InvalidArgumentException();
        }
        if ($item->getWishlistId() == $wishlist->getId()) {
            throw new DomainException(null, 1);
        }
        if (!$customerWishlists->getItemById($item->getWishlistId())) {
            throw new DomainException(null, 2);
        }

        $buyRequest = $item->getBuyRequest();
        if ($qty) {
            $buyRequest->setQty($qty);
        }
        $wishlist->addNewItem($item->getProduct(), $buyRequest);
        $qtyDiff = $item->getQty() - $qty;
        if ($qty && $qtyDiff > 0) {
            $item->setQty($qtyDiff);
            $item->save();
        } else {
            $item->delete();
        }
    }

    /**
     * Move wishlist item to given wishlist
     *
     * @return void
     */
    public function moveitemAction()
    {
        $message = '';
        $wishlist = $this->_getWishlist();
        if (!$wishlist) {
            $message = $this->__('Wishlist does not exist.');
            $response["message"] = $message;
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
            exit;
        }
        $itemId = $this->getRequest()->getParam('item_id');

        if ($itemId) {
            try {
                $wishlists = Mage::getModel('wishlist/wishlist')->getCollection()
                    ->filterByCustomerId(Mage::getSingleton('customer/session')->getCustomerId());

                /* @var Mage_Wishlist_Model_Item $item */
                $item = Mage::getModel('wishlist/item');
                $item->loadWithOptions($itemId);

                $productName = Mage::helper('core')->escapeHtml($item->getProduct()->getName());
                $wishlistName = Mage::helper('core')->escapeHtml($wishlist->getName());

                $this->_moveItem($item, $wishlist, $wishlists, $this->getRequest()->getParam('qty', null));
                $message = Mage::helper('enterprise_wishlist')->__(
                    '"%s" was successfully moved to %s.', $productName, $wishlistName
                );
                Mage::helper('wishlist')->calculate();
                $response["content"] = 'update content';

            } catch (InvalidArgumentException $e) {
                $message = Mage::helper('enterprise_wishlist')->__("Item with specified ID doesn't exist.");
            } catch (DomainException $e) {
                if ($e->getCode() == 1) {
                    $message = Mage::helper('enterprise_wishlist')->__(
                        '"%s" is already present in %s.', $productName, $wishlistName
                    );
                } else {
                    $message = Mage::helper('enterprise_wishlist')->__('"%s" cannot be moved.', $productName);
                }
            } catch (Mage_Core_Exception $e) {
                $message = $e->getMessage();
            } catch (Exception $e) {
                $message = Mage::helper('enterprise_wishlist')->__(
                    'Could not move wishlist item. <br> %s', $e->getMessage()
                );
            }
        }
        $wishlist->save();
        $response["message"] = $message;
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
    }

   /**
     * Copy item to given wishlist
     *
     * @param Mage_Wishlist_Model_Item $item
     * @param Mage_Wishlist_Model_Wishlist $wishlist
     * @param int $qty
     * @throws InvalidArgumentException|DomainException
     */
    protected function _copyItem(Mage_Wishlist_Model_Item $item, Mage_Wishlist_Model_Wishlist $wishlist, $qty = null)
    {
        if (!$item->getId()) {
            throw new InvalidArgumentException();
        }
        if ($item->getWishlistId() == $wishlist->getId()) {
            throw new DomainException();
        }
        $buyRequest = $item->getBuyRequest();
        if ($qty) {
            $buyRequest->setQty($qty);
        }
        $wishlist->addNewItem($item->getProduct(), $buyRequest);
        Mage::dispatchEvent(
            'wishlist_add_product',
            array(
                'wishlist'  => $wishlist,
                'product'   => $item->getProduct(),
                'item'      => $item
            )
        );
    }

    /**
     * Copy wishlist item to given wishlist
     *
     * @return void
     */
    public function copyitemAction()
    {
        $message = '';
        $session = Mage::getSingleton('core/session');
        $requestParams = $this->getRequest()->getParams();
        if ($session->getBeforeWishlistRequest()) {
            $requestParams = $session->getBeforeWishlistRequest();
            $session->unsBeforeWishlistRequest();
        }

        $wishlist = $this->_getWishlist(isset($requestParams['wishlist_id']) ? $requestParams['wishlist_id'] : null);
        if (!$wishlist) {
            $message = $this->__('Cannot copy to another wishlist.');
            $response["message"] = $message;
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
            exit;
        }
        $itemId = isset($requestParams['item_id']) ? $requestParams['item_id'] : null;
        $qty = isset($requestParams['qty']) ? $requestParams['qty'] : null;
        if ($itemId) {
            $productName = '';
            try {
                /* @var Mage_Wishlist_Model_Item $item */
                $item = Mage::getModel('wishlist/item');
                $item->loadWithOptions($itemId);

                $wishlistName = Mage::helper('core')->escapeHtml($wishlist->getName());
                $productName = Mage::helper('core')->escapeHtml($item->getProduct()->getName());

                $this->_copyItem($item, $wishlist, $qty);
                $message = Mage::helper('enterprise_wishlist')->__(
                    '"%s" was successfully copied to %s.', $productName, $wishlistName
                );
                Mage::helper('wishlist')->calculate();
            } catch (InvalidArgumentException $e) {
                $message = Mage::helper('enterprise_wishlist')->__('Item was not found.');
            } catch (DomainException $e) {
                $message = Mage::helper('enterprise_wishlist')->__(
                    '"%s" is already present in %s.', $productName, $wishlistName
                );
            } catch (Mage_Core_Exception $e) {
                $message = $e->getMessage();
            } catch (Exception $e) {
                Mage::logException($e);
                if ($productName) {
                    $message = Mage::helper('enterprise_wishlist')->__('Could not copy "%s".', $productName);
                } else {
                    $message = Mage::helper('enterprise_wishlist')->__('Could not copy wishlist item.');
                }
            }
        }
        $wishlist->save();
        $response["message"] = $message;
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
    }

    /**
     * Add cart item to wishlist and remove from cart
     */
    public function fromcartAction()
    {
        $message = '';
        $wishlist = $this->_getWishlist();
        if (!$wishlist) {
            return $this->norouteAction();
        }
        $itemId = (int) $this->getRequest()->getParam('item');

        /* @var Mage_Checkout_Model_Cart $cart */
        $cart = Mage::getSingleton('checkout/cart');
        $session = Mage::getSingleton('checkout/session');

        try {
            $item = $cart->getQuote()->getItemById($itemId);
            if (!$item) {
                $message = Mage::helper('wishlist')->__("Requested cart item doesn't exist");
                $response["message"] = $message;
                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
                exit;
            }

            $productId  = $item->getProductId();
            $buyRequest = $item->getBuyRequest();

            $wishlist->addNewItem($productId, $buyRequest);

            $productIds[] = $productId;
            $cart->getQuote()->removeItem($itemId);
            $cart->save();
            Mage::helper('wishlist')->calculate();
            $productName = Mage::helper('core')->escapeHtml($item->getProduct()->getName());
            $wishlistName = Mage::helper('core')->escapeHtml($wishlist->getName());
            $message = Mage::helper('wishlist')->__("%s has been moved to wishlist %s", $productName, $wishlistName);
            $wishlist->save();

            //Get Layout update content
            $layout = $this->getLayout();
            if (Mage::getSingleton('customer/session')->isLoggedIn()) {
                    $layout->getUpdate()
                            ->addHandle('default')
                            ->addHandle('customer_logged_in')
                            ->addHandle('checkout_cart_index')
                            ->load();
                } else {
                    $layout->getUpdate()
                            ->addHandle('default')
                            ->addHandle('customer_logged_out')
                            ->addHandle('checkout_cart_index')
                            ->load();
                }
                $layout->generateXml()->generateBlocks();
                $header = $layout->getBlock('header')->toHtml();
                $content = $layout->getBlock('content')->toHtml();
            if (!Mage::getConfig()->getNode('modules/Enterprise_PageCache/active')) {
                $response["header"] = preg_replace("#<div class=\"nav-container\">(.*?)</div>#is", "", trim($header));
            } else {
                $response["header"] = trim($header);
            }
            $response["content"] = trim($content);
            $response["blockitemid"] = $itemId;
            $response["listname"] = $wishlistName;

            $customerId = Mage::getSingleton('customer/session')->getCustomerId();
            if ($customerId) {
                $wishlistCollection = Mage::getModel('wishlist/wishlist')->getCollection()
                ->filterByCustomerId($customerId);
                $limit = Mage::helper('enterprise_wishlist')->getWishlistLimit();
                if (Mage::helper('enterprise_wishlist')->isWishlistLimitReached($wishlistCollection)) {
                    $response["wishlistlimit"] = $limit;
                }
            }

        } catch (Mage_Core_Exception $e) {
            Mage::logException($e);
            $message = $e->getMessage();
        } catch (Exception $e) {
            Mage::logException($e);
            $message = Mage::helper('wishlist')->__('Cannot move item to wishlist');
        }

        $response["message"] = $message;
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
    }

    public function createListAction()
    {
        $message = '';
        $requestParams = $this->getRequest()->getParams();
        $customerId = Mage::getSingleton('customer/session')->getCustomerId();
        $wishlistName = $this->getRequest()->getParam('name');
        $visibility = ($this->getRequest()->getParam('visibility', 0) === 'on' ? 1 : 0);

        $wishlist = Mage::getModel('wishlist/wishlist');

        $wishlistCollection = Mage::getModel('wishlist/wishlist')->getCollection()
                ->filterByCustomerId($customerId);
            $limit = Mage::helper('enterprise_wishlist')->getWishlistLimit();
            if (Mage::helper('enterprise_wishlist')->isWishlistLimitReached($wishlistCollection)) {
                $message = Mage::helper('enterprise_wishlist')->__('Only %d wishlists can be created.', $limit);
                $response["message"] = $message;
                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
                return;
            }
            $wishlist->setCustomerId($customerId);

        $wishlist->setName($wishlistName)
            ->setVisibility($visibility)
            ->generateSharingCode()
            ->save();

        $response["message"] = $message;
        $response["wishlist"] = array("id" => $wishlist->getId(), 'name' => $wishlistName);
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
    }


}