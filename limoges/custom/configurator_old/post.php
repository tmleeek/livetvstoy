<?php
// Single Product Designer post script

include_once '../../../custom/start.php';	

if (($_SERVER['REQUEST_METHOD'] == 'POST' or $_SERVER['REQUEST_METHOD'] == 'GET') && (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) {
	
	global $AJAX_designer_VARS, $VAR_product_code, $product_layers, $product_fields;
	
	FUNC_AJD_set_values($product_layers, $product_fields, $_POST['AJD_fields']);

	FUNC_AJD_session_set();
	
}

?>