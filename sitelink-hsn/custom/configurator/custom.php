<?php
// ======================================
if ( basename($_SERVER['PHP_SELF']) == basename( __FILE__ ) ) {
   $home = 'http://' . $_SERVER['SERVER_NAME'] . '/';
   header ("Location: $home");
   die();
}
// ==============================================
// HTML-AJAX-JAVASCRIPT Custom Functions
// Platform Dependent

// Function for custom combo box for birthstones, metals, etc.
if (!function_exists('FUNC_CUSTOM_cbo_ready')) {
	function FUNC_CUSTOM_cbo_ready($arr, $type, $def = NULL) {
		
		global $VAR_product_code, $AJAX_designer_VARS, $G_VAR_img_type, $site_url;	
		
		// Array for Combo box values
		$ret_arr = array();
	
		foreach($arr as $a => $b) {
			
			$ret_arr[$a] = array();
			
			// if selected in combo box
			$go_sel = false;
			
			// if default value in fields.xml and no value yet in session
			if(isset($b['default']) && is_null($def)) {
				$go_sel = true;
			}
			
			// For Birthstones
			if($type == 'stones') {
				
				$val = 'none';
				
				// Array of library for birthstone, metal, etc. to be used for matching
				$internal_arr = FUNC_get_XML($type);
				
				// Find match in XML library - stones.xml
				foreach($internal_arr[$type] as $a1 => $b1) {
					if(preg_match("/".$b1['name']."/i", $b['title'])) {
						$val = $b1['name'];
					}
				}
				
				// Name and Value passed in combo box
				$ret_arr[$a]['title'] = $b['title'];
				$ret_arr[$a]['value'] = $val;
				$ret_arr[$a]['price'] = floatval($b['price']);
				
				// Other variables used for birthstone combo box
				if(isset($b['id']))
					$ret_arr[$a]['id'] = $b['id'];
					
				if(isset($b['optid']))
					$ret_arr[$a]['optid'] = $b['optid'];
					
				if(isset($b['optvarid']))
					$ret_arr[$a]['optvarid'] = $b['optvarid'];
				
				// Image URL for swatches used on combo box
				$ret_arr[$a]['img'] = $site_url.'/custom/AJAX_designer/components/swatches/stones/STO_'.strtolower(str_replace(" ","_",$val)).'_i.'.$G_VAR_img_type;
				
				// selected in combo box if matching the default value in session
				if(!is_null($def) && $def == $val) {
					$go_sel = true;
				}
			
			// For Metals	
			}else if($type == 'metal') {
				
				$val = '0-Y';
				
				// Array of library for birthstone, metal, etc. to be used for matching
				$internal_arr = FUNC_get_XML($type);
				
				// Find match in XML library - metals.xml
				foreach($internal_arr[$type] as $a1 => $b1) {
					if(preg_match("/".$b1['name']."/i", $b['title'])) {
						$val = $a."-".$b1['code'];
					}
				}
				
				// Split default value - metal number and code
				$def_arr = explode("-", $def);
				
				// Name and Value passed in combo box
				$ret_arr[$a]['title'] = '$'.$b['price'].' - '.$b['title'];
				//$ret_arr[$a]['title'] =  $b['size'].' + $'.$b['price'];;
				$ret_arr[$a]['value'] = $val;
				$ret_arr[$a]['price'] = floatval($b['price']);
				
				// Other variables used for birthstone combo box
				$ret_arr[$a]['id'] = $b['id'];
				
				if(isset($b['optid']))
					$ret_arr[$a]['optid'] = $b['optid'];
					
				if(isset($b['optvarid']))
					$ret_arr[$a]['optvarid'] = $b['optvarid'];
				
				// selected in combo box if matching the default value in session
				if(!is_null($def) && $def_arr[0] == $a) {
					$go_sel = true;
				}
			}else if($type == 'size') {
				
				// Split default value - metal number and code
				//$def_arr = explode("-", $def);
				
				// Name and Value passed in combo box
				$ret_arr[$a]['title'] = $b['title'];
				//$ret_arr[$a]['title'] =  $b['size'].' + $'.$b['price'];;
				$ret_arr[$a]['value'] = $b['title'];
				$ret_arr[$a]['price'] = floatval($b['price']);
				
				// Other variables used for birthstone combo box
				//$ret_arr[$a]['id'] = $b['id'];
				
				if(isset($b['optid']))
					$ret_arr[$a]['optid'] = $b['optid'];
					
				if(isset($b['optvarid']))
					$ret_arr[$a]['optvarid'] = $b['optvarid'];
				
				// selected in combo box if matching the default value in session
				if(!is_null($def) && $def == $ret_arr[$a]['value']) {
					$go_sel = true;
				}
			}
			
			// selected in combo box		
			if($go_sel) {
				$ret_arr[$a]['selected'] = true;
			}
		}
		
		return $ret_arr;
		
	}
}


