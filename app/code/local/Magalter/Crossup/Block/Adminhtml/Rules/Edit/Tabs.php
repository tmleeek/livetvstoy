<?php
 
class Magalter_Crossup_Block_Adminhtml_Rules_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('id');
        $this->setDestElementId('edit_form');
        //$this->setTitle($this->__('Checkout Upsells'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('general_section', array(
            'label'     => $this->__('Upsell Dashboard'),
            'title'     => $this->__('Upsell Dashboard'),
            'content'   => $this->getLayout()->createBlock('magalter_crossup/adminhtml_rules_edit_tab_dashboard')->toHtml(),
            'active'    => true
        )); 
        
         $this->addTab('conditions_section', array(
            'label'     => $this->__('Shopping Cart Conditions'),
            'title'     => $this->__('Shopping Cart Conditions'),
            'content'   => $this->getLayout()->createBlock('magalter_crossup/adminhtml_rules_edit_tab_conditions')->toHtml(),
        )); 
        
        $this->addTab('actions_section', array(
            'label'     => $this->__('Show Upsell Products'),
            'title'     => $this->__('Show Upsell Products'),
            'content'   => $this->getLayout()->createBlock('magalter_crossup/adminhtml_rules_edit_tab_actions')->toHtml(),
        )); 
         
         $this->addTab('customer_groups', array(
            'label'     => $this->__('Customer Groups Visibility'),
            'title'     => $this->__('Customer Groups Visibility'),
            'content'   => $this->getLayout()->createBlock('magalter_crossup/adminhtml_rules_edit_tab_groups')->toHtml(),
        ));    
        
        $this->addTab('design', array(
            'label'     => $this->__('Display Settings'),
            'title'     => $this->__('Display Settings'),
            'content'   => $this->getLayout()->createBlock('magalter_crossup/adminhtml_rules_edit_tab_design')->toHtml(),
        )); 
        
        $this->setActiveTab(preg_replace("/id_/i","",$this->getRequest()->getParam('tab')));

        return parent::_beforeToHtml();
    }

}
