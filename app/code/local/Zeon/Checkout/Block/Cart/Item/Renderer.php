<?php
/**
 * Created by PhpStorm.
 * User: sandhya.sharma
 * Date: 2/23/15
 * Time: 2:42 PM
 */

Class Zeon_Checkout_Block_Cart_Item_Renderer extends Mage_Checkout_Block_Cart_Item_Renderer
{
    /**
     * Get item delete url
     *
     * @return string
     */
    public function getDeleteUrl()
    {
        if ($this->hasDeleteUrl()) {
            return $this->getData('delete_url');
        }

        $encodeKey = Mage_Core_Controller_Front_Action::PARAM_NAME_URL_ENCODED;
        $ajaxUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'checkout/cart/delete/id/'.$this->getItem()->getId()
            .'/'.$encodeKey. '/' .$this->helper('core/url')->getEncodedUrl();

        return $ajaxUrl;
    }
}