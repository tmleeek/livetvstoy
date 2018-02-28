<?php
 
class Magalter_Crossup_Block_Adminhtml_Rules_Edit_Tab_Actions extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {      
       $form = new Varien_Data_Form();      
       $form->setHtmlIdPrefix('rule_');
        
       $upsell = Mage::registry('magalter_crossup_registry'); 
        
         $form->addFieldset('actions_fieldset', array('legend' => $this->__('Upsell Products')))
                ->setRenderer($this->_getRulesFieldset())
                ->addField('actions', 'text', array(
                    'name' => 'actions',
                    'label' => $this->__('Discount Rules'),
                    'title' => $this->__('Discount Rules'),
                    'required' => false,
                ))->setRule($upsell)
                ->setRenderer($this->getLayout()->getBlockSingleton('rule/actions')); 
        
        if (Mage::getSingleton('adminhtml/session')->getElementData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getElementData());
            Mage::getSingleton('adminhtml/session')->setElementData(null);
        } else {
            $form->setValues($upsell->getData());
        }
         
        $this->setForm($form);
        return parent::_prepareForm();
      
    }
    
    protected function _getChildUrl()
    {
        return $this->getUrl('*/adminhtml_rules/actions/form/rule_actions_fieldset');
    }

    protected function _getRulesFieldset()
    {        
        return $this->getLayout()->getBlockSingleton('adminhtml/widget_form_renderer_fieldset')->setNewChildUrl($this->_getChildUrl())->setTemplate('promo/fieldset.phtml');
    }
}
