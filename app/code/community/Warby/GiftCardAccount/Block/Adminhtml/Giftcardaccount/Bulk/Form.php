<?php
/**
 * @category Warby
 * @package Warby_GiftCardAccount
 * @author Warby Parker (Igor Finchuk & Gershon Herczeg) <oss@warbyparker.com>
 * @copyright Massachusetts Institute of Technology License (MITL)
 * @license  http://opensource.org/licenses/MIT
 * Class Warby_GiftCardAccount_Block_Adminhtml_Giftcardaccount_Bulk_Form
 */
class Warby_GiftCardAccount_Block_Adminhtml_Giftcardaccount_Bulk_Form extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     * Builder for bulk codes creation
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array('id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post'));

        $fieldset = $form->addFieldset('base_fieldset',
            array('legend'=>Mage::helper('enterprise_giftcardaccount')->__('Information'))
        );

        $fieldset->addField('status', 'select', array(
            'name'      => 'status',
            'label'     => Mage::helper('enterprise_giftcardaccount')->__('Status'),
            'title'     => Mage::helper('enterprise_giftcardaccount')->__('Status'),
            'required'  => true,
            'values'    => array(
                Enterprise_GiftCardAccount_Model_Giftcardaccount::STATUS_DISABLED => Mage::helper('enterprise_giftcardaccount')->__('Inactive'),
                Enterprise_GiftCardAccount_Model_Giftcardaccount::STATUS_ENABLED => Mage::helper('enterprise_giftcardaccount')->__('Active'),
            ),
        ));

        $fieldset->addField('website_id', 'select', array(
            'name'      => 'website_id',
            'label'     => Mage::helper('enterprise_giftcardaccount')->__('Website'),
            'title'     => Mage::helper('enterprise_giftcardaccount')->__('Website'),
            'required'  => true,
            'values'    => Mage::getSingleton('adminhtml/system_store')->getWebsiteValuesForForm(true),
        ));

        $fieldset->addType('price', 'Enterprise_GiftCardAccount_Block_Adminhtml_Giftcardaccount_Form_Price');

        $fieldset->addField('balance', 'price', array(
            'label'     => Mage::helper('enterprise_giftcardaccount')->__('Balance'),
            'title'     => Mage::helper('enterprise_giftcardaccount')->__('Balance'),
            'name'      => 'balance',
            'class'     => 'validate-number',
            'required'  => true,
            'note'      => '<div id="balance_currency"></div>'
        ));

        $fieldset->addField('giftcardaccount_count', 'text', array(
            'name' => 'giftcardaccount_count',
            'required'  => true,
            'label' => $this->__('Accounts Count')
        ));

        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
