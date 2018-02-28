<?php

class Celigo_Celigoconnector_Model_Cron extends Mage_Core_Model_Abstract {
    const XML_PATH_CONNECTOR_CRON_ENABLED = 'celigoconnector/cronsettings/enabled';
    const XML_PATH_CONNECTOR_CRON_STARTDATE = 'celigoconnector/cronsettings/startdate';
    const CRON_JOB_CODE = 'push_orders_to_ns';
    const CRON_BATCH_SIZE = 'celigoconnector/cronsettings/batchsize';
    const LOG_FILENAME = 'celigo-batch-import.log';
    const MAX_ERROR_COUNT = 100;
    /** @var Celigo_Celigoconnector_Helper_Data */
    protected $_helper;
    public function _construct() {
        parent::_construct();
        $this->_helper = Mage::helper('celigoconnector');
    }
    /**
     * @return Customer Flow ID
     */
    protected function getCronOrderStartDate($storeId = '', $websiteId = '') {
        
        return $this->_helper->getConfigValue(self::XML_PATH_CONNECTOR_CRON_STARTDATE, $storeId, $websiteId);
    }
    /**
     * Check if the cron job is enabled or not
     * @return  Boolean
     */
    public function isCeligoconnectorCronEnabled($storeId = '', $websiteId = '') {
        $returnValue = false;
        $isenabled = $this->_helper->getConfigValue(self::XML_PATH_CONNECTOR_CRON_ENABLED, $storeId, $websiteId);
        if (isset($isenabled) && $isenabled == 1) {
            $returnValue = true;
        }
        
        return $returnValue;
    }
    /**
     * Customer save after send the customer data to RESt URL
     * Module: admin --- Controller: customer --- Action: massSubscribe
     * @param Varien_Event_Observer $observer
     */
    public function pushOrdersToNs($schedule) {
        if (!$this->isCeligoconnectorCronEnabled()) {
            
            return;
        }
        if ($this->checkExistingCronRunning()) {
            $schedule->delete();
            
            return;
        }
        /** If the action is required then make following happen */
        $this->importOrdersToNs();
    }
    public function importOrdersToNs() {
        try {
            $orderArr = array();
            
            foreach (Mage::app()->getStores() as $store) {
                $storeId = $store->getStoreId();
                if (!$this->_helper->getIsCeligoconnectorModuleEnabled($storeId)) {
                    continue;
                }
                $statuses = array();
                $statuses = $this->_helper->getOrderStatusArray($storeId);
                if (count($statuses) == 0) {
                    continue;
                }
                $orderCollection = Mage::getModel("sales/order")->getCollection()->addFieldToFilter('pushed_to_ns', 0)->addFieldToFilter('store_id', $storeId)->addFieldToFilter('status', array(
                    'in' => $statuses
                ));
                $startdate = $this->getCronOrderStartDate();
                if (trim($startdate) != '') {
                    $startdate = Mage::getModel('core/date')->date('Y-m-d H:i:s', strtotime($startdate));
                    $orderCollection->addFieldToFilter('created_at', array(
                        'from' => $startdate
                    ));
                }
                $orderCollection->setOrder('created_at', 'desc');
                if ($orderCollection->count() > 0) {
                    
                    foreach ($orderCollection as $order) {
                        array_push($orderArr, $order->getId());
                    }
                }
            }
            Mage::helper('celigoconnector/celigologger')->info('infomsg="' . 'Batch Process Starting with orders : [' . implode($orderArr, ',') . ']"', self::LOG_FILENAME);
            Mage::getModel('core/config_data')->load(self::CRON_BATCH_SIZE, 'path')->setValue(count($orderArr))->setPath(self::CRON_BATCH_SIZE)->save();
            Mage::getModel('core/config_data')->load(Celigo_Celigoconnector_Model_Celigoconnector::INVALID_NETSUITE_CREDENTIAL, 'path')->setValue(0)->setPath(Celigo_Celigoconnector_Model_Celigoconnector::INVALID_NETSUITE_CREDENTIAL)->save();

            $failureCount = 0;
            foreach ($orderArr as $orderid) {
                $result = Mage::getModel('celigoconnector/celigoconnector')->pushOrderToNS($orderid, Celigo_Celigoconnector_Helper_Async::TYPE_SYNC, false, true);
                if($result !== true) {
                    $failureCount++;
                }
                $isValidCredential = Mage::getModel('core/config_data')->load(Celigo_Celigoconnector_Model_Celigoconnector::INVALID_NETSUITE_CREDENTIAL, 'path');
                if ($isValidCredential->getId()) {
                    if($isValidCredential->getValue() == 1) {
                        Mage::helper('celigoconnector/celigologger')->error('infomsg="Batch Order Import. Invalid NetSuite Credential."', self::LOG_FILENAME);
                        break;
                    }
                }
            }
            if($failureCount > self::MAX_ERROR_COUNT) {
                $this->sendAlertEmail($failureCount);
            }
            Mage::helper('celigoconnector/celigologger')->info('infomsg="Batch Process Completed."', self::LOG_FILENAME);
        }
        catch(Exception $e) {
            Mage::helper('celigoconnector/celigologger')->error('errormsg="' . $e->getMessage() . '"', self::LOG_FILENAME);
        }
    }
    /**
     * @return Boolean
     */
    public function refreshCronJobs() {
        try {
            $collection = Mage::getSingleton('cron/schedule')->getCollection();
            $collection->addFieldToFilter('status', array(
                'eq' => Mage_Cron_Model_Schedule::STATUS_RUNNING
            ));
            $collection->addFieldToFilter('job_code', array(
                'eq' => self::CRON_JOB_CODE
            ));
            $curDateTime = date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time()));
            if (count($collection) > 0) {
                
                foreach ($collection as $cron) {
                    $cron->setStatus(Mage_Cron_Model_Schedule::STATUS_SUCCESS)->setFinishedAt($curDateTime)->setMessages("Forcefully finished by Administrator")->save();
                }
            }
            
