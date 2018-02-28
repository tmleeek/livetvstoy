<?php
        $link=mysql_connect('10.0.0.29','tystoybox','noez@2014') or die(mysql_error());

       mysql_select_db("tystoybox");
    if (($handle = fopen("shipping_matrixrate_correct_10_15_15.csv", "r")) !== FALSE){
	$i=0;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
        {
            echo '<pre>'; print_r($data);
            echo $i;
            if($i != 0){
                echo "UPDATE shipping_matrixrate SET condition_from_value={$data[9]}, condition_to_value={$data[10]}, price={$data[11]}, delivery_type={$data[13]} WHERE pk = {$data[0]}"; print"\n";
            	mysql_query("UPDATE shipping_matrixrate SET condition_from_value={$data[9]}, condition_to_value={$data[10]}, price={$data[11]}, delivery_type='{$data[13]}' WHERE pk = {$data[0]}");
            }
            $i++;
        }
    	fclose($handle);
    }
?>
