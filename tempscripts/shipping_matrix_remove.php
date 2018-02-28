<?php
        $link=mysql_connect('10.0.0.29','tystoybox','noez@2014') or die(mysql_error());

        mysql_select_db("tystoybox");
    if (($handle = fopen("shipping_matrixrate_deleted_rows.csv", "r")) !== FALSE)
    {
        $i = 0;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
        {
            echo '<pre>'; print_r($data);
                if ($i != 0) {
                        mysql_query("DELETE FROM shipping_matrixrate WHERE pk={$data[0]}");
                }
                $i++;
        }
    fclose($handle);
    }
?>
