<?php

class Magebird_CheckoutPersistence_Model_Storagetype
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'1', 'label'=>Mage::helper('checkoutpersistence')->__('Cookie')),            
            array('value'=>'2', 'label'=>Mage::helper('checkoutpersistence')->__('Session')),                                   
        );
    }

}