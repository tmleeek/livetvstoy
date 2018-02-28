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
            $message = "Dear Customer,<br /><br />" . $subject;
            $message .= "<br/><br/><strong>Magento Store Details:</strong><br/><br/>";
			if(trim($storeName) != '') {
				$message .= "<strong>Store Name:</strong> " . $storeName . "<br/>";
			}
            $message .= "<strong>Store URL :</strong> <a href='" . $storeUrl . "'>" . $storeUrl . "</a>";
            if (!$isSuccess && count($errorMsgs) > 0) {
                $message .= "<br/><strong>Error Message: </strong>" . implode(", ", $errorMsgs);
            }
            $message .= "<br /><br />Thanks,<br />Celigo Team";
            $this->sendEmail($subject, $message);
        } catch (Exception $e) { //Log the error
            Mage::helper('celigoconnector/celigologger')->error('errormsg="' . $e->getMeesage() . '"', self::LOG_FILENAME);
        }
    }

    /**
     * Method to send email to Magento product manager
     * @param string $subject
     * @param string $message
     */
    private function sendEmail($subject = '', $message = '') {
        try {
            $techEmail = $this->getConfigValue(Celigo_Celigoconnector_Model_Celigoconnector::XML_PATH_TECHNICAL_CONTACT_EMAIL);
            $fromName = "Celigo Magento Connector";
            $fromEmail = "magento@celigo.com";
            $mail = new Zend_Mail();
            $mail->setFrom($fromEmail, $fromName);
            if (trim($techEmail) != '') {
                $mail->addTo($techEmail, "Technical Contact");
                $mail->addBcc("product.magento@celigo.com");
            } else {
                $mail->addTo("product.magento@celigo.com", "Celigo Magento Product");
            }
            $mail->setSubject($subject);
            $mail->setBodyHtml($message);
            $mail->send();
        } catch (Exception $e) {
            Mage::helper('celigoconnector/celigologger')->error('errormsg="' . $e->getMeesage() . '"', self::LOG_FILENAME);
        }
    }

}
