<?php
class Vtrio_OfflineOrder_Model_Observer
{	
	public function sales_order_place_after($observer)
	{
			$admin_user_session = Mage::getSingleton('admin/session');
			$adminuserId = $admin_user_session->getUser()->getUserId();
			$role_data = Mage::getModel('admin/user')->load($adminuserId)->getRole()->getData();
			$role_id = $role_data['role_id'];
			if ($role_id == Vtrio_OfflineOrder_Helper_Data::OfflineOrder_roleId){
				$order_id = $observer->getEvent()->getOrder()->getId();	 	
				$order = $observer->getOrder();
				$order->setState('processing', true);
				$order->setStatus('pending_fulfillment', true);
				$order->save();
			}			
	}

	public function offlineuserRedirect($observer)
	{
			$admin_user_session = Mage::getSingleton('admin/session');
			$adminuserId = $admin_user_session->getUser()->getUserId();
			$role_data = Mage::getModel('admin/user')->load($adminuserId)->getRole()->getData();
			$role_id = $role_data['role_id'];

			if ($role_id == Vtrio_OfflineOrder_Helper_Data::OfflineOrder_roleId){
		     /*$response = Mage::app()->getResponse();
		     $response->clearHeaders()
		                     ->setRedirect(Mage::helper("adminhtml")->getUrl('adminhtml/order'))
		                     ->sendHeadersAndExit();*/
				//header('Location: http://test.tystoybox.com/index.php/zpanel/sales_order/index');

 				$link = "http://$_SERVER[HTTP_HOST]/index.php/zpanel/sales_order/index";
				header("Location: $link");
				exit();
				//Mage::app()->getResponse()->setRedirect(Mage::helper('adminhtml')->getUrl("adminhtml/sales_order/index"));
			}
		 	return $this;
	}

	public function offlineUserEditOrderButtonDisable($observer) {
        $block = $observer->getBlock();
        if (!isset($block)) {
            return $this;
        }
			
        $_type = $block->getType();
        if ($_type == 'adminhtml/sales_order_view') {

			   $order_id = (int) $block->getRequest()->getParam('order_id'); 
			   $order = Mage::getModel("sales/order")->load($order_id); 
				$payment_method_code = $order->getPayment()->getMethodInstance()->getCode();

				$admin_user_session = Mage::getSingleton('admin/session');
				$adminuserId = $admin_user_session->getUser()->getUserId();
				$role_data = Mage::getModel('admin/user')->load($adminuserId)->getRole()->getData();
				$role_id = $role_data['role_id'];
				
				if ($role_id == Vtrio_OfflineOrder_Helper_Data::OfflineOrder_roleId){
					if($payment_method_code != Vtrio_OfflineOrder_Helper_Data::OfflineOrder_orderType){
						 $block->removeButton('order_edit');   
					}                      
				}
        }
        if ($_type == 'adminhtml/sales_order') {

				$admin_user_session = Mage::getSingleton('admin/session');
				$adminuserId = $admin_user_session->getUser()->getUserId();
				$role_data = Mage::getModel('admin/user')->load($adminuserId)->getRole()->getData();
				$role_id = $role_data['role_id'];

				if ($role_id == Vtrio_OfflineOrder_Helper_Data::OfflineOrder_roleId){
					//$block->removeButton('add');   
				}
				
        }			
    }

}
?>
