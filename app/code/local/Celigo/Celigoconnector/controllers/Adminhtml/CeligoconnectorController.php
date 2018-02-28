<?php

class Celigo_Celigoconnector_Adminhtml_CeligoconnectorController extends Mage_Adminhtml_Controller_Action {
    const VALID_NETSUITE_CREDENTIAL_MSG = 'Congratulations! Your NetSuite details are correct';
    const RESTLET_URL_UPDATED_MSG = 'RESTLet URL updated successfully';
    const NO_EXTENSION_CONFLICTS_MSG = 'No Extension conflicts found';
    const CRON_RESTARTED_MSG = 'Cron refreshed successfully';
    const CRON_RESTARTED_FAILED_MSG = 'Unable to refresh the cron';
    const LOG_FILENAME = 'celigo-connector-adminhtml.log';

    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('system/config/celigoconnector');
    }
    public function indexAction() {
        Mage::helper('celigoconnector/celigologger')->forcelog('infomsg="indexAction started" class="Celigo_Celigoconnector_Adminhtml_CeligoconnectorController"', self::LOG_FILENAME);
        $params = Mage::app()->getRequest()->getParams();
        unset($params['key']);
        // NS credential Validation code goes here
        $result = Mage::helper('celigoconnector')->isValidateNetsuiteCredentials();
        if ($result === true) {
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('celigoconnector')->__(self::VALID_NETSUITE_CREDENTIAL_MSG));
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('celigoconnector')->__($result));
        }
        $configUrl = $this->getUrl('adminhtml/system_config/edit/section/celigoconnector', $params);
        Mage::app()->getFrontController()->getResponse()->setRedirect($configUrl);
    }
    public function resturlAction() {
        Mage::helper('celigoconnector/celigologger')->forcelog('infomsg="resturlAction started" class="Celigo_Celigoconnector_Adminhtml_CeligoconnectorController"', self::LOG_FILENAME);
        $params = Mage::app()->getRequest()->getParams();
        unset($params['key']);
        // Fetch Rest URL
        $result = Mage::helper('celigoconnector')->fetchRestUrl();
        if ($result === true) {
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('celigoconnector')->__(self::RESTLET_URL_UPDATED_MSG));
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('celigoconnector')->__($result));
        }
        $configUrl = $this->getUrl('adminhtml/system_config/edit/section/celigoconnector', $params);
        Mage::app()->getFrontController()->getResponse()->setRedirect($configUrl);
    }
    public function conflictsAction() {
        Mage::helper('celigoconnector/celigologger')->forcelog('infomsg="conflictsAction started" class="Celigo_Celigoconnector_Adminhtml_CeligoconnectorController"', self::LOG_FILENAME);
        $params = Mage::app()->getRequest()->getParams();
        unset($params['key']);
        // Fetch Rest URL
        $result = Mage::helper('celigoconnector/extensionconflict')->RefreshList();
        if ($result === false) {
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('celigoconnector')->__(self::NO_EXTENSION_CONFLICTS_MSG));
        }
        $configUrl = $this->getUrl('adminhtml/system_config/edit/section/celigoconnector', $params);
        Mage::app()->getFrontController()->getResponse()->setRedirect($configUrl);
    }
    public function cronrestartAction() {
        Mage::helper('celigoconnector/celigologger')->forcelog('infomsg="cronrestartAction started" class="Celigo_Celigoconnector_Adminhtml_CeligoconnectorController"', self::LOG_FILENAME);
        $params = Mage::app()->getRequest()->getParams();
        unset($params['key']);
        $result = Mage::getModel('celigoconnector/cron')->refreshCronJobs();
        if ($result === true) {
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('celigoconnector')->__(self::CRON_RESTARTED_MSG));
        } elseif ($result === false) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('celigoconnector')->__(self::CRON_RESTARTED_FAILED_MSG));
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('celigoconnector')->__($result));
        }
        $configUrl = $this->getUrl('adminhtml/system_config/edit/section/celigoconnector', $params);
        Mage::app()->getFrontController()->getResponse()->setRedirect($configUrl);
    }
    public function pushorderAction() {
        Mage::helper('celigoconnector/celigologger')->forcelog('infomsg="pushorderAction started" class="Celigo_Celigoconnector_Adminhtml_CeligoconnectorController"', self::LOG_FILENAME);
        $params = Mage::app()->getRequest()->getParams();
        $result = "Please enter order number";
        if (isset($params['ordernumber'])) {
            Mage::helper('celigoconnector/celigologger')->forcelog('infomsg="pushorderAction called for order='.$params['ordernumber'].'" class="Celigo_Celigoconnector_Adminhtml_CeligoconnectorController"', self::LOG_FILENAME);
            $order = Mage::getModel('sales/order')->loadByIncrementId($params['ordernumber']);
            if ($order->getId()) {
                $debug = true;
                $result = Mage::getModel('celigoconnector/celigoconnector')->pushOrderToNS(array(
                    $order->getId()
                ) , Celigo_Celigoconnector_Helper_Async::TYPE_SYNC, $debug);
                if ($result === true) {
                    $result = "Order successfully pushed to NetSuite";
                }
            } else {
                $result = "This Order does not exists";
            }
        }
        Mage::app()->getResponse()->setBody($result);
    }
    public function cronondemandAction() {
        Mage::helper('celigoconnector/celigologger')->forcelog('infomsg="cronondemandAction started" class="Celigo_Celigoconnector_Adminhtml_CeligoconnectorController"', self::LOG_FILENAME);
        $params = Mage::app()->getRequest()->getParams();
        unset($params['key']);
        try {
            if (!Mage::getModel('celigoconnector/cron')->isCeligoconnectorCronEnabled()) {
                Mage::throwException($this->__('Order Import batch flow disabled'));
            }
            @ini_set("max_execution_time", 18000);
            Mage::getModel('celigoconnector/cron')->importOrdersToNs();
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('celigoconnector')->__("Order Import batch flow successfully executed on demand "));
        }
        catch(Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('celigoconnector')->__($e->getMessage()));
        }
        $configUrl = $this->getUrl('adminhtml/system_config/edit/section/celigoconnector', $params);
        Mage::app()->getFrontController()->getResponse()->setRedirect($configUrl);
    }
}
