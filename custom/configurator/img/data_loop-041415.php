<?php
// Loop Data for Image Rendering for each Step - img/index.php

// Get Session Data for AJAX Designer for each step and put to Array
$ARR_session = FUNC_img_data_source($productid, $g_mode, $id_A, $stepid);	// function located at configurator/func_common.php

if(isset($_GET['1st'])) {
	echo '<pre>';
	print_r($ARR_session);
	echo '<pre>';
	exit;
}

// SET LAYERS AND OPTIONS ASSOCIATED ON IT ----------------------------------------------------------------------------------------------------------------------------
// For XML with No steps
if($stepid == 0 && !$hide_step) {
	$mv_itm_rt = isset($AJAX_designer_VARS['product'][$productid]['values']['mv_itm_rt_F']) ? $AJAX_designer_VARS['product'][$productid]['values']['mv_itm_rt_F'] : 0;
	$mv_itm_y = isset($AJAX_designer_VARS['product'][$productid]['values']['mv_itm_y_F']) ? $AJAX_designer_VARS['product'][$productid]['values']['mv_itm_y_F'] : 0;
	$mv_itm_x = isset($AJAX_designer_VARS['product'][$productid]['values']['mv_itm_x_F']) ? $AJAX_designer_VARS['product'][$productid]['values']['mv_itm_x_F'] : 0;
	$mv_itm_rz = isset($AJAX_designer_VARS['product'][$productid]['values']['mv_itm_rz_F']) ? $AJAX_designer_VARS['product'][$productid]['values']['mv_itm_rz_F'] : 0;
} else {
	$mv_itm_rt = isset($AJAX_designer_VARS['product'][$productid]['values']['mv_itm_rt_B']) ? $AJAX_designer_VARS['product'][$productid]['values']['mv_itm_rt_B'] : 0;
	$mv_itm_y = isset($AJAX_designer_VARS['product'][$productid]['values']['mv_itm_y_B']) ? $AJAX_designer_VARS['product'][$productid]['values']['mv_itm_y_B'] : 0;
	$mv_itm_x = isset($AJAX_designer_VARS['product'][$productid]['values']['mv_itm_x_B']) ? $AJAX_designer_VARS['product'][$productid]['values']['mv_itm_x_B'] : 0;
	$mv_itm_rz = isset($AJAX_designer_VARS['product'][$productid]['values']['mv_itm_rz_B']) ? $AJAX_designer_VARS['product'][$productid]['values']['mv_itm_rz_B'] : 0;	
}

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

// Layer values from session for current step
if(is_array($ARR_session['layers'])) {
	$_LAYER_VALS = $ARR_session['layers'];
}

