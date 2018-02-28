<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition License
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magentocommerce.com/license/enterprise-edition
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Paypal
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */

/**
 * Config model that is aware of all Mage_Paypal payment methods
 * Works with PayPal-specific system configuration
 *
 * ZEON :: Added the functionality to send BB details to Paypal
 */
class Zeon_Paypalbn_Model_Config extends Mage_Paypal_Model_Config
{
    /**
     * Magento BNs code for Enterprice Version
     * @var string
     */
    protected $_magentoEnterpriceBN = 'Zeon_SI_MagentoEE_PPA';

    /**
     * Magento BNs code for Community Version
     * @var string
     */
    protected $_magentoCommunityBN = 'Zeon_SI_MagentoCE_PPA';


    /**
     * BN code getter
     *
     * @param string $countryCode ISO 3166-1
     */
    public function getBuildNotationCode($countryCode = null)
    {
        /*$product = 'WPP';
        if ($this->_methodCode && isset($this->_buildNotationPPMap[$this->_methodCode])) {
            $product = $this->_buildNotationPPMap[$this->_methodCode];
        }
        if (null === $countryCode) {
            $countryCode = $this->_matchBnCountryCode($this->getMerchantCountry());
        }
        if ($countryCode) {
            $countryCode = '_' . $countryCode;
        }
        return sprintf('Varien_Cart_%s%s', $product, $countryCode);
        */

        // Get Order ID
        //$id = Mage::getSingleton('checkout/session')->getLastRealOrderId();

        //Mage::log('Order ID : ' . $id . ' = ' . $this->_magentoEnterpriceBN, null, 'paypalBN.log');

        return $this->_magentoEnterpriceBN;
    }
}

