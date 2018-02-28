<?php

// Imagick Arc Functions
// Chris 	08/09/12
// Mike 	07/02/12


function FUNC_text_Arc($layer) {
	
	global $PATH_components, $core_width, $core_height;
	
	// Parameters passed from attributes of Layer XML
	// Basic Parameters
	$VAR_width = isset($layer['width']) ? $layer['width'] : $core_width;	// Text Canvas Width
	$VAR_height = isset($layer['height']) ? $layer['height'] : 0;	// Text Canvas Height, not needed if using Font Sizeas reference for adjustment
	$VAR_PT = isset($layer['top']) ? $layer['top'] : 0;		// Text Canvas top position
	$VAR_PL = isset($layer['left']) ? $layer['left'] : 0;		// Text Canvas left position
	$VAR_PR = isset($layer['right']) ? $layer['right'] : 0;		// Text Canvas right position
	$VAR_align = isset($layer['align']) ? $layer['align'] : "center";	// Alignment of text on canvas
	$VAR_txt = isset($layer['txt']) ? $layer['txt'] : "";
	
	// Color Parameters
	$text_color = isset($layer['color']) ? $layer['color'] : "black";
	$talpha = isset($layer['talpha']) ? $layer['talpha'] : 1;		// Text alpha
	$shadow_color = isset($layer['scolor']) ? $layer['scolor'] : "black";
	$shadow_xoffset = isset($layer['sxoffset']) ? $layer['sxoffset'] : 0;		// Text Shadow horizontal position offset
	$shadow_yoffset = isset($layer['syoffset']) ? $layer['syoffset'] : 0;		// Text Shadow vertical position offset
	$shadow_depth = isset($layer['sdepth']) ? $layer['sdepth'] : 1;		// Text Shadow vertical position offset
	$shadow_alpha = isset($layer['salpha']) ? $layer['salpha'] : 1;		// Text Shadow alpha
	
	//Font parameters
	$VAR_font_ARR = "";
	
	if(isset($layer['font']) and is_array($layer['font'])) {
		$VAR_font_ARR = $layer['font'];
	}else if(is_string($layer['font'])) {
		$VAR_font_ARR = FUNC_get_XML('font', $layer['font']);
	}else if(!isset($layer['font'])) {
		$VAR_font_ARR = FUNC_get_XML('font', 'Arial');
	}
	
	$VAR_font = $PATH_components.'fonts/'.$VAR_font_ARR['file_name'];
	$VAR_size =  isset($layer['size']) ? $layer['size'] : 10;
	
	// Distortion Parameters
	$VAR_textformat = isset($layer['textformat']) ? $layer['textformat'] : "none";	// Type of Distortion on text canvas
	$VAR_rotation = isset($layer['rotation']) ? $layer['rotation'] : 0;		// Rotation of text canvas
	$VAR_skewx = isset($layer['skewx']) ? $layer['skewx'] : 0;		// Horizontal skew of text canvas
	$VAR_skewy = isset($layer['skewy']) ? $layer['skewy'] : 0;		// Vertical skew of text canvas
	$VAR_xscale = isset($layer['xscale']) ? $layer['xscale'] : 1;	// Horizontal resizing of text canvas
	$VAR_yscale = isset($layer['yscale']) ? $layer['yscale'] : 1;	// Vertical resizing of text canvas
	
	// Distortion Arc Parameters
	$VAR_direction =  isset($layer['direction']) ? $layer['direction'] : 'CW';		// Direction of text arc
	$VAR_args = isset($layer['arc_args']) ? $layer['arc_args'] : 0;		// Prameters of text arc: degree - rotation - outer radius - inside radius
	$VAR_args = explode("-", $VAR_args);
	
	// Adjustment on Text vertical position, position of Arial as reference point
	$VAR_PT = $VAR_PT - ($VAR_font_ARR['minus_y']*($VAR_size/40));
	
	// Adjustment on Font size, 40 as reference point
	$VAR_size = $VAR_size - ($VAR_font_ARR['minus_size']*($VAR_size/40));
	
	$VAR_size = $VAR_size + 8;
	
	//$VAR_length = strlen($VAR_txt);
	
	// Create text canvas
	$tmp_txt_Canvas = new Imagick();  
	
	// Set draw parameters
	$draw = new ImagickDraw();
	
	// For Text Shadow
	$draw->setFillColor($shadow_color);
	$draw->setfillopacity($shadow_alpha);
	//$draw->setFillAlpha($shadow_alpha);
	$draw->setFont($VAR_font);
	$draw->setFontSize($VAR_size);
	//$draw->setFontStretch(Imagick::STRETCH_ULTRAEXPANDED);
	$draw->setTextAntialias(true);
	//$draw->setTextAlignment(imagick::ALIGN_CENTER);
	
	if($VAR_align == 'left') {
		$draw->setGravity(Imagick::GRAVITY_WEST);
	} else if($VAR_align == 'right') {
		$draw->setGravity(Imagick::GRAVITY_EAST);
	} else {
		$draw->setGravity(Imagick::GRAVITY_CENTER);
	}
	
	// Get Font Metrics
	$tmp_metrics = "";
	$tmp_metrics = $tmp_txt_Canvas->queryFontMetrics($draw, $VAR_txt);
	
	// Draw shadow text
	$sx = $shadow_xoffset;
	$sy = $shadow_yoffset;

	for ($i = 1; $i <= $shadow_depth; $i++) {
		$draw->annotation(10 + $sx, $sy, $VAR_txt);
		$sx += $shadow_xoffset;
		$sy += $shadow_yoffset;
	}
	
	// Set color and alpha for text
	$draw->setFillColor($text_color);
	$draw->setfillopacity($talpha);
	//$draw->setFillAlpha($talpha);
	
	// Draw text on canvas
	$draw->annotation(10, 0, $VAR_txt);
	
	$VAR_PT = $VAR_PT - $tmp_metrics['textHeight'];
	
	//Use font height as reference height
	$VAR_height = $tmp_metrics['textHeight'];
	
	$VAR_delta = $VAR_args[0] * (0.017) * $VAR_height;
	//$VAR_delta = $VAR_args[0] * (3.14/180) * $VAR_height;
		
	/*
	echo '<pre>';
	print_r($tmp_metrics);
	echo '</pre>';
	
	exit;
	*/
	
	$tmp_txt_Canvas->newImage($VAR_width + 20, $VAR_height + 20, new ImagickPixel("none"));

	//$tmp_txt_Canvas->annotateImage($draw, 0, 0, $VAR_rotation, $VAR_txt);
	
	$tmp_txt_Canvas->drawImage($draw);
	
	$tmp_txt_Canvas->resizeImage($VAR_width, ($VAR_height * $VAR_yscale), imagick::FILTER_UNDEFINED, 1);

	
	if($VAR_direction == 'CCW') {
			
		$controlPoints = array( 0, 0,
				0, 0,

				0, $tmp_txt_Canvas->getImageHeight(),
				$VAR_delta/2, $tmp_txt_Canvas->getImageHeight(),

				$tmp_txt_Canvas->getImageWidth(), 0,
				$tmp_txt_Canvas->getImageWidth(), 0,

				$tmp_txt_Canvas->getImageWidth(), $tmp_txt_Canvas->getImageHeight(),
				$tmp_txt_Canvas->getImageWidth() - ($VAR_delta/2), $tmp_txt_Canvas->getImageHeight());
	
	} else {
		
		$controlPoints = array( 0, 0,
				$VAR_delta/2, 0,

				0, $tmp_txt_Canvas->getImageHeight(),
				0, $tmp_txt_Canvas->getImageHeight(),

				$tmp_txt_Canvas->getImageWidth(), 0,
				$tmp_txt_Canvas->getImageWidth() - ($VAR_delta/2), 0,

				$tmp_txt_Canvas->getImageWidth(), $tmp_txt_Canvas->getImageHeight(),
				$tmp_txt_Canvas->getImageWidth(), $tmp_txt_Canvas->getImageHeight());
		
	}
     
	$tmp_txt_Canvas->distortImage(Imagick::DISTORTION_PERSPECTIVE, $controlPoints, false);

	if($VAR_direction == 'CCW') {
		$tmp_txt_Canvas->rotateImage(new ImagickPixel('none'), 180);
	}
		
	$tmp_txt_Canvas->distortImage(Imagick::DISTORTION_ARC, $VAR_args, false);
	
	if((isset($VAR_skewx) && $VAR_skewx != 0) || (isset($VAR_skewy) && $VAR_skewy != 0)) {
		$tmp_txt_Canvas->shearImage(new ImagickPixel('none'), $VAR_skewx, $VAR_skewy); 
	}
	
	if((isset($VAR_rotation) && $VAR_rotation != 0)) {
		$tmp_txt_Canvas->rotateImage(new ImagickPixel('none'), $VAR_rotation); 
	}
	
	$tmp_Clone = clone $tmp_txt_Canvas;	
	//$tmp_Clone = $tmp_txt_Canvas->clone();
	$tmp_txt_Canvas->newImage($core_width, $core_height, new ImagickPixel("none"));
	$tmp_txt_Canvas->compositeImage($tmp_Clone, imagick::COMPOSITE_DEFAULT, $VAR_PL, $VAR_PT);
	//$tmp_txt_Canvas->compositeImage($tmp_Clone, imagick::COMPOSITE_BLEND, $VAR_PL, $VAR_PT);
	
	return array('txt_canvas' => $tmp_txt_Canvas);
}



