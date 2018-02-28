<?php
// Single Product Designer post script

include_once '../start.php';	

if (($_SERVER['REQUEST_METHOD'] == 'POST' or $_SERVER['REQUEST_METHOD'] == 'GET') && (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) {
	
	global $AJAX_designer_VARS, $VAR_product_code, $new_wording, $product_layers, $product_fields;
	
	$new_wording = false;
	
	if(isset($_POST['W'])) {
		FUNC_AJD_set_values($product_layers, $product_fields, $_POST['AJD_fields'], $_POST['W']);
	} else {
		FUNC_AJD_set_values($product_layers, $product_fields, $_POST['AJD_fields']);
	}

//	FUNC_AJD_set_values($product_layers, $product_fields, $_POST['AJD_fields'], $_POST['W']);

	FUNC_AJD_session_set();
	
	if(isset($AJAX_designer_VARS['product'][$VAR_product_code]['wording']) && is_array($AJAX_designer_VARS['product'][$VAR_product_code]['wording'])) {
		echo json_encode($AJAX_designer_VARS['product'][$VAR_product_code]['wording']);
	}
}

?>