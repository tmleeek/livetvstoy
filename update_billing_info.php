<?php
require_once('app/Mage.php'); //Path to Magento
umask(0);
Mage::app();

$resource_sel = Mage::getSingleton('core/resource');
$readConnection = $resource_sel->getConnection('core_read');
$writeConnection = $resource_sel->getConnection('core_write');


$send_email = false;
$message = "";
$emailmessage = "";

        $query_email_sel = "SELECT entity_id FROM sales_flat_order WHERE customer_email IS NULL OR customer_email=''";
        $email_results = $readConnection->fetchAll($query_email_sel);
        foreach($email_results as $ro) {
            $orders_mis_email[] = $ro['entity_id'];                                                                                                                                               
        }

        //print_r($orders_mis_email);

        $query_fname_sel = "SELECT entity_id FROM sales_flat_order WHERE customer_firstname IS NULL OR customer_firstname=''";
        $fname_results = $readConnection->fetchAll($query_fname_sel);
        foreach($fname_results as $row) {
            $orders_mis_fname[] = $row['entity_id'];                                                                                                                                              
        }

        //print_r($orders_mis_fname);

        $query_lname_sel = "SELECT entity_id FROM sales_flat_order WHERE customer_lastname IS NULL OR customer_lastname=''";
        $lname_results = $readConnection->fetchAll($query_lname_sel);
        foreach($lname_results as $rows) {
            $orders_mis_lname[] = $rows['entity_id'];                                                                                                                                             
        }

        //print_r($orders_mis_lname);

        if (count($orders_mis_email) > 0) {

                foreach ($orders_mis_email as $order) {
                        $query_fetch_email = "SELECT email FROM sales_flat_order_address WHERE parent_id = $order";
                        $email_result = $readConnection->fetchAll($query_fetch_email);



                        foreach($email_result as $mailresu) {

                        }

                        $cust_email = $mailresu['email'];

                        $writeConnection->query("UPDATE sales_flat_order SET customer_email='$cust_email' WHERE entity_id=$order");
                }
        }                                                                                                                                                                                

        if (count($orders_mis_fname) > 0) {

                foreach ($orders_mis_fname as $order) {
                        $query_fetch_fname = "SELECT firstname FROM sales_flat_order_address WHERE parent_id = $order";
                        $fname_result = $readConnection->fetchAll($query_fetch_fname);


                         foreach($fname_result as $fnameresu) {

                        }


                        $cust_fname = $fnameresu['firstname'];

                        $writeConnection->query("UPDATE sales_flat_order SET customer_firstname='$cust_fname' WHERE entity_id=$order");
                }
        }                                                                                                                                        

        if (count($orders_mis_lname) > 0) {

                foreach ($orders_mis_lname as $order) {
                        $query_fetch_lname = "SELECT lastname FROM sales_flat_order_address WHERE parent_id = $order";
                        $lname_result = $readConnection->fetchAll($query_fetch_lname);
        
                        foreach($lname_result as $lnameresu) {

                        }


                        $cust_lname = $lnameresu['lastname'];

                        $writeConnection->query("UPDATE sales_flat_order SET customer_lastname='$cust_lname' WHERE entity_id=$order");
                }
        }                                                                                                                                        
?>
