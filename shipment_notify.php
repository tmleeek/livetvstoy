<?php
mysql_connect("10.0.0.29", "tystoybox", "noez@2014") or
    die("Could not connect: " . mysql_error());
mysql_select_db("tystoybox");

$result = mysql_query("SELECT id, pkey, delivery_method FROM shipping_method_alert WHERE sent=0");

$new_methods = "";

while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    //printf("ID: %s  Name: %s", $row["pkey"], $row["delivery_method"]);
    $new_methods .= "pk=>".$row["pkey"]."       |       "."delivery_type=>".$row["delivery_method"]."<br>";



    mysql_query("UPDATE shipping_method_alert SET sent=1 WHERE id=".$row["id"]."");
}
//echo $new_methods;
mysql_free_result($result);

if ($row) {
// To send HTML mail, the Content-type header must be set
$header  = 'MIME-Version: 1.0' . "\r\n";
$header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Additional headers
//$headers .= 'To: Mary <mary@example.com>, Kelly <kelly@example.com>' . "\r\n";


    $Name = "CPS Magento"; //senders name
    $email = "shippment@cpsmagento.com"; //senders e-mail adress
    $recipient = "afsar@vtrio.com"; //recipient
    $mail_body = "There are some new shippment methods created ...\n\n"; //mail body
    $mail_body .= $new_methods;
    $subject = "Shipping Method notification "; //subject
    $header .= "From: ". $Name . " <" . $email . ">\r\n"; //optional headerfields
    $header .= 'Cc: bpunati@cpscompany.com' . "\r\n";
    $header .= 'Cc: MTelci@cpscompany.com' . "\r\n";
    $header .= 'Cc: cpatel@cpscompany.com' . "\r\n";

    mail($recipient, $subject, $mail_body, $header); //mail command :

}
?>
