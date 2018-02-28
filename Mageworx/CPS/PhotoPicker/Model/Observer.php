<?php
    class CPS_PhotoPicker_Model_Observer {
        const FLAG_SHOW_CONFIG = 'showConfig';
        const FLAG_SHOW_CONFIG_FORMAT = 'showConfigFormat';

        private $request;

        public function checkForConfigRequest($observer) {
            Mage::log('checkForConfigRequest', null, 'cps.log');
            $this->request = $observer->getEvent()->getData('front')->getRequest();
            if($this->request->{self::FLAG_SHOW_CONFIG} === 'true'){
                $this->setHeader();
                $this->outputConfig();
            }
        }

        public function checkoutCartProductAddAfter($observer) 
        {
          try {

            $item = $observer->getEvent()->getQuoteItem();
            $product = Mage::getModel('catalog/product')->load($item->getProduct()->getId());
            $isPersonalized = $product->getPersonalization();

            if($isPersonalized) {
              $fieldVal = Mage::app()->getRequest()->getParams();
              Mage::log(print_r($fieldVal,1), null, 'cps.log');
              $fieldVal['product_submission_image_url'];
              $fieldVal['product_image_url'];
              if(!isset($fieldVal['photo_submission_id'])) {
                $fieldVal['photo_submission_id'] = 1;
              }
              if(!isset($fieldVal['product_submission_image_url'])) {
                $fieldVal['product_submission_image_url'] = 1;
              }
              if(!isset($fieldVal['product_image_url'])) {
                $fieldVal['product_image_url'] = 1;
              }
              if(!isset($fieldVal['cropped_image'])) {
                $fieldVal['cropped_image'] = 1;
              }
              $item->setData('cps_photo_submission_id', $fieldVal['photo_submission_id']);
              $item->setData('product_submission_image_url', $fieldVal['product_submission_image_url']);
              $item->setData('product_image_url', $fieldVal['product_image_url']);
              $item->setData('cropped_image_url', $fieldVal['cropped_image']);
              $item->setData('background_cropped_url', $fieldVal['background_cropped_url']);
            }
            return $this;

          } catch (Mage_Core_Exception $e) {
            Mage::log("checkoutCartProductAddAfter Error ".$e->getMessage(), null, 'photopicker_orders_'.date("j.n.Y").'.log');
          } catch (Exception $e) {
            Mage::log("checkoutCartProductAddAfter Error ".$e->getMessage(), null, 'photopicker_orders_'.date("j.n.Y").'.log');
          }
         
        }

        private function setHeader() {
            $format = isset($this->request->{self::FLAG_SHOW_CONFIG_FORMAT}) ?
            $this->request->{self::FLAG_SHOW_CONFIG_FORMAT} : 'xml';
            switch($format){
                case 'text':
                    header("Content-Type: text/plain");
                    break;
                default:
                    header("Content-Type: text/xml");
            }
        }

        private function outputConfig() {
            die(Mage::app()->getConfig()->getNode()->asXML());
        }

        public function addProductUrl($observer)
        { 
          try {
            $order = $observer->getEvent()->getOrder();

            $quote = Mage::getSingleton('checkout/session')->getQuote();

            $cartItems = $quote->getAllVisibleItems();
            $orderItems = $order->getAllItems();

            foreach ($cartItems as $citem) {

              if($citem->getProductImageUrl()) {
                
                Mage::log("Quote Id ".$quote->getId(), null, 'photopicker_orders_'.date("j.n.Y").'.log');
                Mage::log("Order Id ".$order->getId(), null, 'photopicker_orders_'.date("j.n.Y").'.log');
                
                Mage::log("Quote Session getProductId ".$citem->getProductId() , null, 'photopicker_orders_'.date("j.n.Y").'.log');
                Mage::log("Quote Session getProductImageUrl ". $citem->getProductImageUrl(), null, 'photopicker_orders_'.date("j.n.Y").'.log');
                Mage::log("Quote Session getProductSubmissionImageUrl " . $citem->getProductSubmissionImageUrl() , null, 'photopicker_orders_'.date("j.n.Y").'.log');
                Mage::log("Quote Session getCpsPhotoSubmissionId " .$citem->getCpsPhotoSubmissionId() , null, 'photopicker_orders_'.date("j.n.Y").'.log');
                Mage::log("Quote Session getCroppedImageUrl " .$citem->getCroppedImageUrl() , null, 'photopicker_orders_'.date("j.n.Y").'.log');
                Mage::log("Quote Session getBackgroundCroppedUrl " .$citem->getBackgroundCroppedUrl() , null, 'photopicker_orders_'.date("j.n.Y").'.log');

                foreach ($orderItems as $key => $oItem) {
                  if($oItem->getProductId() == $citem->getProductId()) {

                    $oItem->setProductImageUrl($citem->getProductImageUrl());
                    $oItem->setProductSubmissionImageUrl($citem->getProductSubmissionImageUrl());
                    $oItem->setCpsPhotoSubmissionId($citem->getCpsPhotoSubmissionId());
                    $oItem->setCroppedImageUrl($citem->getCroppedImageUrl());
                    $oItem->setBackgroundCroppedUrl($citem->getBackgroundCroppedUrl());
                    $oItem->save();

                  }
                }

              }

            }

          } catch (Mage_Core_Exception $e) {
            Mage::log("addProductUrl Error ".$e->getMessage(), null, 'photopicker_orders_'.date("j.n.Y").'.log');
          } catch (Exception $e) {
            Mage::log("addProductUrl Error ".$e->getMessage(), null, 'photopicker_orders_'.date("j.n.Y").'.log');
          }

        }

}