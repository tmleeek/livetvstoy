<?php
// Single Product Designer post script

// Custom Function Hooking AJAX Designer

// Global Variables
global $VAR_product_code, $AJAX_designer_VARS, $product_layers, $product_fields, $AJD_DB, $product_template, $material;

$pid = isset($pid) ? $pid : $_GET['pid'];

$product_template = isset($product_template) ? $product_template : $_GET['template'];

$material = isset($material) ? $material : (isset($_GET['metal']) ? $_GET['metal'] : NULL);

// Product id passed in URL
$VAR_product_code = trim($pid);

include('../../../custom/configurator/func_common.php');		// Common functions
include('../../../custom/configurator/session.php');			// Session functions

// Layers Settings
$product_layers = FUNC_get_XML('layer');

// Options and fields Settings
$product_fields = FUNC_get_XML('fields');

// Configurator Database Connection

// EC2 Database
$DB_server 				= 'configuratorprod.cp7bb1dqswvp.us-east-1.rds.amazonaws.com';
$DB_user 				= 'root';
$DB_password 			= '3YxWuESbTuqhj8G';
$DB_name 				= 'cpsimages_db';

try {
	$AJD_DB = mysql_connect($DB_server,$DB_user,$DB_password);
	mysql_select_db($DB_name, $AJD_DB);

} catch (Exception $e) {
	echo 'Connect Error: ' . $e->getMessage() . '<br />';
}

if (($_SERVER['REQUEST_METHOD'] == 'POST' or $_SERVER['REQUEST_METHOD'] == 'GET') && (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) {
	
	global $AJAX_designer_VARS, $VAR_product_code, $product_layers, $product_fields;
	
	FUNC_AJD_set_values($product_layers, $product_fields, $_POST['AJD_fields']);

	FUNC_AJD_session_set();
	
}

mysql_close($AJD_DB);

?>