<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition License
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magentocommerce.com/license/enterprise-edition
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Zeon
 * @package     Zeon_Responsys
 * @copyright
 * @license
 */
class Zeon_Responsys_Model_Api extends Mage_Core_Model_Abstract
{
    private $_reqMergeArgs;
    private $_client = false;
    private $_customerEmailId;


    public function getClientObj()
    {
        try {
            if ($this->_client) {
                return $this->_client;
            }
            $start = microtime(true);

            $this->_client = new SoapClient(
                $this->_getRequestWsdlUrl(),
                array(
                    'location' => $this->_getRequestWsdlEndpoint(),
                    'uri' => $this->_getRequestInteractUri(),
                    'trace' => TRUE,
                )
            );
            if ($this->_getDebugOn()) {
                $timeTaken = microtime(true) - $start;
                Mage::log('API Request Time (getClientObj) : '.$timeTaken, 1, 'responsys.log', 1);
            }
            return $this->_client;
        } catch (SoapClient $e) {
            $logMessage = "Login Error:" . $e->getMessage() . "\n";
            Mage::log($logMessage, 1, "responsys.log", 1);
            return array('err_msg' => $logMessage, 'flag' => false);
        }
    }

    /**
     * Creates responsys initial setup and object
     * @return boolean
     */
    private function _loginAndSetup($store = null)
    {
        try {
            $start = microtime(true);
            $this->_client = $this->getClientObj();
            if ($this->_getDebugOn()) {
                //Time logging
                $timeTaken = microtime(true) - $start;
                Mage::log('API Request Time (getClientObj) : '.$timeTaken, 1, 'responsys.log', 1);
            }
            if ($this->_reqMergeArgs) {
                return $this->_reqMergeArgs;
            }

            //LOGIN
            $start = microtime(true);
            $loginResult = $this->_client->login(
                array(
                    "username" => $this->_getUserName(),
                    "password" => $this->_getPassword()
                )
            ); //session ID and jsession returned from this call
            if ($this->_getDebugOn()) {
                Mage::log('Session Id : '.$loginResult->result->sessionId, 1, 'responsys.log', 1);
            }
            //---Set Session header and jsession----
            $sessionId = array(
                'sessionId' => new SoapVar(
                    $loginResult->result->sessionId,
                    XSD_STRING,
                    null,
                    null,
                    null,
                    $this->_getRequestInteractUri()
                )
            );
            $sessionHeader = new SoapVar($sessionId, SOAP_ENC_OBJECT);
            $header = new SoapHeader($this->_getRequestInteractUri(), 'SessionHeader', $sessionHeader);
                //how you set the sessionID in the header
            $this->_client->__setSoapHeaders(array($header));
            $jsessionID = $this->_client->_cookies["JSESSIONID"][0]; //how you can retrieve the JSESSIONID
            $this->_client->__setCookie("JSESSIONID", $jsessionID); //how you set the cookie to use jsessionID

            $myFolderName = $this->_getFolderName($store); //name of folder where the campaign exists
            $contactList = $this->_getContactList($store);
            if ($this->_getDebugOn()) {
                $timeTaken = microtime(true) - $start;
                Mage::log('API Request Time (Login) : '.$timeTaken, 1, 'responsys.log', 1);
            }

            if (empty($contactList)) {
                throw new Exception('No Contact List Available');
            }

            if (empty($myFolderName)) {
                throw new Exception('No Folder Available');
            }

            $this->_reqMergeArgs = new stdClass();
            $this->_reqMergeArgs->list = new stdClass();
            $this->_reqMergeArgs->list->folderName = $myFolderName; //Name of folder the list exists
            $this->_reqMergeArgs->list->objectName = $contactList;  //Name of list
            $this->_reqMergeArgs->recordData = new stdClass();

            return $this->_reqMergeArgs;
        } catch (Exception $e) {
            $logMessage = "Login Error:" . $e->getMessage() . "\n";
            Mage::log($logMessage, 1, "responsys.log", 1);
            return array('err_msg' => $logMessage, 'flag' => false);
        } catch (SoapFault $e) {
            $logMessage = "MergeListMembers Call Error ".$e->detail->ListFault->exceptionMessage."\n";
            Mage::log($logMessage, 1, "responsys.log", 1);
            Mage::log($e->getMessage(), 1, "responsys.log", 1);
        }
    }

