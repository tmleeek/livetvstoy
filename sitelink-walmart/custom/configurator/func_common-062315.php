<?php
// 062315

// ======================================
if ( basename($_SERVER['PHP_SELF']) == basename( __FILE__ ) ) {
   $home = 'http://' . $_SERVER['SERVER_NAME'] . '/';
   header ("Location: $home");
   die();
}
// ==============================================
// HTML-AJAX-JAVASCRIPT Designer Functions

// Global variables
global $PATH_designer, $site_root, $G_VAR_img_type, $VAR_product_code,$PATH_components, $PATH_current_product, $PATH_products, $G_ARR_colors, $G_ARR_papers, $G_ARR_fonts, $G_ARR_stones, $G_ARR_cliparts, $G_ARR_component_categories, $G_ARR_chains;

// Array of Birthstones
$G_ARR_stones = array();
$G_ARR_stones[] = array('title' => 'None', 'active' => 'Y');
$G_ARR_stones[] = array('title' => 'January', 'active' => 'Y');
$G_ARR_stones[] = array('title' => 'February', 'active' => 'Y');
$G_ARR_stones[] = array('title' => 'March', 'active' => 'Y');
$G_ARR_stones[] = array('title' => 'April', 'active' => 'Y');
$G_ARR_stones[] = array('title' => 'April2', 'active' => 'Y');
$G_ARR_stones[] = array('title' => 'May', 'active' => 'Y');
$G_ARR_stones[] = array('title' => 'June', 'active' => 'Y');
$G_ARR_stones[] = array('title' => 'July', 'active' => 'Y');
$G_ARR_stones[] = array('title' => 'August', 'active' => 'Y');
$G_ARR_stones[] = array('title' => 'September', 'active' => 'Y');
$G_ARR_stones[] = array('title' => 'October', 'active' => 'Y');
$G_ARR_stones[] = array('title' => 'November', 'active' => 'Y');
$G_ARR_stones[] = array('title' => 'December', 'active' => 'Y');

// Image Type
$G_VAR_img_type = 'png';

// PATH TO DIRECTORIES
$PATH_designer = $site_root.'/custom/configurator/';	// AJAX Designer
$PATH_components = $PATH_designer.'components/';		// Components
$PATH_products = $PATH_designer.'products/';			// All Products

	// EXECUTE SCRIPTS
	function exec_scripts($base_url, $scripts){
	
		foreach( $scripts as $type => $val ){
			
			if ( $type == 'css'){
				foreach($val as $script){
					echo '<link rel="stylesheet" type="text/css" href="' . $base_url . '/custom/configurator/interface/' . $script .'">';
				}
			}
			elseif ( $type == 'js' ){
				foreach($val as $script){
					echo '<script type="text/javascript" src="' . $base_url . '/custom/configurator/interface/' . $script .'"></script>';
				}
			}
		}
	}
	
	// EXECUTE XML
	function exec_XML($xml){
		// Get XML values and put to array
		foreach($xml as $val){
			FUNC_get_XML($val);
		}
	}

//if($VAR_product_code != '')
	//$PATH_current_product = $PATH_products.$product_template.'/';
	//$PATH_current_product = $PATH_products.$VAR_product_code.'/';	// Specific Product

// Function for Getting Color RGB and Settings from Database (?) - maybe not used in Locket Designer
if (!function_exists('FUNC_C_get_color')) {
function FUNC_C_get_color($col_name = NULL) {
	
	global $config, $smarty, $G_ARR_colors;
	
	$C_colors_cnt = 0;
	$C_colors = unserialize($config['C_colors']);

	if (!empty($C_colors)) {
		foreach ($C_colors as $k => $v) {
			if ($v['active'] != 'Y') {
				unset($C_colors[$k]);	
				continue;
			}
			$C_colors[$k] = func_array_map('stripslashes', $v);
		}
	}
	
	if (!empty($C_colors)) {

        foreach ($C_colors as $k => $v) {

			if ($v['type'] == "color"){
				$C_colors_cnt++;
		    }
        }

		if($col_name == '' or $col_name == NULL) {
        	$smarty->assign('C_colors', $C_colors);
			$smarty->assign('C_colors_cnt', $C_colors_cnt);
			$G_ARR_colors = $C_colors;
		}else{
			foreach ($C_colors as $k => $v) {
				if ($v['type'] == "color" and $v['title'] == $col_name){
					return $v;
				}
			}
			return array('type' => 'color', 'id' => 0, 'orderby' => 0, 'title' => 'Black', 'R' => 0, 'G' => 0, 'B' => 0, 'active' => 'Y');
		}

    }
}
}

