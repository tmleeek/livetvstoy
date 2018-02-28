<?php

// Text Functions Imagick

include_once 'text_arcs.php';

function FUNC_text_1($layer) {
	
	global $PATH_components, $core_width, $core_height;
	
	
	$VAR_PR = isset($layer['right']) ? $layer['right'] : 0;
	$VAR_PL = isset($layer['left']) ? $layer['left'] : 0;
	$VAR_textformat = isset($layer['textformat']) ? $layer['textformat'] : "none";
	
	$VAR_font_ARR = "";
	
	if(isset($layer['font']) and is_array($layer['font'])) {
		$VAR_font_ARR = $layer['font'];
	}else if(is_string($layer['font'])) {
		$VAR_font_ARR = FUNC_get_XML('font', $layer['font']);
	}else if(!isset($layer['font'])) {
		$VAR_font_ARR = FUNC_get_XML('font', 'Arial');
	}
	
	$VAR_PT = isset($layer['top']) ? $layer['top'] - $VAR_font_ARR['minus_y'] : 0 - $VAR_font_ARR['minus_y'];
	
	$VAR_size =  isset($layer['size']) ? $layer['size'] - $VAR_font_ARR['minus_size'] : (12 - $VAR_font_ARR['minus_size']);	
	
	if(isset($layer['minus_font_size']))
		$VAR_size = $VAR_size - $layer['minus_font_size'];
	
	$VAR_size = $VAR_size + 8;
	
	$VAR_align = $layer['align']; 			
		
		
	$VAR_font = $PATH_components.'fonts/'.$VAR_font_ARR['file_name'];
	
	$VAR_txt = $layer['txt'];
	
	
	
	$tmp_txt_Canvas = new Imagick();  
	
	$draw = new ImagickDraw();
	$draw->setFillColor("rgb(".$RGB['R'].",".$RGB['G'].",".$RGB['B'].")");
	$draw->setFont($VAR_font);
	$draw->setFontSize($VAR_size);
	$draw->setTextAntialias(true);
	$draw->setGravity(Imagick::GRAVITY_CENTER);
	
	$tmp_metrics = "";
	$tmp_metrics = $tmp_txt_Canvas->queryFontMetrics($draw, $VAR_txt);
	
	$VAR_width = $tmp_metrics['textWidth'];
	$VAR_height = $tmp_metrics['textHeight'];
	
	$tmp_txt_Canvas->newImage($VAR_width, $VAR_height, new ImagickPixel("none"));
	
	if($VAR_align == "center"){	
		$left_offset = $VAR_PL + ($core_width - $VAR_width - $VAR_PL - $VAR_PR) / 2;
	}elseif($VAR_align == "right"){
		$left_offset = $core_width - $VAR_PR - $VAR_width;
	} else {
		$left_offset = $VAR_PL;
	}
	
	$tmp_txt_Canvas->annotateImage($draw, 0, 0, 0, $VAR_txt);
	
	return array('txt_canvas' => $tmp_txt_Canvas, '_x' => $left_offset, '_y' => $VAR_PT);
	
}

