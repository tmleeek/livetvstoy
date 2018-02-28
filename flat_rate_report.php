 <?php
/*
* Cronjob to truncate core_session table
*/
require_once 'app/Mage.php';

// Initialize Magento
Mage::init();

//Flat Rate Report cron
$resource = Mage::getSingleton('core/resource');
if($resource != null) {

    /**
     * Retrieve the read connection
     */
    $readConnection = $resource->getConnection('core_read');
    $flat_shipping_matrixrate = $resource->getTableName('flat_shipping_matrixrate');
    $salesrule = $resource->getTableName('salesrule');


    $query = "SELECT sr.name as promotion_name, fsm.price as flat_rate, fsm.delivery_type as ship_type
                FROM ".$salesrule."  sr
                JOIN ".$flat_shipping_matrixrate." fsm
                ON sr.rule_id=fsm.rule_id 
                where sr.is_active=1 
                order by fsm.pk desc";

    $results = $readConnection->fetchAll($query);
    $report  = '<html><body>';
    $report .= "<h2> Flat Rate Shipping Report</h2>";
    $report .= "<table width='500' style= \"border:1px solid #d4d4d4\">";
    $report .= "<tr> 
                    <th bgcolor='#d4d4d4' >Promotion Name</th>
                    <th bgcolor='#d4d4d4' >Flat Rate</th>
                    <th bgcolor='#d4d4d4' >Ship Type</th>
                </tr>";

    foreach($results as $res) {
        $report .= "<tr>";
        $report .= "<td>".$res['promotion_name']."</td>";
        $report .= "<td>".$res['flat_rate']."</td>";
        $report .= "<td>".$res['ship_type']."</td>";
        $report .= "</tr>";
    }           
    $report  .= "</table>";
    $report  .= "</body></html>";

    
    echo $report;
    //Send email after truncating generating report
    $Name = "NoReply"; //senders name
    $email = "noreply@cpscompany.com"; //senders e-mail adress
    //$recipient = "bpunati@cpscompany.com"; //recipient
    $recipient = "abdul.basit@kwanso.com,cfox@cpscompany.com,bpunati@cpscompany.com"; //recipients
    $mail_body = $report; //"The text for the mail..."; //mail body
    $subject = "Flat Rate Shipping Report - (". Date("Y-m-d").")"; //subject
    $header = "From: ". $Name . " <" . $email . ">\r\n" . //optional headerfields
          "CC: adeel.ahsan@kwanso.com \r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: text/html; charset=ISO-8859-1\r\n";      

    mail($recipient, $subject, $mail_body, $header); //mail command :)

}
//End of core_session cron

?>

