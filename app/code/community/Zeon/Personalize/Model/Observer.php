<?php
/**
 * Created by PhpStorm.
 * User: piyush.sahu
 * Date: 5/22/14
 * Time: 12:02 PM
 */
class Zeon_Personalize_Model_Observer
{
    /**
     * Here is the logic where I update the personalize table by order_id and quote_id
     */
    public function savepersonalize($observer)
    {
        $incrementId = Mage::getSingleton('checkout/session')->getLastRealOrderId();//It has the order ID
        $order       = Mage::getSingleton('sales/order')->load($incrementId, 'increment_id');//Load the order details

        //
        $session = Mage::getSingleton('customer/session');
        if ($session->isLoggedIn()) {
            $customerData = Mage::getSingleton('customer/session')->getCustomer();
            $customer     = $customerData->getId();
            $flag         = 'false';
        } else {
            $customer = $session->getEncryptedSessionId();
            $flag     = 'true';
        }

        $personalizedProdCount = 0;
        $isPersonalizedProduct    = false;
        foreach ($order->getAllItems() as $item) {
            if ($item->getProduct()->getPersonalize()) {
                $isPersonalizedProduct = true;
                $personalizedProdCount++;
            }
        }

        if ($isPersonalizedProduct && $personalizedProdCount > 0) {
            $personalModel  = Mage::getSingleton('personalize/personalize');
            $persononalizeRows = $personalModel->getPersonalizedDetailsByCustomerId($customer, $personalizedProdCount);
            foreach ($persononalizeRows as $rows) {
                $personalModel->setPersonalizeId($rows['personalize_id']);
                $personalModel->setOrderId($incrementId);
                $personalModel->setQuoteId($order['quote_id']);
                $personalModel->setIsGuest($flag);
                $personalModel->save();
            }
        }
    }

    /**
     * If user is logged in then check personalize table for customer id as session id
     * and update it to the users user id
     */
    public function updateUserIdCustomer()
    {
        $session   = Mage::getSingleton('customer/session');
        $sessionId = $session->getEncryptedSessionId();
        if ($session->isLoggedIn()) {
            $customerData = Mage::getSingleton('customer/session')->getCustomer();
            $customer     = $customerData->getId();
            $flag         = 'false';
        } else {
            $customer  = $sessionId;
            $flag      = 'true';
        }

        $personalizeModel = Mage::getSingleton('personalize/personalize');
        $result = $personalizeModel->getPersonalizeByCustId($sessionId);
        if ($result) {
            foreach ($result as $rows) {
                if ($rows['customer_id'] == $sessionId) {
                    $personalizeModel->setPersonalizeId($rows['personalize_id']);
                    $personalizeModel->setSessionId($sessionId);
                    $personalizeModel->setData('customer_id', $customer);
                    $personalizeModel->setIsGuest($flag);
                    $personalizeModel->save();
                }
            }
        }
    }

    /**
     * Function used to unset the poptropica details from the session.
     *
     * This function is calls on Customer -> Logout action.
     */
    public function clearPoptropicaDetails()
    {
        // Unset the poptropica details.
        Mage::getSingleton('customer/session')->setPoptropicaDetails(null);
    }

    /**
     * Method used to change the handler to use separate template file for personalized product
     *
     * Event: controller_action_layout_load_before
     *
     * @param Varien_Event_Observer $observer
     */
    public function setPersonalizedProductHandle(Varien_Event_Observer $observer)
    {
        // Get the current product from registry.
        $product = Mage::registry('current_product');

        // Return if it is not product page.
        if (!($product instanceof Mage_Catalog_Model_Product)) {
            return;
        }

        // Get the personalize value.
        $isPersonalize = $product->getPersonalize();

        // Return if it is not a personalized product.
        if (!$isPersonalize) {
            return;
        }

        /* @var $update Mage_Core_Model_Layout_Update */
        $update  = $observer->getEvent()->getLayout()->getUpdate();
        $handles = $update->getHandles(); // Store all handles in a variable
        $update->resetHandles(); // Remove all handles

        /**
         * Rearrange layout handles to ensure PRODUCT_<product_id>
         * handle is added last
         */
        foreach ($handles as $handle) {
            $update->addHandle($handle);
            if ($handle == 'PRODUCT_TYPE_' . $product->getTypeId()) {
                $update->addHandle('catalog_product_view_personalized');
            }
        }
    }
}