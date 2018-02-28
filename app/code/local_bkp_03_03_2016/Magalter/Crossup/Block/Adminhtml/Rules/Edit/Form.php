<?php
 
class Magalter_Crossup_Block_Adminhtml_Rules_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

    public function __construct()
    {   
        parent::__construct();
        $this->setId('id');
        $this->setTitle($this->__('Upsell Information'));
        
    }

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
            'id' => 'edit_form', 
            'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method' => 'post', 
            'enctype' => 'multipart/form-data')
       );
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
 
}
