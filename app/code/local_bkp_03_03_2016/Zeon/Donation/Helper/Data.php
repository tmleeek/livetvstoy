<?php
/**
 * @category    Zeon
 * @package     Zeon_Donation
 * @copyright   Copyright (c) 2012 Zeon Solutions, Inc. All Rights Reserved.(http://www.zeonsolutions.com)
 */

class Zeon_Donation_Helper_Data extends Mage_Core_Helper_Abstract
{

    Const XPATH_DONATION_PRODUCT_SKU = 'donation_setting/donation_enable/donation_product_sku';

    public function getDonationSku()
    {
        return Mage::getStoreConfig(self::XPATH_DONATION_PRODUCT_SKU);
    }

}