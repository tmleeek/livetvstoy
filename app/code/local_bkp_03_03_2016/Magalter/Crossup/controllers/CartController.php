<?php

require_once 'Mage' . DS . 'Checkout' . DS . 'controllers' . DS . 'CartController.php';

class Magalter_Crossup_CartController extends Mage_Checkout_CartController
{    
    
    public function addSelectedAction()
    {         
      if($this->getRequest()->isPost()) {
        $selected = $this->getRequest()->getParam('magalter-hidden-crossup', array());        
        if(!empty($selected)) {
            $selected = explode(',', $selected);
        }        
      }
      else {        
        $selected = (array) Mage::app()->getRequest()->getParam('product', array());  
      }      
      
        $product = array_shift($selected);
        
        Mage::app()->getRequest()->setParam('product', $product);
        Mage::app()->getRequest()->setParam('related_product', implode(',', $selected));

        $this->_forward('add');
    }

    protected function _goBack()
    {   
       // get url with fallback
       $redirectUrl = $this->getRequest()->getParam('magalter-redirect-url', Mage::getUrl('checkout/onepage'));
       
       $data = preg_split("#(\"|')#", $redirectUrl, -1, PREG_SPLIT_NO_EMPTY);
        
       foreach($data as $url) {           
           if(preg_match("#^http#is", $url)) {               
               $this->getResponse()->setRedirect($url);
           }           
       }
      
        return $this;
    }

}