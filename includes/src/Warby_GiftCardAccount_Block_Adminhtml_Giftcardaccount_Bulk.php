<?php
/**
 * @category Warby
 * @package Warby_GiftCardAccount
 * @author Warby Parker (Igor Finchuk & Gershon Herczeg) <oss@warbyparker.com>
 * @copyright Massachusetts Institute of Technology License (MITL)
 * @license  http://opensource.org/licenses/MIT
 * Class Warby_GiftCardAccount_Block_Adminhtml_Giftcardaccount_Bulk
 */
class Warby_GiftCardAccount_Block_Adminhtml_Giftcardaccount_Bulk extends Mage_Adminhtml_Block_Widget_Form_Container
{

    /**
     * Form mode
     *
     * @var string
     */
    protected $_mode = 'bulk';

    /**
     * Override construct to change button to "create"
     */
    public function __construct()
    {
        $this->_objectId = 'id';
        $this->_controller = 'adminhtml_giftcardaccount';
        $this->_blockGroup = 'warby_giftcardaccount';

        parent::__construct();

        $this->_updateButton('save', 'label', Mage::helper('enterprise_giftcardaccount')->__('Create'));
    }

    public function getHeaderText()
    {
        return Mage::helper('enterprise_giftcardaccount')->__('New Bulk Accounts');
    }

}
