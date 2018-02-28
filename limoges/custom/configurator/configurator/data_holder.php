<?php

	function DHolder_insert($data = null){
	
		global $DHolder_arr;
		
		$query = mysql_query("SELECT * FROM sessions_ajd");
		$row = mysql_fetch_array($query);
		echo "<pre>";
		print_r($DHolder_arr);
		print_r($row);
		echo "</pre>";
	
	}

?>