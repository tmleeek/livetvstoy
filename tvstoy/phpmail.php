 <?php

$to = "testvto@gmail.com";

$subject = "Test mail";

$body = "Test mail sent using php script";

if(mail($to,$subject,$body)){

echo "Test mail sent to $to ...!!!\n";

echo "Subject = $subject\n";

echo "Body = $body\n";

}

else

echo "ERROR: Mail not sent...!!!";

?>
