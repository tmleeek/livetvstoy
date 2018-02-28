<?php
/*
	Session Manager - TEMPO DYNA
*/

// Function to set AJAX designer values in session/database
if (!function_exists('FUNC_AJD_session_set')) {
function FUNC_AJD_session_set() {
	
	global $PATH_designer, $AJAX_designer_VARS, $AJD_DB;

	if(isset($_COOKIE['frontend'])) {
		
		$sess_id = md5($_COOKIE['frontend']);
		$val = serialize($AJAX_designer_VARS);
		
		$query = mysql_query("SELECT data FROM sessions_ajd WHERE sessid ='".$sess_id."' LIMIT 1", $AJD_DB);
		
		$query_insert = "INSERT INTO sessions_ajd (sessid, data) VALUES ('".$sess_id."', '".$val."')";
		$query_update = "UPDATE sessions_ajd SET data = '".$val."' WHERE sessid = '".$sess_id."'";
		
		if (mysql_num_rows($query) > 0) {
        	mysql_query($query_update, $AJD_DB) or die('aaa');				
		}else{		
			mysql_query($query_insert, $AJD_DB);
		}
	}
	
}
}

// Function to get AJAX designer values from session/database
if (!function_exists('FUNC_AJD_session_get')) {
function FUNC_AJD_session_get() {
	
	global $PATH_designer, $AJAX_designer_VARS, $AJD_DB;

	if(isset($_COOKIE['frontend'])) {
		
		$sess_id = md5($_COOKIE['frontend']);
		
		$query = mysql_query("SELECT data FROM sessions_ajd WHERE sessid ='".$sess_id."' LIMIT 1", $AJD_DB);

		if (mysql_num_rows($query) > 0 && $query) {
        	$dat = mysql_result($query, 0, "data");
			$AJAX_designer_VARS = unserialize($dat);
		}
	}
}
}

?>