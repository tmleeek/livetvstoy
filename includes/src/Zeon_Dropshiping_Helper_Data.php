<?php
/**
 * @category    Zeon
 * @package     Zeon_Dropshiping
 * @copyright   Copyright (c) 2012 Zeon Solutions, Inc. All Rights Reserved.(http://www.zeonsolutions.com)
 */

class Zeon_Dropshiping_Helper_Data extends Mage_Core_Helper_Abstract
{
    CONST XML_PATH_FOR_DROPSHIPPING_MESSAGE   = 'dropshiping_setting/cart_setting/dropshiping_message';

    /**
     * Drop Ship Message on cart item
     * @return string
     */
    function getDropShippingMessage()
    {
        if (Mage::getStoreConfig(self::XML_PATH_FOR_DROPSHIPPING_MESSAGE)) {
            return Mage::getStoreConfig(self::XML_PATH_FOR_DROPSHIPPING_MESSAGE);
        }
        return false;
    }

    /**
     * Convert a comma separated file(CSV) into an associated array.
     *
     * @param string $file Path to the CSV file
     * @return array
     */
    public function convertCSVToArray($file)
    {
        $data = array_map('str_getcsv', file($file, FILE_SKIP_EMPTY_LINES));
        $keys = array_shift($data);
        foreach ($data as $i => $row) {
            $data[$i] = array_combine($keys, $row);
        }
        return $data;
    }

    /**
     * Parse a CSV array into an array we will use to convert into ORDENT format.
     * Also, checks for duplicate keys(PO#) in the returned array,
     * if a duplicate key exists, we add the line item data to the already existing returned array
     *
     * @param array Associated array of data to parse
     * @return array
     */
    public function parseCSVArray($data)
    {
        $orders = array();

        foreach ($data as $row) {

            $key = $row['PO#'];

            if (!array_key_exists($key, $orders)) {
                $orders[$key]['PO#'] = $key;
                $orders[$key]['Store'] = $row['Store'];
                $orders[$key]['Ship Via'] = $row['Ship Via'];
                $orders[$key]['Customer'] = $row['Customer'];
                $orders[$key]['Shipping Address 1'] = $row['Shipping Addres 1'];
                $orders[$key]['Shipping Address 2'] = $row['Shipping Addres 2'];
                $orders[$key]['Shipping City'] = $row['Shipping City'];
                $orders[$key]['Shipping State/Province'] = $row['Shipping State/Province'];
                $orders[$key]['Shipping Zip'] = $row['Shipping Zip'];
                $orders[$key]['Shipping Country'] = $row['Shipping Country'];
                $orders[$key]['Shipping Phone'] = $row['Shipping Phone'];
            }

            $orders[$key]['Items'][] = array(
                'Vendor Code' => $row['Vendor Code'],
                'Quantity' => $row['Quantity'],
                'Price' => $row['Price']
            );
        }

        return $orders;
    }

