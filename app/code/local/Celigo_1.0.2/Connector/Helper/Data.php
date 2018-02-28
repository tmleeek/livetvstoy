<?php
/*
 *	Helper class for CONNECTOR Extension
 */
class Celigo_Connector_Helper_Data extends Mage_Core_Helper_Abstract
{
	const XML_PATH_MODULE_VERSION 	= 'connector/magentoconnector/extversion';
	const XML_PATH_MODULE_DISABLED 	= 'connector/magentoconnector/active';
	
	const XML_PATH_NETSUITE_EMAIL 	= 'connector/nsdetails/nsemail';
	const XML_PATH_NETSUITE_PASSWORD = 'connector/nsdetails/nspassword';
	const XML_PATH_NETSUITE_ROLE 	= 'connector/nsdetails/nsrole';
	const XML_PATH_NETSUITE_ACCOUNT = 'connector/nsdetails/nsaccountid';
	const XML_PATH_NETSUITE_ENVIRONMENT = 'connector/nsdetails/nsenvironment';
	
	const XML_PATH_RESTLET_URL 		= 'connector/othersettings/restleturl';
	const XML_PATH_CUSTOMER_FLOW_ID = 'connector/othersettings/customerflowid';
	const XML_PATH_ORDER_FLOW_ID 	= 'connector/othersettings/orderflowid';
	const XML_PATH_ORDER_STATUS 	= 'connector/othersettings/orderstatus';
	const XML_PATH_PUSHED_ORDER_STATUS = 'connector/othersettings/imported_order_status';

	const XML_PATH_CONNECTOR_LOG_ENABLED = 'connector/logsettings/enabled';
	const XML_PATH_CONNECTOR_LOG_FILENAME = 'connector/logsettings/filename';
	
	protected $_logfilename 	= 'connector-error.log';
	protected $_forcelogging 	= true; // If True then errors are logged even the Magento System logging is disabled
	
	const NETSUITE_SANDBOX_RESTLET_URL 		= "https://system.sandbox.netsuite.com/";
	const NETSUITE_PRODUCTION_RESTLET_URL 	= "https://system.netsuite.com/";
	const NETSUITE_BETA_RESTLET_URL 		= "https://system.beta.netsuite.com/";
	
	const NETSUITE_ACCOUNT_ROLE_WRONG_MSG 	= "NetSuite Account ID or NetSuite Role ID is wrong";
	const NETSUITE_EMAIL_PASSWORD_WROG_MSG = "Email address or Password is wrong";
	const UNABLE_TO_VALIDATE_NETSUITE_CREDS_MSG = "Unable to validate the credentials at this time. Please try after some time.";
	const MISSING_DETAILS_MSG = 'Please enter NetSuite Details (Email / Password / Account ID / Role ID / Evinronment)';    
	/**
     * @return  Extension version string
     */
	public function getExtensionVersion()
	{
		return (string) Mage::getConfig()->getNode()->modules->Celigo_Connector->version;
	}
	
    /**
     * @return  RestLet URL
     */
    public function getOrderStatusArray($storeId = '', $websiteId = '')
    {
		$statusArray = array();
		$status = $this->getOrderStatus($storeId, $websiteId);
		if ($status != '' && trim($status) != ",") {
			$statusArray = explode(",", $status);
			if (is_array($statusArray) && count($statusArray) > 0) {
				for ($k = 0; $k < count($statusArray); $k++) {
					if($statusArray[$k] == "") {
						unset($statusArray[$k]);
					}
				}
			}
		}
		return $statusArray;
    }
	
    /**
     * @return  RestLet URL
     */
    private function getRestLetUrlFromSettings($storeId = '', $websiteId = '')
    {
		return $this->getConfigValue(self::XML_PATH_RESTLET_URL, $storeId, $websiteId);
    }
	
    /**
     * @return Customer Flow ID
     */
    public function getCustomerFlowId($storeId = '', $websiteId = '')
    {
		return $this->getConfigValue(self::XML_PATH_CUSTOMER_FLOW_ID, $storeId, $websiteId);
    }

    /**
     * @return Order Flow ID
     */
    public function getOrderFlowId($storeId = '', $websiteId = '')
    {
		return $this->getConfigValue(self::XML_PATH_ORDER_FLOW_ID, $storeId, $websiteId);
    }
	
    /**
     * @return Data Center URL Slug
     */
    private function getOrderStatus($storeId = '', $websiteId = '')
    {
		return $this->getConfigValue(self::XML_PATH_ORDER_STATUS, $storeId, $websiteId);
    }
	
