<?php

    // initialize Magento environment
    include_once "app/Mage.php";
    Mage::app('admin')->setCurrentStore(0);

    $helper = Mage::helper('urapidflow');



    $helper->run(48);


    $profile = $helper->getProfile(48);


    if ($profile->run_status == "finished"){


	$server_ip = exec("hostname"); 

	//$profile_start = $profile->started_at;
	//$profile_finish = $profile->finished_at;	
        $profile_start = date('Y-m-d h:i:s A', strtotime($profile->started_at) - 60 * 60 * 5);
        $profile_finish = date('Y-m-d h:i:s A', strtotime($profile->finished_at) - 60 * 60 * 5);


	$message = "Channel Advisor Product Export began at $profile_start and ceased at $profile_finish. For more information visit https://admin.tystoybox.com/index.php/urapidflowadmin/adminhtml_profile/edit/id/48";
        $Name = "Admin"; //senders name
        $email = "admin@tystoybox.com"; //senders e-mail adress
        //$recipient = "bpunati@cpscompany.com"; //recipient
	$recipient = "Mschroeder@cpscompany.com"; //recipient
        $mail_body = $message; //"The text for the mail..."; //mail body
        $subject = "Channel Advisor Product Export Notification"; //subject
        $header = "From: ". $Name . " <" . $email . ">\r\n" . //optional headerfields
		  "CC: bpunati@cpscompany.com \r\n";


        mail($recipient, $subject, $mail_body, $header); //mail command :) 
    }


