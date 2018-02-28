<?php
    include_once "app/Mage.php";
    Mage::app('admin')->setCurrentStore(0);
    Mage::app('default');

    /**
     * Get the resource model
     */
    $resource = Mage::getSingleton('core/resource');

    /**
     * Retrieve the read connection
     */
    $readConnection = $resource->getConnection('core_read');

    /**
     * Get the table name
     */
    $table_ttb = $resource->getTableName('catalog_product_flat_1');
    $table_pbs = $resource->getTableName('catalog_product_flat_2');
    $table_ppt = $resource->getTableName('catalog_product_flat_4');
    $table_lmj = $resource->getTableName('catalog_product_flat_5');

    $query_ttb = 'SELECT sku  FROM ' . $table_ttb . ' WHERE tax_class_id = 0';
    $query_pbs = 'SELECT sku  FROM ' . $table_pbs . ' WHERE tax_class_id = 0';
    $query_ppt = 'SELECT sku  FROM ' . $table_ppt . ' WHERE tax_class_id = 0';
    $query_lmj = 'SELECT sku  FROM ' . $table_lmj . ' WHERE tax_class_id = 0';



    $result_ttb = $readConnection->query($query_ttb);
    $result_pbs = $readConnection->query($query_pbs);
    $result_ppt = $readConnection->query($query_ppt);
    $result_lmj = $readConnection->query($query_lmj);

    /**
     * Execute the query and store the results in $results
     */
    $results_ttb = $result_ttb->fetchAll();
    $results_pbs = $result_pbs->fetchAll();
    $results_ppt = $result_ppt->fetchAll();
    $results_lmj = $result_lmj->fetchAll();



   $message = "There are some SKUs, which are configured as Non-Taxable</br></br>";

    $has_nontax = false; 

    $ttb_skus = '';
   if (count($results_ttb) > 0) {
        $ttb_skus = "Tystoybox skus:</br>";   
        foreach($results_ttb as $ttb_sku) {
                $ttb_skus .= $ttb_sku['sku']."</br>";
        }

         $has_nontax = true;

    }

    $pbs_skus = '';
    if (count($results_pbs) > 0) {

            $pbs_skus = "PBS Kids skus:</br>";   
            foreach($results_pbs as $pbs_sku) {
                $pbs_skus .= $pbs_sku['sku']."</br>";
            }

             $has_nontax = true;
 
    }


    $ppt_skus = '';
    if (count($results_ppt) > 0) {

            $ppt_skus = "Personalized planet skus:</br>";   
            foreach($results_ppt as $ppt_sku) {
                $ppt_skus .= $ppt_sku['sku']."</br>";
            }

             $has_nontax = true;

    }



    $lmj_skus = '';
    if (count($results_lmj) > 0) {

            $lmj_skus = "Limoges skus:</br>";   
            foreach($results_lmj as $lmj_sku) {
                $lmj_skus .= $lmj_sku['sku']."</br>";
            }
             $has_nontax = true;
 

    }

    if ($has_nontax) {

            $message .= $ttb_skus . $pbs_skus . $ppt_skus . $lmj_skus;


    $header  = 'MIME-Version: 1.0' . "\r\n";
    $header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

    $Name = "CPS Magento";
    $email = "tax@cpsmagento.com"; //senders e-mail adress
    $recipient = "bpunati@cpscompany.com"; //recipient
    $mail_body = $message; //"The text for the mail..."; //mail body
    $subject = "Non Taxable products"; //subject
    $header .= "From: ". $Name . " <" . $email . ">\r\n" . //optional headerfields
              "CC: afsar@vtrio.com \r\n" ;

    mail($recipient, $subject, $mail_body, $header); //mail command :)
        }
?>

