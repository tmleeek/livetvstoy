<?php
/**
 * @category Warby
 * @package Warby_GiftCardAccount
 * @author Warby Parker (Igor Finchuk & Gershon Herczeg) <oss@warbyparker.com>
 * @copyright Massachusetts Institute of Technology License (MITL)
 * @license  http://opensource.org/licenses/MIT
 * Class Warby_GiftCardAccount_Adminhtml_GiftcardaccountController
 */

require_once 'Enterprise/GiftCardAccount/controllers/Adminhtml/GiftcardaccountController.php';


class Warby_GiftCardAccount_Adminhtml_GiftcardaccountController extends Enterprise_GiftCardAccount_Adminhtml_GiftcardaccountController
{

    /**
     * Defines if limit message of code pool is show
     *
     * @var bool
     */
    protected $_showLimitMessage = true;

    /**
     * Create new Gift Card Account
     */
    public function newBulkAction()
    {
        // Add usage statistics message
        $this->_addFreeNotice();
        // Add limit config option
        $this->_addLimitNotice();

        $model = $this->_initGca();

        $this->_title($this->__('New Bulk'));

        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $model->addData($data);
        }

        $this->loadLayout()
            ->_addBreadcrumb(Mage::helper('enterprise_giftcardaccount')->__('New Bulk'), Mage::helper('enterprise_giftcardaccount')->__('New Bulk'))
            ->_addContent($this->getLayout()->createBlock('warby_giftcardaccount/adminhtml_giftcardaccount_bulk')->setData('form_action_url', $this->getUrl('*/*/saveBulk')))
            ->renderLayout();
    }

    /**
     * Save action
     */
    public function saveBulkAction()
    {
        // check if data sent
        if ($data = $this->getRequest()->getPost()) {
            $data = $this->_filterPostData($data);
            // init model and set data

            for ($i=0; $i<$this->getRequest()->getParam('giftcardaccount_count'); $i++) {

                $model = Mage::getModel('enterprise_giftcardaccount/giftcardaccount');

                if (!empty($data)) {
                    $model->addData($data);
                }


                // try to save it
                try {

                    // check if not exceeds limit
                    $limit = Mage::getStoreConfig('giftcard/giftcardaccount_general/bulk_limit');
                    if (!empty($data['giftcardaccount_count']) && $data['giftcardaccount_count'] > $limit) {
                        throw new Exception(
                            Mage::helper('enterprise_giftcardaccount')->__(
                                'You can create <b>%d</b> bulk accounts maximum at once.',
                                $limit <= 0 ? 0 : $limit)
                        );
                    }

                    // save the data
                    $model->save();

                } catch (Exception $e) {
                    // display error message
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    // save data in session
                    Mage::getSingleton('adminhtml/session')->setFormData($data);
                    // redirect to edit form
                    $this->_redirect('*/*/newBulk');
                    return;
                }
            }
        }

        Mage::getSingleton('adminhtml/session')->addSuccess(sprintf($this->__('%d accounts were successfully created!'), $i));
        $this->_redirect('*/*/');
    }

    /**
     * Default action override to change notification about pool usage
     */
    public function indexAction()
    {
        $this->_addFreeNotice();

        return parent::indexAction();
    }

    /**
     * Add pool usage statistics to page
     */
    protected function _addFreeNotice()
    {
        if ($this->_showCodePoolStatusMessage) {
            $usage = Mage::getModel('enterprise_giftcardaccount/pool')->getPoolUsageInfo();

            $function = 'addNotice';
            if ($usage->getPercent() == 100) {
                $function = 'addError';
            }

            Mage::getSingleton('adminhtml/session')->$function(
                Mage::helper('enterprise_giftcardaccount')->__(
                    'Code Pool used: <b>%.2f%%</b> (free <b>%d</b> of <b>%d</b> total). Generate new code pool <a href="%s">here</a>.',
                    $usage->getPercent(),
                    $usage->getFree(),
                    $usage->getTotal(),
                    Mage::getSingleton('adminhtml/url')->getUrl('*/*/generate'))
            );

            $this->_showCodePoolStatusMessage = false;
        }
    }

    /**
     * Add limit notice about maximum bulk accounts available
     */
    protected function _addLimitNotice()
    {
        if ($this->_showLimitMessage) {
            $limit = Mage::getStoreConfig('giftcard/giftcardaccount_general/bulk_limit');

            $function = 'addNotice';
            if ($limit <= 0) {
                $function = 'addError';
            }

            Mage::getSingleton('adminhtml/session')->$function(
                Mage::helper('enterprise_giftcardaccount')->__(
                    'You can create <b>%d</b> bulk accounts maximum at once (you can modify the limitation setting <a href="%s">here</a>).',
                    $limit <= 0 ? 0 : $limit,
                    Mage::getSingleton('adminhtml/url')->getUrl('/system_config/edit/section/giftcard/'))
            );
        }
    }

}
