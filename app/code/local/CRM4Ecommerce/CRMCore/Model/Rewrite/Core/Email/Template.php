<?php

/**
 * 
 * @category   CRM4Ecommerce
 * @package    CRM4Ecommerce_CRMCore
 * @author     Philip Nguyen <philip@crm4ecommerce.com>
 * @link       http://crm4ecommerce.com
 */
class CRM4Ecommerce_CRMCore_Model_Rewrite_Core_Email_Template extends Mage_Core_Model_Email_Template {
    
    private $_mail_id = 0;

    public function __construct() {
        $this->_mail_id = time();
        Mage::dispatchEvent(
            'crm4ecommerce_mail_init',
            array(
                'mail_id' => $this->_mail_id
            )
        );
        parent::__construct();
    }

    public function sendTransactional($templateId, $sender, $email, $name, $vars = array(), $storeId = null) {
        Mage::dispatchEvent(
            'crm4ecommerce_mail_before_send',
            array(
                'mail_id' => $this->_mail_id,
                'mail' => $this->getMail(),
                'template_id' => $templateId,
                'sender' => $sender,
                'email_address' => $email,
                'email_name' => $name,
                'vars' => $vars
            )
        );
        $result = parent::sendTransactional($templateId, $sender, $email, $name, $vars, $storeId);
        Mage::dispatchEvent(
            'crm4ecommerce_mail_after_send',
            array(
                'mail_id' => $this->_mail_id,
                'mail' => $this->getMail(),
                'template_id' => $templateId,
                'sender' => $sender,
                'email_address' => $email,
                'email_name' => $name,
                'vars' => $vars
            )
        );
        return $result;
    }
}