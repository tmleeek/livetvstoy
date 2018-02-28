<?php
class Zeon_Attributemapping_IndexController
    extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function viewAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function optionAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Method used to display the list of all products to select featured items.
     */
    public function cronAction()
    {
        Mage::getModel('zeon_attributemapping/Urlcron')->setAttributeUrls();
    }

    /**
     * Method used to display the list of all products to select featured items.
     */
    public function menuAction()
    {
        Mage::getModel('zeon_attributemapping/Menucron')->setSiteTopMenu();
    }

}