<?php
// AJAX/PHP Locket Designer
// This script and designer/main.php starts the designer
// To be included in designer page
// Developed by eTrader - 05/01/15

if ( basename($_SERVER['PHP_SELF']) == basename( __FILE__ ) ) {
   $home = 'http://' . $_SERVER['SERVER_NAME'] . '/';
   header ("Location: $home");
   die();
}

// Custom Function Hooking AJAX Designer

//$site_root
$site_root = $_SERVER['DOCUMENT_ROOT'];
//$site_root = $_SERVER['DOCUMENT_ROOT'] . '/cps';

$product_template = isset($product_template) ? $product_template : $_GET['template'];

include('renderer/func_common.php');		// Common functions

// Image Rendering Engine - IMAGICK by default
$designer_engine = isset($_GET['EG']) ? $_GET['EG'] : 'IMAGICK'; // Selected Engine - GD, IMAGICK

include('renderer/scripts/'.$designer_engine.'/func.php');		// Image Rendering functions

// Layers Settings
$product_layers = FUNC_get_XML('layer');

?>