<?php
  $pcdata=$_GET['pcdata'];
   require_once('/var/www/CPS/public_html/app/Mage.php'); //Path to Magento

umask(0);
Mage::app('default');
$resource = Mage::getSingleton('core/resource');
                $writeConnection = $resource->getConnection('core_write');
               
        
             $query_sel = "select `quote_item_id` from site_link_external_walmart_transactions where token_id=".$pcdata;
             $results = $writeConnection->fetchAll($query_sel);

      if(count($results)>0) {
       
     foreach($results as $row) {
      $id=$row['quote_item_id'];
   
     }
     
    }
    
    header('Location: http://ep10p.hsn.com/checkout/cart/configure/id/'.$id);    
      ?>