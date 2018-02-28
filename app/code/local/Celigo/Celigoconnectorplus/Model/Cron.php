<?php

class Celigo_Celigoconnectorplus_Model_Cron extends Celigo_Celigoconnector_Model_Cron {
    const CRON_CANCEL_JOB_CODE = 'push_cancelled_orders_to_ns';
    const CRON_CANCEL_BATCH_SIZE = 'celigoconnectorplus/cronsettings/batchsize';
    const LOG_FILENAME = 'celigo-batch-import.log';
    public function pushCancelledOrdersToNs($schedule) {
        if (!$this->isCeligoconnectorCronEnabled()) {
            
            return;
        }
        if ($this->checkExistingCronRunning()) {
            $schedule->delete();
            
            return;
        }
        /** If the action is required then make following happen */
	$this->importCancelledOrdersToNs();
    }
    public function importCancelledOrdersToNs() {
        try {
            $orderArr = array();
            
            foreach (Mage::app()->getStores() as $store) {
                $storeId = $store->getStoreId();
                if (!$this->_helper->getIsCeligoconnectorModuleEnabled($storeId)) {
                    continue;
                }
                $orderCollection = Mage::getModel("sales/order")->getCollection()->addFieldToFilter('main_table.pushed_to_ns', 1)->addFieldToFilter('main_table.cancelled_in_netsuite', 0)->addFieldToFilter('main_table.store_id', $storeId)->distinct(true)->join(array(
                    'order_item' => 'sales/order_item'
                ) , 'order_item.order_id = main_table.entity_id AND order_item.qty_canceled > 0', array());
                $startdate = $this->getCronOrderStartDate();
                if (trim($startdate) != '') {
                    $startdate = Mage::getModel('core/date')->date('Y-m-d H:i:s', strtotime($startdate));
                    $orderCollection->addFieldToFilter('main_table.created_at', array(
                        'from' => $startdate
                    ));
                }
                $orderCollection->setOrder('main_table.created_at', 'desc');
                if ($orderCollection->count() > 0) {
                    
                    foreach ($orderCollection as $order) {
                        array_push($orderArr, $order->getId());
                    }
                }
            }
            Mage::helper('celigoconnector/celigologger')->info('infomsg="' . 'Batch Process Starting with cancelled orders : [' . implode($orderArr, ',') . ']"', self::LOG_FILENAME);
            Mage::getModel('core/config_data')->load(self::CRON_CANCEL_BATCH_SIZE, 'path')->setValue(count($orderArr))->setPath(self::CRON_CANCEL_BATCH_SIZE)->save();
            Mage::getModel('core/config_data')->load(Celigo_Celigoconnector_Model_Celigoconnector::INVALID_NETSUITE_CREDENTIAL, 'path')->setValue(0)->setPath(Celigo_Celigoconnector_Model_Celigoconnector::INVALID_NETSUITE_CREDENTIAL)->save();

            foreach ($orderArr as $orderid) {
                Mage::getModel('celigoconnectorplus/sales_order_cancel')->pushCancelledOrderToNS($orderid, Celigo_Celigoconnectorplus_Helper_Async::TYPE_SYNC, true);
                $isValidCredential = Mage::getModel('core/config_data')->load(Celigo_Celigoconnector_Model_Celigoconnector::INVALID_NETSUITE_CREDENTIAL, 'path');
                if ($isValidCredential->getId()) {
                    if($isValidCredential->getValue() == 1) {
                        Mage::helper('celigoconnector/celigologger')->error('infomsg="Batch Order Cancel Import. Invalid NetSuite Credential."', self::LOG_FILENAME);
                        break;
                    }
                }
            }
            Mage::helper('celigoconnector/celigologger')->info('infomsg="Batch Process Completed."', self::LOG_FILENAME);
        }
        catch(Exception $e) {
            Mage::helper('celigoconnector/celigologger')->error('errormsg="' . $e->getMessage() . '"', self::LOG_FILENAME);
        }
    }
    private function checkExistingCronRunning() {
        try {
            $this->updateStaledJobs();
            $collection = Mage::getSingleton('cron/schedule')->getCollection();
            $collection->addFieldToFilter('job_code', array(
                'eq' => self::CRON_CANCEL_JOB_CODE
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
                'eq' => self::CRON_CANCEL_JOB_CODE
            ))->addFieldToFilter('status', array(
                'eq' => Mage_Cron_Model_Schedule::STATUS_RUNNING
            ))->setOrder('created_at', 'DESC');
            if (count($collection) > 1) {
                $ordercnt = Mage::getStoreConfig(self::CRON_CANCEL_BATCH_SIZE);
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
}
