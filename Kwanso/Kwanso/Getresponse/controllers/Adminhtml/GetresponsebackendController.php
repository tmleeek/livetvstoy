<?php
class Kwanso_Getresponse_Adminhtml_GetresponsebackendController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
    {

    	$getResponse = Mage::getModel('getresponse/cron')->importContacts();



        $this->loadLayout();
	    $this->_title($this->__("Campaings"));
	    $this->renderLayout();
    }
}