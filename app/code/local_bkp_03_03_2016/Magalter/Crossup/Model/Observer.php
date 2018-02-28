<?php

class Magalter_Crossup_Model_Observer
{ 
    /**
     * Prepare product final price depending on custom options
     * @param obj $observer 
     */
    public function prepareFinalPrice($observer)
    {
        $product = $observer->getProduct();

        $customOption = $product->getCustomOption('magalter_crossup_rule');
        if ($customOption && !$customOption->isDeleted()) {              
            $upsellRule = Mage::getSingleton('magalter_crossup/crossup')->load($customOption->getValue());
            if ($upsellRule && $upsellRule->getId()) {
                $this->calculate($upsellRule, $product);
            }
        } else {

            if ($product->getCheckoutSet()) {
                return $this;
            }

            $validData = (array) Mage::getSingleton('customer/session')->getMagalterUpsellRules();

            foreach ($validData as $rule => $products) {
                if (in_array($product->getId(), $products)) {
                    $rule = Mage::getModel('magalter_crossup/crossup')->load($rule);
                    $this->calculate($rule, $product);
                    break;
                }
            }
        }
    }
 
    public function toHtmlBefore($observer)
    {
        if ($observer->getBlock() instanceof Mage_Catalog_Block_Product_Price && !Mage::registry('current_product')) {

            $product = $observer->getBlock()->getProduct();

            if ($product->getCheckoutSet()) {
                return $this;
            }

            $validData = (array) Mage::getSingleton('customer/session')->getMagalterUpsellRules();

            foreach ($validData as $rule => $products) {
                if (in_array($product->getId(), $products)) {
                    $rule = Mage::getModel('magalter_crossup/crossup')->load($rule);
                    $this->calculate($rule, $product);
                }
            }
        }
    }

    public function toHtmlAfter($observer)
    {
        if ($observer->getBlock() instanceof Mage_Checkout_Block_Cart_Crosssell ||
                $observer->getBlock() instanceof Enterprise_TargetRule_Block_Checkout_Cart_Crosssell) {
            if (Mage::getStoreConfig('magalter_crossup/configuration/cross')) {
                $observer->getTransport()->setHtml(null);
            }
        }
    }

    /**
     * Process prepare for cart event only if we in our module
     * @param type $observer 
     */
    public function prepareProductForCart($observer)
    {
        $product = $observer->getProduct();

        $validData = (array) Mage::getSingleton('customer/session')->getMagalterUpsellRules();
        foreach ($validData as $rule => $products) {
            if (in_array($product->getId(), $products)) {
                $product->addCustomOption('magalter_crossup_rule', $rule);
                break;
            }
        }
    }

    /**
     * Calculate product price (percent / fixed)
     * @param obj $rule
     * @param obj $product 
     */
    public function calculate($rule, $product)
    {
        $discountType = $rule->getDiscountType();

        if ($discountType == Magalter_Crossup_Model_Crossup::DISCOUNT_TYPE_PERCENT) {
            $product->setFinalPrice($product->getFinalPrice() - ($product->getFinalPrice() * $rule->getDiscount()) / 100);
        } else {
            $product->setFinalPrice($product->getFinalPrice() - $rule->getDiscount());
        }
    }

}