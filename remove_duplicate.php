<?php
   require_once('/var/www/CPS/public_html/app/Mage.php'); //Path to Magento

umask(0);
Mage::app('default');
$resource = Mage::getSingleton('core/resource');
                $writeConnection = $resource->getConnection('core_write');
               
         $n=0;
             $query_sel = "select `url_key`, count(*) from site_link_configure_products group by `url_key` having count(*) > 1";
             $results = $writeConnection->fetchAll($query_sel);

      if(count($results)>0) {
       
     foreach($results as $row) {
      $n++;
      
         $query_sel_entity = "select entity_id from site_link_configure_products where `url_key`='".$row['url_key']."' limit 1,100";
             $results_entity = $writeConnection->fetchAll($query_sel_entity);
             foreach($results_entity as $row1) {
                 $query_del = "delete from site_link_configure_products where entity_id=".$row1['entity_id'];
               $writeConnection->query($query_del);
               
                           
               
               
             }
   
     }
     echo $n;
    }
      ?>