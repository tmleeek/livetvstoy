<?php
   require_once('/var/www/CPS/public_html/app/Mage.php'); //Path to Magento

umask(0);
Mage::app('default');
$resource = Mage::getSingleton('core/resource');
                $writeConnection = $resource->getConnection('core_write');
                
         
             $query_sel = "select url_key from site_link_configure_products where partner_name='WALMART SITELINK'";
             $results = $writeConnection->fetchAll($query_sel);

      if(count($results)>0) {
       
     foreach($results as $row) {
    
   
   $url='http://personalizeditems-cps.walmart.com/'.$row['url_key'];
   /*$homepage = file_get_contents('http://personalizeditems-cps.walmart.com/17279048');
echo $homepage;exit;
echo file_get_contents('$url');exit;
$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	echo $data = curl_exec($ch);
	curl_close($ch);*/
   
   curl_download($url);
   
   
   
   exit;
   
     }
    }
    
function curl_download($Url){
  // is cURL installed yet?
  if (!function_exists('curl_init')){
    die('Sorry cURL is not installed!');
  }
 
  // OK cool - then let's create a new cURL resource handle
  $ch = curl_init();
 
  // Now set some options (most are optional)
 
  // Set URL to download
  curl_setopt($ch, CURLOPT_URL, $Url);
 
  // User agent
  curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
 
  // Include header in result? (0 = yes, 1 = no)
  curl_setopt($ch, CURLOPT_HEADER, 0);
 
  // Should cURL return or print out the data? (true = retu	rn, false = print)
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
  // Timeout in seconds
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
 
  // Download the given URL, and return output
  $output = curl_exec($ch);
 
  // Close the cURL resource, and free system resources
  curl_close($ch);
 
  return $output;
}
      ?>