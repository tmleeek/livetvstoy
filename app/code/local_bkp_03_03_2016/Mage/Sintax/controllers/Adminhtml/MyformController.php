<?php

class Mage_Sintax_Adminhtml_MyformController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout()->renderLayout();
    }
    
    public function postAction()
    {
        $post = $this->getRequest()->getPost();
        
        try {
            if (empty($post)) {
                Mage::throwException($this->__('Invalid form data.'));
            }
         $roleId = $post['roleId'];
         $rate = $post['rate'];
           $store_id = $post['store_id'];
           $country_id=$post['country_id'];
           $dtype=$post['dtype'];
           $resource = Mage::getSingleton('core/resource');
          $writeConnection = $resource->getConnection('core_write');
          if($country_id=='US')
          {
             $query_sel = "SELECT *  FROM flat_shipping_matrixrate WHERE rule_id = '$roleId' and website_id='$store_id' and delivery_type='$dtype'";
             $results = $writeConnection->fetchAll($query_sel);

                if(count($results)==0)
                {
                            $query = "INSERT INTO `flat_shipping_matrixrate` (`website_id`, `rule_id`, `dest_country_id`, `dest_region_id`, `dest_city`, `dest_zip`, `dest_zip_to`, `condition_name`, `condition_from_value`, `condition_to_value`, `price`, `cost`, `delivery_type`) VALUES($store_id, $roleId, 'US', 0, '', '', '', 'package_value', 0.0000, 9999.0000, $rate, 0.0000, '$dtype')";         
                }
                else
                $query = "update flat_shipping_matrixrate set price='$rate' WHERE rule_id = '$roleId' and website_id='$store_id' and delivery_type='$dtype'";
          }
          else
          {
             $query_sel = "SELECT *  FROM flat_shipping_matrixrate WHERE rule_id = '$roleId' and website_id='$store_id' and delivery_type='Canada Standard'";
             $results = $writeConnection->fetchAll($query_sel);

            if(count($results)==0)
            {
            $query = "INSERT INTO `flat_shipping_matrixrate` (`website_id`, `rule_id`, `dest_country_id`, `dest_region_id`, `dest_city`, `dest_zip`, `dest_zip_to`, `condition_name`, `condition_from_value`, `condition_to_value`, `price`, `cost`, `delivery_type`) VALUES
    ($store_id, $roleId, 'CA', 0, '', '', '', 'package_value', 0.0000, 9999.0000, $rate, 0.0000, 'Canada Standard')";         
            }
            else
            $query = "update flat_shipping_matrixrate set price='$rate' WHERE rule_id = '$roleId' and website_id='$store_id' and delivery_type='Canada Standard'";
          }
          
        if($writeConnection->query($query))
        {
             $message = $this->__('Your Rule has been submitted successfully.');
            Mage::getSingleton('adminhtml/session')->addSuccess($message);
        }
        else
        {
             $message = $this->__('Error.');
            Mage::getSingleton('adminhtml/session')->addError($message);
        }
       
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        $this->_redirect('*/*');
    }
}/*
   $eId = $post->getParam('eid');
       $oId = $post->getParam('oid');
       $resource = Mage::getSingleton('core/resource');
      $writeConnection = $resource->getConnection('core_write');
            if($wId=='')
            {
                 $query_del = "delete FROM vtrio_warehouse_details WHERE order_id = '$oId' and entity_id='$eId'";
                 if($writeConnection->query($query_del))
                echo "success";
                else
                echo "error";
            }
            else
            {
            $query_sel = "SELECT *  FROM vtrio_warehouse_details WHERE 	order_id = '$oId' and entity_id='$eId'";
            $results = $writeConnection->fetchAll($query_sel);
            
	if(count($results)==0)
        $query = "INSERT INTO vtrio_warehouse_details(order_id,entity_id,warehouse_id) values($oId,$eId,$wId)";
        else
        $query = "update vtrio_warehouse_details set warehouse_id=$wId WHERE order_id = '$oId' and entity_id='$eId'";
        if($writeConnection->query($query))
        echo "success";
        else
        echo "error";*/
?>