// Function to Put XML to Array
if (!function_exists('FUNC_XML_to_Array')) {
function FUNC_XML_to_Array($xml,$ns=null){
  $a = array();
  for($xml->rewind(); $xml->valid(); $xml->next()) {
    $key = $xml->key();
    if(!isset($a[$key])) { $a[$key] = array(); $i=0; }
    else $i = count($a[$key]);
    $simple = true;
    foreach($xml->current()->attributes() as $k=>$v) {
        $a[$key][$i][$k]=(string)$v;
        $simple = false;
    }
    if($ns) foreach($ns as $nid=>$name) {
      foreach($xml->current()->attributes($name) as $k=>$v) {
         $a[$key][$i][$nid.':'.$k]=(string)$v;
         $simple = false;
      }
    } 
    if($xml->hasChildren()) {
        if($simple) $a[$key][$i] = FUNC_XML_to_Array($xml->current(), $ns);
        else $a[$key][$i]['content'] = FUNC_XML_to_Array($xml->current(), $ns);
    } else {
        if($simple) $a[$key][$i] = strval($xml->current());
        else $a[$key][$i]['content'] = strval($xml->current());
    }
    $i++;
  }
  return $a;
}  
}



// Function to Get XML Details
if (!function_exists('FUNC_get_XML')) {
function FUNC_get_XML($dir, $value=null, $assoc_layer=null, $assoc_step = NULL, $comp_sel_id = NULL) {
	
	global $PATH_components, $PATH_current_product, $G_ARR_papers, $G_ARR_fonts, $G_ARR_cliparts, $G_ARR_component_categories, $G_ARR_chains, $VAR_product_code, $product_template, $material;
	
	$pre_arr = array();
	
	/*
	echo '<pre>';
	print_r($material);
	echo '<pre>';
	exit;
	*/
	if($dir == 'layer') {
		include("products/".$product_template."/layers.php");
		//include("products/locket-heart-2page-1/layers.php");
		//include($PATH_current_product."layers.php");
		//include("products/layers.php");
	}else if($dir == 'fields') {
		
		if(preg_match("/gold|brass/i", strval($material))) {
			if(preg_match("/white/i", strval($material))) {
				$def_value = "0-W";
			} else {
				$def_value = "0-Y";
			}
		} else if(preg_match("/celebrium/i", strval($material))) {
			if(preg_match("/yellow/i", strval($material))) {
				$def_value = "0-Y";
			} else {
				$def_value = "0-W";
			}
		} else {
			$def_value = "0-S";
		}
		
		include("products/".$product_template."/fields.php");
		//include("products/locket-heart-2page-1/fields.php");
		//include($PATH_current_product."fields.php");
		//include("products/fields.php");
	}else if($dir == 'cliparts') {
		// Path to Dynamic Clipart XML
		//include("components/cliparts/clipart.php");
		$path = $PATH_components.'cliparts/clipart.xml';
		$pre_arr = $G_ARR_cliparts;
	}else if($dir == 'component_categories') {
		// Path to Dynamic Categories XML
		//include("components/categories.php");
		$path = $PATH_components.'categories.xml';
		$pre_arr = $G_ARR_component_categories;
	}else if($dir == 'metal') {
		// Path to Dynamic Metals XML
		include("components/metals.php");
	}else if($dir == 'stones') {
		// Path to Dynamic Stones XML
		//include("products/".$product_template."/stones.xml");
		include("components/stones.php");
	// Not used in Locket Designer
	}else if($dir == 'font') {
		$path = $PATH_components.'fonts/fonts.xml';
		$pre_arr = $G_ARR_fonts;
	}else if($dir == 'graphics') {
		$path = $PATH_components.'graphics/graphics.xml';
	}else if($dir == 'custom') {
		$path = $value;
	}
	
	// Get XML Array values for wordings
	if($dir == 'custom') {
	
		$XML_layer = new SimpleXmlIterator($path, null, true);
		$NS_layer = $XML_layer->getNamespaces(true);
		$__ARR_L = FUNC_XML_to_Array($XML_layer,$NS_layer);	
		
		exit;
	
	}else{
		
		// Assign pre-array values
		if(isset($pre_arr) and is_array($pre_arr) and count($pre_arr) > 0){
			$__ARR = $pre_arr;

		}else{
			// Use Dynamic XML Data instead of Path to Path to Static XML - 04/22/13
			
			if($dir == 'fields' || $dir == 'layer' || $dir == 'metal' || $dir == 'stones') {
		
				$XML = new SimpleXmlIterator($xml_data);
				$namespaces = $XML->getNamespaces(true);
					
				$__ARR = FUNC_XML_to_Array($XML,$namespaces);
			
			} else {
			
				if (file_exists($path)) {
					
					$XML = new SimpleXmlIterator($path, null, true);
					$namespaces = $XML->getNamespaces(true);
					
					$__ARR = FUNC_XML_to_Array($XML,$namespaces);
					
				} else {
					exit('Failed to open the '.$path);
				}
			}
			
		}
		
		if(isset($value)) {
			// Get XML Array values for cliparts
			if($dir == 'cliparts') {
				foreach($__ARR['clipart'] as $a => $b) {
					if($b['id'] == $value) {
						return $b;
					}
				}
			// Get XML Array values for metals
			}else if($dir == 'metal') {

				foreach($__ARR[$dir] as $a => $b) {			
					if(strtolower($value) == 'none' || $value == '') {
						if($b['default'] == 'true') {
							return $b;
						}
					}else{
						if($b['code'] == $value) {
							return $b;
						}
					}
				}
			}else{
				foreach($__ARR[$dir] as $k) {
					// Get XML Array values for metals - check if this is still needed for locket designer
					// Edited - 010513
					// Get XML Array values for birthstones
					if($dir == 'stones') {
					//}else if($dir == 'stones') {
						if(isset($k['index'])) {
							if(!is_null($assoc_step)) {
								if($k['name'] == $value && $k['index'] == $assoc_layer && $k['step'] == $assoc_step) {
									return $k;
								}
							}else if(is_null($assoc_step)) {
								if($k['name'] == $value && $k['index'] == $assoc_layer) {
									return $k;
								}
							}
						}
						
						if(is_null($assoc_step) && is_null($assoc_layer)) {
							if($k['name'] == $value) {
								return $k;
							}
						}
					}else{
						if($k['name'] == $value)
							return $k;
					}
				}
			}
		}else{
			// Array values for fonts
			if($dir == 'font') {
				$G_ARR_fonts = $__ARR;
			// Array values for paper - not needed for locket designer
			}else if($dir == 'cliparts') {
				$G_ARR_cliparts = $__ARR;
			// Array values for component categories
			}else if($dir == 'component_categories') {
				$G_ARR_component_categories = $__ARR;
			// Array values for chains
			}
			
			return $__ARR;
		}	
	}
}
}