function FUNC_text_Arc_2($layer) {
	
	global $PATH_components, $core_width, $core_height;
	
	// Parameters passed from attributes of Layer XML
	// Basic Parameters
	$VAR_width = isset($layer['width']) ? $layer['width'] : $core_width;	// Text Canvas Width
	$VAR_fit = isset($layer['fit']) ? $layer['fit'] : 0;	// Resize  Text to Fit Text Canvas Width
	$VAR_height = isset($layer['height']) ? $layer['height'] : 0;	// Text Canvas Height, use as reference
	$VAR_PT = isset($layer['top']) ? $layer['top'] : 0;		// Text Canvas top position
	$VAR_PL = isset($layer['left']) ? $layer['left'] : 0;		// Text Canvas left position
	$VAR_PR = isset($layer['right']) ? $layer['right'] : 0;		// Text Canvas right position
	$VAR_align = isset($layer['align']) ? $layer['align'] : "center";	// Alignment of text on canvas
	$VAR_txt = isset($layer['txt']) ? $layer['txt'] : "";
	
	// Color Parameters
	$text_color = isset($layer['color']) ? $layer['color'] : "black";
	$talpha = isset($layer['talpha']) ? $layer['talpha'] : 1;		// Text alpha
	$shadow_color = isset($layer['scolor']) ? $layer['scolor'] : "black";
	$shadow_xoffset = isset($layer['sxoffset']) ? $layer['sxoffset'] : 0;		// Text Shadow horizontal position offset
	$shadow_yoffset = isset($layer['syoffset']) ? $layer['syoffset'] : 0;		// Text Shadow vertical position offset
	$shadow_depth = isset($layer['sdepth']) ? $layer['sdepth'] : 0;		// Text Shadow vertical position offset
	$shadow_alpha = isset($layer['salpha']) ? $layer['salpha'] : 1;		// Text Shadow alpha
	
	//Font parameters
	$VAR_font_ARR = "";
	
	if(isset($layer['font']) and is_array($layer['font'])) {
		$VAR_font_ARR = $layer['font'];
	}else if(is_string($layer['font'])) {
		$VAR_font_ARR = FUNC_get_XML('font', $layer['font']);
	}else if(!isset($layer['font'])) {
		$VAR_font_ARR = FUNC_get_XML('font', 'Arial');
	}
	
	$VAR_font = $PATH_components.'fonts/'.$VAR_font_ARR['file_name'];
	$VAR_size =  isset($layer['size']) ? $layer['size'] : 10;
	
	// Distortion Parameters
	$VAR_textformat = isset($layer['textformat']) ? $layer['textformat'] : "none";	// Type of Distortion on text canvas
	$VAR_rotation = isset($layer['rotation']) ? $layer['rotation'] : 0;		// Rotation of text canvas
	$VAR_skewx = isset($layer['skewx']) ? $layer['skewx'] : 0;		// Horizontal skew of text canvas
	$VAR_skewy = isset($layer['skewy']) ? $layer['skewy'] : 0;		// Vertical skew of text canvas
	$VAR_xscale = isset($layer['xscale']) ? $layer['xscale'] : 1;	// Horizontal resizing of text canvas
	$VAR_yscale = isset($layer['yscale']) ? $layer['yscale'] : 1;	// Vertical resizing of text canvas
	$VAR_perspective = isset($layer['perspective']) ? $layer['perspective'] : 1;	// Vertical resizing of text canvas
	
	// Distortion Arc Parameters
	$VAR_direction =  isset($layer['direction']) ? $layer['direction'] : 'CW';		// Direction of text arc
	$VAR_args = isset($layer['arc_args']) ? $layer['arc_args'] : 0;		// Prameters of text arc: degree - rotation - outer radius - inside radius
	$VAR_args = explode("-", $VAR_args);
	
	// Adjustment on Text vertical position, position of Arial as reference point
	$VAR_PT = $VAR_PT - ($VAR_font_ARR['minus_y']*($VAR_size/40));
	
	// Adjustment on Font size, 40 as reference point
	$VAR_size = $VAR_size - ($VAR_font_ARR['minus_size']*($VAR_size/40));
	
	$VAR_size = $VAR_size + 8;
	
	//$VAR_length = strlen($VAR_txt);
	
	// Create text canvas
	$tmp_txt_Canvas = new Imagick();  
	
	// Set draw parameters
	$draw = new ImagickDraw();
	
	// For Text Shadow
	$draw->setFillColor($shadow_color);
	$draw->setfillopacity($shadow_alpha);
	//$draw->setFillAlpha($shadow_alpha);
	$draw->setFont($VAR_font);
	$draw->setFontSize($VAR_size);
	//$draw->setFontStretch(Imagick::STRETCH_ULTRAEXPANDED);
	$draw->setTextAntialias(true);
	//$draw->setTextAlignment(imagick::ALIGN_CENTER);
	
	if($VAR_align == 'left') {
		$draw->setGravity(Imagick::GRAVITY_WEST);
	} else if($VAR_align == 'right') {
		$draw->setGravity(Imagick::GRAVITY_EAST);
	} else {
		$draw->setGravity(Imagick::GRAVITY_CENTER);
	}
	
	// Get Font Metrics
	$tmp_metrics = "";
	$tmp_metrics = $tmp_txt_Canvas->queryFontMetrics($draw, $VAR_txt);
	
	$VAR_PT = $VAR_PT - $VAR_height;
	//$VAR_PT = $VAR_PT - $tmp_metrics['textHeight'];
	
	//Original text height
	$VAR_orig_height = $tmp_metrics['textHeight'];
	
	// Resize text font due to fit to width value
	if((isset($VAR_fit) && $VAR_fit != 0)) {
		
		$VAR_size = $VAR_size * ($VAR_width / $tmp_metrics['textWidth']);
		$draw->setFontSize($VAR_size);
		$tmp_metrics = $tmp_txt_Canvas->queryFontMetrics($draw, $VAR_txt);
		$VAR_new_height = $tmp_metrics['textHeight'];
	}
	
	if((isset($shadow_depth) && $shadow_depth != 0)) {
		// Draw shadow text
		$sx = $shadow_xoffset;
		$sy = $shadow_yoffset;
	
		for ($i = 1; $i <= $shadow_depth; $i++) {
			$draw->annotation($sx, $sy, $VAR_txt);
			$sx += $shadow_xoffset;
			$sy += $shadow_yoffset;
		}
	}
	
	// Set color and alpha for text
	$draw->setFillColor($text_color);
	$draw->setfillopacity($talpha);
	//$draw->setFillAlpha($talpha);
	
	// Draw text on canvas
	$draw->annotation(0, 0, $VAR_txt);
		
	/*
	echo '<pre>';
	print_r($tmp_metrics);
	echo '</pre>';
	
	exit;
	*/
	if((isset($VAR_fit) && $VAR_fit != 0)) {
		$tmp_txt_Canvas->newImage($VAR_width + 20, $VAR_new_height + 20, new ImagickPixel("none"));
	} else {
		$tmp_txt_Canvas->newImage($VAR_width + 20, $VAR_orig_height + 20, new ImagickPixel("none"));
	}

	//$tmp_txt_Canvas->annotateImage($draw, 0, 0, $VAR_rotation, $VAR_txt);
	
	$tmp_txt_Canvas->drawImage($draw);
	
	
	// Resize text due to xscale/yscale values
	$tmp_txt_Canvas->resizeImage(($VAR_width+20)*$VAR_xscale, ($VAR_height+20)*$VAR_yscale, imagick::FILTER_UNDEFINED, 1);
	
	//Set value for Perspecive Distortion
	$VAR_delta = $VAR_args[0] * (0.017 * $VAR_perspective) * $VAR_height;
	//$VAR_delta = $VAR_args[0] * (3.14/180) * $VAR_height;
	
	if($VAR_direction == 'CCW') {
			
		$controlPoints = array( 0, 0,
				0, 0,

				0, $tmp_txt_Canvas->getImageHeight(),
				$VAR_delta/2, $tmp_txt_Canvas->getImageHeight(),

				$tmp_txt_Canvas->getImageWidth(), 0,
				$tmp_txt_Canvas->getImageWidth(), 0,

				$tmp_txt_Canvas->getImageWidth(), $tmp_txt_Canvas->getImageHeight(),
				$tmp_txt_Canvas->getImageWidth() - ($VAR_delta/2), $tmp_txt_Canvas->getImageHeight());
	
	} else {
		
		$controlPoints = array( 0, 0,
				$VAR_delta/2, 0,

				0, $tmp_txt_Canvas->getImageHeight(),
				0, $tmp_txt_Canvas->getImageHeight(),

				$tmp_txt_Canvas->getImageWidth(), 0,
				$tmp_txt_Canvas->getImageWidth() - ($VAR_delta/2), 0,

				$tmp_txt_Canvas->getImageWidth(), $tmp_txt_Canvas->getImageHeight(),
				$tmp_txt_Canvas->getImageWidth(), $tmp_txt_Canvas->getImageHeight());
		
	}
     
	$tmp_txt_Canvas->distortImage(Imagick::DISTORTION_PERSPECTIVE, $controlPoints, false);

	if($VAR_direction == 'CCW') {
		$tmp_txt_Canvas->rotateImage(new ImagickPixel('none'), 180);
	}
		
	$tmp_txt_Canvas->distortImage(Imagick::DISTORTION_ARC, $VAR_args, false);
	
	if((isset($VAR_skewx) && $VAR_skewx != 0) || (isset($VAR_skewy) && $VAR_skewy != 0)) {
		$tmp_txt_Canvas->shearImage(new ImagickPixel('none'), $VAR_skewx, $VAR_skewy); 
	}
	
	if((isset($VAR_rotation) && $VAR_rotation != 0)) {
		$tmp_txt_Canvas->rotateImage(new ImagickPixel('none'), $VAR_rotation); 
	}
	
	
	//$tmp_txt_Canvas->distortImage(Imagick::DISTORTION_AFFINE , array(0,0,0,0 , 0,10,10,10 , 10,0,10,0), false);
	 
	
	//$degrees = 3*$VAR_length;
	//$degrees = array( 5*$VAR_length, 0, 600, 500 );
	
	//$tmp_txt_Canvas->distortImage(Imagick::DISTORTION_ARC, $degrees, true);
	//$tmp_txt_Canvas->evaluateImage(Imagick::EVALUATE_MULTIPLY, 0.5, Imagick::CHANNEL_ALPHA);
	//$tmp_txt_Canvas->waveImage(-20, 100);
	
	//$controlPoints = array(0,0,3,0,0,46,10,46,70,0,70,7,70,46,60,40);
	
	//$tmp_txt_Canvas->distortImage( Imagick::DISTORTION_PERSPECTIVE  , $controlPoints, false );
	//$tmp_txt_Canvas->distortImage( Imagick::DISTORTION_ARC   , array(9, 0, 50), false );
	
	//$draw2 = new ImagickDraw();
	
	//$tmp_txt_Canvas->drawImage($draw2); 

	
	/*
	if(isset($layer['tile'])) {
		$tmp_Canvas = $tmp_Canvas->textureImage($tmp_txt_Canvas);
		$img_x = 0;
		$img_y = 0;
	}else{
		$tmp_Canvas->compositeImage($tmp_txt_Canvas, imagick::COMPOSITE_DEFAULT, 0, 0);
	}
	*/
	
	$tmp_Clone = clone $tmp_txt_Canvas;	
	//$tmp_Clone = $tmp_txt_Canvas->clone();
	$tmp_txt_Canvas->newImage($core_width, $core_height, new ImagickPixel("none"));
	$tmp_txt_Canvas->compositeImage($tmp_Clone, imagick::COMPOSITE_DEFAULT, $VAR_PL, $VAR_PT);
	//$tmp_txt_Canvas->compositeImage($tmp_Clone, imagick::COMPOSITE_BLEND, $VAR_PL, $VAR_PT);
	
	return array('txt_canvas' => $tmp_txt_Canvas);
}

