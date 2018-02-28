<?php
// Loop Data for Image Rendering for each Step - img/index.php

if(isset($data_LAYERS['layer']) && is_array($data_LAYERS['layer']) and count($data_LAYERS['layer']) > 0) {
	$__LAYERS = $data_LAYERS['layer'];
// For XML with steps
}else if(is_array($data_LAYERS['step']) and count($data_LAYERS['step']) > 0) {
	
	// check if step is defined
	if(is_null($stepid))
		exit('Error! stepid');
	
	if($stepid > (count($data_LAYERS['step']) - 1)) {
		$stepid = 0;
	}
	
	// Data from layers.xml for current step
	$__LAYERS = $data_LAYERS['step'][$stepid]['content']['layer'];
	
	$step_flag = true;
}

// check for layers
if(count($__LAYERS) <= 0) {
	exit;
}


// GET THE BASE DESIGN AND BASE SETTINGS. BASE DESIGN ARE ALWAYS CONSIDERED ON INDEX 0 --------------------------------------------------------------------------------
if(is_array($__LAYERS[0])) {
	$VAR_resize_percent = isset($_GET['SZ']) ? $_GET['SZ'] : $__LAYERS[0]['resize'];
	$VAR_img_type = $__LAYERS[0]['img_type'];
	$VAR_jpg_quality = $__LAYERS[0]['jpg_quality'];
	$VAR_border = $__LAYERS[0]['border'];
	$core_width = $__LAYERS[0]['width'];
	$core_height = $__LAYERS[0]['height'];
}else{
	$VAR_resize_percent = isset($_GET['SZ']) ? $_GET['SZ'] : $__LAYERS['resize'];
	$VAR_img_type = $__LAYERS['img_type'];
	$VAR_jpg_quality = $__LAYERS['jpg_quality'];
	$VAR_border = $__LAYERS['border'];
	$core_width = $__LAYERS['width'];
	$core_height = $__LAYERS['height'];
}

// Set dimensions
if($VAR_resize_percent == 'T' || $VAR_resize_percent == 'S' || $VAR_resize_percent == 'P') {
	// for thumbnails
	$final_width = $thumbnail_size;
	$final_height = $thumbnail_size;	
}else{
	// for main image
	$final_width = $core_width * $VAR_resize_percent;
	$final_height = $core_height * $VAR_resize_percent;
}

// GET THE BASE DESIGN AND BASE SETTINGS. BASE DESIGN ARE ALWAYS CONSIDERED ON INDEX 0 --------------------------------------------------------------------------------

include '../scripts/'.$designer_engine.'/engine.php';	

?>