// Function for searching keyword and abbreviation library
if (!function_exists('FUNC_search_key')) {
function FUNC_search_key($val, $switch=true) {
	
	$name_bank = array('name' => 'NM',
					   'content' => 'CN',
					   'line' => 'LN',
					   'add_line_height' => 'ALH',
					   'assoc_cat' => 'ASO',
					   'default' => 'DF',
					   'center' => 'C',
					   'top' => 'T',
					   'right' => 'RI',
					   'bottom' => 'B',
					   'left' => 'L',
					   'align' => 'AL',
					   'font' => 'FO',
					   'color' => 'CO',
					   'id' => 'ID',
					   'minus_size' => 'MS',
					   'file_name' => 'FN',
					   'type' => 'TP',
					   'orderby' => 'OB',
					   'title' => 'TL',
					   'active' => 'AV',
					   'avail' => 'AV',
					   'size' => 'SZ',
					   'R' => 'R',
					   'G' => 'G',
					   'B' => 'B',
					   'GC' => 'GC',
					   'GF' => 'GF',
					   'GW' => 'GW',
					   'GA' => 'GA',
					   'line_spacing' => 'LS',
					   'line_height' => 'LH',
					   'lock_color' => 'LC',
					   'lock_font' => 'LF',
					   'lock_align' => 'LA',
					   'lock_line_height' => 'LLH',
					   'lock_size' => 'LZ',
					   'minus_y' => 'MY',
					   'SOPT' => 'SOPT');	
	
	if(is_numeric($val)) return $val;
	
	foreach($name_bank as $a => $b) {
		if($switch) {
			if($val == $a) {
				return $b;
			}	
		}else{
			if($val == $b) {
				return $a;
			}	
		}
	}
}
}

