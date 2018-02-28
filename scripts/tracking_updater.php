<?php
//conection:
$link = mysqli_connect("10.0.0.29","tystoybox","noez@2014","tvstoybox") or die("Error " . mysqli_error($link));

$file = fopen("MagentoOrdersW-Otracking.csv","r");
$i=0;
while(! feof($file)) {
  $line = fgetcsv($file);
  if ($i > 0) {

    $orderNumber = $line[0]; 
    $trackingNumber = $line[2]; 
    //consultation:
    $query = "SELECT entity_id FROM sales_flat_order WHERE increment_id=$orderNumber" or die("Error in the consult.." . mysqli_error($link));

    //execute the query.
    $result = $link->query($query);

    //display information:
    if($row = mysqli_fetch_array($result)) {
      $order_id = $row["entity_id"];

      $shipquery = "SELECT entity_id, updated_at FROM sales_flat_shipment WHERE order_id = $order_id" or die("Error in the consult.." . mysqli_error($link));
      $shipresult = $link->query($shipquery);


      if($shiprow = mysqli_fetch_array($shipresult)) {
        $parent_id = $shiprow["entity_id"];
        $created_at =  $shiprow["updated_at"];

        mysqli_query($link,"INSERT INTO sales_flat_shipment_track (parent_id, order_id, track_number, title, carrier_code, created_at) VALUES ($parent_id, $order_id, $trackingNumber, '8 FedEx SmartPost', 'fedex', '$created_at')") or die("Error in the consult.." . mysqli_error($link));


      }
    }
  }


$i++;
}
fclose($file);
?>