    /**
     * @return Data Center URL Slug
     */
    public function getPushedOrderStatus($storeId = '', $websiteId = '')
    {
		return $this->getConfigValue(self::XML_PATH_PUSHED_ORDER_STATUS, $storeId, $websiteId);
    }
	
    /**
     * @return Restlet headers array
     */
    public function getRestLetHeaders($storeId = '', $websiteId = '')
    {
		$returnArray = array();
		
		$nsemail = $this->getConfigValue(self::XML_PATH_NETSUITE_EMAIL, $storeId, $websiteId);
		$nspassword = $this->getConfigValue(self::XML_PATH_NETSUITE_PASSWORD, $storeId, $websiteId);
		$nsaccount = $this->getConfigValue(self::XML_PATH_NETSUITE_ACCOUNT, $storeId, $websiteId);
		$nsrole = $this->getConfigValue(self::XML_PATH_NETSUITE_ROLE, $storeId, $websiteId);
		$returnArray['Authorization'] = "NLAuth nlauth_account=".$nsaccount.", nlauth_email=".$nsemail.", nlauth_signature=".$nspassword.",nlauth_role=".$nsrole."";
		$returnArray['Content-Type'] = "application/json";
	
		return $returnArray;
    }

    /**
     * @return  Error log file name
     */
    private function getErrorLogFilename($storeId = '', $websiteId = '')
    {
		$filename = $this->getConfigValue(self::XML_PATH_CONNECTOR_LOG_FILENAME, $storeId, $websiteId);
		if (isset($filename) && $filename != '') {
			$this->_logfilename = $filename;
		}
        return $this->_logfilename;
    }
	
    /**
     * Check if the logging is enabled or not
     * @return  Boolean
     */
    private function isConnectorLoggingEnabled($storeId = '', $websiteId = '')
    {
		$returnValue = false;
		$isenabled = $this->getConfigValue(self::XML_PATH_CONNECTOR_LOG_ENABLED, $storeId, $websiteId);
		if (isset($isenabled) && $isenabled == 1) {
			$returnValue = true;
		}
        return $returnValue;
    }
	
    /**
     * Add the error message to log file if Logging is enabled
     * @param  String Error Message
     */
    public function addErrorMessageToLog($message = '', $level = Zend_Log::ERR, $storeId = '', $websiteId = '')
    {
		if ($message != '' && $this->isConnectorLoggingEnabled($storeId, $websiteId)) {
			$logFileName = $this->getErrorLogFilename($storeId, $websiteId);
			Mage::log($message, $level, $logFileName, $this->_forcelogging);
		}
    }

    /**
     * Check the NetSuite credentials validity by implementing an authentication
     * @param  NetSuite Email, NetSuite Password, NetSuite Account ID, NetSuite Role ID, NetSuite Environment
	 * @return  Boolean
	 * Reference: https://system.na1.netsuite.com/help/helpcenter/en_US/Output/Help/SuiteFlex/SuiteScript/SSScriptTypes_UsingtheRESTrolesServicetoReturnUserAccountRoleandDomain.html#N3315578395-0
     */
    public function validateNetsuiteCredentials($nsemail, $nspassword, $nsaccount, $nsrole, $nsenvironment, $storeId = '', $websiteId = '')
    {
		$headers=array(); 
		$headers['Authorization'] = "NLAuth nlauth_email=$nsemail, nlauth_signature=$nspassword";
		
		$restLetUrl = self::NETSUITE_PRODUCTION_RESTLET_URL;
		switch($nsenvironment) {
			case "SANDBOX":
				$restLetUrl = self::NETSUITE_SANDBOX_RESTLET_URL;
				break;
			case "PRODUCTION":
				$restLetUrl = self::NETSUITE_PRODUCTION_RESTLET_URL;
				break;
			case "BETA":
				$restLetUrl = self::NETSUITE_BETA_RESTLET_URL;
				break;
		}
		
		$url = 'rest/roles';
		$parameters=array();
		
        try {
			$api = new Celigo_Connector_Model_Restclient(array(
				'base_url' => $restLetUrl, 
				'headers'  => $headers
			));
			
			$result = $api->get($url, $parameters, $headers);
			/*if($result->info->http_code < 400)
				json_decode($result->response);*/
			
			if (isset($result->error) && $result->error != '') {
				$message = "Error Message is: ".$result->error;
				Mage::helper('connector')->addErrorMessageToLog($message, Zend_Log::ERR, $storeId, $websiteId);
				return "Error: ".$result->error;
			}

			if (isset($result->response) && $result->response != '') {
				$response = Mage::helper('core')->jsonDecode($result->response);
				if (isset($response['error']) && is_array($response['error'])) {
					if (isset($response['error']['code']) && isset($response['error']['message'])) {
						$message = $response['error']['code']. " : ".$response['error']['message'];
						Mage::helper('connector')->addErrorMessageToLog($message, Zend_Log::ERR, $storeId, $websiteId);
						return self::NETSUITE_EMAIL_PASSWORD_WROG_MSG;
					}
				} else {
					if (is_array($response) && count($response) > 0) {
						foreach ($response as $roleInfo) {
							if (isset($roleInfo['account']['internalId']) 
								&& $roleInfo['account']['internalId'] != '' 
								&& isset($roleInfo['role']['internalId']) 
								&& $roleInfo['role']['internalId'] != '') {
									
								settype($roleInfo['role']['internalId'], gettype($nsrole));

								if ($nsaccount === $roleInfo['account']['internalId'] 
									&& $nsrole === $roleInfo['role']['internalId']) {
									return true;
								}
							}
						}
					}
				}
				
				if (isset($response['error']) && is_array($response['error'])) {
					if (isset($response['error']['code']) && isset($response['error']['message'])) {
						$message = "Error Code is: ".$response['error']['code']. " And Error Message is: ".$response['error']['message'];
						Mage::helper('connector')->addErrorMessageToLog($message, Zend_Log::ERR, $storeId, $websiteId);
						return $response['error']['message'];;
					}
				}

				return self::NETSUITE_ACCOUNT_ROLE_WRONG_MSG; 
			}
		
        } catch (Exception $e) {
            Mage::helper('connector')->addErrorMessageToLog($e->getMessage(), Zend_Log::ERR, $storeId, $websiteId);
        }
		return self::UNABLE_TO_VALIDATE_NETSUITE_CREDS_MSG;
    }