// Function to get Add-on Prices for Engraving Options
if (!function_exists('FUNC_CUSTOM_engraving')) {
	function FUNC_CUSTOM_engraving($pid, $type, $def = NULL) {
		
		// Get values from database
		$opts = array();
		$product = Mage::getModel('catalog/product');
		$productId = $product->getIdBySku( $pid );
		$product->load($productId);
		foreach ($product->getOptions() as $o) {
			if($o->getEnable() == 'true') {
				if ($o->getType() == 'radio') {
					$opts[strtolower($o->getClassCode())] = array();
					foreach ($o->getValues() as $k => $v) {
						$opts[strtolower($o->getClassCode())][strtolower($v->getClassCode())] = array('price' => number_format($v->getPrice(), 2),
													   'optvarid' => $v->getId(),
													   'optid' => $o->getId(),
													   'title' => $v->getTitle());
					}
				}elseif ($o->getType() == 'field') {
					//if(strtolower($o->getClassCode()) == 'data_holder') {
						$opts[strtolower($o->getClassCode())] = array('price' => number_format($o->getPrice(), 2),
													   'optid' => $o->getId(),
													   'max_chars' => $o->getMaxCharacters(),
													   'title' => $o->getTitle());
					//}
				}elseif ($o->getType() == 'area') {
					if(strtolower($o->getClassCode()) == 'data_holder') {
						$opts[strtolower($o->getClassCode())] = array('optid' => $o->getId());
					}
				}
			}
		}
		$eng = array(0 => array
				(
					'id' => 4805
					,'pid' => 70938
					,'cid' => 752
					,'category_mode' => 'lockets'
					,'extra_mode' => ''
					,'useThis' => 'y'
					,'PaperPrice' => ''
					,'cliparts_front' => 25.00
					,'text_front' => 10.00
					,'text_back' => 20.00
					,'text_left' => 35.00
					,'text_right' => 35.00
					,'text_inside1' => 35.00
					,'text_inside2' => 35.00
					,'pic1_front' => 50.00
					,'pic2_back' => 50.00
					,'pic3_left' => 50.00
					,'pic4_right' => 50.00
					,'pic5_inside1' => 50.00
					,'pic6_inside2' => 50.00
					,'color_laser' => ''
					,'notes_photo' => ''
					,'notes_textF' => ''
					,'notes_textB' => ''
					,'notes_textL' => ''
					,'notes_textR' => ''
					,'notes_textI1' => ''
					,'notes_textI2' => ''
				)
		
		);
		
		return $opts;
		
	}
}


