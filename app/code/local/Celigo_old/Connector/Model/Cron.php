<?php
class Celigo_Connector_Model_Cron extends Mage_Core_Model_Abstract
{
	const XML_PATH_CONNECTOR_CRON_ENABLED = 'connector/cronsettings/enabled';
	const XML_PATH_CONNECTOR_CRON_STARTDATE = 'connector/cronsettings/startdate';
	const CRON_JOB_CODE = 'push_orders_to_ns';
	
    /** @var Celigo_Connector_Helper_Data */
    protected $_helper;
	
    public function _construct()
    {
        parent::_construct();
 		$this->_helper = Mage::helper('connector');
    }

    /**
     * @return Customer Flow ID
     */
    private function getCronOrderStartDate($storeId = '', $websiteId = '')
    {
		return $this->_helper->getConfigValue(self::XML_PATH_CONNECTOR_CRON_STARTDATE, $storeId, $websiteId);
    }
	
    /**
     * Check if the cron job is enabled or not
     * @return  Boolean
     */
    public function isConnectorCronEnabled($storeId = '', $websiteId = '')
    {
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
	public function pushOrdersToNs($schedule)
	{
		if (!$this->isConnectorCronEnabled()) {
			return;
		}
		
		if ($this->checkExistingCronRunning()) {
			$schedule->delete();
			return;
		}
		
		/** If the action is required then make following happen */
		try {
			foreach (Mage::app()->getStores() as $store) {
			
				$storeId = $store->getStoreId();
				
				if (!$this->_helper->getIsConnectorModuleEnabled($storeId)) {
					continue;
				}
				
				$statuses = array();
				$statuses = $this->_helper->getOrderStatusArray($storeId);
				
				if (count($statuses) == 0) {
					continue;
				}
				
				$orderCollection = Mage::getModel("sales/order")->getCollection()
									->addFieldToFilter('pushed_to_ns', 0)
									->addFieldToFilter('store_id', $storeId)
									->addFieldToFilter('status', array('in'=>$statuses));
				
				$startdate = $this->getCronOrderStartDate();
				if (trim($startdate) != '') {
					$startdate = Mage::getModel('core/date')->date('Y-m-d', strtotime($startdate));
					$orderCollection->addFieldToFilter('created_at', array('from'=>$startdate));
				}
				
				if ($orderCollection->count() > 0) {
					foreach ($orderCollection as $order) {
						$result = Mage::getModel('connector/connector')->pushOrderToNS($order->getId());
					}			
				}
			}
		} catch (Exception $e) {
			$this->_helper->addErrorMessageToLog($e->getMessage(), Zend_Log::ERR, '', '');
		}
	}
	
    /**
     * @return Boolean
     */
    public function refreshCronJobs()
    {
		try {
			$collection = Mage::getSingleton('cron/schedule')->getCollection();
			$collection->addFieldToFilter('status', array('eq' => Mage_Cron_Model_Schedule::STATUS_RUNNING));
			$collection->addFieldToFilter('job_code', array('eq' => self::CRON_JOB_CODE));
			if (count($collection) > 0) {
				foreach ($collection as $cron) {
					$cron->delete();
				}
			}
			return true;
		} catch (Exception $e) {
			$this->_helper->addErrorMessageToLog($e->getMessage(), Zend_Log::ERR, '', '');
			return $e->getMessage();
		}
		return false;
    }
	
    /**
     * @return Boolean
     */
    private function checkExistingCronRunning()
    {
		try {
		
			$collection = Mage::getSingleton('cron/schedule')->getCollection();
			$collection->addFieldToFilter('job_code', array('eq' => self::CRON_JOB_CODE));
			$collection->addFieldToFilter('status', array('eq' => Mage_Cron_Model_Schedule::STATUS_RUNNING));
			$collection->setOrder('created_at', 'DESC');
			
			if (count($collection) > 1) {
				return true;
			}
			
		} catch (Exception $e) {
			$this->_helper->addErrorMessageToLog($e->getMessage(), Zend_Log::ERR, '', '');
		}
		
		return false;
    }
}