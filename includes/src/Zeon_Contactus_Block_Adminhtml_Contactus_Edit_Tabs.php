<?php
class Zeon_Contactus_Block_Adminhtml_Contactus_Edit_Tabs
    extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('contactus_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('contactus')->__('Contactus information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_section',
            array(
                'label'     => Mage::helper('contactus')
                    ->__('Contactus Entry information'),
                'title'     => Mage::helper('contactus')
                    ->__('Contactus Entry information'),
                'content'   => $this->getLayout()
                    ->createBlock('contactus/adminhtml_contactus_edit_tab_form')
                ->toHtml(),
            )
        );

        return parent::_beforeToHtml();
    }
}