// For Inside Engraving Text
function FUNC_text_Arc_2i($layer) {
	
	global $PATH_components, $core_width, $core_height;
	
	// Parameters passed from attributes of Layer XML
	// Basic Parameters
	$VAR_width = isset($layer['width']) ? $layer['width'] : $core_width;	// Text Canvas Width
	$VAR_fit = isset($layer['fit']) ? $layer['fit'] : 0;	// Resize  Text to Fit Text Canvas Width
	$VAR_height = isset($layer['height']) ? $layer['height'] : 0;	// Text Canvas Height, use as reference
	$VAR_PT = isset($layer['top']) ? $layer['top'] : 0;		// Text Canvas top position
	$VAR_PL = isset($layer['left']) ? $layer['left'] : 0;		// Text Canvas left position
	$VAR_PR = isset($layer['right']) ? $layer['right'] : 0;		// Text Canvas right position
	$VAR_align = isset($layer['align']) ? $layer['align'] : "center";	// Alignment of text on canvas
	$VAR_txt = isset($layer['txt']) ? $layer['txt'] : "";
	
	// Color Parameters
	$text_color = isset($layer['color']) ? $layer['color'] : "black";
	$talpha = isset($layer['talpha']) ? $layer['talpha'] : 1;		// Text alpha
	$shadow_color = "black"; // always use black for now
	//$shadow_color = isset($layer['scolor']) ? $layer['scolor'] : "black";
	$shadow_xoffset = isset($layer['sxoffset']) ? $layer['sxoffset'] : 0;		// Text Shadow horizontal position offset
	$shadow_yoffset = isset($layer['syoffset']) ? $layer['syoffset'] : 0;		// Text Shadow vertical position offset
	$shadow_depth = isset($layer['sdepth']) ? $layer['sdepth'] : 0;		// Text Shadow vertical position offset
	$shadow_alpha = isset($layer['salpha']) ? $layer['salpha'] : 1;		// Text Shadow alpha
	
	//Font parameters
	$VAR_font_ARR = "";
	
	if(isset($layer['font']) and is_array($layer['font'])) {
		$VAR_font_ARR = $layer['font'];
	}else if(is_string($layer['font'])) {
		$VAR_font_ARR = FUNC_get_XML('font', $layer['font']);
	}else if(!isset($layer['font'])) {
		$VAR_font_ARR = FUNC_get_XML('font', 'Arial');
	}
	
	$VAR_font = $PATH_components.'fonts/'.$VAR_font_ARR['file_name'];
	$VAR_size =  isset($layer['size']) ? $layer['size'] : 10;
	
	// Distortion Parameters
	$VAR_textformat = isset($layer['textformat']) ? $layer['textformat'] : "none";	// Type of Distortion on text canvas
	$VAR_rotation = isset($layer['rotation']) ? $layer['rotation'] : 0;		// Rotation of text canvas
	$VAR_skewx = isset($layer['skewx']) ? $layer['skewx'] : 0;		// Horizontal skew of text canvas
	$VAR_skewy = isset($layer['skewy']) ? $layer['skewy'] : 0;		// Vertical skew of text canvas
	$VAR_xscale = isset($layer['xscale']) ? $layer['xscale'] : 1;	// Horizontal resizing of text canvas
	$VAR_yscale = isset($layer['yscale']) ? $layer['yscale'] : 1;	// Vertical resizing of text canvas
	$VAR_perspective = isset($layer['perspective']) ? $layer['perspective'] : 1;	// Vertical resizing of text canvas
	
	// Distortion Arc Parameters
	$VAR_direction =  isset($layer['direction']) ? $layer['direction'] : 'CW';		// Direction of text arc
	$VAR_args = isset($layer['arc_args']) ? $layer['arc_args'] : 0;		// Prameters of text arc: degree - rotation - outer radius - inside radius
	$VAR_args = explode("-", $VAR_args);
	
	// Adjustment on Text vertical position, position of Arial as reference point
	$VAR_PT = $VAR_PT - ($VAR_font_ARR['minus_y']*($VAR_size/40));
	
	// Adjustment on Font size, 40 as reference point
	$VAR_size = $VAR_size - ($VAR_font_ARR['minus_size']*($VAR_size/40));
	
	$VAR_size = $VAR_size + 8;
	
	//$VAR_length = strlen($VAR_txt);
	
	// Create text canvas
	$tmp_txt_Canvas = new Imagick();  
	
	// Set draw parameters
	$draw = new ImagickDraw();
	
	// For Text Shadow
	$draw->setFillColor($text_color);
	$draw->setfillopacity($shadow_alpha);
	//$draw->setFillAlpha($shadow_alpha);
	$draw->setFont($VAR_font);
	$draw->setFontSize($VAR_size);
	//$draw->setFontStretch(Imagick::STRETCH_ULTRAEXPANDED);
	$draw->setTextAntialias(true);
	//$draw->setTextAlignment(imagick::ALIGN_CENTER);
	
	if($VAR_align == 'left') {
		$draw->setGravity(Imagick::GRAVITY_WEST);
	} else if($VAR_align == 'right') {
		$draw->setGravity(Imagick::GRAVITY_EAST);
	} else {
		$draw->setGravity(Imagick::GRAVITY_CENTER);
	}
	
	// Get Font Metrics
	$tmp_metrics = "";
	$tmp_metrics = $tmp_txt_Canvas->queryFontMetrics($draw, $VAR_txt);
	
	$VAR_PT = $VAR_PT - $VAR_height;
	//$VAR_PT = $VAR_PT - $tmp_metrics['textHeight'];
	
	//Original text height
	$VAR_orig_height = $tmp_metrics['textHeight'];
	
	// Resize text font due to fit to width value
	if((isset($VAR_fit) && $VAR_fit != 0)) {
		
		$VAR_size = $VAR_size * ($VAR_width / $tmp_metrics['textWidth']);
		$draw->setFontSize($VAR_size);
		$tmp_metrics = $tmp_txt_Canvas->queryFontMetrics($draw, $VAR_txt);
		$VAR_new_height = $tmp_metrics['textHeight'];
	}
	
	if((isset($shadow_depth) && $shadow_depth != 0)) {
		// Draw shadow text
		$sx = $shadow_xoffset;
		$sy = $shadow_yoffset;
	
		for ($i = 1; $i <= $shadow_depth; $i++) {
			$draw->annotation($sx, $sy, $VAR_txt);
			$sx += $shadow_xoffset;
			$sy += $shadow_yoffset;
		}
	}
	
	// Set color and alpha for text
	$draw->setFillColor($shadow_color);
	$draw->setfillopacity($talpha);
	//$draw->setFillAlpha($talpha);
	
	// Draw text on canvas
	$draw->annotation(0, 0, $VAR_txt);
		
	if((isset($VAR_fit) && $VAR_fit != 0)) {
		$tmp_txt_Canvas->newImage($VAR_width + 20, $VAR_new_height + 20, new ImagickPixel("none"));
	} else {
		$tmp_txt_Canvas->newImage($VAR_width + 20, $VAR_orig_height + 20, new ImagickPixel("none"));
	}

	//$tmp_txt_Canvas->annotateImage($draw, 0, 0, $VAR_rotation, $VAR_txt);
	
	$tmp_txt_Canvas->drawImage($draw);
	
	
	// Resize text due to xscale/yscale values
	$tmp_txt_Canvas->resizeImage(($VAR_width+20)*$VAR_xscale, ($VAR_height+20)*$VAR_yscale, imagick::FILTER_UNDEFINED, 1);
	
	//Set value for Perspecive Distortion
	$VAR_delta = $VAR_args[0] * (0.017 * $VAR_perspective) * $VAR_height;
	//$VAR_delta = $VAR_args[0] * (3.14/180) * $VAR_height;
	
	if($VAR_direction == 'CCW') {
			
		$controlPoints = array( 0, 0,
				0, 0,

				0, $tmp_txt_Canvas->getImageHeight(),
				$VAR_delta/2, $tmp_txt_Canvas->getImageHeight(),

				$tmp_txt_Canvas->getImageWidth(), 0,
				$tmp_txt_Canvas->getImageWidth(), 0,

				$tmp_txt_Canvas->getImageWidth(), $tmp_txt_Canvas->getImageHeight(),
				$tmp_txt_Canvas->getImageWidth() - ($VAR_delta/2), $tmp_txt_Canvas->getImageHeight());
	
	} else {
		
		$controlPoints = array( 0, 0,
				$VAR_delta/2, 0,

				0, $tmp_txt_Canvas->getImageHeight(),
				0, $tmp_txt_Canvas->getImageHeight(),

				$tmp_txt_Canvas->getImageWidth(), 0,
				$tmp_txt_Canvas->getImageWidth() - ($VAR_delta/2), 0,

				$tmp_txt_Canvas->getImageWidth(), $tmp_txt_Canvas->getImageHeight(),
				$tmp_txt_Canvas->getImageWidth(), $tmp_txt_Canvas->getImageHeight());
		
	}
     
	$tmp_txt_Canvas->distortImage(Imagick::DISTORTION_PERSPECTIVE, $controlPoints, false);

	if($VAR_direction == 'CCW') {
		$tmp_txt_Canvas->rotateImage(new ImagickPixel('none'), 180);
	}
		
	$tmp_txt_Canvas->distortImage(Imagick::DISTORTION_ARC, $VAR_args, false);
	
	if((isset($VAR_skewx) && $VAR_skewx != 0) || (isset($VAR_skewy) && $VAR_skewy != 0)) {
		$tmp_txt_Canvas->shearImage(new ImagickPixel('none'), $VAR_skewx, $VAR_skewy); 
	}
	
	if((isset($VAR_rotation) && $VAR_rotation != 0)) {
		$tmp_txt_Canvas->rotateImage(new ImagickPixel('none'), $VAR_rotation); 
	}
		
	$tmp_Clone = clone $tmp_txt_Canvas;	
	$tmp_txt_Canvas->newImage($core_width, $core_height, new ImagickPixel("none"));
	$tmp_txt_Canvas->compositeImage($tmp_Clone, imagick::COMPOSITE_DEFAULT, $VAR_PL, $VAR_PT);
	
	return array('txt_canvas' => $tmp_txt_Canvas);
}


