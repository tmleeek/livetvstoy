<?php

require 'app/Mage.php';
Mage::app();

 $resource = Mage::getSingleton('core/resource');
 $writeConnection = $resource->getConnection('core_write');


  $query_sel = "SELECT cps_sku,long_description  FROM site_link_configure_products";
  $results = $writeConnection->fetchAll($query_sel);
  $data = array($results[0]);
  print_r($data);

?>