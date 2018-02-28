<?php

class Celigo_Celigoconnector_Model_Cron extends Mage_Core_Model_Abstract {
    const XML_PATH_CONNECTOR_CRON_ENABLED = 'celigoconnector/cronsettings/enabled';
    const XML_PATH_CONNECTOR_CRON_STARTDATE = 'celigoconnector/cronsettings/startdate';
    const CRON_JOB_CODE = 'push_orders_to_ns';
    const CRON_BATCH_SIZE = 'celigoconnector/cronsettings/batchsize';
    const LOG_FILENAME = 'celigo-batch-import.log';
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
                if ($orderCollection->count() > 0) {
                    
                    foreach ($orderCollection as $order) {
                        array_push($orderArr, $order->getId());
                    }
                }
            }
            Mage::helper('celigoconnector/celigologger')->info('infomsg="' . 'Batch Process Starting with orders : [' . implode($orderArr, ',') . ']"', self::LOG_FILENAME);
            Mage::getModel('core/config_data')->load(self::CRON_BATCH_SIZE, 'path')->setValue(count($orderArr))->setPath(self::CRON_BATCH_SIZE)->save();
            
            foreach ($orderArr as $orderid) {
                Mage::getModel('celigoconnector/celigoconnector')->pushOrderToNS($orderid, Celigo_Celigoconnector_Helper_Async::TYPE_SYNC, false, true);
            }
            Mage::helper('celigoconnector/celigologger')->info('infomsg="Batch Process Completed."', self::LOG_FILENAME);
        }
        catch(Exception $e) {
            Mage::helper('celigoconnector/celigologger')->error('errormsg="' . $e->getMeesage() . '"', self::LOG_FILENAME);
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
            Mage::helper('celigoconnector/celigologger')->error('errormsg="' . $e->getMeesage() . '"', self::LOG_FILENAME);
            
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
            Mage::helper('celigoconnector/celigologger')->error('errormsg="' . $e->getMeesage() . '"', self::LOG_FILENAME);
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
            Mage::helper('celigoconnector/celigologger')->error('errormsg="' . $e->getMeesage() . '"', self::LOG_FILENAME);
        }
    }
}
