<?php
/**
 * Helper class to send installer Email
 */
class Celigo_Celigoconnector_Helper_Installeremail extends Celigo_Celigoconnector_Helper_Data {

    const INSTALL_SUCCESSFUL_MSG = "Celigo Magento Extensions Install Successful | vVERSION";
    const UPGRADE_SUCCESSFUL_MSG = "Celigo Magento Extensions Upgrade Successful | vVERSION";
    const INSTALL_FAILURE_MSG = "Celigo Magento Extensions Install Failure | vVERSION";
    const UPGRADE_FAILURE_MSG = "Celigo Magento Extensions Upgrade Failure | vVERSION";
    const LOG_FILENAME = 'celigo-installer-email.log';

    /**
     * Function to send installation email
     * @param boolean $isceligoconnectorPlus 
     * @param boolean $isUpgraded
     * @param array $errorMsgs
     */
    public function sendInstallationEmail($isceligoconnectorPlus = false, $isUpgraded = false, $errorMsgs = array()) {
        try {
            $isSuccess = (count($errorMsgs) > 0) ? false : true;
            $celigoconnectorPlusVer = Mage::getConfig()->getModuleConfig('Celigo_Celigoconnectorplus')->version;
            if (!$isceligoconnectorPlus && $isSuccess && $celigoconnectorPlusVer != '') {
                return;
            }
            if ($isSuccess && !$isUpgraded) { // Install Success
                $subject = self::INSTALL_SUCCESSFUL_MSG;
            } elseif ($isSuccess && $isUpgraded) { // Upgrade Success
                $subject = self::UPGRADE_SUCCESSFUL_MSG;
            } elseif (!$isSuccess && !$isUpgraded) { // Install Failure
                $subject = self::INSTALL_FAILURE_MSG;
            } elseif (!$isSuccess && $isUpgraded) { // Upgrade Failure
                $subject = self::UPGRADE_FAILURE_MSG;
            }
            $current_version = Mage::getConfig()->getModuleConfig('Celigo_Celigoconnector')->version;
            $subject = str_replace("VERSION", $current_version, $subject);
            $coreConfigData = Mage::getModel('core/config_data');
            $storeName = $coreConfigData->load('general/store_information/name', 'path')->getValue();
            $storeUrl = $coreConfigData->load('web/secure/base_url', 'path')->getValue();
            $message = "Dear Customer,<br /><br />";
            $message .= $subject . "<br/><br/>";
            if(trim($storeName) != '') {
                $message .= "Store Name: " . $storeName . "<br/>";
            }
            $message .= "Store URL : <a href='" . $storeUrl . "'>" . $storeUrl . "</a><br/>";
            $techEmail = Mage::getModel('core/config_data')->load(Celigo_Celigoconnector_Model_Celigoconnector::XML_PATH_TECHNICAL_CONTACT_EMAIL, 'path')->getValue();
            if(trim($techEmail) != '') {
                $message .= "Technical Contact: " . $techEmail;
            }
            if (!$isSuccess && count($errorMsgs) > 0) {
                $message .= "<br/>Error Message: " . implode(", ", $errorMsgs);
                $message .= "<br/><br/>Please contact Celigo Support <support@celigo.com> for further assistance";
            }
            $message .= "<br /><br />Thanks,<br />Celigo, Inc";
            $this->sendEmail($subject, $message);
        } catch (Exception $e) { //Log the error
            Mage::helper('celigoconnector/celigologger')->error('errormsg="' . $e->getMessage() . '"', self::LOG_FILENAME);
        }
    }

    /**
     * Method to send email to Magento product manager
     * @param string $subject
     * @param string $message
     */
    private function sendEmail($subject = '', $message = '') {
        try {
            $coreConfigData = Mage::getModel('core/config_data');
            $techEmail = $coreConfigData->load(Celigo_Celigoconnector_Model_Celigoconnector::XML_PATH_TECHNICAL_CONTACT_EMAIL, 'path')->getValue();
            $recipientEmails = array();
            $techEmails = explode(",", $techEmail);
            if (!empty($techEmail) && count($techEmails) > 0) {
                foreach($techEmails as $_techEmail) {
                    if(!empty($_techEmail) && filter_var($_techEmail, FILTER_VALIDATE_EMAIL)) {
                        $recipientEmails["Technical Contact"] = trim($_techEmail);
                    }
                }
            }
            $fromName = "Celigo Magento Connector";
            $fromEmail = "magento@celigo.com";
            $mail = new Zend_Mail();
            $mail->setFrom($fromEmail, $fromName);
            if (count($recipientEmails) > 0) {
                $mail->addTo($recipientEmails);
                $mail->addBcc("product.magento@celigo.com");
            } else {
                $mail->addTo("product.magento@celigo.com", "Celigo Magento Product");
            }
            $mail->setSubject($subject);
            $mail->setBodyHtml($message);
            $mail->send();
        } catch (Exception $e) {
            Mage::helper('celigoconnector/celigologger')->error('errormsg="' . $e->getMessage() . '"', self::LOG_FILENAME);
        }
    }

}