function FUNC_top_text_1($layer) {
	
	global $PATH_components, $core_width, $core_height;
	
	
	$VAR_txt_1 = $VAR_txt = isset($layer['txt']) ? $layer['txt'] : "";
	$VAR_txt_2 = $VAR_txt = isset($layer['txt_2']) ? $layer['txt_2'] : "";
	$VAR_width = isset($layer['width']) ? $layer['width'] : $core_width;
	$VAR_height = isset($layer['height']) ? $layer['height'] : 0;
	$VAR_PT = isset($layer['top']) ? $layer['top'] : 0;		
	$VAR_PL = isset($layer['left']) ? $layer['left'] : 0;

	$VAR_star_size = isset($layer['star_size']) ? $layer['star_size'] : 0;
	$VAR_star_y = isset($layer['star_y']) ? $layer['star_y'] : 0;
	$VAR_color = isset($layer['color']) ? $layer['color'] : "black";
	$VAR_em_color = isset($layer['scolor']) ? $layer['scolor'] : "black";
	$VAR_emb_depth = isset($layer['sdepth']) ? $layer['sdepth'] : 5;
	$VAR_side_padding = isset($layer['side_pad']) ? $layer['side_pad'] : 0;
	$VAR_font_size = isset($layer['size']) ? $layer['size'] : 10;
	$VAR_arc_top_rad = isset($layer['arc_top_rad']) ? $layer['arc_top_rad'] : 0;
	$VAR_arc_bot_rad = isset($layer['arc_bot_rad']) ? $layer['arc_bot_rad'] : 0;
	$VAR_perspective = isset($layer['perspective']) ? $layer['perspective'] : 0;	
	$VAR_rotation = isset($layer['rotation']) ? $layer['rotation'] : 0;		
	
	$VAR_rect = isset($layer['rect']) ? $layer['rect'] : 0;	
	
	//Font parameters
	$VAR_font_ARR = "";
	
	if(isset($layer['font']) and is_array($layer['font'])) {
		$VAR_font_ARR = $layer['font'];
	}else if(is_string($layer['font'])) {
		$VAR_font_ARR = FUNC_get_XML('font', $layer['font']);
	}else if(!isset($layer['font'])) {
		$VAR_font_ARR = FUNC_get_XML('font', 'Arial');
	}
	
	$VAR_font = $PATH_components.'fonts/'.$VAR_font_ARR['file_name'];
	
	
	
	
	$VAR_txt_1 = " ".$VAR_txt_1." ";
	$VAR_txt_2 = " ".$VAR_txt_2." ";
	
	
	if($VAR_star_size > 0) {
		if((strlen($VAR_txt_2) - 2) == 0) {
			$VAR_side_padding = 0;
		}elseif((strlen($VAR_txt_1) - 2) == 0) {
			$VAR_side_padding = -1;
		}
	}
	
	
	
	
	
	$tmp_txt_Canvas = new Imagick();

	$draw = new ImagickDraw();
	$draw->setFillColor($VAR_em_color);
	$draw->setFont($VAR_font);
	$draw->setFontSize($VAR_font_size);
	$draw->setTextAntialias(true);
	$draw->setGravity(Imagick::GRAVITY_NORTHWEST);

	$tmp_metrics = array();
	$tmp_metrics['U'] = $tmp_txt_Canvas->queryFontMetrics($draw, $VAR_txt_1);
	$tmp_metrics['D'] = $tmp_txt_Canvas->queryFontMetrics($draw, $VAR_txt_2);
	
	$VAR_txt_width = $tmp_metrics['U']['textWidth'] + $tmp_metrics['D']['textWidth'] + ($VAR_side_padding * 4);
	$VAR_txt_height = max($tmp_metrics['U']['textHeight'],$tmp_metrics['D']['textHeight']);
	
	
	$ln = strlen($VAR_txt_1) + strlen($VAR_txt_2);
	
	$ln = $ln - 2;
	
	if($ln == 1) {
		$VAR_kern = 300;
	}else if($ln == 2) {
		$VAR_kern = 200;
	}else if($ln == 3) {
		$VAR_kern = 150;
	}else if($ln == 4) {
		$VAR_kern = 100;
	}else if($ln == 5) {
		$VAR_kern = 50;
	}else if($ln == 6) {
		$VAR_kern = 20;
	}else if($ln > 6) {
		$VAR_kern = 10;
	}
	
	if($ln > 10) $VAR_kern = 7;
	if($ln > 13) $VAR_kern = 5;
	if($ln > 18) $VAR_kern = 1;
	
	
	$draw->setTextKerning($VAR_kern);

	$tmp_metrics = array();
	$tmp_metrics['U'] = $tmp_txt_Canvas->queryFontMetrics($draw, $VAR_txt_1);
	$tmp_metrics['D'] = $tmp_txt_Canvas->queryFontMetrics($draw, $VAR_txt_2);

	$VAR_txt_width = $tmp_metrics['U']['textWidth'] + $tmp_metrics['D']['textWidth'] + ($VAR_side_padding * 4);
	$VAR_txt_height = max($tmp_metrics['U']['textHeight'],$tmp_metrics['D']['textHeight']);

	$tmp_txt_Canvas->newImage($VAR_txt_width, $VAR_txt_height, new ImagickPixel("none"));
	
	// Text
	for ($i = 1; $i <= $VAR_emb_depth; $i++) {
		$draw->annotation($VAR_side_padding, $i-6, $VAR_txt_1);
	}
	$draw->setFillColor($VAR_color);
	$draw->annotation($VAR_side_padding, -6, $VAR_txt_1);
	
	
	// Shadow
	$draw->setFillColor($VAR_em_color);
	$draw->rotate(180);
	for ($i = 1; $i <= $VAR_emb_depth; $i++) {
		$draw->annotation(-$tmp_metrics['D']['textWidth'] - $tmp_metrics['U']['textWidth'] - 1 - ($VAR_side_padding * 3), -$i + 3 - $tmp_metrics['D']['textHeight'], $VAR_txt_2);
	}
	$draw->setFillColor($VAR_color);
	$draw->annotation(-$tmp_metrics['D']['textWidth'] - $tmp_metrics['U']['textWidth'] - 1 - ($VAR_side_padding * 3), 3 - $tmp_metrics['D']['textHeight'], $VAR_txt_2);
	
	
	$tmp_txt_Canvas->drawImage($draw);
	
	if($VAR_star_size > 0) {
	
		$draw_star = new ImagickDraw(); 
		
		$go_star = false;
		
		if((strlen($VAR_txt_2) - 2) == 0) {
			FUNC_starator($draw_star, $tmp_metrics['U']['textWidth'] + ($tmp_metrics['D']['textWidth'] / 2) - ($VAR_star_size * 8), $VAR_star_y, $VAR_star_size, $VAR_emb_depth, $VAR_color,$VAR_em_color);
			$go_star = true; 
		}elseif((strlen($VAR_txt_1) - 2) == 0) {
			FUNC_starator($draw_star, ($tmp_metrics['U']['textWidth'] / 2) - ($VAR_star_size * 9.2), $VAR_star_y, $VAR_star_size, $VAR_emb_depth, $VAR_color,$VAR_em_color);
			$go_star = true; 
		}else{
			FUNC_starator($draw_star, - ($VAR_star_size * 8.4), $VAR_star_y, $VAR_star_size, $VAR_emb_depth, $VAR_color,$VAR_em_color); 
			FUNC_starator($draw_star, $VAR_txt_width - ($VAR_star_size * 8.8), $VAR_star_y, $VAR_star_size, $VAR_emb_depth, $VAR_color,$VAR_em_color);
			
			FUNC_starator($draw_star, $tmp_metrics['U']['textWidth'] - ($VAR_star_size * 4), $VAR_star_y, $VAR_star_size, $VAR_emb_depth, $VAR_color,$VAR_em_color); 
			$go_star = true;
		}
		
		if($go_star)
			$tmp_txt_Canvas->drawImage($draw_star);
	
	}

	$VAR_D = ($VAR_txt_width / 2) - (($tmp_metrics['U']['textWidth'] / 2) + ($VAR_side_padding));
	
	$VAR_deg = ($VAR_D / $VAR_txt_width) * 360;
	
	/* Perform the distortion */ 
	$tmp_txt_Canvas->distortImage(Imagick::DISTORTION_ARC, array(360, $VAR_deg, $VAR_arc_top_rad, $VAR_arc_bot_rad), false);
	
	//Distort to oval
	$tmp_txt_Canvas->scaleimage($VAR_width, $VAR_height, false);

		
	$VAR_txt_c_w = $tmp_txt_Canvas->getImageWidth();
	$VAR_txt_c_h = $tmp_txt_Canvas->getImageHeight();

	if($VAR_rect > 0) {
	
		$VAR_txt_c_w = $VAR_txt_c_w + ($VAR_rect * 2);
		$VAR_txt_c_h = $VAR_txt_c_h + ($VAR_rect * 2);
	
		$points = array( 
					$VAR_rect,$VAR_rect, 0,0, # top left 
					$VAR_rect, ($VAR_txt_c_h - $VAR_rect), 0, $VAR_txt_c_h, # bottom left 
					($VAR_txt_c_w - $VAR_rect),$VAR_rect, $VAR_txt_c_w,0, # top right
					($VAR_txt_c_w - $VAR_rect),($VAR_txt_c_h - $VAR_rect), $VAR_txt_c_w ,$VAR_txt_c_h # bottom right
					);
	
		$tmp_shep_Canvas = new Imagick();
		$tmp_shep_Canvas->newImage($tmp_txt_Canvas->getImageWidth() + ($VAR_rect * 2),$tmp_txt_Canvas->getImageHeight() + ($VAR_rect * 2), new ImagickPixel("none"));
		$tmp_shep_Canvas->compositeImage($tmp_txt_Canvas, imagick::COMPOSITE_DEFAULT, $VAR_rect,$VAR_rect);		
		$tmp_shep_Canvas->distortImage( Imagick::DISTORTION_SHEPARDS, $points, false );
		
		$tmp_txt_Canvas = $tmp_shep_Canvas;
		
		//$tmp_shep_Canvas->destroy();
	}
	
	if($VAR_perspective > 0) {
		
		$controlPoints = array( 0, 0,
						$VAR_perspective, 0,
		
						0, $VAR_txt_c_h,
						0, $VAR_txt_c_h,
		
						$VAR_txt_c_w, 0,
						$VAR_txt_c_w - $VAR_perspective, 0,
		
						$VAR_txt_c_w, $VAR_txt_c_h,
						$VAR_txt_c_w, $VAR_txt_c_h);
						
		$tmp_txt_Canvas->distortImage(Imagick::DISTORTION_PERSPECTIVE, $controlPoints, false);
	
	}
	
	if((isset($VAR_rotation) && $VAR_rotation != 0)) {
		$tmp_txt_Canvas->rotateImage(new ImagickPixel('none'), $VAR_rotation); 
	}

	return array('txt_canvas' => $tmp_txt_Canvas, '_x' => $VAR_PL, '_y' => $VAR_PT);
}





