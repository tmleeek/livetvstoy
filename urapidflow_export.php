<?php

    // initialize Magento environment
    include_once "app/Mage.php";
    Mage::app('admin')->setCurrentStore(0);
    Mage::app('default');

    $helper = Mage::helper('urapidflow');


    $coreResource = Mage::getSingleton('core/resource');

    /** @var $conn Varien_Db_Adapter_Pdo_Mysql */
    $connection = $coreResource->getConnection('core_write'); 

    $filename = 'Product_Export_'.date('l').'.csv';
    $connection->query("UPDATE urapidflow_profile SET filename = '$filename' WHERE profile_id=43"); 

    $helper->run(43);


    $profile = $helper->getProfile(43);


    if ($profile->run_status == "finished"){

        $profile_name = $profile->title;
	$server_ip = exec("hostname"); 

	//$profile_start = $profile->started_at;
	//$profile_finish = $profile->finished_at;	
        $profile_start = date('Y-m-d h:i:s A', strtotime($profile->started_at) - 60 * 60 * 5);
        $profile_finish = date('Y-m-d h:i:s A', strtotime($profile->finished_at) - 60 * 60 * 5);

	$message = "Product Export began at $profile_start and ceased at $profile_finish. For more information visit https://admin.tystoybox.com/index.php/urapidflowadmin/adminhtml_profile/edit/id/43";
        $Name = "Admin"; //senders name
        $email = "admin@tystoybox.com"; //senders e-mail adress
        $recipient = "bpunati@cpscompany.com"; //recipient
	//$recipient = "afsar@vtrio.com"; //recipient
        $mail_body = $message; //"The text for the mail..."; //mail body
        $subject = "$profile_name Notification"; //subject
        $header = "From: ". $Name . " <" . $email . ">\r\n" . //optional headerfields
		  "CC: abhilash@vtrio.com \r\n"; 
	
        mail($recipient, $subject, $mail_body, $header); //mail command :) 
    }


