<?php
class Zeon_Contactus_Block_Adminhtml_Contactus_Edit_Tab_Form
    extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'contactus_form',
            array(
                'legend' => Mage::helper('contactus')
                    ->__('Contactus Information')
            )
        );

        $fieldset->addField(
            'name',
            'label',
            array(
                'label'     => Mage::helper('contactus')->__('Name :'),
                'class'     => '',
                'required'  => false,
                'name'      => 'title',
            )
        );
        $fieldset->addField(
            'email',
            'label',
            array(
                 'label'     => Mage::helper('contactus')->__('Email :'),
                 'class'     => '',
                 'required'  => false,
                 'name'      => 'title',
             )
        );
        $fieldset->addField(
            'phone',
            'label',
            array(
                 'label'     => Mage::helper('contactus')->__('Phone :'),
                 'class'     => '',
                 'required'  => false,
                 'name'      => 'title',
            )
        );
        $fieldset->addField(
            'message',
            'label', array(
                'label'     => Mage::helper('contactus')->__('Message :'),
                'class'     => '',
                'required'  => false,
                'name'      => 'title',
            )
        );
        $fieldset->addField(
            'contacted_on',
            'label',
            array(
                 'label'     => Mage::helper('contactus')->__('Contacted on :'),
                 'class'     => '',
                 'required'  => false,
                 'name'      => 'title',
            )
        );

        if ( Mage::getSingleton('contactus/session')->getContactusData() ) {
            $form->setValues(
                Mage::getSingleton('contactus/session')->getContactusData()
            );
            Mage::getSingleton('contactus/session')
                ->setContactusData(null);
        } elseif ( Mage::registry('contactus_data') ) {
            $form->setValues(Mage::registry('contactus_data')->getData());
        }
        return parent::_prepareForm();
    }
}