<?php
class Celigo_Connector_Adminhtml_ConnectorController extends Mage_Adminhtml_Controller_Action
{
	const VALID_NETSUITE_CREDENTIAL_MSG = 'Congratulations! Your NetSuite details are correct';
	const RESTLET_URL_UPDATED_MSG 		= 'RESTLet URL updated successfully';
	const NO_EXTENSION_CONFLICTS_MSG 	= 'No Extension conflicts found';
	const CRON_RESTARTED_MSG 			= 'Cron refreshed successfully';
	const CRON_RESTARTED_FAILED_MSG		= 'Unable to refresh the cron';

	public function indexAction() 
	{
		$storeId = ''; $websiteId = '';
		$params = Mage::app()->getRequest()->getParams();
		unset($params['key']);
		
		if (isset($params['store']) && $params['store'] != '') {
			$storeId = Mage::app()->getStore($params['store'])->getId();
		}
		
		if (isset($params['website']) && $params['website'] != '') {
			$websiteId = Mage::app()->getWebsite($params['website'])->getId();
			$websiteId = $params['website'];
		}
		
		// NS credential Validation code goes here
		$result = Mage::helper('connector')->isValidateNetsuiteCredentials($storeId, $websiteId);
		if ($result === true) {
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('connector')->__(self::VALID_NETSUITE_CREDENTIAL_MSG));		
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('connector')->__($result));
		}
		$configUrl = $this->getUrl('adminhtml/system_config/edit/section/connector', $params);
		Mage::app()->getFrontController()->getResponse()->setRedirect($configUrl);
	}
	
	public function resturlAction() 
	{
		
		$storeId = ''; $websiteId = '';
		$params = Mage::app()->getRequest()->getParams();
		unset($params['key']);
		
		if (isset($params['store']) && $params['store'] != '') {
			$storeId = Mage::app()->getStore($params['store'])->getId();
		}
		
		if (isset($params['website']) && $params['website'] != '') {
			$websiteId = Mage::app()->getWebsite($params['website'])->getId();
			$websiteId = $params['website'];
		}
		
		// Fetch Rest URL
		$result = Mage::helper('connector')->fetchRestUrl($storeId, $websiteId);
		if ($result === true) {
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('connector')->__(self::RESTLET_URL_UPDATED_MSG));
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('connector')->__($result));
		}
		$configUrl = $this->getUrl('adminhtml/system_config/edit/section/connector', $params);
		Mage::app()->getFrontController()->getResponse()->setRedirect($configUrl);
	}
	
	public function conflictsAction() 
	{
		$params = Mage::app()->getRequest()->getParams();
		unset($params['key']);
		
		// Fetch Rest URL
		
		$result = Mage::helper('connector/extensionconflict')->RefreshList();
		
		if ($result === false) {
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('connector')->__(self::NO_EXTENSION_CONFLICTS_MSG));
		}
		
		$configUrl = $this->getUrl('adminhtml/system_config/edit/section/connector', $params);
		Mage::app()->getFrontController()->getResponse()->setRedirect($configUrl);
	}
	
	public function cronrestartAction() 
	{
		$params = Mage::app()->getRequest()->getParams();
		unset($params['key']);
		
		$result = Mage::getModel('connector/cron')->refreshCronJobs();
		
		if ($result === true) {
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('connector')->__(self::CRON_RESTARTED_MSG));
		} elseif ($result === false) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('connector')->__(self::CRON_RESTARTED_FAILED_MSG));
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('connector')->__($result));
		}
		
		$configUrl = $this->getUrl('adminhtml/system_config/edit/section/connector', $params);
		Mage::app()->getFrontController()->getResponse()->setRedirect($configUrl);
	}
	
	public function pushorderAction() 
	{
		$params = Mage::app()->getRequest()->getParams();
		$result = "Please enter order number";
		
		if(isset($params['ordernumber'])) {
			$order = Mage::getModel('sales/order')->loadByIncrementId($params['ordernumber']);
			
			if($order->getId()) {
				$debug = true;
				$result = Mage::getModel('connector/connector')->pushOrderToNS($order->getId());
				if($result === true) {
					 $result = "Order successfully pushed to NetSuite";
				}
			} else {
				$result = "This Order does not exists";
			}
		}
		
		Mage::app()->getResponse()->setBody($result);
	}
	
	public function cronondemandAction() 
	{
		$params = Mage::app()->getRequest()->getParams();
		unset($params['key']);

		try {
			if (!Mage::getModel('connector/cron')->isConnectorCronEnabled()) {
				 Mage::throwException($this->__('Order Import batch flow disabled')); 
			}
			@ini_set("max_execution_time", 18000);
			Mage::getModel('connector/cron')->pushOrdersToNs();
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('connector')->__("Order Import batch flow successfully executed on demand "));
		} catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('connector')->__($e->getMessage()));
		}
			
		$configUrl = $this->getUrl('adminhtml/system_config/edit/section/connector', $params);
		Mage::app()->getFrontController()->getResponse()->setRedirect($configUrl);
	}
}