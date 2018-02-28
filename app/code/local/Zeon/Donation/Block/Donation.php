<?php
class Zeon_Donation_Block_Donation extends Mage_Core_Block_Template
{
    const XML_PATH_FOR_DONATION_IS_ENABLE = 'donation_setting/donation_enable/is_enable';
    protected function _beforeToHtml()
    {
        if (Mage::getStoreConfig(self::XML_PATH_FOR_DONATION_IS_ENABLE)) {
            $this->setTemplate('donation/donation.phtml');
        }
        return $this;
    }

    public function checkForDonationInCart()
    {
        $products = Mage::getSingleton('checkout/session')->getQuote()->getAllVisibleItems();
        $donationSku = Mage::helper('donation')->getDonationSku();
        if (!$donationSku) {
            return false;
        }
        foreach ($products as $product) {
            if ($product->getSku() == $donationSku) {
                return true;
            }
        }
        return false;
    }
}