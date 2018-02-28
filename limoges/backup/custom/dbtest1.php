<?php
$server   = "10.0.0.35";
$database = "cpsimages_db";
$username = "cpsimages_user";
$password = "v55idWl<Uf";


        $connect=mysql_connect($server,$username,$password) or die("Unable to Connect");
        mysql_select_db($database) or die("Could not open the db");
        $showtablequery="SHOW TABLES FROM ".$database;
        $query_result=mysql_query($showtablequery);
        while($showtablerow = mysql_fetch_array($query_result))
        {
        echo $showtablerow[0]." ";
        }

?>