<?php
/**
 * Controller Class to push the canceled orders to Netsuite
 */

class Celigo_Celigoconnectorplus_Adminhtml_CeligoconnectorplusController extends Mage_Adminhtml_Controller_Action {
    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('system/config/celigoconnector');
    }
    public function indexAction() {
        $this->_redirect('adminhtml/sales_order');
    }
    public function ordercancelimportondemandAction() {
        $params = Mage::app()->getRequest()->getParams();
        unset($params['key']);
        try {
            if (!Mage::getModel('celigoconnectorplus/cron')->isCeligoconnectorCronEnabled()) {
                Mage::throwException($this->__('Order Cancel Import batch flow disabled'));
            }
            @ini_set("max_execution_time", 18000);
            Mage::getModel('celigoconnectorplus/cron')->importCancelledOrdersToNs();
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('celigoconnector')->__("Order Cancel Import batch flow successfully executed on demand "));
        }
        catch(Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('celigoconnector')->__($e->getMessage()));
        }
        $configUrl = $this->getUrl('adminhtml/system_config/edit/section/celigoconnector', $params);
        Mage::app()->getFrontController()->getResponse()->setRedirect($configUrl);
    }
}