// Function to get Metal details and price
if (!function_exists('FUNC_CUSTOM_metals')) {
	function FUNC_CUSTOM_metals($pid, $type, $def = NULL) {
		
		// Get values from database
		$opts = array();
		$product = Mage::getModel('catalog/product');
		$productId = $product->getIdBySku( $pid );
		$product->load($productId);
		foreach ($product->getOptions() as $o) {
			if($o->getEnable() == 'true') {
				if ($o->getType() == 'drop_down' && $o->getClassCode() == 'METAL_SIZE') {
					foreach ($o->getValues() as $k => $v) {
						$_arr = array();
						$_arr['pid'] = $pid;
						$_arr['optvarid'] = $v->getId();
						$_arr['optid'] = $o->getId();
						$_arr['title'] = $v->getTitle();
						$_arr['price'] = number_format($v->getPrice(), 2);
						$opts[] = $_arr;
					}
				}
			}
		}
		if (isset($opts) && count($opts) > 0) {
			foreach($opts as $a => $b) {
				$arr_list[] = $b;
			}
		} else {
			$opts = false;
		}
		
		if(!$opts) {
			// Generate values for basic combo box
			//return FUNC_cbo_ready(FUNC_get_XML($type), $type, $def != '' ? $def : NULL);
			return 'disabled';
		}else{
			// Generate values for custom metal combo box
			return FUNC_CUSTOM_cbo_ready($arr_list, $type, $def != '' ? $def : NULL);
		}
		
	}
}

// Function to get Birthstone details and price
if (!function_exists('FUNC_CUSTOM_stones')) {
	function FUNC_CUSTOM_stones($pid, $type, $def = NULL, $label = NULL) {
		
		// Get values from database
		$opts = array();
		$product = Mage::getModel('catalog/product');
		$productId = $product->getIdBySku( $pid );
		$product->load($productId);
		foreach ($product->getOptions() as $o) {
			if($o->getEnable() == 'true') {
				if ($o->getType() == 'drop_down' && ($o->getTitle() == $label)) {
				//if ($o->getType() == 'drop_down' && ($o->getClassCode() == 'BIRTH_STONE' || $o->getClassCode() == 'BIRTH_STONE1' || $o->getClassCode() == 'BIRTH_STONE2')) {
					foreach ($o->getValues() as $k => $v) {
						$_arr = array();
						$_arr['pid'] = $pid;
						$_arr['optvarid'] = $v->getId();
						$_arr['optid'] = $o->getId();
						$_arr['title'] = $v->getTitle();
						$_arr['price'] = number_format($v->getPrice(), 2);
						$opts[] = $_arr;
					}
				}
			}
		}
		
		if (isset($opts) && count($opts) > 0) {
			foreach($opts as $a => $b) {
				$arr_list[] = $b;
			}
		
		} else {
			$opts = false;
		}
	
		if(!$opts) {
			// Generate values for basic combo box
			//return FUNC_cbo_ready(FUNC_get_XML($type), $type, $def != '' ? $def : NULL);
			return 'disabled';
		}else{
			// Generate values for custom component combo box
			return FUNC_CUSTOM_cbo_ready($arr_list, $type, $def != '' ? $def : NULL);
		}
	}
}

// Function to get Size details and price
if (!function_exists('FUNC_CUSTOM_sizes')) {
	function FUNC_CUSTOM_sizes($pid, $type, $def = NULL) {
		
		$opts = array();
		$product = Mage::getModel('catalog/product');
		$productId = $product->getIdBySku( $pid );
		$product->load($productId);
		
		// Collect options applicable to the configurable product
		$productAttributeOptions = $product->getTypeInstance(true)->getConfigurableAttributesAsArray($product);
	
		foreach ($productAttributeOptions as $o ) {
			if ($o['label'] == "Size") {
				
				foreach ($o['values'] as $k => $v) {
							$_arr = array();
							$_arr['pid'] = $pid;
							$_arr['optid'] = $o['attribute_id'];
							$_arr['optvarid'] = $v['value_index'];
							$_arr['title'] = $v['label'];
							$_arr['price'] = number_format(0, 2);
							$opts[] = $_arr;
				}
			}
		}
	
		if (isset($opts) && count($opts) > 0) {
			foreach($opts as $a => $b) {
				$arr_list[] = $b;
			}
		} else {
			$opts = false;
		}
		
		if(!$opts) {
			// Generate values for basic combo box
			return 'disabled';
		}else{
			return FUNC_CUSTOM_cbo_ready($arr_list, $type, $def != '' ? $def : NULL);
			//return $arr_list;
		}
		
	}
}

?>