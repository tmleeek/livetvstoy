<?php

/**
 * BuyRequest JSON model observer
 *
 * @category    Zeon
 * @package     Zeon_ProductOptionsJson
 * @author      Blake Bauman <blake.bauman@zeonsolutions.com>
 */
class Zeon_ProductOptionsJson_Model_Observer extends Mage_Core_Model_Abstract
{

    /**
     * Converts attributes and custom options to JSON
     * Save JSON object to line item order level
     *
     * @param   Varien_Event_Observer $observer
     * @return  Zeon_ProductOptionsJson_Model_Observer
     */
    public function saveProductOptionsJson(Varien_Event_Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $items = $order->getAllItems();

        foreach ($items as $item) {
            $options = (array)$item->getProductOptions();

            $product = Mage::getModel('catalog/product')->load($item->getProduct()->getId());
            $isPersonalized = $product->getPersonalization();

            if($isPersonalized) {
                /*$options['photouploader'][0]['label'] = 'cps_photo_submission_id';
                $options['photouploader'][0]['value'] =  $item->getCpsPhotoSubmissionId();
                $options['photouploader'][1]['label'] = 'product_submission_image_url';
                $options['photouploader'][1]['value'] =  $item->getProductSubmissionImageUrl();
                $options['photouploader'][2]['label'] =  'image_name'; //'product_image_url';
                $options['photouploader'][2]['value'] =  $item->getProductImageUrl();
                $options['photouploader'][3]['label'] = 'cropped_image_url';
                $options['photouploader'][3]['value'] =  $item->getCroppedImageUrl();*/
                $options['photouploader'][0]['label'] = 'processed_image_name';
                $options['photouploader'][0]['value'] =  $order->getIncrementId().'_'.$item->getCpsPhotoSubmissionId().'_processed.jpg';
                $options['photouploader'][0]['label'] = 'cropped_image_name';
                $options['photouploader'][0]['value'] =  $order->getIncrementId().'_'.$item->getCpsPhotoSubmissionId().'_cropped.jpg';
            }

            Mage::log(($options), null, 'personalization.log');

            if (isset($options['attributes_info']) || isset($options['options']) || isset($options['photouploader']) ) {
                $converted = Mage::helper('zeon_productoptionsjson')->process($options);
                if ($converted) {
                    //Mage::log("Logging product option", null, 'logfile.log'); 
                    //to add product options as csv format
                    $csv_format = '';
                    $csv_format .= implode("~", array_values($converted))."\n";
    
                    $item->setProductOptionsCsv($csv_format);
                    $item->setProductOptionsJson(Mage::helper('core')->jsonEncode($converted))->save();
                    Mage::log($item->debug(), null, 'personalization.log');
                }
            }
        }

        return $this;
    }
}
