<?php
/**
 * @category    Zeon
 * @package     Zeon_Responsys
 * @copyright
 * @license
 */
class Zeon_Responsys_Helper_Data extends Mage_Core_Helper_Abstract
{
    const RESPONSYS_WSDL_URL         = 'responsys/general/responsys_wsdl_url';
    const RESPONSYS_WSDL_ENDPOINT     = 'responsys/general/responsys_wsdl_endpoint';
    const RESPONSYS_INTERACT_URI     = 'responsys/general/responsys_interact_uri';
    const RESPONSYS_USERNAME          = 'responsys/general/responsys_username';
    const RESPONSYS_PASSWORD          = 'responsys/general/responsys_password';
    const RESPONSYS_FOLDER_NAME      = 'responsys/general/responsys_folder_name';
    const RESPONSYS_CONTACT_LIST      = 'responsys/general/responsys_contatct_list';
    const RESPONSYS_VARIABLE          = 'responsys/responsys_variable/responsys_add_variable';
    const RESPONSYS_DEBUG            = 'responsys/general/enable_debug';

    //Add multiple values comma saperated
    const NEWSLETTER_FORM_TYPES = 'newsletter_update,account_create,newsletter_subscribe';

    public function getUserName()
    {
        return     Mage::getStoreConfig(self::RESPONSYS_USERNAME);
    }
    public function getPassword()
    {
        return     Mage::getStoreConfig(self::RESPONSYS_PASSWORD);
    }
    public function getRequestWsdlUrl()
    {
        return     Mage::getStoreConfig(self::RESPONSYS_WSDL_URL);
    }

    public function getRequestWsdlEndpoint()
    {
        return     Mage::getStoreConfig(self::RESPONSYS_WSDL_ENDPOINT);
    }

    public function getRequestInteractUri()
    {
        return     Mage::getStoreConfig(self::RESPONSYS_INTERACT_URI);
    }

    public function getFolderName($store = null)
    {
        return     Mage::getStoreConfig(self::RESPONSYS_FOLDER_NAME, $store);
    }

    public function getContactList($store = null)
    {
        return Mage::getStoreConfig(self::RESPONSYS_CONTACT_LIST, $store);
    }

    public function getDebugOn($store = null)
    {
        return Mage::getStoreConfig(self::RESPONSYS_DEBUG, $store);
    }

    public function getResponsysVariables()
    {
        $variables = array();
        $settings = unserialize(Mage::getStoreConfig(self::RESPONSYS_VARIABLE));

        foreach ($settings['email_variables'] as $key => $settingEmailVariables) {
            if (empty($settingEmailVariables)) {
                continue;
            }

            $emailVariables = explode(',', $settingEmailVariables);
            foreach ($emailVariables as $emailVariable) {
                if (isset($variables[trim($emailVariable)])) {
                    $variables[trim($emailVariable)] = array_merge(
                        $variables[trim($emailVariable)],
                        explode(',', $settings['campaign_variables'][$key])
                    );
                } else {
                    $variables[trim($emailVariable)] = explode(',', $settings['campaign_variables'][$key]);
                }
            }
        }
        //print_r($variables);exit;
        return $variables;
    }

}