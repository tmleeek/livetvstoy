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
 * @package     Mage_Core
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */


/**
 * Base html block
 *
 * @category   Mage
 * @package    Mage_Core
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Zeon_Core_Block_Template extends Mage_Core_Block_Template
{
    /**
     * Determines carrier by tracking number
     *
     * @param string $tracking
     * @param string $carrier
     * @return string
     */
    public function carrierFromTracking($tracking)
    {
        $ups = "/^(1Z\s?[0-9A-Z]{3}\s?[0-9A-Z]{3}\s?[0-9A-Z]{2}\s?[0-9A-Z]{4}\s?
            [0-9A-Z]{3}\s?[0-9A-Z]$|[\dT]\d{3}\s?\d{4}s?\d{3})$/i";
        $usps = "/^(EA|EC|CP|RA)\d{9}(\D{2})?$|^(7\d|03|23|91)\d{2}\s?\d{4}\s?\d{4}
            \s?\d{4}\s?\d{4}(\s\d{2})?$|^82\s?\d{3}\s?\d{3}\s?\d{2}$/i";
        $fedex = "/^(((96|98)\d{5}\s?\d{4}$|^(96|98)\d{2})\s?\d{4}\s?\d{4}(\s?\d{3})?)
            $|^[0-9]{15}$/i";
        if (preg_match($ups, $tracking)) {
            return "ups";
        } else if (preg_match($usps, $tracking)) {
            return "usps";
        } else if (preg_match($fedex, $tracking)) {
            return "fedex";
        } else {
            return false;
        }
    }

        /**
     * Generates a tracking link from tracking number
     *
     * @param string $tracking
     * @param string $carrier
     * @return string
     */
    public function makeTrackingLink($tracking, $text=null)
    {
        if ($text === null) {
            $text = $tracking;
        }
        //geeting carrier code from tracking number
        $trackDetail = Mage::getModel('sales/order_shipment_track')
            ->getCollection();
        $trackDetail->addFieldToSelect('carrier_code');
        $trackDetail->addAttributeToFilter('track_number', array('eq' => $tracking))
            ->load();
        $trackingData = $trackDetail->getData();
        $carrier = '';
        if ($trackingData[0]['carrier_code']) {
            $carrier = $trackingData[0]['carrier_code'];
        }

        switch ($carrier)
        {
            case "ups":
                $url = "http://wwwapps.ups.com/etracking/tracking.cgi?tracknum=".$tracking.
                    "&accept_UPS_license_agreement=yes&nonUPS_title=QuickBase%20Package%20Tracking%20System";
                break;
            case "usps":
                $url = "http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do?" .
                    "CAMEFROM=OK&strOrigTrackNum={$tracking}";
                break;
            case "fedex":
                $url = "https://www.fedex.com/fedextrack/?tracknumbers={$tracking}&cntry_code=us";
                break;
            default:
                return $tracking . "<!-- no matching carrier -->";
        }
        return "<a class='trackingLink' href='$url'>$text</a>";
    }

}
