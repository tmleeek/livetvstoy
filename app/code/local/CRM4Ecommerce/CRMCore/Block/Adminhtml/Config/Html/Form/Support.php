<?php

/**
 * 
 * @category   CRM4Ecommerce
 * @package    CRM4Ecommerce_CRMCore
 * @author     Philip Nguyen <philip@crm4ecommerce.com>
 * @link       http://crm4ecommerce.com
 */
class CRM4Ecommerce_CRMCore_Block_Adminhtml_Config_Html_Form_Support extends Mage_Adminhtml_Block_Template {

    public function __construct() {
        parent::__construct();
        $this->setTemplate('crm4ecommerce_crmcore/config/form/support.phtml');
    }

}