// Generate image based on layers and values
foreach ($__LAYERS as $id => $layer) {
	
	if(isset($_LAYER_VALS[$id]) && is_array($_LAYER_VALS[$id])) {
		foreach ($_LAYER_VALS[$id] as $S1 => $S2) {
			
			$go_hidden = false;
			
			// For component selector
			if($S1 == 'comp_selector') {
													
				$sel_id = 0;
				
				// Set value of selected option
				foreach ($__LAYERS[$id]['option'] as $O1 => $O2) {
					if(isset($O2['default']) and $O2['default'] == 'true'){
						$sel_id = $O1;
					}
				}
				
				// Set metal color
				if(isset($__LAYERS[$id]['metal']))
					$tmp_metal = $__LAYERS[$id]['metal'];
	
				if(is_array($S2)) {
					$tmp_keys = array_keys($S2);
					$sel_id = $tmp_keys[0];
					$str_val = $S2[$sel_id][$stepid][$id];
					
					$__LAYERS[$id] = $__LAYERS[$id]['option'][$sel_id];
					$sopt_id = $str_val['SOPT'];
				}else{
					$__ARR_multi = array();
					$__ARR_multi = explode("~", $S2);
					
	
					if(count($__ARR_multi) > 0 && strtolower($__ARR_multi[0]) != 'none'){
						$sel_id = $__ARR_multi[0];
					}
						
			
					// Set selected option/component id
					if(isset($__LAYERS[$id]['option'][$sel_id]))
						$__LAYERS[$id] = $__LAYERS[$id]['option'][$sel_id];
	
					// Set value of selected component ( if not available, use init_value from layers.xml)
					if(isset($__ARR_multi[1]))
						$str_val = strlen($__ARR_multi[1]) > 0 ? $__ARR_multi[1] : (isset($__LAYERS[$id]['init_value']) ? $__LAYERS[$id]['init_value'] : NULL);
					
					// Set selected sub-option id
					if(isset($__ARR_multi[2]))
						$sopt_id = strlen($__ARR_multi[2]) > 0 ? $__ARR_multi[2] : 'N';
									
					if(isset($sopt_id) && strtolower($sopt_id) == 'undefined') {
						$sopt_id = 'N';
					}
					
					// Set selected sub-option 2 id
					if(isset($__ARR_multi[3]))
						$sopt2_id = strlen($__ARR_multi[3]) > 0 ? $__ARR_multi[3] : 'N';
									
					if(isset($sopt2_id) && strtolower($sopt2_id) == 'undefined') {
						$sopt2_id = 'N';
					}
				}
				
				// Set metal color
				if(isset($tmp_metal))
					$__LAYERS[$id]['metal'] = $tmp_metal;
				
				
				if(isset($__LAYERS[$id]['comp_type']) && $__LAYERS[$id]['comp_type'] == 'uploader') {
					
					
					// Sub option 1
					if(is_array($__LAYERS[$id]['content']['option'][0]['content']['option'])) {
		
						foreach ($__LAYERS[$id]['content']['option'][0]['content']['option'] as $O1 => $O2) {
							
							if($sopt_id == 'N') {
								if(isset($O2['default']) and $O2['default'] == 'true'){
									$sopt_id = $O1;
								}
							}
		
							if($sopt_id == 'N') {
								$sopt_id = 0;
							}
							
						}
		
						if($sopt_id != 'N') {
							$insert_method = $__LAYERS[$id]['content']['option'][0]['content']['option'][$sopt_id]['ajd_id'];
							$__LAYERS[$id]['insert_method'] = $insert_method;
							if($insert_method == 'email') {
								$__LAYERS[$id]['value'] = '/graphics/design/to-email-photo.png';
							}else if($insert_method == 'mail') {
								$__LAYERS[$id]['value'] = '/graphics/design/to-mail-photo.png';	
							}
						}
					}
					
					// Set selected sub-option 2 for component, get back and optimize the code some more
					if(is_array($__LAYERS[$id]['content']['option'][1]['content']['option'])) {
		
						foreach ($__LAYERS[$id]['content']['option'][1]['content']['option'] as $O1 => $O2) {
							
							if($sopt2_id == 'N') {
								if(isset($O2['default']) and $O2['default'] == 'true'){
									$sopt2_id = $O1;
								}
							}
		
							if($sopt2_id == 'N') {
								$sopt2_id = 0;
							}
							
						}
		
						if($sopt2_id != 'N') {
							foreach ($__LAYERS[$id]['content']['option'][1]['content']['option'][$sopt2_id] as $O1 => $O2) {
								$__LAYERS[$id][$O1] = $O2;
							}
						}
		
						unset($__LAYERS[$id]['content']);
					}
					
					
				} else {
				
					// Set selected sub-option for component, get back and optimize the code some more
					if(isset($__LAYERS[$id]['content']['option']) && is_array($__LAYERS[$id]['content']['option'])) {
		
						foreach ($__LAYERS[$id]['content']['option'] as $O1 => $O2) {
							
							if($sopt_id == 'N') {
								if(isset($O2['default']) and $O2['default'] == 'true'){
									$sopt_id = $O1;
								}
							}
		
							if($sopt_id == 'N') {
								$sopt_id = 0;
							}
							
						}
		
						if($sopt_id != 'N') {
							foreach ($__LAYERS[$id]['content']['option'][$sopt_id] as $O1 => $O2) {
								$__LAYERS[$id][$O1] = $O2;
							}
						}
		
						unset($__LAYERS[$id]['content']);
					}
				}
					
				// Set value for Clipart option
				if(isset($__LAYERS[$id]['comp_type'])) {
					if($__LAYERS[$id]['comp_type'] == 'cliparts') {
						$__LAYERS[$id]['cliparts'] = FUNC_get_XML('cliparts', $str_val);
					// Set value for Wording option
					}else if($__LAYERS[$id]['comp_type'] == 'wording') {
						
						if(isset($_LAYER_VALS[$id]['wording'][$stepid][$id])) {
							$str_val = $_LAYER_VALS[$id]['wording'][$stepid][$id];
						}
						
						if(is_array($str_val)) {
							$w_name = $str_val['name'];
							$__LAYERS[$id]['hidden_data'] = $str_val;
						}else{
							$w_name = $str_val;
						}
						
						if(is_null($stepid)) {
							$__LAYERS[$id]['wording'] = FUNC_get_XML('wording', $w_name, $id, NULL, $sel_id);
						}else{
							$__LAYERS[$id]['wording'] = FUNC_get_XML('wording', $w_name, $id, $stepid, $sel_id);
						}
					// Set value for Photo option
					}else if($__LAYERS[$id]['comp_type'] == 'uploader') {
						if($__LAYERS[$id]['insert_method'] == 'upload') {
							$__ARR_img_resizer = array();
							$__ARR_img_resizer = explode("|", $str_val);
							$__LAYERS[$id]['uploader'] = FUNC_UPLOAD_get_img($__ARR_img_resizer[0]);
							
							if(is_array($__LAYERS[$id]['uploader'])) {
								if(is_array($__ARR_img_resizer) && isset($__ARR_img_resizer[1])) {
									$__LAYERS[$id]['crop_to_x'] = $__ARR_img_resizer[1];
									$__LAYERS[$id]['crop_to_y'] = $__ARR_img_resizer[2];
									$__LAYERS[$id]['crop_to_width'] = $__ARR_img_resizer[3];
									$__LAYERS[$id]['crop_to_height'] = $__ARR_img_resizer[4];
									$__LAYERS[$id]['crop_scale_factor'] = $__ARR_img_resizer[5];
									$__LAYERS[$id]['crop_to_rotate'] = $__ARR_img_resizer[6];
								}
							}
						}
					// Set value for Monogram option
					}else if($__LAYERS[$id]['comp_type'] == 'text' && $__LAYERS[$id]['text_style']) {											
						$__LAYERS[$id]['txt'] = $str_val;
					}
				}
															
			}else if($S1 == 'stones') {
				if(isset($layer['stone_style'])) {	
					$__LAYERS[$id]['stones'] = FUNC_get_XML('stones', $S2);
				}else{
					if(is_null($stepid)) {
						$__LAYERS[$id]['stones'] = FUNC_get_XML('stones', $S2, $id);
					}else{
						$__LAYERS[$id]['stones'] = FUNC_get_XML('stones', $S2, $id, $stepid);
					}
				}

			}else if($S1 == 'metal') {
				
				// Assign metal values (code - Y, W, S)
				$def_arr = explode("-", $S2);
				
				if(count($def_arr) > 1) {
					$val = $def_arr[1];
				}else{
					$val = $S2;
				}
				
				$__LAYERS[$id]['metal'] = FUNC_get_XML('metal', $val);
			
			}else if($S1 == 'text') {
				//$str_val = $S2;
				//$str_val = isset($S2) ? $S2 : (isset($__LAYERS[$id]['init_value']) ? $__LAYERS[$id]['init_value'] : 'Test');
				//$__LAYERS[$id]['txt'] = $str_val;
				$__LAYERS[$id]['txt'] = isset($S2) ? $S2 : (isset($__LAYERS[$id]['init_value']) ? $__LAYERS[$id]['init_value'] : '');
			}else if($S1 == 'text_2') {
				//$str_val = $S2;
				//$str_val = isset($S2) ? $S2 : (isset($__LAYERS[$id]['init_value']) ? $__LAYERS[$id]['init_value'] : 'Test');
				//$__LAYERS[$id]['txt_2'] = $str_val;
				$__LAYERS[$id]['txt_2'] = isset($S2) ? $S2 : (isset($__LAYERS[$id]['init_value']) ? $__LAYERS[$id]['init_value'] : '');
			}
			
			
			if($go_hidden == true)
				$__LAYERS[$id]['hidden_data'] = $S7;
				
				
		}
	}
}

