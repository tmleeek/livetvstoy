<?php

$link = mysql_connect('10.0.0.29', 'tystoybox', 'noez@2014');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}

$to_be_sent = false;

mysql_select_db('tvstoybox', $link) or die('Could not select database.');

//$from_date = date('Y-m-d H:i:s', strtotime("now") + 60 * 60 * 2);
$from_date = date('Y-m-d H:i:s', strtotime("now") + 60 * 60 * 3);

//$to_date = date('Y-m-d H:i:s', strtotime("now") + 60 * 60 * 4);
$to_date = date('Y-m-d H:i:s', strtotime("now") + 60 * 60 * 5);


$tvs_result = mysql_query("SELECT COUNT(*) AS total FROM sales_flat_order WHERE store_id = 1 AND created_at BETWEEN '$from_date' AND '$to_date'");
$tvs_data = mysql_fetch_assoc($tvs_result);


$Name = "No Reply"; //senders name
$email = "noreply@cpscompany.com"; //senders e-mail adress
$header = "From: ". $Name . " <" . $email . ">\r\n" ; //optional headerfields

if ($tvs_data['total'] < 1){
        $period_from = date('Y-m-d H:i:s', strtotime("now") - 60 * 60 * 3);
        $period_to =  date('Y-m-d H:i:s', strtotime("now") - 60 * 60 );


        $tvs_message = "TVSTOYBOX had no sales in the last two hour between $period_from to $period_to";

        $to_be_sent = true;
        $recipient = "ttb_zero_sales@cpscompany.com";
        $mail_body = $tvs_message;
        $subject = "TVS Zero dollar Sales Alert!"; //subject

        mail($recipient, $subject, $mail_body, $header); //mail command :)
}


$pbs_result = mysql_query("SELECT COUNT(*) AS total FROM sales_flat_order WHERE store_id = 2 AND created_at BETWEEN '$from_date' AND '$to_date'");
$pbs_data = mysql_fetch_assoc($pbs_result);

if ($pbs_data['total'] < 1){
        $period_from = date('Y-m-d H:i:s', strtotime("now") - 60 * 60 * 3);
        $period_to =  date('Y-m-d H:i:s', strtotime("now") - 60 * 60);


        $pbs_message = "\nPBSKIDS had no sales in the last two hour between $period_from to $period_to";

        $to_be_sent = true;
        $recipient = "pbs_zero_sales@cpscompany.com";
        $mail_body = $pbs_message;
        $subject = "PBS Zero dollar Sales Alert!"; //subject

        mail($recipient, $subject, $mail_body, $header); //mail command :)
}


$pp_result = mysql_query("SELECT COUNT(*) AS total FROM sales_flat_order WHERE store_id = 4 AND created_at BETWEEN '$from_date' AND '$to_date'");
$pp_data = mysql_fetch_assoc($pp_result);

if ($pp_data['total'] < 1){
        $period_from = date('Y-m-d H:i:s', strtotime("now") - 60 * 60 * 3);
        $period_to =  date('Y-m-d H:i:s', strtotime("now") - 60 * 60);


        $pp_message = "\nPERSONALIZEDPLANET had no sales in the last two hour between $period_from to $period_to";

        $to_be_sent = true;
        $recipient = "pp_zero_sales@cpscompany.com";
        $mail_body = $pp_message;
        $subject = "PP Zero dollar Sales Alert!"; //subject

        mail($recipient, $subject, $mail_body, $header); //mail command :)
}

$lj_result = mysql_query("SELECT COUNT(*) AS total FROM sales_flat_order WHERE store_id = 5 AND created_at BETWEEN '$from_date' AND '$to_date'");
$lj_data = mysql_fetch_assoc($lj_result);

if ($lj_data['total'] < 1){
        $period_from = date('Y-m-d H:i:s', strtotime("now") - 60 * 60 * 3);
        $period_to =  date('Y-m-d H:i:s', strtotime("now") - 60 * 60);


        $lj_message = "\nLIMOGESJEWELRY had no sales in the last two hour between $period_from to $period_to";

        $to_be_sent = true;
        $recipient = "lj_zero_sales@cpscompany.com";
        $mail_body = $lj_message;
        $subject = "LJ Zero dollar Sales Alert!"; //subject

        mail($recipient, $subject, $mail_body, $header); //mail command :)
}

mysql_close($link);

?>
