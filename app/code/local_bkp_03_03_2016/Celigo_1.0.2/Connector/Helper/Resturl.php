<?php
/*
 *	Helper class to build RestLet URL
 */
class Celigo_Connector_Helper_Resturl extends Mage_Core_Helper_Abstract
{
	const XML_PATH_RESTLET_URL = 'connector/othersettings/restleturl';
	const RESTLET_URL_END_SLUG = '/app/site/hosting/restlet.nl?script=customscript_celigo_mg_rt_import_rest&deploy=customdeploy_celigo_mg_rt_import_deploy';

    /**
     * Fetch RESTLet URL
     * @param  NetSuite Email, NetSuite Password, NetSuite Account ID, NetSuite Role ID, NetSuite Environment
	 * @return  Boolean
	 * Reference: https://system.na1.netsuite.com/help/helpcenter/en_US/Output/Help/SuiteFlex/SuiteScript/SSScriptTypes_UsingtheRESTrolesServicetoReturnUserAccountRoleandDomain.html#N3315578395-0
     */
    public function fetchRestUrl($nsemail, $nspassword, $nsaccount, $nsrole, $nsenvironment, $storeId = '', $websiteId = '')
    {
		if($nsemail == '' || $nspassword == '' 
				|| $nsaccount == '' || $nsrole == ''|| $nsenvironment == '') {
			
			return $returnValue = Celigo_Connector_Helper_Data::MISSING_DETAILS_MSG;
		}
		
		$headers=array(); 
		$headers['Authorization'] = "NLAuth nlauth_email=$nsemail, nlauth_signature=$nspassword";
		
		$restLetUrl = Celigo_Connector_Helper_Data::NETSUITE_PRODUCTION_RESTLET_URL;
		switch ($nsenvironment) {
			case "SANDBOX":
				$restLetUrl = Celigo_Connector_Helper_Data::NETSUITE_SANDBOX_RESTLET_URL;
				break;
			case "PRODUCTION":
				$restLetUrl = Celigo_Connector_Helper_Data::NETSUITE_PRODUCTION_RESTLET_URL;
				break;
			case "BETA":
				$restLetUrl = Celigo_Connector_Helper_Data::NETSUITE_BETA_RESTLET_URL;
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

			if (isset($result->error) && $result->error != '') {
				$message = "Error Message is: " . $result->error;
				Mage::helper('connector')->addErrorMessageToLog($message, Zend_Log::ERR, $storeId, $websiteId);
				return "Error: ".$result->error;
			}

			if (isset($result->response) && $result->response != '') {
				$response = Mage::helper('core')->jsonDecode($result->response);
				if (isset($response['error']) && is_array($response['error'])) {
					if (isset($response['error']['code']) && isset($response['error']['message'])) {
						$message = $response['error']['code']. " : ".$response['error']['message'];
						Mage::helper('connector')->addErrorMessageToLog($message, Zend_Log::ERR, $storeId, $websiteId);
						return Celigo_Connector_Helper_Data::NETSUITE_EMAIL_PASSWORD_WROG_MSG;
					}
				} else {
					if (is_array($response) && count($response) > 0) {
						foreach ($response as $roleInfo) {
							if (isset($roleInfo['account']['internalId']) && $roleInfo['account']['internalId'] != '' 
									&& isset($roleInfo['role']['internalId']) && $roleInfo['role']['internalId'] != '') {
								if ($nsaccount == $roleInfo['account']['internalId'] && $nsrole == $roleInfo['role']['internalId']) {
									
									$coreConfig = new Mage_Core_Model_Config();
									$coreConfig ->saveConfig(self::XML_PATH_RESTLET_URL, $roleInfo['dataCenterURLs']['restDomain'].self::RESTLET_URL_END_SLUG, 'default', 0);
									Mage::app()->getConfig()->reinit();
									Mage::app()->reinitStores();
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
				
				return Celigo_Connector_Helper_Data::NETSUITE_ACCOUNT_ROLE_WRONG_MSG;
			}
			
		
        } catch (Exception $e) {
            Mage::helper('connector')->addErrorMessageToLog($e->getMessage(), Zend_Log::ERR, $storeId, $websiteId);
        }
		return Celigo_Connector_Helper_Data::UNABLE_TO_VALIDATE_NETSUITE_CREDS_MSG;
    }
}