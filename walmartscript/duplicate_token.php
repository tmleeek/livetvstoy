<?php
   require_once('/var/www/CPS/public_html/app/Mage.php'); //Path to Magento

umask(0);
Mage::app('default');
$resource = Mage::getSingleton('core/resource');
                $writeConnection = $resource->getConnection('core_write');
                
         
             $query_sel = "select token_id, count(*)
        from site_link_external_walmart_transactions
        where 	createdate >= DATE_SUB(NOW(),INTERVAL 1 HOUR)
       group by token_id
      having count(*) > 1";
             $results = $writeConnection->fetchAll($query_sel);

      if(count($results)>0) {
       
     foreach($results as $row) {
    $to = "renjith@vtrio.com,bpunati@cpscompany.com;
";
    $from = "renjith@vtrio.com";
    $subject = "Duplicate Token";

    //begin of HTML message
    $message = "
<html>
  <body >
   <table>
   Hello,<br><br>
  
   Duplicate Token found.  Token Id:".$row['token_id'].".<br><br>
   
   Thanks,<br>
   Renjith
   </table>
     </body>
</html>";

   //end of message
    $headers  = "From: $from\r\n";
    $headers .= "Content-type: text/html\r\n";

    //options to send to cc+bcc
    //$headers .= "Cc: [email]maa@p-i-s.cXom[/email]";
    //$headers .= "Bcc: [email]email@maaking.cXom[/email]";
    
    // now lets send the email.
   mail($to, $subject, $message, $headers);
     }
    }
      ?>
