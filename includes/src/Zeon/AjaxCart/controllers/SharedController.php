<?php
include_once('Mage/Wishlist/controllers/SharedController.php');
class Zeon_AjaxCart_SharedController
    extends Mage_Wishlist_SharedController
{
    /**
     *
     * function to add all items to cart
     */
    public function allcartAction()
    {
        $session    = Mage::getSingleton('core/session');
        $cart       = Mage::getSingleton('checkout/cart');

        $wishlist = $this->_getWishlist();
        if (!$wishlist) {
            $session->addError(Mage::helper('wishlist')->__('Sorry, this wishlist no longer exists.'));
            return $this->_redirectUrl(Mage::getUrl('checkout/cart'));
        }

        $wishListItems =  Mage::getResourceModel('wishlist/item_collection')
            ->addWishlistFilter($wishlist);

        if (count($wishListItems)) {
            $redirectUrl = $this->_getRefererUrl();
            foreach ($wishListItems as $item) {
                $itemId = $item->getId();
                $item = Mage::getModel('wishlist/item')->load($itemId);
                try {
                    $options = Mage::getModel('wishlist/item_option')->getCollection()
                        ->addItemFilter(array($itemId));
                    $item->setOptions($options->getOptionsByItem($itemId));

                    $item->addToCart($cart);

                    if (Mage::helper('checkout/cart')->getShouldRedirectToCart()) {
                        $redirectUrl = Mage::helper('checkout/cart')->getCartUrl();
                    }
                } catch (Mage_Core_Exception $e) {
                    if ($e->getCode() == Mage_Wishlist_Model_Item::EXCEPTION_CODE_NOT_SALABLE) {
                        $session->addError(Mage::helper('wishlist')->__('This product(s) is currently out of stock'));
                        Mage::log('Exc 1: '.$e->getMessage(), null, 'wishlist.log');
                    } else {
                        Mage::getSingleton('catalog/session')->addNotice($e->getMessage());
                        Mage::log('Exc 2: '.$e->getMessage(). ' ' . $redirectUrl, null, 'wishlist.log');
                        $redirectUrl = $item->getProductUrl();
                    }
                } catch (Exception $e) {
                    $session->addException($e, Mage::helper('wishlist')->__('Cannot add item to shopping cart'));
                    Mage::log('Exc 3: '.$e->getMessage(), null, 'wishlist.log');
                }
            }
            try {
                $cart->save()->getQuote()->collectTotals();
            } catch (Mage_Core_Exception $e) {
                $session->addException($e, Mage::helper('wishlist')->__('Error Adding to cart'));
                Mage::log('Exc 4: '.$e->getMessage(), null, 'wishlist.log');
            }
        } else {
            $session->addError(Mage::helper('wishlist')->__('No items in wishlist.'));
            return $this->_redirectUrl(Mage::getUrl('checkout/cart'));
        }

        return $this->_redirectUrl($redirectUrl);
    }

}