    /**
     * Prepares Mergerule basic variables
     */
    public function prepareMergeRuleParameters()
    {
        //List Merge Rules
        $this->_reqMergeArgs->mergeRule = new stdClass();
        $this->_reqMergeArgs->mergeRule->insertOnNoMatch = True;
        $this->_reqMergeArgs->mergeRule->updateOnMatch = 'REPLACE_ALL';
        $this->_reqMergeArgs->mergeRule->matchColumnName1 = 'EMAIL_ADDRESS_';  //field to match
        $this->_reqMergeArgs->mergeRule->matchOperator = 'NONE';
        $this->_reqMergeArgs->mergeRule->optinValue = 'I';
        $this->_reqMergeArgs->mergeRule->optoutValue = 'O';
        $this->_reqMergeArgs->mergeRule->htmlValue = 'HTML';
        $this->_reqMergeArgs->mergeRule->textValue = 'TEXT';
        $this->_reqMergeArgs->mergeRule->rejectRecordIfChannelEmpty = 'E';
        //$this->_reqMergeArgs->mergeRule->defaultPermissionStatus = 'OPTOUT';
    }

    public function updateResponsysData($formData, $eventType)
    {
        if (!$this->_loginAndSetup()) {
            return false;
        }

        if ($this->setFieldsArray($formData, $eventType)) {
            $this->prepareMergeRuleParameters();
            $start = microtime(true);
            try {
                //Call to update Contact List
                $response = $this->_client->mergeListMembersRIID($this->_reqMergeArgs); //mergeListMembers call
                if ($response) {
                    $rid = $response->recipientResult->recipientId;
                    $formData->setData('responsys_rrid', $rid)->save();
                    if ($eventType == 'create_customer') {
                        $subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($formData->getEmail());
                        if ($subscriber && $subscriber->getData()) {
                            $subscriber->setData('responsys_rrid', $rid)->save();
                        }
                    }
                }
                if ($this->_getDebugOn()) {
                    Mage::log($response, 1, "responsys.log", 1);
                }
            } catch (SoapFault $err) {
                $logMessage = "MergeListMembers Call Error : ".$err->detail->ListFault->exceptionMessage."\n";
                Mage::log($logMessage, 1, "responsys.log", 1);
                Mage::log($err->getMessage(), 1, "responsys.log", 1);
            } catch (Exception $e) {
                Mage::log($e->getMessage(), 1, "responsys.log", 1);
            }
            if ($this->_getDebugOn()) {
                $timeTaken = microtime(true) - $start;
                Mage::log('API Request Time : '.$timeTaken, 0, 'responsys.log', 1);
            }
        }
        return;
    }

