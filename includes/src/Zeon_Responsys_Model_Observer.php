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
 * @category    Zeon
 * @package     Zeon_Responsys
 * @copyright
 * @license
 */
class Zeon_Responsys_Model_Observer
{
    /**
     *
     * update newsletter subscription at responsys
     * @param object $observer
     */
    public function updateMemberList(Varien_Event_Observer $observer)
    {
        try {
            $event = $observer->getEvent();
            $object = $observer->getObject();
            Mage::getModel('responsys/api')->updateResponsysData($object, $event->getType());
        } catch (Exception $e) {
            Mage::log('updateMemberList Exception : '.$e->getMessage(), null, 'responsys.log');
        }
        return $this;
    }

    /**
     *
     * update registered user at responsys
     * @param object $observer
     */
    public function customerRegisterResponsys(Varien_Event_Observer $observer)
    {
        try {
            $customer = $observer->getCustomer();
            Mage::getModel('responsys/api')->updateResponsysData($customer, 'create_customer');
        } catch (Exception $e) {
            Mage::log('customerRegisterResponsys Exception : '.$e->getMessage(), null, 'responsys.log');
        }
        return $this;
    }

    /**
     *
     * update check out registered user at responsys
     * @param object $observer
     */
    public function checkoutCustomerRegistered(Varien_Event_Observer $observer)
    {
        try {
            $quote = $observer->getQuote();
            $checkoutMethod = $quote->getData();
            $checkoutMethod = $checkoutMethod['checkout_method'];
            if ($checkoutMethod == Mage_Checkout_Model_Type_Onepage::METHOD_REGISTER) {
                if (Mage::getSingleton('customer/session')->isLoggedIn()) {
                    $customer = Mage::getSingleton('customer/session')->getCustomer();
                    Mage::getModel('responsys/api')->updateResponsysData($customer, 'checkout_register');
                }
            }
        } catch (Exception $e) {
            Mage::log('checkoutCustomerRegistered Exception : '.$e->getMessage(), null, 'responsys.log');
        }
        return $this;
    }

}