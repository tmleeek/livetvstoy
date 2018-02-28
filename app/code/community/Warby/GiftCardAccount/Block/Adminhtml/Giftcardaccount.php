<?php
/**
 * @category Warby
 * @package Warby_GiftCardAccount
 * @author Warby Parker (Igor Finchuk & Gershon Herczeg) <oss@warbyparker.com>
 * @copyright Massachusetts Institute of Technology License (MITL)
 * @license  http://opensource.org/licenses/MIT
 * Class Warby_GiftCardAccount_Block_Adminhtml_Giftcardaccount
 */
class Warby_GiftCardAccount_Block_Adminhtml_Giftcardaccount extends Enterprise_GiftCardAccount_Block_Adminhtml_Giftcardaccount
{

    /**
     * Add bulk button to giftcard account grid view
     */
    public function __construct()
    {
        parent::__construct();

        $this->_addButton('add_bulk', array(
            'label'     => $this->__('Add Bulk'),
            'onclick'   => 'setLocation(\'' .$this->getUrl('*/*/newBulk') .'\')',
            'class'     => 'add',
        ), 0, 1);
    }

}