// SET LAYERS AND OPTIONS ASSOCIATED ON IT ----------------------------------------------------------------------------------------------------------------------------

// FINAL DATA FIX for Metal Color and Image - check again to optimize codes

foreach ($__LAYERS as $id => $layer) {	

	$img_metal = isset($layer['metal']) && is_array($layer['metal']) ? $layer['metal'] : NULL;
	
	if(is_array($img_metal)) {
		if(isset($img_metal['color'])) {
			
			// Set color of text and monogram engraving based on metal
			if((isset($layer['type']) && ($layer['type'] == "text" || $layer['type'] == "wording")) || (isset($layer['comp_type']) && $layer['comp_type'] == 'cliparts')) {
				
				$__LAYERS[$id]['color'] = $img_metal['color'];
				
				$__metal = $__LAYERS[$id]['color'];
			}
			
			
		}
		
		if(isset($img_metal['code'])) {
			// Set product image based on metal
			if(isset($layer['type']) && $layer['type'] == "image" && $id == 0) {
				$f_tmp = pathinfo($layer['value']);
				
				$file_image_sku = $f_tmp['dirname'].'/'.$productid.'/'.$f_tmp['filename'].'_'.$img_metal['code'].'.'.$f_tmp['extension'];
				$file_image_default = $f_tmp['dirname'].'/'.$f_tmp['filename'].'_'.$img_metal['code'].'.'.$f_tmp['extension'];
				
				if (file_exists($PATH_components.$file_image_sku)) {
					$__LAYERS[$id]['value'] = $file_image_sku;
				} else {
					$__LAYERS[$id]['value'] = $file_image_default;
				}
				//$__LAYERS[$id]['value'] = $f_tmp['dirname'].'/'.$f_tmp['filename'].'_'.$img_metal['code'].'.'.$f_tmp['extension'];
			}
		}
	}
}

// FINAL DATA FIX	

if(isset($_GET['2nd'])) {
	echo '<pre>';
	print_r($__LAYERS);
	echo '<pre>';
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