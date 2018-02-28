<?php
require_once('/var/www/CPS/public_html/app/Mage.php'); //Path to Magento

umask(0);

Mage::app();

$resource = Mage::getSingleton('core/resource');
$readConnection = $resource->getConnection('core_read');

$remote_filename = $argv[1];

if ($remote_filename ==''){
        print "You must specify a valid File name. For eg: 'ns_update.php my_file.csv' \n\n";
        exit;
}


$filetype = explode(".", $remote_filename);
if(strtolower(end($filetype)) != 'csv'){
        print "You must specify a valid csv File name !  \n\n";
        exit;

}


$ftp_server = "50.200.52.67";
$ftp_user_name = "nsintegrator";
$ftp_user_pass = "CPSmbm1556";


$remote_file = "/Walmart/orders/Input/$remote_filename";
$local_file = 'Walmart_order_'.date('Ymd_his').'.csv';

// open some file to write to
$local_handle = fopen($local_file, 'w');

// set up basic connection
$conn_id = ftp_connect($ftp_server);

// login with username and password
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);


// try to download $remote_file and save it to $handle
if (ftp_fget($conn_id, $local_handle, $remote_file, FTP_ASCII, 0)) {
        print  "Successfully written to $local_file .\n";
} else {
        print "There was a problem while downloading $remote_file to $local_file. Please make sure $remote_file exist in FTP Server\n";
        exit;
}

fclose($local_handle);



// Open the output csv file
$output_file = 'Walmart_order_NS'.date('Ymd_his').'.csv';
$outputfp = fopen($output_file, 'w');
// Read from the input csv file
if (($inputfp = fopen($local_file, "r")) !== FALSE) {
        $i=0;

    while (($data = fgetcsv($inputfp, 1000, ",")) !== FALSE) {
                //print_r($data);
        // Declare an array which will be used to hoe the new csv line
        $line = array();

        if($i != 0){

                $token_id = $data[0];

                $query = "SELECT slewt.offer_id, eaov.value, slewt.raw_pers_string FROM site_link_external_walmart_transactions AS slewt LEFT JOIN site_link_configure_products AS slcp ON slewt.cps_sku = slcp.cps_sku LEFT JOIN eav_attribute_option_value AS eaov ON slewt.extpartno = eaov.option_id WHERE slewt.token_id = $token_id";

                 $results = $readConnection->fetchAll($query);
                 //print_r($results);
        }
        // Add the data from the read csv file to the output array
        $line[]=$data[0];
        $line[]=$data[1];
        $line[]=$data[2];
        $line[]=$data[3];
        $line[]=$data[4];

        if ($i == 0)
                $line[] = $data[5];
        else 
                $line[] = $results[0]['offer_id'];

        $line[]=$data[6];
        $line[]=$data[7];
        $line[]=$data[8];

        if ($i == 0)
                $line[] = $data[9];
        else
                $line[] = $results[0]['value'];

        $line[]=$data[10];
        $line[]=$data[11];
        $line[]=$data[12];
        $line[]=$data[13];
        $line[]=$data[14];
        $line[]=$data[15];
        $line[]=$data[16];
        $line[]=$data[17];
        $line[]=$data[18];
        $line[]=$data[19];
        $line[]=$data[20];
        $line[]=$data[21];
        $line[]=$data[22];
        $line[]=$data[23];
        $line[]=$data[24];
        $line[]=$data[25];
        $line[]=$data[26];
        $line[]=$data[27];
        $line[]=$data[28];
        $line[]=$data[29];
        $line[]=$data[30];
        $line[]=$data[31];
        $line[]=$data[32];
        $line[]=$data[33];
        $line[]=$data[34];
        $line[]=$data[35];
        $line[]=$data[36];
        $line[]=$data[37];

        if ($i == 0)
                $line[] = $data[38];
        else
                $line[] = $results[0]['raw_pers_string'];

        $line[]=$data[39];
        $line[]=$data[40];
        $line[]=$data[41];
        $line[]=$data[42];
        $line[]=$data[43];
        $line[]=$data[44];
        $line[]=$data[45];
        $line[]=$data[46];
        $line[]=$data[47];
        
         
        // Write the line to the file
        fputcsv($outputfp, $line);
        $i++;
    }    
    // Clean up
    fclose($inputfp);
    fclose($outputfp);
}


$file_to_load = $output_file;

// upload file
if (ftp_put($conn_id, "/Walmart/orders/Output/$file_to_load", $file_to_load, FTP_ASCII))
  {
        print "Successfully uploaded $file_to_load to FTP server..\n";
        print "Done !!! \n\n";
  }
else
  {
  echo "Error uploading $file_to_load.";
exit;
  }

// close connection
ftp_close($conn_id);