    /**
     * Check the NetSuite credentials are there in Database and trigger validating the details
     * @return  Boolean
     */
    public function isValidateNetsuiteCredentials($storeId = '', $websiteId = '')
    {
		$returnValue = self::MISSING_DETAILS_MSG;
		$nsemail = $this->getConfigValue(self::XML_PATH_NETSUITE_EMAIL, $storeId, $websiteId);
		$nspassword = $this->getConfigValue(self::XML_PATH_NETSUITE_PASSWORD, $storeId, $websiteId);
		$nsaccount = $this->getConfigValue(self::XML_PATH_NETSUITE_ACCOUNT, $storeId, $websiteId);
		$nsrole = $this->getConfigValue(self::XML_PATH_NETSUITE_ROLE, $storeId, $websiteId);
		$nsenvironment = $this->getConfigValue(self::XML_PATH_NETSUITE_ENVIRONMENT, $storeId, $websiteId);
		
		if ($nsemail != '' && $nspassword != '' 
				&& $nsaccount != '' && $nsrole != '' && $nsenvironment != '') {
			
			$returnValue = $this->validateNetsuiteCredentials($nsemail, $nspassword, $nsaccount, $nsrole, $nsenvironment, $storeId, $websiteId);
		}
		
		if ($returnValue !== true) {
			Mage::helper('connector')->addErrorMessageToLog($returnValue, Zend_Log::ERR, $storeId, $websiteId);
		}
		
		return $returnValue;
    }
	
    /**
     * Check wether we need to show the Check Now button or not
     * @return  Boolean
     */
    public function showCheckNowButton($storeId = '', $websiteId = '')
    {
		$nsemail = $this->getConfigValue(self::XML_PATH_NETSUITE_EMAIL, $storeId, $websiteId);
		$nspassword = $this->getConfigValue(self::XML_PATH_NETSUITE_PASSWORD, $storeId, $websiteId);
		$nsaccount = $this->getConfigValue(self::XML_PATH_NETSUITE_ACCOUNT, $storeId, $websiteId);
		$nsrole = $this->getConfigValue(self::XML_PATH_NETSUITE_ROLE, $storeId, $websiteId);
		$nsenvironment = $this->getConfigValue(self::XML_PATH_NETSUITE_ENVIRONMENT, $storeId, $websiteId);
		
		if ($nsemail != '' && $nspassword != '' 
				&& $nsaccount != '' && $nsrole != '' && $nsenvironment != '') {
			return true;
		}
		
		return false;
    }
	