/*
// Function for Arranging Array for Wordings
if (!function_exists('FUNC_wording_re_array')) {
function FUNC_wording_re_array($arr, $re_arr=true) {
			   
	$new_arr = array();
	
	foreach($arr as $a => $b) {
		if(is_array($b)) {
			foreach($b as $c => $d) {
				if(is_array($d)) {
					foreach($d as $e => $f) {
						if(is_array($f)) {
							foreach($f as $g => $h) {
								if(is_array($h)) {
									foreach($h as $i => $j) {
										if(is_array($j)) {
											foreach($j as $k => $l) {
												$new_arr[FUNC_search_key($a, $re_arr)][FUNC_search_key($c, $re_arr)][FUNC_search_key($e, $re_arr)][FUNC_search_key($g, $re_arr)][FUNC_search_key($i, $re_arr)][FUNC_search_key($k, $re_arr)]  = $l;
											}
										}else{
											$new_arr[FUNC_search_key($a, $re_arr)][FUNC_search_key($c, $re_arr)][FUNC_search_key($e, $re_arr)][FUNC_search_key($g, $re_arr)][FUNC_search_key($i, $re_arr)]  = $j;
										}
									}
								}else{
									$new_arr[FUNC_search_key($a, $re_arr)][FUNC_search_key($c, $re_arr)][FUNC_search_key($e, $re_arr)][FUNC_search_key($g, $re_arr)]  = $h;
								}
							}
						}else{
							$new_arr[FUNC_search_key($a, $re_arr)][FUNC_search_key($c, $re_arr)][FUNC_search_key($e, $re_arr)] = $f;
						}
					}
				}else{
					$new_arr[FUNC_search_key($a, $re_arr)][FUNC_search_key($c, $re_arr)] = $d;
				}
			}
		}else{
			
			$new_arr[FUNC_search_key($a, $re_arr)] = $b;
		}
	}
	
	return $new_arr;
}
}
*/

/*
Sets data source of image engine
Values configured here are used by the image engine - img/index.php, data_loop.php
*/
if (!function_exists('FUNC_img_data_source')) {
function FUNC_img_data_source($productid, $g_mode, $id_A = NULL, $stepid = NULL, $set_prod = false) {
	
	$__ARR_DATA = array();
	
	if($g_mode == 'prod') {
		// SINGLE PRODUCT DATA
		// SESSION SOURCE
		// if $id_A is set, data source will be the cart items
		
		global $AJAX_designer_VARS;	
		
		// Used for previewing AJAV designer variables - disable later
		if(isset($_GET['DT'])) {
			echo '<pre>';
			print_r($AJAX_designer_VARS);
			echo '</pre>';
			
			exit;
		}
			
		
		if(!is_null($stepid) && is_array($AJAX_designer_VARS))
		//if(isset($AJAX_designer_VARS['product'][$productid]['layers'][$stepid]) && is_array($AJAX_designer_VARS))
			$__ARR_DATA_LAYERS = $AJAX_designer_VARS['product'][$productid]['layers'][$stepid];
		
	}

	if(isset($__ARR_DATA_LAYERS) && is_array($__ARR_DATA_LAYERS))
		return array('layers' => $__ARR_DATA_LAYERS);
}
}

