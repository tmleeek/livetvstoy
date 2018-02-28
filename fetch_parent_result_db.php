<?php
require 'app/Mage.php';
Mage::app();
ini_set('memory_limit', '1G');
$resource_sel = Mage::getSingleton('core/resource');
$readConnection = $resource_sel->getConnection('core_read');
$writeConnection = $resource_sel->getConnection('core_write');
$path = Mage::getBaseDir('media') . DS ;
   
        $file_to_write = $path."/sitelink/sitelink-product-export-bulk.csv";
        

        $out = fopen($file_to_write,'w');
        if($out) {
          chmod($file_to_write,0777);
        }
        
        $header = array('external_productid',
                        'cps_sku',
                        'offer_id',
                        'partnername',
                        'modelno',
                        'productname',
                        'introdate',
                        'canceldate',
                        'shortdescription',
                        'longdescription',
                        'retailprice',
                        'saleprice',
                        'top_custom_field',
                        'top_custom_field_text',
                        'top_custom_field_url',
                        'bottom_custom_field',
                        'bottom_custom_field_text',
                        'bottom_custom_field_url',
                        'url_key'
                      );
        importProducts($header, $out);
$array=Array(57117,57118,57119,58599,58252,51473,51472,61327,59773,51618,59770,59764,61526,61525,61325,63457,61326,62913,51680,62905,62911,62912,63975,64006,64007,64003,64799,64791,64645,64313,64310,64652,64653,64654,64642,64650,64646,64648,64647,64649,60229,60230);
for ($i = 0; $i < count($array); $i++) {
    //echo $array[$i]."</br>";exit;

        
      
        $query_sel_dup = "select * from site_link_configure_products where cps_sku='$array[$i]' and partner_name='WALMART SITELINK'";
            $results_dup = $writeConnection->fetchAll($query_sel_dup);
            
            $writeData=array();
            //echo $sku[0]."</br>";
           
                $writeData[]=$results_dup[0]['part_no'];
                $writeData[]=$array[$i];
                $writeData[]=$results_dup[0]['product_id'];
                $writeData[]='WALMART SITELINK';
                $writeData[]='';
                $writeData[]=$results_dup[0]['product_name'];
                $writeData[]='8/17/2017';
                $writeData[]='';
                $writeData[]='';
                $writeData[]='';
                $writeData[]='';
                $writeData[]='';
                $writeData[]='';
                $writeData[]='';
                $writeData[]='';
                $writeData[]='';
                $writeData[]='';
                $writeData[]='';
                $writeData[]=$results_dup[0]['part_no'];
                importProducts($writeData, $out);
               
           
           

           
         
         
       
       
}
echo $i;
 fclose($out);
          fclose($handle_raku);
     function importProducts($data, $fh)
  {
    
    if($fh){
      fputcsv($fh, $data);
    }
  }

  ?>