    /**
     * Create and return REST URL using the NS credentials
     * @return  String RestLet URL
     */
    public function getRestletURL($storeId = '', $websiteId = '')
    {
		$restletUrl = '';
		$nsemail = $this->getConfigValue(self::XML_PATH_NETSUITE_EMAIL, $storeId, $websiteId);
		$nspassword = $this->getConfigValue(self::XML_PATH_NETSUITE_PASSWORD, $storeId, $websiteId);
		$nsaccount = $this->getConfigValue(self::XML_PATH_NETSUITE_ACCOUNT, $storeId, $websiteId);
		$nsrole = $this->getConfigValue(self::XML_PATH_NETSUITE_ROLE, $storeId, $websiteId);
		$nsenvironment = $this->getConfigValue(self::XML_PATH_NETSUITE_ENVIRONMENT, $storeId, $websiteId);
		$restLetUrl = $this->getRestLetUrlFromSettings();
		$orderStatus = $this->getOrderStatus();
		
		if ($nsemail != '' && $nspassword != '' && $nsaccount != '' && $nsrole != '' && $nsenvironment != '') {
			if ($restLetUrl != '') {
				return $restLetUrl;
			} else {
				Mage::helper('connector/resturl')->fetchRestUrl($nsemail, $nspassword, $nsaccount, $nsrole, $nsenvironment, $storeId, $websiteId);
				return $restLetUrl = $this->getRestLetUrlFromSettings();
			}
		}
		return $restletUrl;
    }

    /**
     * Check if the Module is enabled or not
     * @return  Boolean
	 * Usage: Mage::helper('connector')->getIsConnectorModuleEnabled($storeId, $websiteId);
     */
    public function getIsConnectorModuleEnabled($storeId = '', $websiteId = '')
    {
		$isModuleEnabled = $this->getConfigValue(self::XML_PATH_MODULE_DISABLED, $storeId, $websiteId);
		
		if (isset($isModuleEnabled) && $isModuleEnabled == 1)
			return true;
			
		return false;
    }


    /**
     * Get the cinfig value based on parameters
     * @return  Config Value
	 * 
     */
    public function getConfigValue($path = '', $storeId = '', $websiteId = '')
    {
		if($path != '') {
			$returnValue = '';
			if($storeId != '') {
				$returnValue = Mage::getStoreConfig($path, $storeId);
			} elseif($websiteId != '') {
				$returnValue = Mage::app()->getWebsite($websiteId)->getConfig($path);
			} else {
				$returnValue = Mage::getStoreConfig($path);
			}
			
			if ($path == self::XML_PATH_NETSUITE_PASSWORD) {
				$returnValue = Mage::helper('core')->decrypt($returnValue);
			}
			
			return $returnValue;
		}
    }
	
    /**
     * Ftech the RESt URL
     * @return  Boolean
     */
    public function fetchRestUrl($storeId = '', $websiteId = '')
    {
		$returnValue = self::MISSING_DETAILS_MSG;
		$nsemail = $this->getConfigValue(self::XML_PATH_NETSUITE_EMAIL, $storeId, $websiteId);
		$nspassword = $this->getConfigValue(self::XML_PATH_NETSUITE_PASSWORD, $storeId, $websiteId);
		$nsaccount = $this->getConfigValue(self::XML_PATH_NETSUITE_ACCOUNT, $storeId, $websiteId);
		$nsrole = $this->getConfigValue(self::XML_PATH_NETSUITE_ROLE, $storeId, $websiteId);
		$nsenvironment = $this->getConfigValue(self::XML_PATH_NETSUITE_ENVIRONMENT, $storeId, $websiteId);
		
		if ($nsemail != '' && $nspassword != '' && $nsaccount != '' && $nsrole != '' && $nsenvironment != '') {
			try {
				return Mage::helper('connector/resturl')->fetchRestUrl($nsemail, $nspassword, $nsaccount, $nsrole, $nsenvironment, $storeId, $websiteId);
				 
			} catch (Exception $e) {
				Mage::helper('connector')->addErrorMessageToLog($e->getMessage(), Zend_Log::ERR, $storeId, $websiteId);
			}
		}
		
		return $returnValue;
    }
	
	
    /**
     * Get admin Module Name
     * @return  String
     */
    public function getAdminModuleName($storeId = '', $websiteId = '')
    {
		$adminModule = 'admin';
		
		try {
		
			$adminModule = Mage::getConfig()->getNode('admin/routers/adminhtml/args/frontName');
			 
		} catch (Exception $e) {
			Mage::helper('connector')->addErrorMessageToLog($e->getMessage(), Zend_Log::ERR, $storeId, $websiteId);
		}
			
		if($adminModule == '') {
			$adminModule = 'admin';
		}
		
		return $adminModule;
    }
}