// Prepare Array values to be used by AJAX Selector - configurator/selector.php
// For cliparts, stones, fonts
// How about for chains?
if (!function_exists('FUNC_AD_selector_prep')) {
function FUNC_AD_selector_prep($main_arr, $com_type, $def_val, $cat_list_a = NULL, $val_type = NULL) {
	
	global $G_ARR_component_categories, $site_url;
	
	$VAR_img_type = 'png';
	
	// Categories Array
	$cat_arr_main = $G_ARR_component_categories[$com_type];
	
	if(isset($cat_list_a) and $cat_list_a != "") {
		
		if($cat_list_a == 'all') {
			
			foreach($cat_arr_main[0]['content']['cat'] as $c => $d){
				$sub_cats = array();		
				if(isset($d['content']['cat']) && is_array($d['content']['cat'])) {			
					foreach($d['content']['cat'] as $e => $f){
						$sub_cats[] = array('title' => $f['name'], 'id' => $c, 'group' => $c);
					}
				}		
				$arr_cats[] = array('title' => $d['name'], 'id' => $c, 'group' => $c, 'sub_cats' => $sub_cats);
			}
				
		} else {
			
			$cat_list = explode("-", $cat_list_a);
			
			if(count($cat_list) > 0) {
			
				foreach($cat_list as $a => $b){
					foreach($cat_arr_main[0]['content']['cat'] as $c => $d){
						if($b == $c) {
							$sub_cats = array();
							
							if(isset($d['content']['cat']) && is_array($d['content']['cat'])) {
								
								foreach($d['content']['cat'] as $e => $f){
									$sub_cats[] = array('title' => $f['name'], 'id' => $b, 'group' => $b);
								}
							}
							
							$arr_cats[] = array('title' => $d['name'], 'id' => $b, 'group' => $b, 'sub_cats' => $sub_cats);
						}
					}
				}
			}
		}
		$go_categorized = true;
	}
	
	// For Component values
	foreach($main_arr as $a => $b){	
		
		$go_add = false;
		
		$__tmp_arr = array();
		
				
		if(isset($b['name']))
			$__tmp_arr['title'] = $b['name'];
				
		if(isset($b['title']))
			$__tmp_arr['title'] = $b['title'];
		
		// For cliparts, add file_name in array
		if(isset($b['file_name']))
			$__tmp_arr['file_name'] = $b['file_name'];
		// For cliparts, add assoc_cat in array	
		if(isset($b['assoc_cat']))
			$__tmp_arr['assoc_cat'] = $b['assoc_cat'];
		
		if(isset($b['pid']))
			$__tmp_arr['pid'] = $b['pid'];
			
		if(isset($b['id']))
			$__tmp_arr['id'] = $b['id'];
			
		if(!is_null($val_type)) {
			if($val_type == 'id') {
				$__tmp_arr['value'] = $a;
			}else if($val_type == 'title') {
				$__tmp_arr['value'] = $__tmp_arr['title'];
			}
		}
		
		// Adjustment to show all items in main category
		$assoc_cat_array = explode("_", $b['assoc_cat']);
		
		if(count($assoc_cat_array) > 1) {
			$b['assoc_cat'] = $assoc_cat_array[0].'-'.$b['assoc_cat'];
		}
		
		// With categories
		if(count($arr_cats) > 0) {
			
			// Split associated categories and find matching items
			foreach($arr_cats as $c => $d){
				if(in_array($d['id'], explode("-", $b['assoc_cat'])) and ($b['avail'] == 'true' or $b['active'] == 'Y')) {
					$go_add = true;
				}
			}
			
			$__tmp_arr['cat'] = $b['assoc_cat'];
		
		// No category		
		}else{
			if($b['avail'] == 'true' or $b['active'] == 'Y') 
				$go_add = true;
		}
		
		// Include in component array list
		if($go_add == true)
			$arr_list[] = $__tmp_arr;
			
	}
	
	// Set selected or default value
	foreach($arr_list as $a => $b){
		
		if(isset($b['id'])) {
			$arr_list[$a]['id'] = $b['id'];
		}else{
			$arr_list[$a]['id'] = $a;
		}
		// use this value as attribute for clipart image of selector
		if(isset($b['assoc_cat'])) {
			$assoc_cat_arr = explode("_", $b['assoc_cat']);
			$arr_list[$a]['main_cat'] = $assoc_cat_arr[0];
		}
		
		if(isset($b['value']))
			$arr_list[$a]['value'] = $b['value'];
			
		if(isset($def_val)) {
			$val_str = '';
			
			// Check if this function is called in selector.php for chains, metal
			if($com_type == 'chains') {
				$val_str = $b['pid'];
			}else if($com_type == 'metal') {
				$val_str = $a;
			}else if($com_type == 'cliparts') {
				$val_str = $b['id'];
			}else{
				$val_str = strtolower($b['title']);
			}
			
			if($val_str == strtolower($def_val)) {
				$arr_list[$a]['def'] = true;
			}
		}
		
		// Set image icons used for selector
			$arr_list[$a]['image'] = $site_url.'custom/configurator/components/cliparts/files/png/'.$b['file_name'];
	}
	
	return array($arr_list, $arr_cats, $go_categorized);
}
}