    public function setFieldsArray($dataObj, $formType)
    {
        try {
            $variables = $this->_getResponsysVariables();
            //unset unwanted variables
            if (isset($variables['---Please Select---'])) {
                unset($variables['---Please Select---']);
            }

            //retrun if no variables are found
            if (empty($variables)) {
                Mage::log(
                    'No variables are selected. Please select variables from admin configuration settings',
                    1,
                    "responsys.log",
                    1
                );
                return false;
            }

            $data = $dataObj->getData();

            // set proper variables data
            if ($formType == 'newsletter_subscribe') {
                if (isset($variables['Email_Address'])) {
                    $variables['Email_Address'][] = $data['subscriber_email'];
                }
                if (isset($variables['Customer_Id']) && $data['customer_id']) {
                    $variables['Customer_Id'][] = $data['customer_id'];
                }
                if (isset($variables['Is_Subscribed'])) {
                    $subscriptionStatus = $data['subscriber_status'];
                    if ($subscriptionStatus != '1') {
                        $subscriptionStatus = '0';
                    }
                    $data['is_subscribed'] = $subscriptionStatus;
                    $variables['Is_Subscribed'][] = $subscriptionStatus;
                }
            } elseif ($formType == 'create_customer') {
                if (isset($variables['Email_Address'])) {
                    $variables['Email_Address'][] = $data['email'];
                }
                if (isset($variables['Customer_Id']) && $data['entity_id'] > 0) {
                    $variables['Customer_Id'][] = $data['entity_id'];
                }
                if (isset($variables['First_Name'])) {
                    $variables['First_Name'][] = $data['firstname'];
                }
                if (isset($variables['Last_Name'])) {
                    $variables['Last_Name'][] = $data['lastname'];
                }
                if (isset($variables['Is_Subscribed'])) {
                    $subscriptionStatus = $data['is_subscribed'];
                    if ($subscriptionStatus != '1') {
                        $subscriptionStatus = '0';
                    }
                    $variables['Is_Subscribed'][] = $subscriptionStatus;
                }
            } elseif ($formType == 'checkout_register') {
                $address = $dataObj->getDefaultBillingAddress()->getData();
                if (isset($variables['Email_Address'])) {
                    $variables['Email_Address'][] = $data['email'];
                }
                if (isset($variables['Customer_Id']) && $data['entity_id'] > 0) {
                    $variables['Customer_Id'][] = $data['entity_id'];
                }
                if (isset($variables['First_Name'])) {
                    $variables['First_Name'][] = $data['firstname'];
                }
                if (isset($variables['Last_Name'])) {
                    $variables['Last_Name'][] = $data['lastname'];
                }
                if (isset($variables['Address']) && isset($address['street'])) {
                    $variables['Address'][] = $address['street'];
                }
                if (isset($variables['City']) && isset($address['city'])) {
                    $variables['City'][] = $address['city'];
                }
                if (isset($variables['State']) && isset($address['region'])) {
                    $variables['State'][] = $address['region'];
                }
                if (isset($variables['Country']) && isset($address['country_id'])) {
                    $variables['Country'][] = $address['country_id'];
                }
                if (isset($variables['Zip_Code']) && isset($address['postcode'])) {
                    $variables['Zip_Code'][] = $address['postcode'];
                }
                if (isset($variables['Telephone_no']) && isset($address['telephone'])) {
                    $variables['Telephone_no'][] = $address['telephone'];
                }
                if (isset($variables['Is_Subscribed']) && isset($data['is_subscribed'])) {
                    $subscriptionStatus = $data['is_subscribed'];
                    if ($subscriptionStatus != '1') {
                        $subscriptionStatus = '0';
                    }
                    $variables['Is_Subscribed'][] = $subscriptionStatus;
                }
            }
            $permiossionStatus = 'O';
            if (isset($data['is_subscribed']) && $data['is_subscribed'] == '1') {
                $permiossionStatus = 'I';
            }

            $variables['Email_Format'] = array('EMAIL_FORMAT_', 'HTML');
            $variables['Email_Permission'] = array('EMAIL_PERMISSION_STATUS_', $permiossionStatus);
            $i=0;
            $varIds = NULL;
            $varValues = NULL;
            foreach ($variables as $varData) {
                if (isset($varData[1])) {
                    $varIds[$i] = $varData[0];
                    $varValues[$i] = $varData[1];
                    $i++;
                }
            }

            //Recreate records
            $this->_reqMergeArgs->recordData->fieldNames = $varIds;
            $fieldValues=array();
            $fieldValues[]['fieldValues'] = $varValues;
            $this->_reqMergeArgs->recordData->records = new stdClass();
            $this->_reqMergeArgs->recordData->records = $fieldValues;
            if ($this->_getDebugOn()) {
                Mage::log('Request: '.$fieldValues, 1, "responsys.log", 1);
            }

        } catch (Exception $e) {
            $logMessage = "Variables Error:" . $e->getMessage() . "\n";
            Mage::log($logMessage, 1, "responsys.log", 1);
            return array('err_msg' => $logMessage, 'flag' => false);
        }
        return true;
    }


    public function _getUserName()
    {
        return Mage::helper('responsys')->getUserName();
    }
    public function _getPassword()
    {
        return     Mage::helper('responsys')->getPassword();
    }
    public function _getRequestWsdlUrl()
    {
        return     Mage::helper('responsys')->getRequestWsdlUrl();
    }

    public function _getRequestWsdlEndpoint()
    {
        return     Mage::helper('responsys')->getRequestWsdlEndpoint();
    }

    public function _getRequestInteractUri()
    {
        return     Mage::helper('responsys')->getRequestInteractUri();
    }

    public function _getFolderName($store = null)
    {
        return Mage::helper('responsys')->getFolderName($store);
    }

    public function _getContactList($store = null)
    {
        return Mage::helper('responsys')->getContactList($store);
    }

    public function _getResponsysVariables()
    {
        return Mage::helper('responsys')->getResponsysVariables();
    }

    public function _getDebugOn($store = null)
    {
        return Mage::helper('responsys')->getDebugOn($store);
    }
}