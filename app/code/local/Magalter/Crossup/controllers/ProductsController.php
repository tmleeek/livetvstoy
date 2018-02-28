<?php

class Magalter_Crossup_ProductsController extends Mage_Core_Controller_Front_Action
{
    public function upsellsAction()
    {
        $this->loadLayout();

        $this->getResponse()->setBody(
                $this->getLayout()->getBlock('magalter.crossup.products')
                        ->setTemplate('magalter_crossup/products.phtml')
                        ->toHtml());
    }
}