function FUNC_top_side_text($layer) {
	
	global $PATH_components, $core_width, $core_height;
	
	
	$VAR_txt_1 = $VAR_txt = isset($layer['txt']) ? $layer['txt'] : "";
	$VAR_txt_2 = $VAR_txt = isset($layer['txt_2']) ? $layer['txt_2'] : "";
	$VAR_width = isset($layer['width']) ? $layer['width'] : $core_width;
	$VAR_height = isset($layer['height']) ? $layer['height'] : 0;
	$VAR_PT = isset($layer['top']) ? $layer['top'] : 0;		
	$VAR_PL = isset($layer['left']) ? $layer['left'] : 0;

	$VAR_star_size = isset($layer['star_size']) ? $layer['star_size'] : 0;
	$VAR_star_y = isset($layer['star_y']) ? $layer['star_y'] : 0;
	$VAR_color = isset($layer['color']) ? $layer['color'] : "black";
	$VAR_em_color = isset($layer['scolor']) ? $layer['scolor'] : "black";
	$VAR_emb_depth = isset($layer['sdepth']) ? $layer['sdepth'] : 5;
	$VAR_side_padding = isset($layer['side_pad']) ? $layer['side_pad'] : 0;
	$VAR_font_size = isset($layer['size']) ? $layer['size'] : 10;

	$VAR_side = isset($layer['view_side']) ? $layer['view_side'] : 'L';
	$VAR_perspective = isset($layer['perspective']) ? $layer['perspective'] : 0;
	$VAR_xscale = isset($layer['xscale']) ? $layer['xscale'] : 1;
	$VAR_yscale = isset($layer['yscale']) ? $layer['yscale'] : 1;
	$VAR_degree = isset($layer['degree']) ? $layer['degree'] : 0;
	$VAR_args = isset($layer['arc_args']) ? $layer['arc_args'] : 0;	
	$VAR_args = explode("-", $VAR_args);
	
	
	//Font parameters
	$VAR_font_ARR = "";
	
	if(isset($layer['font']) and is_array($layer['font'])) {
		$VAR_font_ARR = $layer['font'];
	}else if(is_string($layer['font'])) {
		$VAR_font_ARR = FUNC_get_XML('font', $layer['font']);
	}else if(!isset($layer['font'])) {
		$VAR_font_ARR = FUNC_get_XML('font', 'Arial');
	}
	
	$VAR_font = $PATH_components.'fonts/'.$VAR_font_ARR['file_name'];
	
	
	$VAR_star_w = 0;
	
	if($VAR_side == 'L') {
		$VAR_txt_1 = " ".substr($VAR_txt_1, 0, strlen($VAR_txt_1)/2)." ";
		$VAR_txt_2 = " ".substr($VAR_txt_2, 0, strlen($VAR_txt_2)/2)." ";
	} else {
		$VAR_txt_1 = " ".substr($VAR_txt_1, strlen($VAR_txt_1)/2, strlen($VAR_txt_1))." ";
		$VAR_txt_2 = " ".substr($VAR_txt_2, strlen($VAR_txt_2)/2, strlen($VAR_txt_2))." ";		
	}
	
	//$VAR_txt_1 = " ".$VAR_txt_1." ";
	//$VAR_txt_2 = " ".$VAR_txt_2." ";
	
	
	if($VAR_star_size > 0) {
		if((strlen($VAR_txt_2) - 2) == 0) {
			$VAR_side_padding = 0;
		}elseif((strlen($VAR_txt_1) - 2) == 0) {
			$VAR_side_padding = -1;
		}
	}
	
	
	$tmp_txt_Canvas = new Imagick();

	$draw = new ImagickDraw();
	$draw->setFillColor($VAR_em_color);
	$draw->setFont($VAR_font);
	$draw->setFontSize($VAR_font_size);
	$draw->setTextAntialias(true);
	$draw->setGravity(Imagick::GRAVITY_NORTHWEST);

	$tmp_metrics = array();
	$tmp_metrics['U'] = $tmp_txt_Canvas->queryFontMetrics($draw, $VAR_txt_1);
	$tmp_metrics['D'] = $tmp_txt_Canvas->queryFontMetrics($draw, $VAR_txt_2);
	
	$VAR_txt_width = $tmp_metrics['U']['textWidth'] + $tmp_metrics['D']['textWidth'] + ($VAR_side_padding * 4);
	$VAR_txt_height = max($tmp_metrics['U']['textHeight'],$tmp_metrics['D']['textHeight']);
	
	
	$ln = strlen($VAR_txt_1) + strlen($VAR_txt_2);
	
	$ln = $ln - 2;
	
	if($ln == 1) {
		$VAR_kern = 300;
	}else if($ln == 2) {
		$VAR_kern = 200;
	}else if($ln == 3) {
		$VAR_kern = 150;
	}else if($ln == 4) {
		$VAR_kern = 100;
	}else if($ln == 5) {
		$VAR_kern = 50;
	}else if($ln == 6) {
		$VAR_kern = 20;
	}else if($ln > 6) {
		$VAR_kern = 10;
	}
	
	if($ln > 10) $VAR_kern = 7;
	if($ln > 13) $VAR_kern = 5;
	if($ln > 18) $VAR_kern = 2;
	
	
	$draw->setTextKerning($VAR_kern/3);

	$tmp_metrics = array();
	$tmp_metrics['U'] = $tmp_txt_Canvas->queryFontMetrics($draw, $VAR_txt_1);
	$tmp_metrics['D'] = $tmp_txt_Canvas->queryFontMetrics($draw, $VAR_txt_2);

	$VAR_txt_width = $tmp_metrics['U']['textWidth'] + $tmp_metrics['D']['textWidth'] + ($VAR_side_padding * 4);
	$VAR_txt_height = max($tmp_metrics['U']['textHeight'],$tmp_metrics['D']['textHeight']);

	$tmp_txt_Canvas->newImage($VAR_txt_width, $VAR_txt_height, new ImagickPixel("none"));
	
	
	
	if($VAR_side == 'L') {
		$draw->rotate(180);
		for ($i = 1; $i <= $VAR_emb_depth; $i++) {
			$draw->annotation(-$VAR_side_padding - $tmp_metrics['U']['textWidth'] - $i + 4, 0 - $tmp_metrics['U']['textHeight'], $VAR_txt_1);
		}
		$draw->setFillColor($VAR_color);
		$draw->annotation(-$VAR_side_padding - $tmp_metrics['U']['textWidth']+ 4, 0 - $tmp_metrics['U']['textHeight'], $VAR_txt_1);
		
		$draw->setFillColor($VAR_em_color);
		$draw->rotate(180);
		
		for ($i = 1; $i <= $VAR_emb_depth; $i++) {
			$draw->annotation($tmp_metrics['U']['textWidth'] + ($VAR_side_padding * 3) + $i, -3, $VAR_txt_2);
		}
		$draw->setFillColor($VAR_color);
		$draw->annotation($tmp_metrics['U']['textWidth'] + ($VAR_side_padding * 3)+ $i, -3, $VAR_txt_2);
		$VAR_star_w = $tmp_metrics['U']['textWidth'];
	}else{
		for ($i = 1; $i <= $VAR_emb_depth; $i++) {
			$draw->annotation($VAR_side_padding - $i, -3, $VAR_txt_2);
		}
		$draw->setFillColor($VAR_color);
		$draw->annotation($VAR_side_padding, -3, $VAR_txt_2);
		$draw->setFillColor($VAR_em_color);		
		$draw->rotate(180);

		for ($i = 1; $i <= $VAR_emb_depth; $i++) {
			$draw->annotation(-$tmp_metrics['U']['textWidth'] - $tmp_metrics['D']['textWidth'] - 1 - ($VAR_side_padding * 3) + $i - 4, 0 - $tmp_metrics['U']['textHeight'], $VAR_txt_1);
		}
		$draw->setFillColor($VAR_color);
		$draw->annotation(-$tmp_metrics['U']['textWidth'] - $tmp_metrics['D']['textWidth'] - 1 - ($VAR_side_padding * 3) - 4, 0 - $tmp_metrics['U']['textHeight'], $VAR_txt_1);
		
		$VAR_star_w = $tmp_metrics['D']['textWidth'];
	}
	
	/*
	// Text
	for ($i = 1; $i <= $VAR_emb_depth; $i++) {
		$draw->annotation($VAR_side_padding, $i-6, $VAR_txt_1);
	}
	$draw->setFillColor($VAR_color);
	$draw->annotation($VAR_side_padding, -6, $VAR_txt_1);
	
	
	// Shadow
	$draw->setFillColor($VAR_em_color);
	$draw->rotate(180);
	for ($i = 1; $i <= $VAR_emb_depth; $i++) {
		$draw->annotation(-$tmp_metrics['D']['textWidth'] - $tmp_metrics['U']['textWidth'] - 1 - ($VAR_side_padding * 3), -$i + 3 - $tmp_metrics['D']['textHeight'], $VAR_txt_2);
	}
	$draw->setFillColor($VAR_color);
	$draw->annotation(-$tmp_metrics['D']['textWidth'] - $tmp_metrics['U']['textWidth'] - 1 - ($VAR_side_padding * 3), 3 - $tmp_metrics['D']['textHeight'], $VAR_txt_2);
	*/
	
	$tmp_txt_Canvas->drawImage($draw);
	
	if($VAR_star_size > 0) {
	
		$draw_star = new ImagickDraw(); 
		
		$go_star = false;
		
		if((strlen($VAR_txt_2) - 2) == 0) {
			FUNC_starator($draw_star, $tmp_metrics['U']['textWidth'] + ($tmp_metrics['D']['textWidth'] / 2) - ($VAR_star_size * 8), $VAR_star_y, $VAR_star_size, $VAR_emb_depth, $VAR_color,$VAR_em_color);
			$go_star = true; 
		}elseif((strlen($VAR_txt_1) - 2) == 0) {
			FUNC_starator($draw_star, ($tmp_metrics['U']['textWidth'] / 2) - ($VAR_star_size * 9.2), $VAR_star_y, $VAR_star_size, $VAR_emb_depth, $VAR_color,$VAR_em_color);
			$go_star = true; 
		}else{
			FUNC_starator($draw_star, - ($VAR_star_size * 8.4), $VAR_star_y, $VAR_star_size, $VAR_emb_depth, $VAR_color,$VAR_em_color); 
			FUNC_starator($draw_star, $VAR_txt_width - ($VAR_star_size * 8.8), $VAR_star_y, $VAR_star_size, $VAR_emb_depth, $VAR_color,$VAR_em_color);
			
			FUNC_starator($draw_star, $VAR_star_w - ($VAR_star_size * 4), $VAR_star_y, $VAR_star_size, $VAR_emb_depth, $VAR_color,$VAR_em_color); 
			$go_star = true;
		}
		
		if($go_star)
			$tmp_txt_Canvas->drawImage($draw_star);
	
	}

	$tmp_txt_Canvas->resizeImage($VAR_width * $VAR_xscale, ($VAR_txt_height * $VAR_yscale), imagick::FILTER_UNDEFINED, 1);
	
	$controlPoints = array( 0, 0,
					$VAR_perspective, 0,
	
					0, $VAR_txt_height,
					0, $VAR_txt_height,
	
					$VAR_width, 0,
					$VAR_width - $VAR_perspective, 0,
	
					$VAR_width, $VAR_txt_height,
					$VAR_width, $VAR_txt_height);
	
	
	if($VAR_perspective > 0)				
		$tmp_txt_Canvas->distortImage(Imagick::DISTORTION_PERSPECTIVE, $controlPoints, false);
	
	$tmp_txt_Canvas->rotateImage(new ImagickPixel('none'), 180);
	
	$tmp_txt_Canvas->distortImage(Imagick::DISTORTION_ARC, $VAR_args, false);
	
	$VAR_PL = -(($VAR_width / 2) - ($VAR_width / 2)) + $VAR_PL;

	return array('txt_canvas' => $tmp_txt_Canvas, '_x' => $VAR_PL, '_y' => $VAR_PT);
}




	
		   
		   
function FUNC_star_points($_X, $_Y, $_size) {
	return array('star' => array(array('x' => (0 + $_X), 'y' => (6.25 * $_size + $_Y)),
						   array('x'=> (16.66 * $_size + $_X),'y'=> (6.25 * $_size) + $_Y),
						   array('x' => (3.3 * $_size) + $_X, 'y' => (16.66 * $_size) + $_Y),
						   array('x'=> (8.33 * $_size) + $_X,'y'=> (0 + $_Y)),
						   array('x' => (14.16 * $_size) + $_X, 'y' => (16.66 * $_size) + $_Y),
						   array('x'=> (0 + $_X),'y' => (6.25 * $_size) + $_Y)),
						   
				'penta' => array(array('x' => (10.52 * $_size) + $_X, 'y' => (6.25 * $_size + $_Y)),
						   array('x'=> (11.84 * $_size) + $_X,'y' => (10.01 * $_size) + $_Y),
						   array('x' => (8.6 * $_size) + $_X, 'y' => (12.57 * $_size) + $_Y),
						   array('x'=> (5.25 * $_size) + $_X,'y'=>(10.1 * $_size) + $_Y),
						   array('x' => (6.43 * $_size) + $_X, 'y' => (6.25 * $_size) + $_Y)));			
}


