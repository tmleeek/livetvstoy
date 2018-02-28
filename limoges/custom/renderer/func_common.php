<?php
// 051414

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
$PATH_designer = $site_root.'/custom/renderer/';	// AJAX Designer
$PATH_components = $PATH_designer.'components/';		// Components
$PATH_products = $PATH_designer.'products/';			// All Products

	// EXECUTE SCRIPTS
if (!function_exists('exec_scripts')) {
	function exec_scripts($base_url, $scripts){
	
		foreach( $scripts as $type => $val ){
			
			if ( $type == 'css'){
				foreach($val as $script){
					echo '<link rel="stylesheet" type="text/css" href="' . $base_url . '/custom/renderer/interface/' . $script .'">';
				}
			}
			elseif ( $type == 'js' ){
				foreach($val as $script){
					echo '<script type="text/javascript" src="' . $base_url . '/custom/renderer/interface/' . $script .'"></script>';
				}
			}
		}
	}
}
	
	// EXECUTE XML
if (!function_exists('exec_XML')) {
	function exec_XML($xml){
		// Get XML values and put to array
		foreach($xml as $val){
			FUNC_get_XML($val);
		}
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
	
	global $PATH_components, $PATH_current_product, $G_ARR_papers, $G_ARR_fonts, $G_ARR_cliparts, $G_ARR_component_categories, $G_ARR_chains, $VAR_product_code, $product_template, $material, $avatar_id, $avatar_name;
	
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

?>