// Function to generate swatches for components
if (!function_exists('FUNC_generate_swatches')) {
function FUNC_generate_swatches() {
	
	global $designer_engine;

	if($designer_engine == 'IMAGICK'){		//extension_loaded('imagick')) {
		FUNC_IMAGICK_swatches();	
	}else{
		FUNC_GD_swatches();	
	}
	
}
}

/*
// Function for getting values for wordings - used by scripts/IMAGICK/text.php
if (!function_exists('FUNC_get_W_val')) {
function FUNC_get_W_val($name, $V, $W, $XML, $def) {

	if(isset($V[$name]) && is_array($V)) {
		if($name == 'color') {
			$_VAR = $V['color'];
		}else{
			if($XML) {
				if(!is_array($V[$name])) {
					$_VAR = FUNC_get_XML($name, $V[$name]);
				}else{
					$_VAR = $V[$name];
				}
			}else{
				$_VAR = $V[$name];
			}
		}
	}else{
		if(isset($W[$name])) {
			if($name == 'color') {
				$_VAR = $W['color'];
			}else{
				if($XML) {
					if(!is_array($W[$name])) {
						$_VAR = FUNC_get_XML($name, $W[$name]);
					}else{
						$_VAR = $W[$name];
					}
				}else{
					$_VAR = $W[$name];
				}
			}
		}else{
			if($name == 'color') {
				$_VAR = $def;
			}else{
				if($XML) {
					if(!is_array($def)) {
						$_VAR = FUNC_get_XML($name, $def);
					}else{
						$_VAR = $def;
					}
				}else{
					$_VAR = $def;
				}
			}
		}
	}
	
	return $_VAR;
}
}
*/

// Function to convert to lowercase string
if (!function_exists('FUNC_lower')) {
function FUNC_lower(&$string){
    $string = strtolower($string);
}
}

// Function to prepare values for generic combo box
if (!function_exists('FUNC_cbo_ready')) {
function FUNC_cbo_ready($arr, $type, $def = NULL) {
	
	global $VAR_product_code, $AJAX_designer_VARS, $G_VAR_img_type, $site_url;	
	
	$ret_arr = array();
	
	foreach($arr[$type] as $a => $b) {
		$ret_arr[$a] = array();
		
		$go_sel = false;
		
		if(isset($b['default']) && is_null($def)) {
			$go_sel = true;
		}
		
		// For birthstones
		if($type == 'stones') {
			$ret_arr[$a]['title'] = $b['name'];
			$ret_arr[$a]['value'] = $b['name'];
			$ret_arr[$a]['img'] = $site_url.'/custom/configurator/components/swatches/stones/STO_'.strtolower(str_replace(" ","_",$b['name'])).'_i.'.$G_VAR_img_type;
			if(!is_null($def) && $def == $b['name']) {
				$go_sel = true;
			}
		// For metals
		}else if($type == 'metal') {
			$ret_arr[$a]['title'] = $b['key'];
			$ret_arr[$a]['value'] = $b['code'];
			if(!is_null($def) && $def == $a) {
				$go_sel = true;
			}
		}
				
		if($go_sel) {
			$ret_arr[$a]['selected'] = true;
		}
	}
	
	return $ret_arr;
}
}