    public function generatePO($poArray = Null)
    {
        $ordent = '';
        if ($poArray && is_array($poArray)) {
            if (isset($poArray['PO#']) && $poArray['PO#']) {
                $btCustomerNumber = '';
                $method = '';
                //Customer Number
                if (isset($poArray['Store']) && $store = $poArray['Store']) {
                    $btCustomerNumber = $this->getCustomerNumber($store);
                }
                // SHIPPING METHODS
                if (isset($poArray['Ship Via']) && $method = $poArray['Ship Via']) {
                    $method = $this->getShippingMethod($method);
                }
                $ln = "000000";
                $ordent  = str_pad($ln++, 6, "0", STR_PAD_LEFT) . ";;ORDENT\r\n";
                $ordent .= str_pad($ln++, 6, "0", STR_PAD_LEFT) . ";~EDIID="
                    . $btCustomerNumber . "/EDIORD=Y/BO=N/"
                    . "SHPVIA=$method/CHGVIA=$method/EDIADR=Y/PONUM="
                    . $poArray['PO#'];

                $shippingCountry = '';
                if (isset($poArray['Shipping Country'])) {
                    $shippingCountry = Zend_Locale_Data_Translation::$regionTranslation[$poArray['Shipping Country']];
                }
                // If this is an international order, we have to add some extra information
                // to the header
                if ($shippingCountry != 'US') {
                    $ordent .= "/EDIINT=Y/SHPCPL=Y/EDICOU=" . $shippingCountry;
                }
                $ordent .= "\r\n";
                $customerName = '';
                if (isset($poArray['Customer'])) {
                    $customerName = $poArray['Customer'];
                }
                $ordent .= str_pad($ln++, 6, "0", STR_PAD_LEFT) . ";EDINAM="
                    . $customerName . "\r\n";

                $street1 = '';
                if (isset($poArray['Shipping Address 1'])) {
                    $street1 = $poArray['Shipping Address 1'];
                }
                $ordent .= str_pad($ln++, 6, "0", STR_PAD_LEFT) . ";EDIAD1="
                    . $street1 . "\r\n";

                $street2 = '';
                if (isset($poArray['Shipping Address 2'])) {
                    $street2 = $poArray['Shipping Address 2'];
                }
                $ordent .= str_pad($ln++, 6, "0", STR_PAD_LEFT) . ";EDIAD2="
                    .$street2 . "\r\n";

                $city = '';
                if (isset($poArray['Shipping City'])) {
                    $city = $poArray['Shipping City'];
                }
                $ordent .= str_pad($ln++, 6, "0", STR_PAD_LEFT) . ";EDICIT="
                    . $city . "\r\n";

                $ordent .= str_pad($ln++, 6, "0", STR_PAD_LEFT) . ";EDISTA=";

                // If this is an international order, the state must be set to
                // "ZZ"

                if ($shippingCountry != 'US') {
                    $ordent .= "ZZ";
                }
                $ordent .= "\r\n";

                $zipCode = '';
                if (isset($poArray['Shipping Zip'])) {
                    $zipCode = $poArray['Shipping Zip'];
                }
                $ordent .= str_pad($ln++, 6, "0", STR_PAD_LEFT) . ";EDIZIP="
                    . $zipCode . "\r\n";

                $ordent .= str_pad($ln++, 6, "0", STR_PAD_LEFT) . ";EDIFON=";
                // Add telephone number if this is an international order
                if ($shippingCountry != 'US') {
                    $ordent .= $poArray['Shipping Phone'];
                }
                $ordent .= "\r\n";
            }
            //

            if (isset($poArray['Items']) && is_array($poArray['Items'])) {
                $ordent .= $this->getItemsBuildForPO($poArray['Items']);
            }

            $ordent .= "999999;" . str_pad($ln - 1, 6, "0", STR_PAD_LEFT) . "\r\n";

            //$this->message("\tDone\n");

            // Strip out any stray "!"
            $ordent = str_replace("!", "", $ordent);
        }
        return $ordent;

    }

    public function getCustomerNumber($store = NUll)
    {
        $btCustomerNumber = '';
        if ($store) {
            if (preg_match("/hitshop/i", $store)) $btCustomerNumber = "70158629";
            if (preg_match("/allaboard/i", $store)) $btCustomerNumber = "70158626";
            if (preg_match("/ttb/i", $store) or preg_match("/alcon/i", $store)) $btCustomerNumber = "70158631";
            if (preg_match("/pbsshop/i", $store)) $btCustomerNumber = "70158630";

        }
        return $btCustomerNumber;
    }

    public function getShippingMethod($method = NUll)
    {
        if ($method) {
            $method = preg_replace("/Select.*-\ /", "", $method);
            // First code changed from UPX to save money
            // In the future, this code will only be used for DVDs, books will get @46
            if (preg_match("/Free/", $method)) $method = "@33";   // Free Shipping
            if (preg_match("/Ground/", $method)) $method = "@33";   // UPS Ground
            if (preg_match("/Economy/", $method)) $method = "@33";  // UPS Ground
            if (preg_match("/Expedited/", $method)) $method = "ULB"; // UPS Second Day
            if (preg_match("/2nd Day/", $method)) $method = "ULB"; // UPS Second Day
            if (preg_match("/International/", $method)) $method = "DHP"; // DHL International
        }
        return $method;
    }

    public function getItemsBuildForPO($itemCollection = array(), $qualifier = Null)
    {
        $itemOrdent = '';
        if (!empty($itemCollection)) {
            $ln = "000000";
            foreach ($itemCollection as $item) {
                if (isset($item['Vendor Code']) && $item['Vendor Code']) {
                    if (strlen($item['Vendor Code']) == '13') {
                        $qualifier = "IB";
                    } else {
                        $qualifier = "UPC";
                    }
                    $itemOrdent .= str_pad($ln++, 6, "0", STR_PAD_LEFT) . ";$qualifier="
                        . $item['Vendor Code'];
                    if (isset($item['Quantity']) && $item['Quantity']) {
                        $itemOrdent .= "/QTY=". round($item['Quantity']);
                    }
                    if (isset($item['Quantity']) && $item['Quantity']) {
                        $itemOrdent .= "/EDIPRC=". round($item['Price'], 2) . "/BO=N\r\n";
                    }
                }
            }
        }
        return $itemOrdent;
    }
}