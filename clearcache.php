 <?php
require_once 'app/Mage.php';
$app = Mage::app();

//echo "<pre>";
if($app != null) {
    //echo "The app was initialized.\n";
    $cache = $app->getCache();
    if($cache != null) {
        //echo "The cache is not empty. Clean it.\n";
        //$cache->clean();
        Mage::app()->cleanCache();
        $app->getCacheInstance()->flush(); 
        $message = "Indexing and Caching is Complete. Please proceed with the testing.";
        $Name = "NoReply"; //senders name
    	$email = "noreply@cpscompany.com"; //senders e-mail adress
        //$recipient = "bpunati@cpscompany.com"; //recipient
    	$recipient = "admin_tasks_alerts@cpscompany.com"; //recipient
        $mail_body = $message; //"The text for the mail..."; //mail body
    	$subject = "Admin task Notification"; //subject
        $header = "From: ". $Name . " <" . $email . ">\r\n" . //optional headerfields
              "CC: renjith@vtrio.com \r\n";

    	//mail($recipient, $subject, $mail_body, $header); //mail command :)

    }
}


?>
