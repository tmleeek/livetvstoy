<?php

class Vtrio_OfflineOrder_Adminhtml_Sales_Order_CreateController extends Mage_Adminhtml_Controller_Action
{


    protected function _construct()
    {
        $this->setUsedModuleName('Mage_Sales');

        // During order creation in the backend admin has ability to add any products to order
        Mage::helper('catalog/product')->setSkipSaleableCheck(true);
    }

    protected function _getSession()
    {
        return Mage::getSingleton('adminhtml/session_quote');
    }

    public function cancelAction()
    {
        if ($orderId = $this->_getSession()->getReordered()) {
            $this->_getSession()->clear();
            $this->_redirect('*/sales_order/view', array(
                'order_id'=>$orderId
            ));
        } else {
            $this->_getSession()->clear();
            $this->_redirect('*/*');
        }

    }

	protected function _isAllowed(){
		 return true;
	 }

}