            return true;
        }
        catch(Exception $e) {
            Mage::helper('celigoconnector/celigologger')->error('errormsg="' . $e->getMessage() . '"', self::LOG_FILENAME);
            
            return $e->getMessage();
        }
        
        return false;
    }
    /**
     * @return Boolean
     */
    private function checkExistingCronRunning() {
        try {
            $this->updateStaledJobs();
            $collection = Mage::getSingleton('cron/schedule')->getCollection();
            $collection->addFieldToFilter('job_code', array(
                'eq' => self::CRON_JOB_CODE
            ));
            $collection->addFieldToFilter('status', array(
                'eq' => Mage_Cron_Model_Schedule::STATUS_RUNNING
            ));
            $collection->setOrder('created_at', 'DESC');
            if (count($collection) > 1) {
                
                return true;
            }
        }
        catch(Exception $e) {
            Mage::helper('celigoconnector/celigologger')->error('errormsg="' . $e->getMessage() . '"', self::LOG_FILENAME);
        }
        
        return false;
    }
    private function updateStaledJobs() {
        try {
            $collection = Mage::getSingleton('cron/schedule')->getCollection()->addFieldToFilter('job_code', array(
                'eq' => self::CRON_JOB_CODE
            ))->addFieldToFilter('status', array(
                'eq' => Mage_Cron_Model_Schedule::STATUS_RUNNING
            ))->setOrder('created_at', 'DESC');
            if (count($collection) > 1) {
                $ordercnt = Mage::getStoreConfig(self::CRON_BATCH_SIZE);
                $curDateTime = date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time()));
                
                foreach ($collection as $cronjob) {
                    $startDateTime = Mage::getModel('core/date')->date('Y-m-d H:i:s', strtotime($cronjob->getExecutedAt()));
                    if ((strtotime($curDateTime) - strtotime($startDateTime)) / 60 > $ordercnt) {
                        $cronjob->setStatus(Mage_Cron_Model_Schedule::STATUS_SUCCESS)->setFinishedAt($curDateTime)->save();
                    }
                }
            }
        }
        catch(Exception $e) {
            Mage::helper('celigoconnector/celigologger')->error('errormsg="' . $e->getMessage() . '"', self::LOG_FILENAME);
        }
    }
    /**
     * Method to send email to Product Magento Team
     * @param string $subject
     * @param string $message
     */
    private function sendEmail($subject = '', $message = '') {
        try {
            $techEmail = $this->_helper->getConfigValue(Celigo_Celigoconnector_Model_Celigoconnector::XML_PATH_TECHNICAL_CONTACT_EMAIL);
            $fromName = "Celigo Magento Connector";
            $fromEmail = "magento@celigo.com";
            $recipientEmails = array();
            $techEmails = explode(",", $techEmail);
            if (!empty($techEmail) && count($techEmails) > 0) {
                foreach($techEmails as $_techEmail) {
                    if(!empty($_techEmail) && filter_var($_techEmail, FILTER_VALIDATE_EMAIL)) {
                        $recipientEmails["Technical Contact"] = trim($_techEmail);
                    }
                }
            }
            $mail = new Zend_Mail();
            $mail->setFrom($fromEmail, $fromName);
            if(count($recipientEmails) > 0) {
                $mail->addTo($recipientEmails);
                $mail->addBcc("product.magento@celigo.com");
            } else {
                $mail->addTo("product.magento@celigo.com", "Celigo Magento Product");
            }
            $mail->setSubject($subject);
            $mail->setBodyHtml($message);
            $mail->send();
        }
        catch (Exception $e) {
            Mage::helper('celigoconnector/celigologger')->error('errormsg="' . $e->getMessage() . '"', self::LOG_FILENAME);
        }
    }
    /**
     * Function to send failure count alert email
     * @param integer $failureCount
     */
    private function sendAlertEmail($failureCount) {
        try {
            if(!Mage::getModel('celigoconnector/celigoconnector')->canSendErrorEmail()) {
                return;
            }
            $moduleVersion = (string)Mage::helper('celigoconnector')->getExtensionVersion();
            $storeUrl = Mage::getBaseUrl();
            $subject = "Celigo Magento connector order import failure (" . $failureCount . ")";
            $message = "Dear Customer,<br /><br />";
            $message .= "Celigo's Magento Connector for NetSuite detected failures to import a batch of orders from Magento into NetSuite.<br/><br/>";
            $message .= "Count of failed orders: " . $failureCount . "<br/>";
            $message .= "Store URL: <a href='" . $storeUrl . "'>" . $storeUrl . "</a><br/><br/>";
            $message .= "Celigo Magento extension version: " . $moduleVersion . "<br/><br/>";
            $message .= "Please see detailed error logs in Celigo dashboard in NetSuite (Setup > Integration > Celigo Integrator)<br/><br/>";
            $message .= "You may reach out to Celigo support for further assistance.";
            $message .= "<br /><br />Thanks,<br />Celigo, Inc";
            $this->sendEmail($subject, $message);
       }
       catch (Exception $e) {
           Mage::helper('celigoconnector/celigologger')->error('errormsg="' . $e->getMessage() . '"', self::LOG_FILENAME);
       }
   }
}
