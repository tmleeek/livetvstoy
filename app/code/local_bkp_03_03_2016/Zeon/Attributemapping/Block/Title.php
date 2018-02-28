<?php
/**
 * Zeon Attribute Mapping module
 *
 * @category   Zeon
 * @package    Zeon_Attributemapping
 * @copyright  Copyright (c) 2008 Zeon Solutions.
 * @license    http://www.opensource.org/licenses/gpl-3.0.html GNU General
 * Public License version 3
 */
class Zeon_Attributemapping_Block_Title
    extends Mage_Core_Block_Template
{

    protected $_optionData = null;
    protected $_attribute = null;


    /**
     * Prepare layout
     *
     * @return Mage_CatalogSearch_Block_Result
     */
    protected function _prepareLayout()
    {
        try {
            // add Home breadcrumb
            $headBlock = $this->getLayout()->getBlock('head');
            if ($headBlock) {
                $option = $this->getOptionData();
                $title = $option['label'];
                //Set Meta Information
                $headBlock->setTitle($title);
                if ($option['data']['meta_title']) {
                    $headBlock->setTitle($option['data']['meta_title']);
                }
                if ($option['data']['meta_keywords']) {
                    $headBlock->setKeywords($option['data']['meta_keywords']);
                }
                if ($option['data']['meta_description']) {
                    $headBlock->setDescription($option['data']['meta_description']);
                }

                //Canonical url
                $urlString = Mage::helper('core/url')->getCurrentUrl();
                if ($urlString) {
                    $urlString = explode('?', $urlString);
                    $urlString = $urlString[0];
                    $headBlock->addLinkRel("canonical", $urlString);
                }

                $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
                if ($breadcrumbs) {
                    $breadcrumbs->addCrumb(
                        'home',
                        array(
                            'label' => $this->__('Home'),
                            'title' => $this->__('Go to Home Page'),
                            'link'  => Mage::getBaseUrl()
                        )
                    )->addCrumb(
                        'attribute',
                        array(
                            'label' => $title,
                            'title' => $title
                        )
                    );
                }
                if (!$this->getRequest()->getParam($option['attribute_code'])) {
                    $this->getRequest()->setParam($option['attribute_code'], $option['value']);
                }
                $this->getRequest()->setParam('attribute_name', $option['attribute_name']);
            }
        } catch (Mage_Core_Exception $e) {
            Mage::log('breadcrumb :: '.$e->getMessage(), null, 'site_errors.log');
        }

        return parent::_prepareLayout();
    }

    /**
     * get attribute code
     */
    public function getOptionData()
    {
        if (Mage::registry('optionData')) {
            $this->_optionData = Mage::registry('optionData');
        } else if (!$this->_optionData) {
            $optionId  = $this->getRequest()->getParam('id');
            $optionText = '';
            $optionAllData = '';
            $attribute = $this->getAttributeData();
            $attr = Mage::getSingleton('eav/config')
                ->getAttribute(
                    'catalog_product',
                    $attribute->getAttributeCode()
                );

            if ($attr->usesSource()) {
                $optionText = $attr->getSource()
                    ->getOptionText($optionId);

                $optionListData = Mage::getSingleton(
                    'zeon_attributemapping/attributemapping'
                )->getCollection()
                    ->addFieldToFilter('option_id', $optionId)
                    ->getFirstItem();
                $optionAllData = $optionListData->getData();
            }
            $this->_optionData = array(
                'value' => $optionId,
                'label' => $optionText,
                'attribute_code' => $attribute->getAttributeCode(),
                'attribute_name' => $attribute->getFrontendLabel(),
                'input_type' => $attribute->getFrontendInput(),
                'data' => $optionAllData
            );
            Mage::register('optionData', $this->_optionData);
            // update meta deta
            $title = $optionText;
        }

        return  $this->_optionData;
    }

    /**
     *
     * set page title
     */
    public function setPageTitle()
    {
        $option = $this->getOptionData();
        return $option['label'];
    }

    /**
     * set page description
     */
    public function setPageDescripion()
    {
        $option = $this->getOptionData();
        $data = '';
        if ($option['data']['description'] != '') {
            $data = '<div class="category-description std">
                '.$option['data']['description'].'
                </div>';
        }
        return $data;
    }

    /**
     *
     * set attribute data
     */
    public function getAttributeData()
    {
        if (Mage::registry('attribute_data')) {
            $this->_optionData = Mage::registry('attribute_data');
        } else if (!$this->_attribute) {
            $optionId  = $this->getRequest()->getParam('id');
            $_attributeId = Mage::getModel('eav/entity_attribute_option')
                ->load($optionId)->getAttributeId();
            $this->_attribute = Mage::getModel('eav/entity_attribute')
                ->load($_attributeId);
            Mage::register('attribute_data', $this->_attribute);
        }
        return $this->_attribute;
    }


    /**
     * set page background image
     */
    public function setBackgroundImage()
    {
        $option = $this->getOptionData();
        $imageHtml = '';
        if ($option['data']['page_background_image'] != '') {
            $_imageUrl = Mage::getBaseUrl('media') .
                $option['data']['page_background_image'];
            $imageHtml = '<p class="category-image">
                <img src="'.$_imageUrl.'"
                    alt="'.$option['label'].'"
                        title="'.$option['label'].'" />
            </p>';
        }
        return $imageHtml;
    }

    /**
     * Function checked is the current character is Poptropica Character or not.
     *
     * @return Boolean
     */
    public function isPoptropicaCharacter()
    {
        // Boolean var to store the status of poptropica character or not.
        $_isPoptropicaCharacter = true;

        // Get the current selected option/character data
        $_optionData = $this->getOptionData();

        // Check, if the current selected character is Proptropica or not.
        if (isset($_optionData['label']) && 'poptropica' == strtolower($this->_optionData['label'])) {
            $_isPoptropicaCharacter = false;
            // Customer Session
            $_customerSession = Mage::getSingleton('customer/session');

            // Store the POSTed data in a var.
            $_poptropicaDetails = $this->getRequest()->getPost();

            // Check, if the data is available then set the data in session.
            if (isset($_poptropicaDetails['id']) && isset($_poptropicaDetails['avatar'])) {
                $_customerSession->setPoptropicaDetails($_poptropicaDetails);
            }

            // Added code to check whether the Poptropica details are set or not.
            if ($_customerSession->getPoptropicaDetails()) {
                $_poptropicaDetails = $_customerSession->getPoptropicaDetails();

                // Call the function to copy Poptropica image to FTP server.
                if ($this->_copyPoptropicaImageToArtifi($_poptropicaDetails['id']) &&
                    $this->_copyPoptropicaImageToFTP($_poptropicaDetails['id'])) {
                    $_isPoptropicaCharacter = true;
                }
            } else {
                // If NO then return FALSE
                $_isPoptropicaCharacter = false;
            }
        }
        Mage::register('isPoptropicaCharacter', $_isPoptropicaCharacter);
        // Return the status of poptropica character.
        return $_isPoptropicaCharacter;
    }

    /**
     * Function used to copy the Poptropica image to FTP server.
     *
     * @param String $_poptropicaImageId
     *
     * @return Boolean
     */
    protected function _copyPoptropicaImageToFTP($_poptropicaImageId)
    {
        // Boolean var to store the status of image copy.
        $_isImageCopied = false;

        // Retrive the Poptropica setting.
        $_poptropicaSettings = Mage::getStoreConfig('personalize/poptropica_settings');

        // Prepare the Poptropica Image url.
        $_poptropicaImage = $_poptropicaSettings['poptropica_image_url']
            . '?password=' . $_poptropicaSettings['poptropica_password']
            . '&id=' . $_poptropicaImageId;

        // Setup the basic connection.
        $_connectionId = ftp_connect($_poptropicaSettings['poptropica_ftp_host']);

        // Login with the username and password
        $_login = ftp_login(
            $_connectionId,
            $_poptropicaSettings['poptropica_ftp_username'],
            $_poptropicaSettings['poptropica_ftp_password']
        );

        // Upload the file to FTP server.
        $_ftpImageFile = $_poptropicaSettings['poptropica_ftp_directory'] . $_poptropicaImageId . '.png';

        // First check, whether the file is already exists or not.
        $_isFileExists = ftp_size($_connectionId, $_ftpImageFile);

        // If file is not exists...
        if ($_isFileExists == -1) {
            // Then upload the image on the FTP server.
            if (ftp_put($_connectionId, $_ftpImageFile, $_poptropicaImage, FTP_BINARY)) {
                $_isImageCopied = true;
            }
        } else {
            $_isImageCopied = true;
        }

        // close the connection
        ftp_close($_connectionId);

        // Return the image copy status.
        return $_isImageCopied;
    }

    /**
     * Function used to copy the Poptropica image to Artifi server.
     *
     * @param String $_poptropicaImageId
     *
     * @return Boolean
     */
    protected function _copyPoptropicaImageToArtifi($_poptropicaImageId)
    {
        // Boolean var to store the status of image copy.
        $_isImageCopied = false;

        // Retrive the Poptropica setting.
        $_poptropicaSettings = Mage::getStoreConfig('personalize');

        // Prepare the Poptropica Image url.
        $_poptropicaImage = $_poptropicaSettings['poptropica_settings']['poptropica_image_url']
            . '?password=' . $_poptropicaSettings['poptropica_settings']['poptropica_password']
            . '&id=' . $_poptropicaImageId;

        // Prepare the Artifi-URL to upload image.
        $_artifiURL = $_poptropicaSettings['mycustom_group']['artifi_domain']
            . $_poptropicaSettings['mycustom_group']['artifi_upload_image']
            . '?url=' . $_poptropicaImage
            . '&imageId=' . $_poptropicaImageId
            . '&webApiclientKey=' . $_poptropicaSettings['mycustom_group']['artifi_web_api_client_key']
        ;

        // Init the cURL.
        $ch = curl_init();

        // Set the Artifi URL
        curl_setopt($ch, CURLOPT_URL, $_artifiURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);

        // Execute the curl to copy the Poptropica Image to Artifi server
        $_result = curl_exec($ch);

        // Close the connection.
        curl_close($ch);

        // Decode the result.
        $_response = json_decode($_result);

        $_responseTxt = 'Response';

        // Check the response
        if ($_response && $_response->$_responseTxt && 'success' == strtolower((string)$_response->$_responseTxt)) {
            $_isImageCopied = true;
        }

        // Return the image copy status.
        return $_isImageCopied;
    }
}