function FUNC_starator($drw, $_X, $_Y, $_size, $depth, $col, $em_col) {

	$drw->setFillColor($em_col);
	
	if(isset($depth)) {
		for ($i = 1; $i <= $depth; $i++) {
			$tmp_arr = FUNC_star_points($_X, $_Y + $i, $_size);
			$drw->polygon($tmp_arr['star']); 
			$drw->polygon($tmp_arr['penta']); 
		}
	}
	
	$tmp_arr = FUNC_star_points($_X, $_Y, $_size);
	$drw->setFillColor($col);
	$drw->polygon($tmp_arr['star']); 
	$drw->polygon($tmp_arr['penta']); 

}
// function FUNC_vert_arc_text($layer){
// global $PATH_components, $core_width, $core_height;
	
	//Parameters passed from attributes of Layer XML
	//Basic Parameters
	
	
	// $VAR_width = isset($layer['width']) ? $layer['width'] : $core_width;	// Text Canvas Width
	// $VAR_height = isset($layer['height']) ? $layer['height'] : 0;	// Text Canvas Height, not needed if using Font Sizeas reference for adjustment
	// $VAR_PT = isset($layer['top']) ? $layer['top'] : 0;		// Text Canvas top position
	// $VAR_PL = isset($layer['left']) ? $layer['left'] : 0;		// Text Canvas left position
	// $VAR_PR = isset($layer['right']) ? $layer['right'] : 0;		// Text Canvas right position
	// $VAR_align = isset($layer['align']) ? $layer['align'] : "center";	// Alignment of text on canvas
	// $VAR_txt = isset($layer['txt']) ? $layer['txt'] : "";
	
	// $var_x ="";
	// $i=0;
	// $textlength= strlen($VAR_txt);
	 // for($i=0;$i< $textlength ;$i++)
	 // {
	   // $var_x .=substr($VAR_txt,$i,1) . "\n"; 
	 // }
    // $VAR_txt = $var_x;
	//Color Parameters
	// $text_color = isset($layer['color']) ? $layer['color'] : "black";
	// $talpha = isset($layer['talpha']) ? $layer['talpha'] : 1;		// Text alpha
	// $shadow_color = isset($layer['scolor']) ? $layer['scolor'] : "black";
	// $shadow_xoffset = isset($layer['sxoffset']) ? $layer['sxoffset'] : 0;		// Text Shadow horizontal position offset
	// $shadow_yoffset = isset($layer['syoffset']) ? $layer['syoffset'] : 0;		// Text Shadow vertical position offset
	// $shadow_depth = isset($layer['sdepth']) ? $layer['sdepth'] : 1;		// Text Shadow vertical position offset
	// $shadow_alpha = isset($layer['salpha']) ? $layer['salpha'] : 1;		// Text Shadow alpha
	
	//Font parameters
	// $VAR_font_ARR = "";
	
	// if(isset($layer['font']) and is_array($layer['font'])) {
		// $VAR_font_ARR = $layer['font'];
	// }else if(is_string($layer['font'])) {
		// $VAR_font_ARR = FUNC_get_XML('font', $layer['font']);
	// }else if(!isset($layer['font'])) {
		// $VAR_font_ARR = FUNC_get_XML('font', 'Arial');
	// }
	
	// $VAR_font = $PATH_components.'fonts/'.$VAR_font_ARR['file_name'];
	// $VAR_size =  isset($layer['size']) ? $layer['size'] : 10;
	
	//Distortion Parameters
	// $VAR_textformat = isset($layer['textformat']) ? $layer['textformat'] : "none";	// Type of Distortion on text canvas
	// $VAR_rotation = isset($layer['rotation']) ? $layer['rotation'] : 0;		// Rotation of text canvas
	// $VAR_skewx = isset($layer['skewx']) ? $layer['skewx'] : 0;		// Horizontal skew of text canvas
	// $VAR_skewy = isset($layer['skewy']) ? $layer['skewy'] : 0;		// Vertical skew of text canvas
	// $VAR_xscale = isset($layer['xscale']) ? $layer['xscale'] : 1;	// Horizontal resizing of text canvas
	// $VAR_yscale = isset($layer['yscale']) ? $layer['yscale'] : 1;	// Vertical resizing of text canvas
	
	//Distortion Arc Parameters
	// $VAR_direction =  isset($layer['direction']) ? $layer['direction'] : 'CW';		// Direction of text arc
	// $VAR_args = isset($layer['arc_args']) ? $layer['arc_args'] : 0;		// Prameters of text arc: degree - rotation - outer radius - inside radius
	// $VAR_args = explode("-", $VAR_args);
	
	//Adjustment on Text vertical position, position of Arial as reference point
	// $VAR_PT = $VAR_PT - ($VAR_font_ARR['minus_y']*($VAR_size/40));
	
	//Adjustment on Font size, 40 as reference point
	// $VAR_size = $VAR_size - ($VAR_font_ARR['minus_size']*($VAR_size/40));
	
	// $VAR_size = $VAR_size + 8;
	
	//$VAR_length = strlen($VAR_txt);
	
	//Create text canvas
	// $tmp_txt_Canvas = new Imagick();  
	
	//Set draw parameters
	// $draw = new ImagickDraw();
	
	//For Text Shadow
	// $draw->setFillColor($shadow_color);
	// $draw->setFillAlpha($shadow_alpha);
	// $draw->setFont($VAR_font);
	// $draw->setFontSize($VAR_size);
	//$draw->setFontStretch(Imagick::STRETCH_ULTRAEXPANDED);
	// $draw->setTextAntialias(true);
	//$draw->setTextAlignment(imagick::ALIGN_CENTER);
	
	// if($VAR_align == 'left') {
		// $draw->setGravity(Imagick::GRAVITY_WEST);
	// } else if($VAR_align == 'right') {
		// $draw->setGravity(Imagick::GRAVITY_EAST);
	// } else {
		// $draw->setGravity(Imagick::GRAVITY_CENTER);
	// }
	
	//Get Font Metrics
	// $tmp_metrics = "";
	// $tmp_metrics = $tmp_txt_Canvas->queryFontMetrics($draw, $VAR_txt);
	
	//Draw shadow text
	// $sx = $shadow_xoffset;
	// $sy = $shadow_yoffset;

	// for ($i = 1; $i <= $shadow_depth; $i++) {
		// $draw->annotation(10 + $sx, $sy, $VAR_txt);
		// $sx += $shadow_xoffset;
		// $sy += $shadow_yoffset;
	// }
	
	//Set color and alpha for text
	// $draw->setFillColor($text_color);
	// $draw->setFillAlpha($talpha);
	
	//Draw text on canvas
	// $draw->annotation(10, 0, $VAR_txt);
	
	// $VAR_PT = $VAR_PT - $tmp_metrics['textHeight'];
	
//	Use font height as reference height
	// $VAR_height = $tmp_metrics['textHeight'];
	
	// $VAR_delta = $VAR_args[0] * (0.017) * $VAR_height;
	//$VAR_delta = $VAR_args[0] * (3.14/180) * $VAR_height;
		
	// /*
	// echo '<pre>';
	// print_r($tmp_metrics);
	// echo '</pre>';
	
	// exit;
	// */
	
	// $tmp_txt_Canvas->newImage($VAR_width + 20, $VAR_height + 20, new ImagickPixel("none"));

	//$tmp_txt_Canvas->annotateImage($draw, 0, 0, $VAR_rotation, $VAR_txt);
	
	// $tmp_txt_Canvas->drawImage($draw);
	
	// $tmp_txt_Canvas->resizeImage($VAR_width, ($VAR_height * $VAR_yscale), imagick::FILTER_UNDEFINED, 1);

	
	// if($VAR_direction == 'CCW') {
			
		// $controlPoints = array( 0, 0,
				// 0, 0,

				// 0, $tmp_txt_Canvas->getImageHeight(),
				// $VAR_delta/2, $tmp_txt_Canvas->getImageHeight(),

				// $tmp_txt_Canvas->getImageWidth(), 0,
				// $tmp_txt_Canvas->getImageWidth(), 0,

				// $tmp_txt_Canvas->getImageWidth(), $tmp_txt_Canvas->getImageHeight(),
				// $tmp_txt_Canvas->getImageWidth() - ($VAR_delta/2), $tmp_txt_Canvas->getImageHeight());
	
	// } else {
		
		// $controlPoints = array( 0, 0,
				// $VAR_delta/2, 0,

				// 0, $tmp_txt_Canvas->getImageHeight(),
				// 0, $tmp_txt_Canvas->getImageHeight(),

				// $tmp_txt_Canvas->getImageWidth(), 0,
				// $tmp_txt_Canvas->getImageWidth() - ($VAR_delta/2), 0,

				// $tmp_txt_Canvas->getImageWidth(), $tmp_txt_Canvas->getImageHeight(),
				// $tmp_txt_Canvas->getImageWidth(), $tmp_txt_Canvas->getImageHeight());
		
	// }
     
	// $tmp_txt_Canvas->distortImage(Imagick::DISTORTION_PERSPECTIVE, $controlPoints, false);

	// if($VAR_direction == 'CCW') {
		// $tmp_txt_Canvas->rotateImage(new ImagickPixel('none'), 180);
	// }
	// $tmp_txt_Canvas->rotateImage(new ImagickPixel('none'), -90)	
	
	// $tmp_txt_Canvas->distortImage(Imagick::DISTORTION_ARC, $VAR_args, false);
	
	// if((isset($VAR_skewx) && $VAR_skewx != 0) || (isset($VAR_skewy) && $VAR_skewy != 0)) {
		// $tmp_txt_Canvas->shearImage(new ImagickPixel('none'), $VAR_skewx, $VAR_skewy); 
	// }
	
	
	// if((isset($VAR_rotation) && $VAR_rotation != 0)) {
		// $tmp_txt_Canvas->rotateImage(new ImagickPixel('none'), $VAR_rotation); 
	// }
	
		
	// $tmp_Clone = $tmp_txt_Canvas->clone();
	// $tmp_txt_Canvas->newImage($core_width, $core_height, new ImagickPixel("none"));
	// $tmp_txt_Canvas->compositeImage($tmp_Clone, imagick::COMPOSITE_BLEND, $VAR_PL, $VAR_PT);
	
	// return array('txt_canvas' => $tmp_txt_Canvas);

// }

Function FUNC_vert_arc_text($layer)
{
global $PATH_components, $core_width, $core_height;
	
	//Parameters passed from attributes of Layer XML
	//Basic Parameters
	
	
	 $VAR_width = isset($layer['width']) ? $layer['width'] : $core_width;	// Text Canvas Width
	 $VAR_height = isset($layer['height']) ? $layer['height'] : 0;	// Text Canvas Height, not needed if using Font Sizeas reference for adjustment
	 $VAR_PT = isset($layer['top']) ? $layer['top'] : 0;		// Text Canvas top position
	 $VAR_PL = isset($layer['left']) ? $layer['left'] : 0;		// Text Canvas left position
	 $VAR_PR = isset($layer['right']) ? $layer['right'] : 0;		// Text Canvas right position
	 $VAR_align = isset($layer['align']) ? $layer['align'] : "center";	// Alignment of text on canvas
	 $VAR_txt = isset($layer['txt']) ? $layer['txt'] : "2012";
	 /*
	 $VAR_txt ="2012";
	 $var_x ="";
	 $i=0;
	 $textlength= strlen($VAR_txt);
	 for($i=0;$i< $textlength ;$i++)
	  {
	    $var_x .=substr($VAR_txt,$i,1) . "\n"; 
	  }
      $VAR_txt =  $var_x;
	  */
//$VAR_txt ="2\n0\n1\n2";	  
$image = new Imagick();
$draw = new ImagickDraw();
$color = new ImagickPixel('#f2f4f3');
$background = new ImagickPixel('none'); // Transparent

/* Font properties */
$draw->setFont('Arial');
$draw->setFontSize(50);
//$draw->setFillColor($color);
$draw->setFillColor('black');
//$draw->setStrokeAntialias(true);
$draw->setTextAntialias(true);
$draw->setGravity(Imagick::GRAVITY_CENTER);
//$draw->rotate(90);
/* Get font metrics */
$metrics = $image->queryFontMetrics($draw, $VAR_txt);

/* Create text */
//$draw->annotation(0, $metrics['ascender'], $text);
$draw->annotation(0, 10, $VAR_txt);

/* Create image */
//$image->newImage(300, 300, $background);
$image->newImage(300, 300, new ImagickPixel('none'));
//$image->setImageFormat('png');
$image->drawImage($draw);
//$image->rotateImage(new ImagickPixel('none'), -90);
//$VAR_args= array(150);
//$image->distortImage(Imagick::DISTORTION_ARC, $VAR_args, false);
//$image->rotateImage(new ImagickPixel('none'), 80);
//$image->setImageFormat('png');

/* Output the image with headers */
//header('Content-type: image/png');
   
return array('txt_canvas' => $image) ;  
}