// Function to set AJAX Designer values
if (!function_exists('FUNC_AJD_set_values')) {
	function FUNC_AJD_set_values($layers, $fields, $values = NULL, $W = NULL) {
		
		global $VAR_product_code, $AJAX_designer_VARS, $new_wording;
		
		$AJAX_designer_VARS['product'][$VAR_product_code] = array();
		$AJAX_designer_VARS['product'][$VAR_product_code]['layers'] = array();
		
		// Assign values from session
		if(isset($values) && is_array($values)) {
		//if(!is_null($values) && is_array($values)) {
			$AJAX_designer_VARS['product'][$VAR_product_code]['values'] = $values;
		}
		
		$hh = 0;
		
		// Wording not changed
		$new_wording = false;
		
		// Assign values for each step - from layers.xml
		foreach ($layers['step'] as $a => $b) {
			
			// If step is not place holder such as chains
			if(!isset($b['place_holder'])) {
				
				$AJAX_designer_VARS['product'][$VAR_product_code]['layers'][$a] = array();
				
				// Assign values for each layer
				foreach ($b['content']['layer'] as $a2 => $b2) {
					
					$AJAX_designer_VARS['product'][$VAR_product_code]['layers'][$a][$a2] = array();
					
					// Assign values for each field - from fields.xml
					foreach ($fields['fields'] as $a3 => $b3) {
						
						// Get associated steps
						$assoc_steps = explode("-", $b3['assoc_step']);
						$assoc_layers = '';
						
						$multi_layers_assoc = array();
						
						// Get associated layers
						if(isset($b3['assoc_layers'])) {
							$assoc_layers = explode("-", $b3['assoc_layers']);
							
							if(is_array($assoc_layers)) {
								foreach ($assoc_layers as $a4 => $b4) {
									$step_split = explode("|", $b4);
									// Assign for multiple layers
									if(isset($step_split[1])) {
										$multi_layers_assoc[$a4] = $step_split;
										$multi_layers_assoc[$a4][1] = explode(",", $step_split[1]);
									}
								}
							}
						}
						
						// Assign values from session
						if(isset($values) && is_array($values) && isset($values[$a3])) {
							$val = $values[$a3];
						// Assign default values
						}else{
							$val = $b3['default'];
							$AJAX_designer_VARS['product'][$VAR_product_code]['values'][$a3] = $val;
						}
						
						// If current step is associated
						if(in_array($a,$assoc_steps)){
							// If there are associated layers
							if($assoc_layers != '') {
								if(is_array($assoc_layers)) {
									// If multiple layers are associated
									if(count($multi_layers_assoc) > 0) {
										foreach ($multi_layers_assoc as $a4 => $b4) {
											// If current layer is associated
											if($b4[0] == $a && in_array($a2,$b4[1])) {
												$AJAX_designer_VARS['product'][$VAR_product_code]['layers'][$a][$a2][$b3['type']] = $val;
											}
										}
									}else{
										// If current layer is associated
										if(in_array($a2,$assoc_layers)) {
											$AJAX_designer_VARS['product'][$VAR_product_code]['layers'][$a][$a2][$b3['type']] = $val;
										}
									}
								}
							// If no associated layers
							}else{
								$AJAX_designer_VARS['product'][$VAR_product_code]['layers'][$a][$a2][$b3['type']] = $val;
							}
							
							// Split to get value for default or selected option/sub-option 
							
							$def_val = '';
							$def_opt = '';
							$def_sopt = '';
							$def_sopt2 = '';
							
							$sel_arr = explode("~", $val);
							
							if(is_array($sel_arr)) {
								// Selected Component Option
								if(isset($sel_arr[0])) {
									$def_opt = $sel_arr[0];
								}
								// Selected Component Option Value
								if(isset($sel_arr[1])) {
									$def_val = $sel_arr[1];
								}
								// Selected Component Sub-Option Value
								if(isset($sel_arr[2])) {
									$def_sopt = $sel_arr[2];
								}
								// Selected Component Sub-Option 2 Value
								if(isset($sel_arr[3])) {
									$def_sopt2 = $sel_arr[3];
								}
							}
						}
					}
				}
			}
		}
	}
}

?>