// For rendering Monogram text
function FUNC_text_monogram($layer) {
	
	global $PATH_components, $core_width, $core_height;
	
	$VAR_default_text = "MTV";
	
	// Set text color
	if(isset($layer['RGB'])) $RGB = $layer['RGB'];
	
	if(isset($RGB) && is_array($RGB)) {
		$img_color = "rgb(".$RGB['R'].",".$RGB['G'].",".$RGB['B'].")";
	}else{
		$img_color = $layer['color'];
	}
	
	$shad_color = "white";
	
	if(strtolower($img_color) == 'none') $img_color = "";
	
	// Default position
	$VAR_PL = isset($layer['left']) ? $layer['left'] : 0;
	$VAR_PT = isset($layer['top']) ? $layer['top'] : 0;
	// Font size
	$VAR_size = isset($layer['size']) ? $layer['size'] : 12;	
	
	// Text value
	$VAR_txt = isset($layer['txt']) ? $layer['txt'] : '';
	
	if($VAR_txt == '') {
		$VAR_txt = $VAR_default_text;
	}
	
	$VAR_txt = strtolower($VAR_txt);	// lowercase
	$arr_str = str_split($VAR_txt);		// split text
	
	// Library for replacing third character of text
	$__alphabetical = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
	$__symbol = array("1","2","3","4","5","6","7","8","9","0","!","@","#","$","%","^","&","*","(",")","-","=","[","]","|",";");
	
	// Additional width or height
	$scale_w = 0;
	$scale_h = 0;
	
	// Define Canvas
	$tmp_txt_Canvas = new Imagick();  
	$draw = new ImagickDraw();
	$shad = new ImagickDraw();
	
	// Set colors
	$draw->setStrokeColor("#777777");
	//$draw->setStrokeColor($img_color);
	$shad->setStrokeColor($shad_color);
	
	// Set default stroke width and alpha
	$draw->setStrokeWidth(1);
	$draw->setStrokeOpacity(1);
	//$draw->setStrokeAlpha(1);
	$shad->setStrokeWidth(1);
	$shad->setStrokeOpacity(1);
	//$shad->setStrokeAlpha(1);
	
	// Set font, text, position, stroke width
	if($layer['font_style'] == "Script") {
		// Script Font Style	
		$VAR_font = $PATH_components.'fonts/'."vinesms.ttf";
		//$VAR_font = $PATH_components.'fonts/'."vinemsb.ttf";
		// 1 character
		if ( strlen($VAR_txt) == 1 ){
			$VAR_txt = " ".strtoupper($VAR_txt)." ";	// uppercase text
			$VAR_PL = $VAR_PL + (9 * ($VAR_size / 86));	
		// 2 characters
		}elseif ( strlen($VAR_txt) == 2 ){
			$VAR_txt = $arr_str[0]. strtoupper($arr_str[1]);
			$VAR_PL = $VAR_PL - (18 * ($VAR_size / 86));
			//$VAR_PL = $VAR_PL - (30 * ($VAR_size / 86));
		// 3 characters	
		}else{
			$VAR_txt = $arr_str[0].strtoupper($arr_str[1]).$arr_str[2];			
		}
		
		$draw->setStrokeWidth(1);
		$shad->setStrokeWidth(1);
		
	} else {
	// Block Font Style
		// 1 character
		if ( strlen($VAR_txt) == 1 ){
			
			if($layer['font'] == "Circle") {
					
				$VAR_txt = strtoupper($VAR_txt);
				$VAR_font = $PATH_components.'fonts/'."circle_1.ttf";
					
				$VAR_PT = $VAR_PT + (5 * ($VAR_size / 160));
				$VAR_PL = $VAR_PL - (50 * ($VAR_size / 160));
				$scale_h = -12;
					
				$draw->setStrokeWidth(2);
				$shad->setStrokeWidth(2);
					
			}else if($layer['font'] == "Heart") {
					
				$VAR_txt = " ".strtoupper($VAR_txt)." ";
				$VAR_font = $PATH_components.'fonts/'."heart.ttf";
					
				$VAR_PT = $VAR_PT - (6 * ($VAR_size / 150));
				$VAR_PL = $VAR_PL - (132 * ($VAR_size / 150));
				$scale_w = 50;

				$draw->setStrokeWidth(1);
				$shad->setStrokeWidth(1);
					
			}else if($layer['font'] == "Oval") {
					
				$str_a = strtoupper($VAR_txt);
				$VAR_txt = $str_a ;
				$VAR_font = $PATH_components.'fonts/'."oval_3.ttf";
					
				$VAR_PT = $VAR_PT + 6;
				$VAR_PL = $VAR_PL - (95 * ($VAR_size / 164));
				//$VAR_PL = $VAR_PL - (113 * ($VAR_size / 164));
				$scale_w = 50;
				$scale_h = -10;
					
				$draw->setStrokeWidth(2);
				$shad->setStrokeWidth(2);
			}
		// 2 characters
		} elseif ( strlen($VAR_txt) == 2 ){
			
			$VAR_txt = strtolower($arr_str[0]).strtoupper($arr_str[1]);
			
			if($layer['font'] == "Circle") {
				$VAR_font = $PATH_components.'fonts/'."circle_2.ttf";
			}else if($layer['font'] == "Heart") {
				$VAR_font = $PATH_components.'fonts/'."heart_2.ttf";
			}else if($layer['font'] == "Oval") {
				$VAR_font = $PATH_components.'fonts/'."oval_2.ttf";
				$VAR_PL = $VAR_PL - 15;
				//$VAR_PL = $VAR_PL + 5;
			}
		// 3 characters	
		}else{
			
			$i = 0;
			for( $i ; $i< strlen($VAR_txt); $i++ ){
				
				if($i==0){
					$str_a = strtolower($arr_str[$i]);
				}elseif($i==1){
					$str_b = strtoupper($arr_str[$i]);
				}elseif($i==2){
					
					if($layer['font'] == "Circle") {
						
						$VAR_font = $PATH_components.'fonts/'."circle_3.ttf";
							
						for($z=0;$z<count($__alphabetical);$z++){
							if($arr_str[$i]==$__alphabetical[$z]){
								$str_c = $__symbol[$z];
							}
						}
					}else if($layer['font'] == "Heart") {
						
						$VAR_font = $PATH_components.'fonts/'."heart.ttf";
							
						for($z=0;$z<count($__alphabetical);$z++){
							if($arr_str[$i]==$__alphabetical[$z]){
								$str_c = $__symbol[$z];
							}
						}
					}else if($layer['font'] == "Oval") {
						
						$VAR_font = $PATH_components.'fonts/'."oval_3.ttf";
							
						for($z=0;$z<count($__alphabetical);$z++){
							if($arr_str[$i]==$__alphabetical[$z]){
								$str_c = $__symbol[$z];
							}
						}
					}
				}
			}
			$VAR_txt = $str_a.$str_b.$str_c;
		}	
	}

	// Set Draw parameters
	$draw->setFillColor($img_color);	
	$draw->setFont($VAR_font);
	$draw->setFontSize($VAR_size);
	$draw->setTextAntialias(true);
	$draw->setGravity(Imagick::GRAVITY_CENTER);
		
	// Set dimensions
	$tmp_metrics = "";
	
	if($layer['font_style'] == "Script") {
		// Script Font Style
		$tmp_metrics = $tmp_txt_Canvas->queryFontMetrics($draw, "aAa");		// adjustment for lower version of Imagick
		$VAR_width = $tmp_metrics['textWidth'] + 10;
		$VAR_height = $tmp_metrics['textHeight'];
		
	}else{
		// Block Font Style
		$tmp_metrics = $tmp_txt_Canvas->queryFontMetrics($draw, "aA3");		// adjustment for lower version of Imagick
		$VAR_width = $tmp_metrics['textWidth'] + 50;
		$VAR_height = $tmp_metrics['textHeight'] + 30;
	}
	
	// Render transparent image
	$tmp_txt_Canvas->newImage($VAR_width, $VAR_height, new ImagickPixel("none"));
	
	// Set Shadow parameters
	$shad->setFillColor($shad_color);
	$shad->setFont($VAR_font);
	$shad->setFontSize($VAR_size);
	$shad->setTextAntialias(true);
	$shad->setGravity(Imagick::GRAVITY_CENTER);
	
	// Render text with engraved effect	
	if ( strlen($VAR_txt) == 1 && $layer['font'] == "Circle"){
		
		$tmp_txt_Canvas->annotateImage($shad, -40, 0, 0, $VAR_txt);
		
		$tmp_txt_Canvas->annotateImage($draw, -41, -1, 0, $VAR_txt);
	} else {
		
		$tmp_txt_Canvas->annotateImage($shad, 0, 0, 0, $VAR_txt);
		
		$tmp_txt_Canvas->annotateImage($draw, -1.5, -1.5, 0, $VAR_txt);
	}
	
	// Resize Image
	$tmp_txt_Canvas->scaleImage($VAR_width + $scale_w, $VAR_height + $scale_h, false);
	// Return canvas and position parameters
	return array('txt_canvas' => $tmp_txt_Canvas, '_x' => $VAR_PL, '_y' => $VAR_PT);
	
}