function FUNC_text_Arc_3($layer) {
	
	global $PATH_components, $core_width, $core_height;
	
	// Parameters passed from attributes of Layer XML
	// Basic Parameters
	$VAR_width = isset($layer['width']) ? $layer['width'] : $core_width;	// Text Canvas Width
	//$VAR_fit = isset($layer['fit']) ? $layer['fit'] : 0;	// Resize  Text to Fit Text Canvas Width
	$VAR_height = isset($layer['height']) ? $layer['height'] : 0;	// Text Canvas Height, use as reference
	$VAR_PT = isset($layer['top']) ? $layer['top'] : 0;		// Text Canvas top position
	$VAR_PL = isset($layer['left']) ? $layer['left'] : 0;		// Text Canvas left position
	$VAR_PR = isset($layer['right']) ? $layer['right'] : 0;		// Text Canvas right position
	$VAR_align = isset($layer['align']) ? $layer['align'] : "center";	// Alignment of text on canvas
	$VAR_txt = isset($layer['txt']) ? $layer['txt'] : "";
	/*
	$var_x ="";
	$i=0;
	$textlength= strlen($VAR_txt);
	
	for($i=0;$i< $textlength ;$i++) {
		$var_x .=substr($VAR_txt,$i,1) . "\n"; 
	}
	
    $VAR_txt =  $var_x;
	*/
	
	// Color Parameters
	$text_color = isset($layer['color']) ? $layer['color'] : "black";
	$talpha = isset($layer['talpha']) ? $layer['talpha'] : 1;		// Text alpha
	$shadow_color = isset($layer['scolor']) ? $layer['scolor'] : "black";
	$shadow_xoffset = isset($layer['sxoffset']) ? $layer['sxoffset'] : 0;		// Text Shadow horizontal position offset
	$shadow_yoffset = isset($layer['syoffset']) ? $layer['syoffset'] : 0;		// Text Shadow vertical position offset
	$shadow_depth = isset($layer['sdepth']) ? $layer['sdepth'] : 0;		// Text Shadow vertical position offset
	$shadow_alpha = isset($layer['salpha']) ? $layer['salpha'] : 1;		// Text Shadow alpha
	
	//Font parameters
	$VAR_font_ARR = "";
	
	if(isset($layer['font']) and is_array($layer['font'])) {
		$VAR_font_ARR = $layer['font'];
	}else if(is_string($layer['font'])) {
		$VAR_font_ARR = FUNC_get_XML('font', $layer['font']);
	}else if(!isset($layer['font'])) {
		$VAR_font_ARR = FUNC_get_XML('font', 'Arial');
	}
	
	$VAR_font = $PATH_components.'fonts/'.$VAR_font_ARR['file_name'];
	$VAR_size =  isset($layer['size']) ? $layer['size'] : 10;
	
	// Distortion Parameters
	$VAR_textformat = isset($layer['textformat']) ? $layer['textformat'] : "none";	// Type of Distortion on text canvas
	$VAR_rotation = isset($layer['rotation']) ? $layer['rotation'] : 0;		// Rotation of text canvas
	$VAR_skewx = isset($layer['skewx']) ? $layer['skewx'] : 0;		// Horizontal skew of text canvas
	$VAR_skewy = isset($layer['skewy']) ? $layer['skewy'] : 0;		// Vertical skew of text canvas
	$VAR_xscale = isset($layer['xscale']) ? $layer['xscale'] : 1;	// Horizontal resizing of text canvas
	$VAR_yscale = isset($layer['yscale']) ? $layer['yscale'] : 1;	// Vertical resizing of text canvas
	$VAR_perspective = isset($layer['perspective']) ? $layer['perspective'] : 1;	// Vertical resizing of text canvas
	
	// Distortion Arc Parameters
	$VAR_direction =  isset($layer['direction']) ? $layer['direction'] : 'CW';		// Direction of text arc
	$VAR_args = isset($layer['arc_args']) ? $layer['arc_args'] : 0;		// Prameters of text arc: degree - rotation - outer radius - inside radius
	$VAR_args = explode("-", $VAR_args);
	
	// Adjustment on Text vertical position, position of Arial as reference point
	$VAR_PT = $VAR_PT - ($VAR_font_ARR['minus_y']*($VAR_size/40));
	
	// Adjustment on Font size, 40 as reference point
	$VAR_size = $VAR_size - ($VAR_font_ARR['minus_size']*($VAR_size/40));
	
	$VAR_size = $VAR_size + 8;
	
	//$VAR_length = strlen($VAR_txt);
	
	// Create text canvas
	$tmp_txt_Canvas = new Imagick();  
	
	// Set draw parameters
	$draw = new ImagickDraw();
	
	// For Text Shadow
	$draw->setFillColor($shadow_color);
	$draw->setfillopacity($shadow_alpha);
	//$draw->setFillAlpha($shadow_alpha);
	$draw->setFont($VAR_font);
	$draw->setFontSize($VAR_size);
	//$draw->setFontStretch(Imagick::STRETCH_ULTRAEXPANDED);
	$draw->setTextAntialias(true);
	//$draw->setTextAlignment(imagick::ALIGN_CENTER);
	/*
	if($VAR_align == 'left') {
		$draw->setGravity(Imagick::GRAVITY_WEST);
	} else if($VAR_align == 'right') {
		$draw->setGravity(Imagick::GRAVITY_EAST);
	} else {
		$draw->setGravity(Imagick::GRAVITY_CENTER);
	}
	*/
	$draw->setGravity(Imagick::GRAVITY_CENTER);
	
	// Get Font Metrics
	$tmp_metrics = "";
	$tmp_metrics = $tmp_txt_Canvas->queryFontMetrics($draw, $VAR_txt);
	
	$VAR_PT = $VAR_PT - $VAR_height;
	//$VAR_PT = $VAR_PT - $tmp_metrics['textHeight'];
	/*
	//Original text height
	$VAR_orig_height = $tmp_metrics['textHeight'];
	
	// Resize text font due to fit to width value
	if((isset($VAR_fit) && $VAR_fit != 0)) {
		
		$VAR_size = $VAR_size * ($VAR_width / $tmp_metrics['textWidth']);
		$draw->setFontSize($VAR_size);
		$tmp_metrics = $tmp_txt_Canvas->queryFontMetrics($draw, $VAR_txt);
		$VAR_new_height = $tmp_metrics['textHeight'];
	}
	
	if((isset($shadow_depth) && $shadow_depth != 0)) {
		// Draw shadow text
		$sx = $shadow_xoffset;
		$sy = $shadow_yoffset;
	
		for ($i = 1; $i <= $shadow_depth; $i++) {
			$draw->annotation($sx, $sy, $VAR_txt);
			$sx += $shadow_xoffset;
			$sy += $shadow_yoffset;
		}
	}
	*/
	// Set color and alpha for text
	$draw->setFillColor($text_color);
	$draw->setfillopacity($talpha);
	//$draw->setFillAlpha($talpha);
	
	// Draw text on canvas
	//$draw->annotation(0, 0, $VAR_txt);
	$i=0;
	$textlength= strlen($VAR_txt);
	$h=0;
	
	for($i=0;$i< $textlength ;$i++) {
		$draw->annotation(0, $h, substr($VAR_txt,$i,1));
		$h+=$tmp_metrics['ascender']*0.9;
	}
		
	/*
	echo '<pre>';
	print_r($tmp_metrics);
	echo '</pre>';
	
	exit;
	
	if((isset($VAR_fit) && $VAR_fit != 0)) {
		$tmp_txt_Canvas->newImage($VAR_width + 20, $VAR_new_height + 20, new ImagickPixel("none"));
	} else {
		$tmp_txt_Canvas->newImage($VAR_width + 20, $VAR_height + 20, new ImagickPixel("none"));
		//$tmp_txt_Canvas->newImage($VAR_width + 20, $VAR_orig_height + 20, new ImagickPixel("none"));
	}
	*/
	
	$tmp_txt_Canvas->newImage($VAR_width + 20, $VAR_height + 20, new ImagickPixel("none"));

	//$tmp_txt_Canvas->annotateImage($draw, 0, 0, $VAR_rotation, $VAR_txt);
	
	$tmp_txt_Canvas->drawImage($draw);
	
	$tmp_txt_Canvas->rotateImage(new ImagickPixel('none'), -90);
	
	$tmp_txt_Canvas->distortImage(Imagick::DISTORTION_ARC, $VAR_args, false);
	
	if((isset($VAR_rotation) && $VAR_rotation != 0)) {
		$tmp_txt_Canvas->rotateImage(new ImagickPixel('none'), $VAR_rotation); 
	}
	
	
	// Resize text due to xscale/yscale values
	$tmp_txt_Canvas->resizeImage(($tmp_txt_Canvas->getImageWidth())*$VAR_xscale, ($tmp_txt_Canvas->getImageHeight())*$VAR_yscale, imagick::FILTER_UNDEFINED, 1);
	/*
	//Set value for Perspecive Distortion
	if((isset($VAR_perspective) && $VAR_perspective != 1)) {
		$VAR_delta = $VAR_args[0] * (0.017 * $VAR_perspective) * ($tmp_txt_Canvas->getImageHeight());
		//$VAR_delta = $VAR_args[0] * (3.14/180) * $VAR_height;
		
		if($VAR_direction == 'CCW') {
				
			$controlPoints = array( 0, 0,
					0, 0,
	
					0, $tmp_txt_Canvas->getImageHeight(),
					$VAR_delta/2, $tmp_txt_Canvas->getImageHeight(),
	
					$tmp_txt_Canvas->getImageWidth(), 0,
					$tmp_txt_Canvas->getImageWidth(), 0,
	
					$tmp_txt_Canvas->getImageWidth(), $tmp_txt_Canvas->getImageHeight(),
					$tmp_txt_Canvas->getImageWidth() - ($VAR_delta/2), $tmp_txt_Canvas->getImageHeight());
		
		} else {
			
			$controlPoints = array( 0, 0,
					$VAR_delta/2, 0,
	
					0, $tmp_txt_Canvas->getImageHeight(),
					0, $tmp_txt_Canvas->getImageHeight(),
	
					$tmp_txt_Canvas->getImageWidth(), 0,
					$tmp_txt_Canvas->getImageWidth() - ($VAR_delta/2), 0,
	
					$tmp_txt_Canvas->getImageWidth(), $tmp_txt_Canvas->getImageHeight(),
					$tmp_txt_Canvas->getImageWidth(), $tmp_txt_Canvas->getImageHeight());
			
		}
		 
		$tmp_txt_Canvas->distortImage(Imagick::DISTORTION_PERSPECTIVE, $controlPoints, false);
	
	}
	*/
	/*
	if($VAR_direction == 'CCW') {
		$tmp_txt_Canvas->rotateImage(new ImagickPixel('none'), 180);
	}
	*/	
	//$tmp_txt_Canvas->distortImage(Imagick::DISTORTION_ARC, $VAR_args, false);
	
	if((isset($VAR_skewx) && $VAR_skewx != 0) || (isset($VAR_skewy) && $VAR_skewy != 0)) {
		$tmp_txt_Canvas->shearImage(new ImagickPixel('none'), $VAR_skewx, $VAR_skewy); 
	}
	
	
	
	//$VAR_args= array(50);
	
	//$tmp_txt_Canvas->rotateImage(new ImagickPixel('none'), 63);
	$tmp_Clone = clone $tmp_txt_Canvas;	
	//$tmp_Clone = $tmp_txt_Canvas->clone();
	$tmp_txt_Canvas->newImage($core_width, $core_height, new ImagickPixel("none"));
	$tmp_txt_Canvas->compositeImage($tmp_Clone, imagick::COMPOSITE_DEFAULT, $VAR_PL, $VAR_PT);
	//$tmp_txt_Canvas->compositeImage($tmp_Clone, imagick::COMPOSITE_BLEND, $VAR_PL, $VAR_PT);
	
	return array('txt_canvas' => $tmp_txt_Canvas);
}


