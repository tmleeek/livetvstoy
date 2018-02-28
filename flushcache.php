<?php
        //Following 3 lines will load the magento admin environment.
        require_once 'app/Mage.php';
        $app = Mage::app('admin');
        umask(0);

        // to reindex the processes we require their ids
        // for default magento there are 9 processes to reindex, numbered 1 to 9. 
        //$ids = array(1,2,3,4,5,6,7,8,9);

        // Sometimes there are processes from our custom modules that also require reindexing
        // We need to add those ids to our existing array of ids
        // To know the id of process, just hover on each process in your
        // admin panel-> System-> Index Management
        // You will get a url : admin/process/some_id/......
        // this id corresponds to the process
        $success = false;

        //enable Error Reporting
        error_reporting(E_ALL & ~E_NOTICE);
        Mage::setIsDeveloperMode(true);

        try
        {
                //CLEAN OVERALL CACHE
                flush();
                Mage::app()->cleanCache();
                // CLEAN IMAGE CACHE     
                flush();
                Mage::getModel('catalog/product_image')->clearCache();
                //print
                //print  'done';
                $success = true;
        }
        catch(Exception $e)
        {
                //something went wrong...
                print($e->getMessage());
        }

	if($success){
                $message = "Indexing and Caching is Complete. Please proceed with the testing.";
        $Name = "NoReply"; //senders name
        $email = "noreply@cpscompany.com"; //senders e-mail adress
        //$recipient = "bpunati@cpscompany.com"; //recipient
        $recipient = "admin_tasks_alerts@cpscompany.com"; //recipient
        $mail_body = $message; //"The text for the mail..."; //mail body
        $subject = "Admin task Notification"; //subject
        $header = "From: ". $Name . " <" . $email . ">\r\n" . //optional headerfields
                  "CC: renjith@vtrio.com \r\n";

        mail($recipient, $subject, $mail_body, $header); //mail command :)


        }


?>
