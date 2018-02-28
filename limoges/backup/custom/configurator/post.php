<?php
// Single Product Designer post script

//include_once '../../../custom/start.php';

// Custom Function Hooking AJAX Designer

// Global Variables
global /*$designer_engine, $site_root, */$VAR_product_code, $AJAX_designer_VARS, $product_layers, $product_fields, $AJD_DB, $product_template, $material;

//$site_root = $_SERVER['DOCUMENT_ROOT'];
//$site_url = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';

//if(isset($_COOKIE['frontend']))
	//$sess_id = md5($_COOKIE['frontend']);

$pid = isset($pid) ? $pid : $_GET['pid'];

$product_template = isset($product_template) ? $product_template : $_GET['template'];

$material = isset($material) ? $material : (isset($_GET['metal']) ? $_GET['metal'] : NULL);

// Product id passed in URL
$VAR_product_code = trim($pid);
//$VAR_product_code = preg_replace('/[^0-9]/', '', $pid);

	//echo '<pre style="display:none">';
	//print_r(__DIR__.' - start');
	//echo '</pre>';
	//exit;

include('../../../custom/configurator/func_common.php');		// Common functions
include('../../../custom/configurator/session.php');			// Session functions

//include('configurator/custom.php');			// Custom functions specialized for the current platform
//include('configurator/func_common.php');		// Common functions
//include('configurator/session.php');			// Session functions

// Image Rendering Engine - IMAGICK by default
//$designer_engine = isset($_GET['EG']) ? $_GET['EG'] : 'IMAGICK'; // Selected Engine - GD, IMAGICK

//include('configurator/scripts/'.$designer_engine.'/func.php');		// Image Rendering functions

// Layers Settings
$product_layers = FUNC_get_XML('layer');

// Options and fields Settings
$product_fields = FUNC_get_XML('fields');

// Configurator Database Connection

// CPS Images Database
//$DB_server 					= 'localhost';
$DB_server 					= '10.0.0.35';
$DB_user 					= 'cpsimages_user';
$DB_password 				= 'v55idWl<Uf';
$DB_name 					= 'cpsimages_db';

/*
$DB_server 					= '10.0.0.29';
$DB_user 					= 'tystoybox';
$DB_password 				= 'noez@2014';
$DB_name 					= 'tystoybox';
*/

try {
	$AJD_DB = mysql_connect($DB_server,$DB_user,$DB_password);
	mysql_select_db($DB_name, $AJD_DB);

} catch (Exception $e) {
	echo 'Connect Error: ' . $e->getMessage() . '<br />';
}

if (($_SERVER['REQUEST_METHOD'] == 'POST' or $_SERVER['REQUEST_METHOD'] == 'GET') && (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) {
	
	//global $AJAX_designer_VARS, $VAR_product_code, $product_layers, $product_fields;
	
	FUNC_AJD_set_values($product_layers, $product_fields, $_POST['AJD_fields']);

	FUNC_AJD_session_set();
	
}

mysql_close($AJD_DB);

?>