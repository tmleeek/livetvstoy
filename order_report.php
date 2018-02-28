<?php
$servername = "10.0.0.29";
$username = "tystoybox";
$password = "noez@2014";
$dbname = "tystoybox";


/* Change database details according to your database */
$dbConnection = mysqli_connect($servername, $username, $password, $dbname)or die(mysql_error());

$query  = "SELECT entity_id, increment_id, created_at, status FROM sales_flat_order WHERE created_at > '2015-05-15' AND status <> 'complete' AND status <> 'closed'";
$result = mysqli_query($dbConnection, $query);

if (mysqli_num_rows($result) > 0) {

    //echo "<ul>";

    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

        //echo "<li>{$row['increment_id']} {$row['created_at']} {$row['status']}</li>";
	$sub_query = "SELECT status, created_at FROM sales_flat_order_status_history WHERE parent_id = ".$row['entity_id']." ORDER BY created_at ASC limit 1";

	$sub_result = mysqli_query($dbConnection, $sub_query);
	$sub_row = mysqli_fetch_array($sub_result, MYSQLI_ASSOC);
        
	if ($sub_row['status']=='pending_fulfillment')
		echo $row['increment_id'].",". $row['created_at'].",".$row['status']."\n";

    }

    //echo "</ul>";

} else {

    echo "Query didn't return any result";

}
?>