function FUNC_text_Arc_4($layer) {
	
	global $PATH_components, $core_width, $core_height;
	
	// Parameters passed from attributes of Layer XML
	// Basic Parameters
	$VAR_width = isset($layer['width']) ? $layer['width'] : $core_width;	// Text Canvas Width
	//$VAR_fit = isset($layer['fit']) ? $layer['fit'] : 0;	// Resize  Text to Fit Text Canvas Width
	$VAR_height = isset($layer['height']) ? $layer['height'] : 0;	// Text Canvas Height, use as reference
	$VAR_PT = isset($layer['top']) ? $layer['top'] : 0;		// Text Canvas top position
	$VAR_PL = isset($layer['left']) ? $layer['left'] : 0;		// Text Canvas left position
	$VAR_PR = isset($layer['right']) ? $layer['right'] : 0;		// Text Canvas right position
	$VAR_align = isset($layer['align']) ? $layer['align'] : "center";	// Alignment of text on canvas
	$VAR_line_height = isset($layer['line_height']) ? $layer['line_height'] : 1;		// Text Canvas right position
	$VAR_txt = isset($layer['txt']) ? $layer['txt'] : "";
	/*
	$var_x ="";
	$i=0;
	$textlength= strlen($VAR_txt);
	
	for($i=0;$i< $textlength ;$i++) {
		$var_x .=substr($VAR_txt,$i,1) . "\n"; 
	}
	
    $VAR_txt =  $var_x;
	*/
	
	// Color Parameters
	$text_color = isset($layer['color']) ? $layer['color'] : "black";
	$talpha = isset($layer['talpha']) ? $layer['talpha'] : 1;		// Text alpha
	$shadow_color = isset($layer['scolor']) ? $layer['scolor'] : "black";
	$shadow_xoffset = isset($layer['sxoffset']) ? $layer['sxoffset'] : 0;		// Text Shadow horizontal position offset
	$shadow_yoffset = isset($layer['syoffset']) ? $layer['syoffset'] : 0;		// Text Shadow vertical position offset
	$shadow_depth = isset($layer['sdepth']) ? $layer['sdepth'] : 0;		// Text Shadow vertical position offset
	$shadow_alpha = isset($layer['salpha']) ? $layer['salpha'] : 1;		// Text Shadow alpha
	
	//Font parameters
	$VAR_font_ARR = "";
	
	if(isset($layer['font']) and is_array($layer['font'])) {
		$VAR_font_ARR = $layer['font'];
	}else if(is_string($layer['font'])) {
		$VAR_font_ARR = FUNC_get_XML('font', $layer['font']);
	}else if(!isset($layer['font'])) {
		$VAR_font_ARR = FUNC_get_XML('font', 'Arial');
	}
	
	$VAR_font = $PATH_components.'fonts/'.$VAR_font_ARR['file_name'];
	$VAR_size =  isset($layer['size']) ? $layer['size'] : 10;
	
	// Distortion Parameters
	$VAR_textformat = isset($layer['textformat']) ? $layer['textformat'] : "none";	// Type of Distortion on text canvas
	$VAR_rotation = isset($layer['rotation']) ? $layer['rotation'] : 0;		// Rotation of text canvas
	$VAR_skewx = isset($layer['skewx']) ? $layer['skewx'] : 0;		// Horizontal skew of text canvas
	$VAR_skewy = isset($layer['skewy']) ? $layer['skewy'] : 0;		// Vertical skew of text canvas
	$VAR_xscale = isset($layer['xscale']) ? $layer['xscale'] : 1;	// Horizontal resizing of text canvas
	$VAR_yscale = isset($layer['yscale']) ? $layer['yscale'] : 1;	// Vertical resizing of text canvas
	$VAR_perspective = isset($layer['perspective']) ? $layer['perspective'] : 1;	// Vertical resizing of text canvas
	
	// Distortion Arc Parameters
	$VAR_direction =  isset($layer['direction']) ? $layer['direction'] : 'CW';		// Direction of text arc
	$VAR_args = isset($layer['arc_args']) ? $layer['arc_args'] : 0;		// Prameters of text arc: degree - rotation - outer radius - inside radius
	$VAR_args = explode("-", $VAR_args);
	
	// Adjustment on Text vertical position, position of Arial as reference point
	$VAR_PT = $VAR_PT - ($VAR_font_ARR['minus_y']*($VAR_size/40));
	
	// Adjustment on Font size, 40 as reference point
	$VAR_size = $VAR_size - ($VAR_font_ARR['minus_size']*($VAR_size/40));
	
	$VAR_size = $VAR_size + 8;
	
	//$VAR_length = strlen($VAR_txt);
	
	// Create text canvas
	$tmp_txt_Canvas = new Imagick();  
	
	// Set draw parameters
	$draw = new ImagickDraw();
	
	// For Text Shadow
	$draw->setFillColor($shadow_color);
	$draw->setfillopacity($shadow_alpha);
	//$draw->setFillAlpha($shadow_alpha);
	$draw->setFont($VAR_font);
	$draw->setFontSize($VAR_size);
	//$draw->setFontStretch(Imagick::STRETCH_ULTRAEXPANDED);
	$draw->setTextAntialias(true);
	//$draw->setTextAlignment(imagick::ALIGN_CENTER);
	/*
	if($VAR_align == 'left') {
		$draw->setGravity(Imagick::GRAVITY_WEST);
	} else if($VAR_align == 'right') {
		$draw->setGravity(Imagick::GRAVITY_EAST);
	} else {
		$draw->setGravity(Imagick::GRAVITY_CENTER);
	}
	*/
	$draw->setGravity(Imagick::GRAVITY_CENTER);
	
	// Get Font Metrics
	$tmp_metrics = "";
	$tmp_metrics = $tmp_txt_Canvas->queryFontMetrics($draw, $VAR_txt);
	
	$VAR_PT = $VAR_PT - $VAR_height;
	//$VAR_PT = $VAR_PT - $tmp_metrics['textHeight'];
	/*
	//Original text height
	$VAR_orig_height = $tmp_metrics['textHeight'];
	
	// Resize text font due to fit to width value
	if((isset($VAR_fit) && $VAR_fit != 0)) {
		
		$VAR_size = $VAR_size * ($VAR_width / $tmp_metrics['textWidth']);
		$draw->setFontSize($VAR_size);
		$tmp_metrics = $tmp_txt_Canvas->queryFontMetrics($draw, $VAR_txt);
		$VAR_new_height = $tmp_metrics['textHeight'];
	}
	
	if((isset($shadow_depth) && $shadow_depth != 0)) {
		// Draw shadow text
		$sx = $shadow_xoffset;
		$sy = $shadow_yoffset;
	
		for ($i = 1; $i <= $shadow_depth; $i++) {
			$draw->annotation($sx, $sy, $VAR_txt);
			$sx += $shadow_xoffset;
			$sy += $shadow_yoffset;
		}
	}
	*/
	// Set color and alpha for text
	$draw->setFillColor($text_color);
	$draw->setfillopacity($talpha);
	//$draw->setFillAlpha($talpha);
	
	// Draw text on canvas
	//$draw->annotation(0, 0, $VAR_txt);
	$i=0;
	$textlength= strlen($VAR_txt);
	$h=0;
	
	for($i=0;$i< $textlength ;$i++) {
		$draw->annotation(0, $h, substr($VAR_txt,$i,1));
		$h+=$tmp_metrics['ascender'] * $VAR_line_height;
	}
		
	/*
	echo '<pre>';
	print_r($tmp_metrics);
	echo '</pre>';
	
	exit;
	
	if((isset($VAR_fit) && $VAR_fit != 0)) {
		$tmp_txt_Canvas->newImage($VAR_width + 20, $VAR_new_height + 20, new ImagickPixel("none"));
	} else {
		$tmp_txt_Canvas->newImage($VAR_width + 20, $VAR_height + 20, new ImagickPixel("none"));
		//$tmp_txt_Canvas->newImage($VAR_width + 20, $VAR_orig_height + 20, new ImagickPixel("none"));
	}
	*/
	
	$tmp_txt_Canvas->newImage($VAR_width + 20, $VAR_height + 20, new ImagickPixel("none"));

	//$tmp_txt_Canvas->annotateImage($draw, 0, 0, $VAR_rotation, $VAR_txt);
	
	$tmp_txt_Canvas->drawImage($draw);
	
	if($VAR_direction == 'CCW') {
	
		$tmp_txt_Canvas->rotateImage(new ImagickPixel('none'), -90);
		
	} else {
		
		$tmp_txt_Canvas->rotateImage(new ImagickPixel('none'), 90);
	}
	
	$tmp_txt_Canvas->distortImage(Imagick::DISTORTION_ARC, $VAR_args, false);
	
	if((isset($VAR_rotation) && $VAR_rotation != 0)) {
		$tmp_txt_Canvas->rotateImage(new ImagickPixel('none'), $VAR_rotation); 
	}
	
	
	// Resize text due to xscale/yscale values
	$tmp_txt_Canvas->resizeImage(($tmp_txt_Canvas->getImageWidth())*$VAR_xscale, ($tmp_txt_Canvas->getImageHeight())*$VAR_yscale, imagick::FILTER_UNDEFINED, 1);
	/*
	//Set value for Perspecive Distortion
	if((isset($VAR_perspective) && $VAR_perspective != 1)) {
		$VAR_delta = $VAR_args[0] * (0.017 * $VAR_perspective) * ($tmp_txt_Canvas->getImageHeight());
		//$VAR_delta = $VAR_args[0] * (3.14/180) * $VAR_height;
		
		if($VAR_direction == 'CCW') {
				
			$controlPoints = array( 0, 0,
					0, 0,
	
					0, $tmp_txt_Canvas->getImageHeight(),
					$VAR_delta/2, $tmp_txt_Canvas->getImageHeight(),
	
					$tmp_txt_Canvas->getImageWidth(), 0,
					$tmp_txt_Canvas->getImageWidth(), 0,
	
					$tmp_txt_Canvas->getImageWidth(), $tmp_txt_Canvas->getImageHeight(),
					$tmp_txt_Canvas->getImageWidth() - ($VAR_delta/2), $tmp_txt_Canvas->getImageHeight());
		
		} else {
			
			$controlPoints = array( 0, 0,
					$VAR_delta/2, 0,
	
					0, $tmp_txt_Canvas->getImageHeight(),
					0, $tmp_txt_Canvas->getImageHeight(),
	
					$tmp_txt_Canvas->getImageWidth(), 0,
					$tmp_txt_Canvas->getImageWidth() - ($VAR_delta/2), 0,
	
					$tmp_txt_Canvas->getImageWidth(), $tmp_txt_Canvas->getImageHeight(),
					$tmp_txt_Canvas->getImageWidth(), $tmp_txt_Canvas->getImageHeight());
			
		}
		 
		$tmp_txt_Canvas->distortImage(Imagick::DISTORTION_PERSPECTIVE, $controlPoints, false);
	
	}
	*/
	/*
	if($VAR_direction == 'CCW') {
		$tmp_txt_Canvas->rotateImage(new ImagickPixel('none'), 180);
	}
	*/	
	//$tmp_txt_Canvas->distortImage(Imagick::DISTORTION_ARC, $VAR_args, false);
	
	if((isset($VAR_skewx) && $VAR_skewx != 0) || (isset($VAR_skewy) && $VAR_skewy != 0)) {
		$tmp_txt_Canvas->shearImage(new ImagickPixel('none'), $VAR_skewx, $VAR_skewy); 
	}
	
	
	
	//$VAR_args= array(50);
	
	//$tmp_txt_Canvas->rotateImage(new ImagickPixel('none'), 63);
	$tmp_Clone = clone $tmp_txt_Canvas;	
	//$tmp_Clone = $tmp_txt_Canvas->clone();
	$tmp_txt_Canvas->newImage($core_width, $core_height, new ImagickPixel("none"));
	$tmp_txt_Canvas->compositeImage($tmp_Clone, imagick::COMPOSITE_DEFAULT, $VAR_PL, $VAR_PT);
	//$tmp_txt_Canvas->compositeImage($tmp_Clone, imagick::COMPOSITE_BLEND, $VAR_PL, $VAR_PT);
	
	return array('txt_canvas' => $tmp_txt_Canvas);
}


?>