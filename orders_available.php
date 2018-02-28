<?php

$link = mysql_connect('10.0.0.29', 'tystoybox', 'noez@2014');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}

$to_be_sent = false;

mysql_select_db('tvstoybox', $link) or die('Could not select database.');

$from_date = date('Y-m-d H:i:s', strtotime("now") + 60 * 60 * 3); 
$to_date = date('Y-m-d H:i:s', strtotime("now") + 60 * 60 * 5); 

echo "SELECT COUNT(*) AS total FROM sales_flat_order WHERE store_id = 1 AND created_at BETWEEN '$from_date' AND '$to_date'";
$tvs_result = mysql_query("SELECT COUNT(*) AS total FROM sales_flat_order WHERE store_id = 1 AND created_at BETWEEN '$from_date' AND '$to_date'"); print "\n";

$tvs_data = mysql_fetch_assoc($tvs_result);



if ($tvs_data['total'] >1){
        echo $period_from = date('Y-m-d H:i:s', strtotime("now") - 60 * 60 * 3); print "\n";
        echo $period_to =  date('Y-m-d H:i:s', strtotime("now") - 60 * 60 ); print "\n";


        $tvs_message = "TVSTOYBOX had no sales in the last two hour between $period_from to $period_to"; print "\n";

        
}

echo $pbs_result = mysql_query("SELECT COUNT(*) AS total FROM sales_flat_order WHERE store_id = 2 AND created_at BETWEEN '$from_date' AND '$to_date'");
$pbs_data = mysql_fetch_assoc($pbs_result);

if ($pbs_data['total'] < 1){
        echo $period_from = date('Y-m-d H:i:s', strtotime("now") - 60 * 60 * 3);print "\n";
        echo $period_to =  date('Y-m-d H:i:s', strtotime("now") - 60 * 60);print "\n";


        echo $pbs_message = "\nPBSKIDS had no sales in the last two hour between $period_from to $period_to";print "\n";


}


$pp_result = mysql_query("SELECT COUNT(*) AS total FROM sales_flat_order WHERE store_id = 4 AND created_at BETWEEN '$from_date' AND '$to_date'");
$pp_data = mysql_fetch_assoc($pp_result);

if ($pp_data['total'] < 1){
        $period_from = date('Y-m-d H:i:s', strtotime("now") - 60 * 60 * 3);
        $period_to =  date('Y-m-d H:i:s', strtotime("now") - 60 * 60);


        $pp_message = "\nPERSONALIZEDPLANET had no sales in the last two hour between $period_from to $period_to";

        
}

$lj_result = mysql_query("SELECT COUNT(*) AS total FROM sales_flat_order WHERE store_id = 5 AND created_at BETWEEN '$from_date' AND '$to_date'");
$lj_data = mysql_fetch_assoc($lj_result);

if ($lj_data['total'] < 1){
        $period_from = date('Y-m-d H:i:s', strtotime("now") - 60 * 60 * 3);
        $period_to =  date('Y-m-d H:i:s', strtotime("now") - 60 * 60);


        $lj_message = "\nLIMOGESJEWELRY had no sales in the last two hour between $period_from to $period_to";

        
}

mysql_close($link);

?>
