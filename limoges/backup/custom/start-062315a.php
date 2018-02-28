<?php
// AJAX/PHP Locket Designer
// This script and configurator/main.php starts the designer
// To be included in designer page
// Developed by eTrader - 03/01/15

if ( basename($_SERVER['PHP_SELF']) == basename( __FILE__ ) ) {
   $home = 'http://' . $_SERVER['SERVER_NAME'] . '/';
   header ("Location: $home");
   die();
}

// Custom Function Hooking AJAX Designer

// Global Variables
global $designer_engine, $site_root, $VAR_product_code, $AJAX_designer_VARS, $product_layers, $product_fields, $AJD_DB, $product_template, $material;

$site_root = $_SERVER['DOCUMENT_ROOT'];
//$site_url = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';

$pid = isset($pid) ? $pid : $_GET['pid'];

$product_template = isset($product_template) ? $product_template : $_GET['template'];

$material = isset($material) ? $material : (isset($_GET['metal']) ? $_GET['metal'] : NULL);

	/* STYLES */
	
	$scripts['css'][] = 'assets/lib/jqueryui/jquery-ui-1.9.1.custom.min.css';
	$scripts['css'][] = 'assets/css/main.css';
	
	/* SCRIPTS */
	
	$scripts['js'][] = 'assets/lib/jquery/jquery-1.8.1.min.js';
	$scripts['js'][] = 'assets/lib/jqueryui/jquery-ui-1.9.1.custom.min.js';
	$scripts['js'][] = 'assets/js/main.js';
	$scripts['js'][] = 'assets/js/custom.js';
	$scripts['js'][] = 'assets/lib/validate/jquery.validate.min.js?v=2.1.5';
	
	/* XML */
	
	$xml[] = 'font';
	$xml[] = 'cliparts';
	$xml[] = 'component_categories';

// Product id passed in URL
$VAR_product_code = trim($pid);
//$VAR_product_code = preg_replace('/[^0-9]/', '', $pid);

include('configurator/custom.php');			// Custom functions specialized for the current platform
include('configurator/func_common.php');		// Common functions
include('configurator/session.php');			// Session functions

// Image Rendering Engine - IMAGICK by default
$designer_engine = isset($_GET['EG']) ? $_GET['EG'] : 'IMAGICK'; // Selected Engine - GD, IMAGICK

include('configurator/scripts/'.$designer_engine.'/func.php');		// Image Rendering functions

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

if(isset($_COOKIE['frontend']))
	$sess_id = md5($_COOKIE['frontend']);

// Get session values
FUNC_AJD_session_get();

// Set AJAX designer values
if(isset($AJAX_designer_VARS['product'][$VAR_product_code]) && !is_array($AJAX_designer_VARS['product'][$VAR_product_code])) {
	FUNC_AJD_set_values($product_layers, $product_fields);
}

?>