<?php
/**
 * Sales module base helper
 *
 * @category    Mage
 * @package     Mage_Sales
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Zeon_Sales_Helper_Data extends Mage_Sales_Helper_Data
{
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

    /**
     * Function to return order related information
     *
     * @param int $orderId
     *
     * @return array
     */
    public function getCurrentOrderDetails($orderId)
    {
        //loading the order object
        $orderObj = Mage::getSingleton('sales/order')->loadByIncrementId($orderId);
        $couponCode = $orderObj->getCouponCode();
        $discountAmount = abs($orderObj->getDiscountAmount());

        $orderInfo = array();
        $orderInfo['coupon_code'] = $couponCode;
        $orderInfo['discount_amount'] = $discountAmount;

        return $orderInfo;
    }
}
