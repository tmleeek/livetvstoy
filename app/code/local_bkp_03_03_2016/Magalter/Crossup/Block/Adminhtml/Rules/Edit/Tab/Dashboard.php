<?php

class Magalter_Crossup_Block_Adminhtml_Rules_Edit_Tab_Dashboard extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $fieldset = $form->addFieldset('base_fieldset', array('legend' => $this->__('Rule settings')));
        $form->setHtmlIdPrefix('magalter_crossup_'); 
   
        $magalterUpsell = Mage::registry('magalter_crossup_registry');
         
        if($magalterUpsell->getStores() !== NULL) {
            $magalterUpsell->setStoreIds(explode(',', $magalterUpsell->getStores()));
        }
        
        if(!$magalterUpsell->getId()) {  
            $magalterUpsell->setData('min_qty', 1);
            $magalterUpsell->setData('additional_settings0', 3);
        }
        
        $outputFormat = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);

        if ($magalterUpsell->getAvailableFrom()) {
            $magalterUpsell->setAvailableFrom(
                    Mage::app()->getLocale()->date($magalterUpsell->getAvailableFrom(), Varien_Date::DATETIME_INTERNAL_FORMAT)
            );
        }
        if ($magalterUpsell->getAvailableTo()) {
            $magalterUpsell->setAvailableTo(
                    Mage::app()->getLocale()->date($magalterUpsell->getAvailableTo(), Varien_Date::DATETIME_INTERNAL_FORMAT)
            );
        }
        
          $fieldset->addField('hidden_upsell_ids', 'hidden', array(
                'name' => 'hidden_upsell_ids',
                'id' => 'hidden_upsell_ids'
                
         ));          
         $fieldset->addField('hidden_upsell_anchor_ids', 'hidden', array(
                'name' => 'hidden_upsell_anchor_ids',
                'id' => 'hidden_upsell_anchor_ids'                
         ));
         
        $fieldset->addField('label', 'text', array(
            'label' => $this->__('Upsell Label'),
            'title' => $this->__('Upsell Label'),
            'name' => 'label'         
        ));        

        $fieldset->addField('name', 'text', array(
            'name' => 'magalter_name',
            'label' => $this->__('Upsell Name'),
            'title' => $this->__('Upsell Name'),
        ));        
        
        $fieldset->addField('status', 'select', array(
            'label' => $this->__('Status'),
            'title' => $this->__('Status'),
            'name' => 'magalter_status',
            'options' => array(
                '1' => $this->__('Enabled'),
                '0' => $this->__('Disabled'),
            ),
        ));
        
        $fieldset->addField('priority', 'text', array(
            'name' => 'priority',
            'label' => $this->__('Sort Order'),
            'title' => $this->__('Sort Order')          
        )); 
       
        $fieldset->addField('description', 'textarea', array(
            'name' => 'description',
            'label' => $this->__('Description'),
            'title' => $this->__('Description')          
        ));        
        
        $fieldset->addField('available_from', 'date', array(
            'name' => 'magalter_available_from',
            'label' => $this->__('Available From'),
            'title' => $this->__('Available From'),
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format' => $outputFormat,
            'time' => true
        ));
       
        $fieldset->addField('available_to', 'date', array(
            'name' => 'magalter_available_to',
            'label' => $this->__('Available To'),
            'title' => $this->__('Available To'),
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format' => $outputFormat,
            'time' => true
        )); 
        
         /*
        $fieldset->addField('discount', 'text', array(
            'name' => 'magalter_discount',
            'label' => $this->__('Apply Discount To Upsells'),
            'title' => $this->__('Apply Discount To Upsells')         
        ));
         
        $fieldset->addField('discount_type', 'select', array(
            'label' => $this->__('Discount Type'),
            'title' => $this->__('Discount Type'),
            'name' => 'magalter_discount_type',
            'options' => array(
                '1' => $this->__('Fixed'),
                '2' => $this->__('Percent'),
            ),
        ));*/

        if (Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_ids', 'hidden', array(
                'name' => 'store_ids'               
            ));
        } else {
            $fieldset->addField('store_ids', 'multiselect', array(
                'name' => 'store_ids[]',
                'label' => $this->__('Store View'),
                'title' => $this->__('Store View'),
                'required' => true,
                'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ));
        }  
 
       if( Mage::getSingleton('adminhtml/session')->getElementData() ) {          
            $form->setValues(Mage::getSingleton('adminhtml/session')->getElementData());
            Mage::getSingleton('adminhtml/session')->setElementData(null);
        } else {            
            $form->setValues($magalterUpsell->getData());          
        }
 
        $this->setForm($form);
        return parent::_prepareForm();
    }

}
