<?php
 
class Magalter_Crossup_Block_Adminhtml_Rules_Edit_Tab_Design extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
       $form = new Varien_Data_Form();
        
        $form->setHtmlIdPrefix('magalter_crossup_'); 
        
        $magalterUpsell = Mage::registry('magalter_crossup_registry');
        
        $displayFieldset = $form->addFieldset('product_fieldset', array('legend' => $this->__('Display Settings'), 'class' => 'magalter_crossup_display_settings'));
         
        $displayFieldset->addField('additional_settings0', 'text', array(
            'name' => 'additional_settings[0]',
            'label' => $this->__('The number of upsells to show'),
            'title' => $this->__('The number of upsells to show'),
        ));          
      
         $displayFieldset->addField('additional_settings1', 'select', array(
            'label' => $this->__('Sort by'),
            'title' => $this->__('Sort by'),
            'name' => 'additional_settings[1]',
            'options' => Mage::getSingleton('magalter_crossup/source_sortorder')->toFlatArray()
        ));
         
        $displayFieldset->addField('additional_settings2', 'select', array(
            'label' => $this->__('Sort direction'),
            'title' => $this->__('Sort direction'),
            'name' => 'additional_settings[2]',
            'options' => Mage::getSingleton('magalter_crossup/source_sortdir')->toFlatArray()
        ));
       
        if (Mage::getSingleton('adminhtml/session')->getElementData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getElementData());
            Mage::getSingleton('adminhtml/session')->setElementData(null);
        } elseif ($magalterUpsell->getId()) {
            $form->setValues($magalterUpsell->getData());
        }
        
        $form->setValues($magalterUpsell->getData());         
        $this->setForm($form);
        return parent::_prepareForm();
      
    }
}
