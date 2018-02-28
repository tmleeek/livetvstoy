<?php

class Magalter_Crossup_Block_System_Config_Selector extends Mage_Adminhtml_Block_System_Config_Form_Field
{

    public function render(Varien_Data_Form_Element_Abstract $element)
    {       
        if (Mage::helper('magalter_crossup')->isEe()) {
            $element->setComment("
                    Predefined positions<br />
                    $$('.totals')[0]<br />
                    $$('.cart')[0]<br />
                    $('shopping-cart-table')<br />
                    Leave empty to insert block manually"
            );          
        } else {
            $element->setComment("
                    Predefined positions<br />
                    $$('.cart-collaterals')[0]<br />
                    $$('.cart')[0]<br />
                    $('shopping-cart-table')<br />
                    Leave empty to insert block manually"
            );           
        }
       
        return parent::render($element);
    }

}