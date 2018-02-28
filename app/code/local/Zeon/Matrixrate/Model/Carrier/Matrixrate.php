<?php
class Zeon_Matrixrate_Model_Carrier_Matrixrate
    extends Webshopapps_Matrixrate_Model_Carrier_Matrixrate
{
    /**
     * Zeon Catalog setup
     *
     * @category    Zeon
     * @package     Zeon_Catalog
     * @author      Zeon Team
     */
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }
        // exclude Virtual products price from Package value if pre-configured
        if (!$this->getConfigFlag('include_virtual_price') && $request->getAllItems()) {
            foreach ($request->getAllItems() as $item) {
                if ($item->getParentItem()) {
                    continue;
                }
                if ($item->getHasChildren() && $item->isShipSeparately()) {
                    foreach ($item->getChildren() as $child) {
                        if ($child->getProduct()->isVirtual() || $item->getProductType() == 'downloadable') {
                            $request->setPackageValue($request->getPackageValue() - $child->getBaseRowTotal());
                        }
                    }
                } elseif ($item->getProduct()->isVirtual() || $item->getProductType() == 'downloadable') {
                    $request->setPackageValue($request->getPackageValue() - $item->getBaseRowTotal());
                }
            }
        }

        // Free shipping by qty
        $freeQty = 0;
        $onlyDropShipItemPresent = false;
        $bulkyPrice = 0;
        $expeditedShippingEnabled = 0;
        if ($request->getAllItems()) {
            //Code Modified By Zeon
            $dropShipItemCount = 0;
            $itemCount = 0;
            //End
            foreach ($request->getAllItems() as $item) {
                //end
                if ($item->getProduct()->isVirtual()
                    || $item->getParentItem()
                    || ($item->getProduct()->getSku() =='donate')) {
                    continue;
                }
                $itemCount++;

                //checking for the store if enabled for expedited shipping
                $expeditedEnabledStore = Mage::getStoreConfig('expedited_setting/cart_settings/active');

                //Code Modified By Zeon to retrive the drop ship item attribute of product
                if ($item->getProductType() == 'configurable') {
                    $productId = $item->getOptionByCode('simple_product')->getProduct()->getId();
                    $simpleProduct = Mage::getModel('catalog/product')->load($productId);

                    if ($simpleProduct->getData('expedited_shipping')
                        && $simpleProduct->getData('expedited_shipping') == 1
                        && $expeditedEnabledStore) {
                        $expeditedShippingEnabled = 1;
                    }
                    //logic added if the expedited_shipping is set and having surcharge value set
                    $surchargeShipAttrValue = $simpleProduct->getData('expedited_shipping_surcharge');
                    //Mage::log('$surchargeShipAttrValue - ' .$surchargeShipAttrValue, NULL, 'shipping.log', true);
                    if ($expeditedShippingEnabled && $surchargeShipAttrValue) {
                        $dropShipItemCount++;
                        $bulkyPrice = $bulkyPrice + $surchargeShipAttrValue;
                        Mage::log('$surchargeShipAttrValue - ' .$surchargeShipAttrValue, Null, 'shipping.log', true);
                    } else if ($simpleProduct->getData('drop_ship_item')) {
                        $dropShipItemCount++;
                        //Code Modified By Zeon
                        //check for bulk extra shipping cost
                        $bulky =   $simpleProduct->getData('bulky');
                        if ($bulky) {
                            $bulkyPrice = $bulkyPrice + $bulky;
                        }
                    }
                } else {
                    $productId = $item->getProduct()->getId();
                    $productResource = $item->getProduct()->getResource();
                    $dropShipAttributValue =
                        $productResource->getAttributeRawValue($productId, 'drop_ship_item', Mage::app()->getStore());
                    Mage::log('$dropShipAttributValue - ' .$dropShipAttributValue, Null, 'shipping.log', true);

                    //getting product data for expedited shipping
                    if ($expeditedShippingEnabled == 0) {
                        $productData = Mage::getModel('catalog/product')->load($productId);
                        if ($productData->getData('expedited_shipping')
                            && $productData->getData('expedited_shipping') == 1
                            && $expeditedEnabledStore) {
                            $expeditedShippingEnabled = 1;
                        }
                    }
                    //logic added if the expedited_shipping is set and having surcharge value set
                    $surchargeShipAttrValue = $productResource
                        ->getAttributeRawValue($productId, 'expedited_shipping_surcharge', Mage::app()->getStore());
                    //Mage::log('$surchargeShipAttrValue - ' .$surchargeShipAttrValue, NULL, 'shipping.log', true);
                    if ($expeditedShippingEnabled && $expeditedShipAttrValue) {
                        $dropShipItemCount++;
                        $bulkyPrice = $bulkyPrice + $surchargeShipAttrValue;
                        Mage::log('$surchargeShipAttrValue - ' .$surchargeShipAttrValue, Null, 'shipping.log', true);
                    } else if ($dropShipAttributValue) {
                        //check for presence of drop ship attribute
                        $dropShipItemCount++;
                        //Code Modified By Zeon
                        //check for bulk extra shipping cost
                        $bulky = $productResource->getAttributeRawValue($productId, 'bulky', Mage::app()->getStore());
                        Mage::log('$bulky - ' .$bulky, Null, 'shipping.log', true);
                        if ($bulky) {
                            $bulkyPrice = $bulkyPrice + $bulky;
                        }
                    }
                }
                if ($item->getHasChildren() && $item->isShipSeparately()) {
                    foreach ($item->getChildren() as $child) {
                        if ($child->getFreeShipping() && !$child->getProduct()->isVirtual()) {
                            $freeQty += $item->getQty() *
                                ($child->getQty() - (is_numeric($child->getFreeShipping())
                                        ? $child->getFreeShipping() : 0));
                        }
                    }
                } elseif ($item->getFreeShipping()) {
                    $freeQty +=
                        ($item->getQty() - (is_numeric($item->getFreeShipping()) ? $item->getFreeShipping() : 0));
                }
            }

            //Code Modified By Zeon
            //check for only drop ship item
            if ($dropShipItemCount && ($dropShipItemCount == $itemCount)) {
                $onlyDropShipItemPresent = true;
            }
            //end
        }

        if (!$request->getMRConditionName()) {
            $request->setMRConditionName(
                $this->getConfigData('condition_name') ?
                $this->getConfigData('condition_name') : $this->_default_condition_name
            );
        }

        // Package weight and qty free shipping
        $oldWeight = $request->getPackageWeight();
        $oldQty = $request->getPackageQty();

        if ($this->getConfigData('allow_free_shipping_promotions') &&
            !$this->getConfigData('include_free_ship_items')) {
            $request->setPackageWeight($request->getFreeMethodWeight());
            $request->setPackageQty($oldQty - $freeQty);
        }

        $result = Mage::getModel('shipping/rate_result');
        $ratearray = $this->getRate($request);

        $freeShipping=false;

        if (is_numeric($this->getConfigData('free_shipping_threshold')) &&
            $this->getConfigData('free_shipping_threshold')>0 &&
            $request->getPackageValue()>$this->getConfigData('free_shipping_threshold')) {
            $freeShipping=true;
        }
        if ($this->getConfigData('allow_free_shipping_promotions') && ($request->getFreeShipping() === true ||
                $request->getPackageQty() == $this->getFreeBoxes())) {
            $freeShipping=true;
        }
        if ($freeShipping) {
            $method = Mage::getModel('shipping/rate_result_method');
            $method->setCarrier('matrixrate');
            $method->setCarrierTitle($this->getConfigData('title'));
            $method->setMethod('matrixrate_free');
            $method->setPrice('0.00');
            $method->setMethodTitle($this->getConfigData('free_method_text'));
            $result->append($method);

            if ($this->getConfigData('show_only_free')) {
                return $result;
            }
        }

        $expeditedStoreMethods = '';
        $expeditedStoreMethodsArray = array();
        if ($expeditedEnabledStore) {
            $expeditedStoreMethods = Mage::getStoreConfig(
                'expedited_setting/cart_settings/shipping_method'
            );
            $expeditedStoreMethodsArray = explode(',', $expeditedStoreMethods);
        }
        foreach ($ratearray as $rate) {
            //checking for expedited shipping to display or not
            if (!$expeditedShippingEnabled &&
                $expeditedEnabledStore &&
                in_array($rate['delivery_type'], $expeditedStoreMethodsArray)) {
                continue;
            }

            if (!empty($rate) && $rate['price'] >= 0) {
                $method = Mage::getModel('shipping/rate_result_method');
                //Code Modified By Zeon
                //Only to show 'Standard Shipping' shipping method
                if ($onlyDropShipItemPresent && ($rate['delivery_type'] !='Standard Shipping')) {
                    continue;
                }
                //End
                $method->setCarrier('matrixrate');
                $method->setCarrierTitle($this->getConfigData('title'));
                $method->setMethod('matrixrate_'.$rate['pk']);
                $method->setMethodTitle(Mage::helper('matrixrate')->__($rate['delivery_type']));
                $shippingPrice = $this->getFinalPriceWithHandlingFee($rate['price']);
                $method->setCost($rate['cost']);
                $method->setDeliveryType($rate['delivery_type']);

                $method->setPrice($shippingPrice+$bulkyPrice);

                $result->append($method);
            }
        }
        return $result;
    }

}
