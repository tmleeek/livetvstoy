<?php
/**
 * Magebird.com
 *
 * @category   Magebird
 * @package    Magebird_CheckoutPersistence
 * @copyright  Copyright (c) 2014 Magebird (http://www.Magebird.com)
 * @license    http://www.magebird.com/licence
 * Any form of ditribution, sell, transfer forbidden see licence above 
 */
class Magebird_CheckoutPersistence_IndexController extends Mage_Core_Controller_Front_Action {        
    public function indexAction() {
    
    }      
    
    public function saveAction() {
      $storageType = Mage::getStoreConfig('checkout_persistence/general/storage_type');
      if($storageType==1){
        $cookie = Mage::getSingleton('core/cookie');
        $cookieLifetime = Mage::getStoreConfig('checkout_persistence/general/storage_type')>0 ? Mage::getStoreConfig('checkout_persistence/general/storage_type') : 365;
        $cookie->set($_POST['fieldId'], $_POST['value'],60*60*24*$cookieLifetime,'/');
      }else{
        $session = Mage::getSingleton("core/session",  array("name"=>"frontend"));
        $session->setData($_POST['fieldId'], $_POST['value']); 
      }          
    }     
  
}