function FUNC_wording_1($layer, $id) {
	
	global $VAR_line_spacing, $VAR_transparency, $PATH_components, $core_width, $core_height;
	
	// Get overall margins
	if(isset($VAR_font_ARR['minus_y'])){
		$VAR_PT = isset($layer['top']) ? $layer['top'] - $VAR_font_ARR['minus_y'] : 20 - $VAR_font_ARR['minus_y'];
	} else {
		$VAR_PT = isset($layer['top']) ? $layer['top'] : 20;
	}
	
	$VAR_PB = isset($layer['bottom']) ? $layer['bottom'] : 20;
	$VAR_PR = isset($layer['right']) ? $layer['right'] : 20;
	$VAR_PL = isset($layer['left']) ? $layer['left'] : 20;
	
	$img_effect = isset($layer['effect']) ? $layer['effect'] : "Engraved";
	
	$VAR_width = $core_width;
	$VAR_height = $core_height;
	
	$VAR_default_text = "Enter your text";
	
	// Initialize variables
	$VAR_total_Y = 0;
	$VAR_total_Yoffset = 0;
	$largest_line_width = 0;
	$n = 0;
	
	
	// Get wording data
	$__WORDING = array();
	
	if(isset($layer['hidden_data']) && is_array($layer['hidden_data'])) {
		$__WORDING = $layer['hidden_data'];
	}else{
		if(isset($layer['wording']) && is_array($layer['wording'])) {
			$__WORDING = $layer['wording'];
		}else{
			if(isset($layer['default'])) {
				$__WORDING = FUNC_get_XML('wording', $layer['default'], $id);
			}else{
				$__WORDING = FUNC_get_XML('wording', 'W1');
			}
		}
	}

	
	$__WORDING['font'] = isset($layer['font']) ? $layer['font'] : $__WORDING['font'];
	$__WORDING['color'] = isset($layer['RGB']) ? $layer['RGB'] : $__WORDING['color'];
	$__WORDING['align'] = isset($layer['align']) ? $layer['align'] : $__WORDING['align'];
	
	// Adjustments to allow single and double quotes in wording
	foreach($__WORDING['content']['line'] as &$Y){
		
		$Y = str_replace("#39", "'", $Y);	// Change back to single quote
		$Y = str_replace('#34', '"', $Y);	// Change back to double quote
	}
	

	$tmp_txt_Canvas = new Imagick();  
	$tmp_txt_Canvas->newImage($VAR_width, $VAR_height, new ImagickPixel("none"));
	
	$draw = new ImagickDraw();
	$draw->setTextAntialias(true);

		
	// Calculate Approximate Overall Text Width and Height 
	foreach($__WORDING['content']['line'] as $W => $V) {
		
		// Get data for each line
		$LN = $V;
		
		if($__WORDING['lock_font'] == 1)
			$LN = NULL;
		
		$_ARR_FONT = FUNC_get_W_val('font', $LN, $__WORDING, true, 'Arial');	
		
		$VAR_line_spacing = FUNC_get_W_val('line_spacing', $V, $__WORDING, false, 5);	
		$VAR_add_line_height = FUNC_get_W_val('add_line_height', $V, $__WORDING, false, 0);	
		$VAR_size = FUNC_get_W_val('size', $V, $__WORDING, false, 12);		
		$VAR_size = $VAR_size - $_ARR_FONT['minus_size'];	
		$VAR_txt = is_array($V) && isset($V['content']) ? $V['content'] : $V;
		
		if(count($__WORDING['content']['line']) <= 1 && $VAR_txt == "") {
			$VAR_txt = $VAR_default_text;
		}
			
		$VAR_font = $PATH_components.'fonts/'.$_ARR_FONT['file_name'];
	
		$draw->setFont($VAR_font);
		$draw->setFontSize($VAR_size);
		
		// Calculate dimensions for each line
		
		$tmp_metrics = "";
		$tmp_metrics = $tmp_txt_Canvas->queryFontMetrics($draw, $VAR_txt);

		$line_width = $tmp_metrics['textWidth'];
		$line_height = $tmp_metrics['textHeight'];
		
		//Get maximum line width
		if($line_width > $largest_line_width) $largest_line_width = $line_width;
		$VAR_total_width = $largest_line_width;
		
		// Get total height
		$VAR_total_Y = $VAR_total_Y + $line_height;
		
		if($VAR_add_line_height > 0){
			$VAR_total_Y = $VAR_total_Y + $VAR_add_line_height;
		}
		
		if($VAR_txt != "") {
			$VAR_total_Y = $VAR_total_Y + $VAR_line_spacing;
		}
		
					 
		$n++;		
	}
	
	$VAR_total_height = $VAR_total_Y;
	$VAR_container_height = $VAR_height - $VAR_PT - $VAR_PB;
	$VAR_container_width = $VAR_width - $VAR_PL - $VAR_PR;
	
	// Calculate resize factor to adjust text to fit container
	if($VAR_total_height > $VAR_container_height) {
		$VAR_resize = ($VAR_container_height) / $VAR_total_height;
	} else {
		$VAR_resize = 1;
	}
	
	if($VAR_total_width * $VAR_resize > $VAR_container_width) {
		$VAR_resize = ($VAR_container_width) / $VAR_total_width;
	}
	
	
	// Initialize variables again
	$n = 0;
	$tmp_spacing = 0;
	$VAR_total_Y = 0;
	$largest_line_width = 0;
	
	
	// Calculate Exact Overall Text Width and Height - with adjusted font
	foreach($__WORDING['content']['line'] as $W => $V) {
		
		// Get data for each line
		$LN = $V;
		
		if($__WORDING['lock_font'] == 1)
			$LN = NULL;
		
		$_ARR_FONT = FUNC_get_W_val('font', $LN, $__WORDING, true, 'Arial');	
		$VAR_line_spacing = FUNC_get_W_val('line_spacing', $V, $__WORDING, false, 5);	
		$VAR_add_line_height = FUNC_get_W_val('add_line_height', $V, $__WORDING, false, 0);	
		$VAR_size = FUNC_get_W_val('size', $V, $__WORDING, false, 12);		
		$VAR_size = $VAR_size - $_ARR_FONT['minus_size'];	
		$VAR_txt = is_array($V) && isset($V['content']) ? $V['content'] : $V;
		
		if(count($__WORDING['content']['line']) <= 1 && $VAR_txt == "") {
			$VAR_txt = $VAR_default_text;
		}
		
		$VAR_font = $PATH_components.'fonts/'.$_ARR_FONT['file_name'];
		
		// Adjust font size, line spacing and line height
		$VAR_size = floor($VAR_size * $VAR_resize);
		$VAR_line_spacing = floor($VAR_line_spacing * $VAR_resize);
		$VAR_add_line_height = floor($VAR_add_line_height * $VAR_resize);
		
		// Calculate dimensions for each line
		$draw->setFont($VAR_font);
		$draw->setFontSize($VAR_size);

		
		// Calculate dimensions for each line
		$tmp_metrics = "";
		$tmp_metrics = $tmp_txt_Canvas->queryFontMetrics($draw, $VAR_txt);
		
		$line_width = $tmp_metrics['textWidth'];
		$line_height = abs($tmp_metrics['textHeight']);
		
		//Get maximum line width
		if($line_width > $largest_line_width) $largest_line_width = $line_width;
		
		// Get total height
		$VAR_total_Y = $VAR_total_Y + $line_height;
		
		if($VAR_add_line_height > 0){
			$VAR_total_Y = $VAR_total_Y + $VAR_add_line_height;
		}
		
		if($VAR_txt != "") {
			$VAR_total_Y = $VAR_total_Y + $VAR_line_spacing;
		}
		
					 
		$n++;		
	}
	
	$VAR_total_height = $VAR_total_Y + 10;
	
	$VAR_total_width = $VAR_width - $VAR_PL - $VAR_PR + 10;
	
	// Initialize variables again
	$n = 0;
	$tmp_spacing = 0;
	$VAR_total_Y = 0;
	$largest_line_width = 0;

	
	foreach($__WORDING['content']['line'] as $W => $V) {
		
		// Assign data for each line
		$LN = $V;
		
		if($__WORDING['lock_font'] == 1)
			$LN = NULL;
		
		$_ARR_FONT = FUNC_get_W_val('font', $LN, $__WORDING, true, 'Arial');	
		$_RGB_ARR = isset($V['color']) && is_array($V['color']) ? $V['color'] : FUNC_get_W_val('color', $V, $__WORDING, false, 'Black');
		//$_RGB_ARR = is_array($V['color']) ? $V['color'] : FUNC_get_W_val('color', $V, $__WORDING, false, 'Black');
		
		if(isset($layer['inherit_step_color']) && is_array($__WORDING['color']))
			$_RGB_ARR = $__WORDING['color'];
			
		$VAR_line_spacing = FUNC_get_W_val('line_spacing', $V, $__WORDING, false, 5);	
		$VAR_add_line_height = FUNC_get_W_val('add_line_height', $V, $__WORDING, false, 0);
		$VAR_size = FUNC_get_W_val('size', $V, $__WORDING, false, 12);		
		$VAR_size = $VAR_size - $_ARR_FONT['minus_size'];
		$VAR_align = strtolower(FUNC_get_W_val('align', $V, $__WORDING, false, 'left'));
		
		// Adjust font size, line spacing and line height
		$VAR_size = floor($VAR_size * $VAR_resize);
		$VAR_line_spacing = floor($VAR_line_spacing * $VAR_resize);
		$VAR_add_line_height = floor($VAR_add_line_height * $VAR_resize);
		
		$VAR_txt = is_array($V) && isset($V['content']) ? $V['content'] : $V;
		
		if(count($__WORDING['content']['line']) <= 1 && $VAR_txt == "") {
			$VAR_txt = $VAR_default_text;
		}
		
		$VAR_font = $PATH_components.'fonts/'.$_ARR_FONT['file_name'];
		
		if(isset($_RGB_ARR['R']) && isset($_RGB_ARR['G']) && isset($_RGB_ARR['B']))
			$draw->setFillColor("rgb(".$_RGB_ARR['R'].",".$_RGB_ARR['G'].",".$_RGB_ARR['B'].")");
			
		//$draw->setFillColor("rgb(".$_RGB_ARR['R'].",".$_RGB_ARR['G'].",".$_RGB_ARR['B'].")");
		$draw->setFont($VAR_font);
		$draw->setFontSize($VAR_size);
		
		// Calculate dimensions for each line
		$tmp_metrics = "";
		$tmp_metrics = $tmp_txt_Canvas->queryFontMetrics($draw, $VAR_txt);
		
		$line_width = $tmp_metrics['textWidth'];
		$line_height = abs($tmp_metrics['textHeight']);

		// Calculate total height and line spacing
		$VAR_total_Y = $VAR_total_Y + $line_height;
		
		if($VAR_add_line_height > 0){
			$VAR_total_Y = $VAR_total_Y + $VAR_add_line_height;
		}
		
		if($VAR_txt != "") {
			$VAR_total_Y = $VAR_total_Y + $VAR_line_spacing;
		}
		
		// Calculate left position of text		
		if(strtolower($VAR_align) == "center" || $VAR_align == "c"){	
			$left_offset = ($VAR_total_width - $line_width) / 2;
		}elseif(strtolower($VAR_align) == "right" || $VAR_align == "ri"){
			$left_offset = $VAR_total_width - $line_width - 5;
		} else {
			$left_offset = 0;
		}
		
		if ( $img_effect == "Engraved"){
			$draw->setFillColor($layer['color']);
			$tmp_txt_Canvas->annotateImage($draw, $left_offset - 1, $VAR_total_Y - 1, 0, stripslashes($VAR_txt));
			$tmp_txt_Canvas->modulateImage(70,100,100);
		
		}elseif ( $img_effect == "None" || $img_effect == "Emboss" ){
			
			$tmp_txt_Canvas->annotateImage($draw, $left_offset, $VAR_total_Y, 0, stripslashes($VAR_txt));
	
		}
						 
		$n++;		
	}

	$VAR_total_Yoffset = ($VAR_height - $VAR_total_Y - $VAR_PT - $VAR_PB)/2;
	
	$VAR_total_Xoffset = $VAR_PL;
	
	return array('txt_canvas' => $tmp_txt_Canvas, '_x' => $VAR_total_Xoffset, '_y' => $VAR_PT + $VAR_total_Yoffset);
}

?>