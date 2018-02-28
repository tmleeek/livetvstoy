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
 * @package     Mage_Newsletter
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */


/**
 * Customers newsletter subscription controller
 *
 * @category   Mage
 * @package    Mage_Newsletter
 * @author      Magento Core Team <core@magentocommerce.com>
 */
require_once("Mage/Newsletter/controllers/ManageController.php");
class Zeon_Newsletter_ManageController extends Mage_Newsletter_ManageController
{
    public function saveAction()
    {
        if (!$this->_validateFormKey()) {
            return $this->_redirect('customer/account/');
        }
        try {
            $customer = Mage::getSingleton('customer/session')->getCustomer()
            ->setStoreId(Mage::app()->getStore()->getId())
            ->setIsSubscribed(
                (boolean)$this->getRequest()->getParam('is_subscribed', false)
            )
            ->save();
            if ((boolean)$this->getRequest()->getParam(
                'is_subscribed',
                false
            )
            ) {
                Mage::log('send to responsys subscribe manage', null, 'responsys.log');
                Mage::getSingleton('customer/session')
                    ->addSuccess($this->__('The subscription has been saved.'));
            } else {
                Mage::log('send to responsys unsubscribe manage', null, 'responsys.log');
                Mage::getSingleton('customer/session')
                    ->addSuccess(
                        $this->__('The subscription has been removed.')
                    );
            }
            /*********** responsys set data ************/
            $subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($customer->getEmail());
            Mage::dispatchEvent('responsys_merge_list', array('object' => $subscriber, 'type' => 'newsletter_subscribe'));
        }
        catch (Exception $e) {
            Mage::getSingleton('customer/session')
                ->addError(
                    $this->__(
                        'An error occurred while saving your subscription.'
                    )
                );
        }
        $this->_redirect('*/*/');
    }
}
