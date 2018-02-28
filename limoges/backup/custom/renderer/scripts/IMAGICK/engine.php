<?php
// ImageMagick Rendering Engine for AJAX Designer

global $PATH_components, $core_width, $core_height, $PATH_current_product, $PATH_uploader, $site_root;	

// Create Imagick Canvas for Product Image for each step
$main_Canvas = new Imagick();

// Define black color
$col_black = new ImagickPixel();
$col_black->setColor("black");

// Create image with white background ( to use "none" for transparent)
$main_Canvas->newImage($core_width, $core_height, new ImagickPixel("white"));

// Render image for each layer
foreach ($__LAYERS as $id => $layer) {
	
	$tmp_Canvas = NULL;
	
	// Color style
	$img_color_style = isset($layer['color_style']) ? $layer['color_style'] : "";
	// Transparency
	$img_alpha = isset($layer['alpha']) ? $layer['alpha'] : 100;
	
	$RGB = "";
	
	// color as RGB
	if(isset($layer['RGB'])) $RGB = $layer['RGB'];
	
	// Set color to use
	if(is_array($RGB) && $RGB['title'] != 'Black') {
		$img_color = "rgb(".$RGB['R'].",".$RGB['G'].",".$RGB['B'].")";
	}else{
		if(isset($layer['color']))
			$img_color = $layer['color'];
	}
	
	if(strtolower($img_color) == 'none') $img_color = "";
	
	// Start Render image type
	if(isset($layer['type']) && $layer['type'] == "image") {
		
		// Position
		$img_x = isset($layer['left']) ? $layer['left'] : 0;
		$img_y = isset($layer['top']) ? $layer['top'] : 0;
		// Dimensions
		$img_width = isset($layer['width']) ? $layer['width'] : $core_width;
		$img_height = isset($layer['height']) ? $layer['height'] : $core_height;
		
		// Shape
		$img_shape = isset($layer['shape']) ? $layer['shape'] : "rectangle";
		// Fill
		$img_fill = isset($layer['fill']) ? $layer['fill'] : "none";
		$img_repeat = isset($layer['repeat']) ? $layer['repeat'] : "no";
		$img_tile = isset($layer['tile']) ? $layer['tile'] : "/graphics/canvas.png";
		// Border
		$img_border = isset($layer['border']) ? $layer['border'] : 0;
		
		if($step_flag) {
			$img_border = 0;
		}
		
		// Effect
		$img_effect = isset($layer['effect']) ? $layer['effect'] : "None";		
		
		// Set value for image or photo upload
		if(isset($layer['value'])) {
			
			// For image
			$img_str = $PATH_components.$layer['value'];
			$var_up = false;
			
			// Check if image exists
			
			$f_tmp = pathinfo($layer['value']);
				
			$file_image_sku = $f_tmp['dirname'].'/'.$productid.'/'.$f_tmp['filename'].'.'.$f_tmp['extension'];
			$file_image_default = $f_tmp['dirname'].'/'.$f_tmp['filename'].'.'.$f_tmp['extension'];
			
			// Path of image
			if (file_exists($PATH_components.$file_image_sku)) {
				$img_str = $PATH_components.$file_image_sku;
			} else {
				$img_str = $PATH_components.$file_image_default;
			}
				
			try	{
				 $tmp_Canvas = new Imagick($img_str) ;
			}catch (Exception $e){
				die('ERROR: No image found in server - ID: '.$id);
			}
				
			// Get dimensions
			$tmp_GEO = $tmp_Canvas->getImageGeometry();  			
	
			$img_width = isset($layer['width']) ? $layer['width'] : $tmp_GEO['width'];
			$img_height = isset($layer['height']) ? $layer['height'] :  $tmp_GEO['height'];

			// Resize image
			if($img_width != 0 or $img_height != 0)
				$tmp_Canvas->scaleImage($img_width, $img_height, false);
		}

	} // End Render image type
		

	
	if(isset($layer['type']) && $layer['type'] == "avatar") {

			// create transparent layer canvas
			$tmp_Canvas = new Imagick();  
			$tmp_Canvas->newImage($core_width, $core_height, new ImagickPixel("none"));
			// Set path of avatar image
			$img_str = $PATH_components.'avatar/'.$aid.'.png';
			
			// Check if avatar image exists on server
			if (file_exists($img_str)) {
				$tmp_avatar_Canvas = new Imagick($img_str);
			} else {
			
				// Fetch avatar image from Poptropica - to be done only once
				$img_url = 'http://ext2.poptropica.com/shop/php/fetchimage.php?id='.$aid.'&password=brand-xpand';
				$handle = fopen($img_url, 'rb');
				$tmp_avatar_Canvas = new Imagick();
				$tmp_avatar_Canvas->readImageFile($handle);
				// Resize avatar image for faster rendering
				$tmp_avatar_Canvas->scaleImage(0, 550, false);
				$tmp_avatar_Canvas->writeImage($img_str);
				fclose($handle);
				
			}
			
			// For local	
			//$img_str = $PATH_components.'samples/'.$aid.'.png';
			//$tmp_Canvas = new Imagick($img_str); 
			
			// Get dimensions
			$tmp_GEO = $tmp_avatar_Canvas->getImageGeometry();  
			
			$clip_width = $tmp_GEO['width'];
			$clip_height = $tmp_GEO['height'];
			
			// Resize birthstone
			if(isset($layer['resize'])) {
				$clip_width = $clip_width * $layer['resize'];
				$clip_height = $clip_height * $layer['resize'];
				$tmp_avatar_Canvas->scaleImage($clip_width, $clip_height, false);
			}
			
			$VAR_PL = isset($layer['left']) ? $layer['left'] : 0;
			$VAR_PR = isset($layer['right']) ? $layer['right'] : 0;
			
			if($VAR_PR > 0) {
				$img_x = $VAR_PL + ($core_width - $clip_width - $VAR_PL - $VAR_PR) / 2;
			} else {
				$img_x = $VAR_PL;
			}
			
			//$img_x = isset($layer['left']) ? $layer['left'] : 0;
			$img_y = isset($layer['top']) ? $layer['top'] : 0;
			
			//Use bottom position
			if(isset($layer['bottom']))
				$img_y = $core_height - ($clip_height + $layer['bottom']);
				
			$tmp_Canvas->compositeImage($tmp_avatar_Canvas, imagick::COMPOSITE_DEFAULT, $img_x, $img_y);
			
			// Set position to 0,0
			$img_x = 0;
			$img_y = 0;		
				
			if(isset($tmp_avatar_Canvas))
				$tmp_avatar_Canvas->destroy();
		
	
	// For Text/Monogram
	}else if(isset($layer['type']) && $layer['type'] == "text") {
				
		$layer['txt'] = $aname;
		$layer['txt'] = str_replace("#39", "'", $layer['txt']);
		$layer['txt'] = str_replace("#92", "\\", $layer['txt']);
		
		$tmp_Canvas = new Imagick();  
		$tmp_Canvas->newImage($core_width, $core_height, new ImagickPixel("none"));

		
		if(isset($layer['text_style'])) {
			
			if($layer['text_style'] == 'arc'){
				$tmp_arr = array();
				$tmp_arr = FUNC_text_Arc($layer);
				$tmp_Canvas->compositeImage($tmp_arr['txt_canvas'], imagick::COMPOSITE_DEFAULT, 0, 0);
			} else if($layer['text_style'] == 'arc2') {
				$tmp_arr = array();
				$tmp_arr = FUNC_text_Arc_2($layer);
				$tmp_Canvas->compositeImage($tmp_arr['txt_canvas'], imagick::COMPOSITE_DEFAULT, 0, 0);
				//$tmp_Canvas->compositeImage($tmp_arr['txt_canvas'], imagick::COMPOSITE_BLEND, 0, 0);
			} else if($layer['text_style'] == 'arc2i') {
				$tmp_arr = array();
				$tmp_arr = FUNC_text_Arc_2i($layer);
				$tmp_Canvas->compositeImage($tmp_arr['txt_canvas'], imagick::COMPOSITE_DEFAULT, 0, 0);
				//$tmp_Canvas->compositeImage($tmp_arr['txt_canvas'], imagick::COMPOSITE_BLEND, 0, 0);
			} else if($layer['text_style'] == 'top_text_1') {
				$tmp_arr = array();
				$tmp_arr = FUNC_top_text_1($layer);
				$tmp_Canvas->compositeImage($tmp_arr['txt_canvas'], imagick::COMPOSITE_DEFAULT, $tmp_arr['_x'], $tmp_arr['_y']);
			} else if($layer['text_style'] == 'top_side_text') {
				$tmp_arr = array();
				$tmp_arr = FUNC_top_side_text($layer);
				$tmp_Canvas->compositeImage($tmp_arr['txt_canvas'], imagick::COMPOSITE_DEFAULT, $tmp_arr['_x'], $tmp_arr['_y']);
			} else if($layer['text_style'] == 'vert_arc_text') {
				 $tmp_arr = array();
				 $tmp_arr = FUNC_text_Arc_3($layer);
				 //$tmp_arr = FUNC_vert_arc_text($layer);
				 //$tmp_Canvas->compositeImage($tmp_arr['txt_canvas'], imagick::COMPOSITE_DEFAULT, $tmp_arr['_x'], $tmp_arr['_y']);
				 $tmp_Canvas->compositeImage($tmp_arr['txt_canvas'], imagick::COMPOSITE_DEFAULT, 0, 0);
				 //$tmp_Canvas->compositeImage($tmp_arr['txt_canvas'], imagick::COMPOSITE_BLEND, 0, 0);
			} else if($layer['text_style'] == 'vert_text_1') {
				 $tmp_arr = array();
				 $tmp_arr = FUNC_text_Arc_4($layer);
				 $tmp_Canvas->compositeImage($tmp_arr['txt_canvas'], imagick::COMPOSITE_DEFAULT, 0, 0);
				 //$tmp_Canvas->compositeImage($tmp_arr['txt_canvas'], imagick::COMPOSITE_BLEND, 0, 0);
			// For Monogram
			} else if($layer['text_style'] == 'monogram') {
				$tmp_arr = array();
				// Create monogram text using function in text.php
				$tmp_arr = FUNC_text_monogram($layer);
				// Render monogram text to layer canvas
				$tmp_Canvas->compositeImage($tmp_arr['txt_canvas'], imagick::COMPOSITE_DEFAULT, $tmp_arr['_x'], $tmp_arr['_y']);
			// For Text 2
			} else if($layer['text_style'] == 'text2') {
				$tmp_arr = array();
				// Create monogram text using function in text.php
				$tmp_arr = FUNC_text_2($layer);
				// Render monogram text to layer canvas
				$tmp_Canvas->compositeImage($tmp_arr['txt_canvas'], imagick::COMPOSITE_DEFAULT, $tmp_arr['_x'], $tmp_arr['_y']);
			}
			
		// For Text
		}else{

			$tmp_arr = array();
			$tmp_arr = FUNC_text_1($layer);
			
			$tmp_Canvas->compositeImage($tmp_arr['txt_canvas'], imagick::COMPOSITE_DEFAULT, $tmp_arr['_x'], $tmp_arr['_y']);
		}
		
		// Reset position to 0,0				
		$img_x = 0;
		$img_y = 0;		
			
		if(isset($tmp_txt_Canvas))
			$tmp_txt_Canvas->destroy();
	
	}
	
	if(isset($tmp_Canvas) && !is_null($tmp_Canvas)) {
		// Apply color style/effects
		if($img_color != "") {
			if($img_color_style == "sigmodial") {
				$tmp_Col_Canvas = clone $tmp_Canvas;		// Cloning an Imagick object from 3.1.0 on
				//$tmp_Col_Canvas = $tmp_Canvas->clone();
				$tmp_Col_Canvas->sigmoidalcontrastImage(0, 3, 50);
				$tmp_Col_Canvas->colorizeImage($img_color,0);
				$tmp_Canvas->compositeImage($tmp_Col_Canvas, imagick::COMPOSITE_COLORIZE, 0, 0);
			}else if($img_color_style == "modulate") {
				$tmp_Col_Canvas = clone $tmp_Canvas;		// Cloning an Imagick object from 3.1.0 on
				//$tmp_Col_Canvas = $tmp_Canvas->clone();
				$tmp_Col_Canvas->colorizeImage($img_color,0);
				//$tmp_Col_Canvas->modulateImage($img_brightness,$img_saturation,100);
				$tmp_Canvas->compositeImage($tmp_Col_Canvas, imagick::COMPOSITE_MULTIPLY, 0, 0);
				$tmp_Canvas->modulateImage($img_brightness,$img_saturation,100);
				//$tmp_Canvas->compositeImage($tmp_Col_Canvas, imagick::COMPOSITE_COLORIZE, 0, 0);
			}else if($img_color_style == "emboss") {
				$shadow_xoffset = isset($layer['sxoffset']) ? $layer['sxoffset'] : 0;		// Shadow horizontal position offset
				$shadow_yoffset = isset($layer['syoffset']) ? $layer['syoffset'] : 0;		// Shadow vertical position offset
				$tmp_Col_Canvas = clone $tmp_Canvas;		// Cloning an Imagick object from 3.1.0 on
				//$tmp_Col_Canvas = $tmp_Canvas->clone();
				$tmp_Col_Canvas->sigmoidalcontrastImage(0, 3, 50);
				$tmp_Col_Canvas->colorizeImage($img_color,0);
				$tmp_Canvas->compositeImage($tmp_Col_Canvas, imagick::COMPOSITE_COLORIZE, 0, 0);
				$tmp_Col_Canvas->modulateImage(50,50,100);
				$tmp_Canvas->compositeImage($tmp_Col_Canvas, imagick::COMPOSITE_DSTOVER, $shadow_xoffset, $shadow_yoffset);
			}else if($img_color_style == "fill") {
				$tmp_Col_Canvas = new Imagick();  
				$tmp_Col_Canvas->newImage($tmp_Canvas->getImageWidth(), $tmp_Canvas->getImageHeight(), new ImagickPixel($img_color));
				$tmp_Col_Canvas->setImageMatte(1);
				$tmp_Col_Canvas->compositeImage($tmp_Canvas, Imagick::COMPOSITE_DSTIN, 0, 0);
				$tmp_Canvas = $tmp_Col_Canvas;
			}else if($img_color_style == "photo_laser_engraved") {
				$tmp_Col_Canvas = clone $tmp_Canvas;		// Cloning an Imagick object from 3.1.0 on
				//$tmp_Col_Canvas = $tmp_Canvas->clone();
				$tmp_Col_Canvas->modulateImage(100,0,100);
				$tmp_Col_Canvas->sigmoidalcontrastImage(0, 3, 50);
				if(isset($layer['metal']) && is_array($layer['metal'])) {
					$img_color = $layer['metal']['color'];
				}
				
				$tmp_Col_Canvas->colorizeImage($img_color,0);
				$tmp_Col_Canvas->modulateImage(100,70,100);
				
				$tmp_Canvas->compositeImage($tmp_Col_Canvas, imagick::COMPOSITE_COLORIZE, 0, 0);

			}else if($img_color_style == "clipart_laser_engraved") {
				$tmp_Col_Canvas = clone $tmp_Canvas;		// Cloning an Imagick object from 3.1.0 on
				//$tmp_Col_Canvas = $tmp_Canvas->clone();
				if(isset($layer['metal']) && is_array($layer['metal'])) {
					$img_color = $layer['metal']['color'];
				}
				
				if(strpos($layer['metal']['name'], 'Yellow') !== false) {
					$tmp_Col_Canvas->modulateImage(0,100,100);
					$tmp_Col_Canvas->colorizeImage($img_color,0);
					$tmp_Col_Canvas->modulateImage(200,100,98);
					$tmp_Canvas->compositeImage($tmp_Col_Canvas, imagick::COMPOSITE_DEFAULT, 0, 0);
					$tmp_Col_Canvas->modulateImage(60,65,100);
					$tmp_Canvas->compositeImage($tmp_Col_Canvas, imagick::COMPOSITE_DEFAULT, -0.7, -0.7);
					$tmp_Col_Canvas->modulateImage(130,100,100);
					$tmp_Canvas->compositeImage($tmp_Col_Canvas, imagick::COMPOSITE_DEFAULT, -0.35, -0.35);
				} else {
					$tmp_Col_Canvas->modulateImage(0,100,100);
					$tmp_Col_Canvas->colorizeImage($img_color,0);
					$tmp_Col_Canvas->modulateImage(180,100,98);
					$tmp_Canvas->compositeImage($tmp_Col_Canvas, imagick::COMPOSITE_DEFAULT, 0, 0);
					$tmp_Col_Canvas->modulateImage(80,65,100);
					$tmp_Canvas->compositeImage($tmp_Col_Canvas, imagick::COMPOSITE_DEFAULT, -0.7, -0.7);
					$tmp_Col_Canvas->modulateImage(170,100,100);
					$tmp_Canvas->compositeImage($tmp_Col_Canvas, imagick::COMPOSITE_DEFAULT, -0.35, -0.35);
				}
				
			}
		}
		
		
		// Adjust image transparency
		if(isset($layer['alpha']) && $img_alpha < 100) {
			$tmp_Canvas->evaluateImage(Imagick::EVALUATE_MULTIPLY, $img_alpha * 0.01, Imagick::CHANNEL_ALPHA);
		}
		
		// Set border
		if($img_border != 0 && $id != 0) {
			$tmp_Canvas->borderImage($col_black,$img_border,$img_border);
		}
		
		// Apply masking
		if(isset($layer['mask'])) {
			//Set position
			$mask_top = isset($layer['mask_top']) ? $layer['mask_top'] : 0;
			$mask_left = isset($layer['mask_left']) ? $layer['mask_left'] : 0;
			
			$f_tmp = pathinfo($layer['mask']);
				
			$file_image_sku = $f_tmp['dirname'].'/'.$productid.'/'.$f_tmp['filename'].'.'.$f_tmp['extension'];
			$file_image_default = $f_tmp['dirname'].'/'.$f_tmp['filename'].'.'.$f_tmp['extension'];
			
			// Path of mask image
			if (file_exists($PATH_components.$file_image_sku)) {
				$img_str = $PATH_components.$file_image_sku;
			} else {
				$img_str = $PATH_components.$file_image_default;
			}
			//$img_str = $PATH_components.$layer['mask'];
			

			// Put mask image to mask canvas
			$tmp_Mask = new Imagick($img_str);  
			// Apply masking to layer content
			if(isset($layer['cliparts']) || $layer['type'] == "text" || $layer['type'] == "avatar") {
				//Mask for Clipart
				$tmp_Canvas->compositeImage($tmp_Mask, Imagick::COMPOSITE_DSTIN, $mask_left, $mask_top, Imagick::CHANNEL_ALPHA);
			} else {
				// Mask for Photo, etc.
				$tmp_Canvas->compositeImage($tmp_Mask, Imagick::COMPOSITE_COPYOPACITY, $mask_left, $mask_top);
			}
			$tmp_Mask->clear();
		}
		
		// Merge layer to step canvas
		$main_Canvas->compositeImage($tmp_Canvas, imagick::COMPOSITE_DEFAULT, $img_x, $img_y);
		
		
		$tmp_Canvas->destroy();
		$tmp_Canvas->clear();
	}
}


// For thumbnails
if($VAR_resize_percent == 'T' || $VAR_resize_percent == 'S' || $VAR_resize_percent == 'P') {
	// JPEG Image quality
	$VAR_jpg_quality = "70";
	// Dimensions
	$tmp_final_width = $final_width;
	$tmp_final_height = $final_height;
	
	$final_height = $final_height - 10;
	$final_width = $final_width - 10;
	
	$ratio = $final_height / $core_height;
	$final_width = $core_width * $ratio;
	
	if($final_width > $tmp_final_width) {
		$ratio = $tmp_final_width / $core_width;
      	$final_height = $core_height * $ratio;
		
		$ratio = $final_height / $core_height;
		$final_width = $core_width * $ratio;
	}
	// Position
	$x_off = ($tmp_final_width - $final_width) / 2;
	$y_off = ($tmp_final_height - $final_height) / 2;
	// Resize
	$main_Canvas->scaleImage($final_width, $final_height, false);
	
	// Reposition image to canvas
	$tmp_S_Canvas = new Imagick();  
					
	$final_width = $tmp_final_width;
	$final_height = $tmp_final_height;
	
	if($VAR_resize_percent == 'P') {
		$final_height = $final_height + $space_below;
	}
	
	
	$tmp_S_Canvas->newImage($final_width, $final_height, new ImagickPixel("white"));
	$tmp_S_Canvas->compositeImage($main_Canvas, imagick::COMPOSITE_DEFAULT, $x_off, $y_off);
	
	
	if($VAR_resize_percent == 'P') {		
		$label_Canvas = new Imagick();
		$label_draw = new ImagickDraw();
		$label_Canvas->newImage($final_width, 35, new ImagickPixel("white"));
		
		$label_draw->setFont($PATH_components.'fonts/arial.ttf');
		$label_draw->setFontSize(18);
		$label_draw->setFillColor("black");
		$label_draw->setStrokeAntialias(true);
		$label_draw->setTextAlignment(2);
		$label_draw->setTextAntialias(true);
		
		$tmp_l = explode(': ',$data_LAYERS['step'][$stepid]['name']);
		
		$text = $tmp_l[1];
		
		$metrics = $label_Canvas->queryFontMetrics($label_draw, $text);
		$label_draw->annotation($final_width / 2, 24, $text);
		$label_Canvas->drawImage($label_draw);
		
		$tmp_S_Canvas->compositeImage($label_Canvas, imagick::COMPOSITE_DEFAULT, 0, $final_height - 40);
		
	}
	
	$main_Canvas = $tmp_S_Canvas;

	$VAR_border = false;
}else{
	// Resize main product image
	$main_Canvas->scaleImage($final_width, $final_height, false);
}

// Add border to image
//if($VAR_border) {
	//$main_Canvas->borderImage($col_black,$VAR_border,$VAR_border);
//}

if(isset($img_append) && $img_append) {
	// Merge thumbnail image to thumbnails canvas
	$final_Canvas->compositeImage($main_Canvas, imagick::COMPOSITE_DEFAULT, $append_x, $append_y);
}else{
	// Main product image is set as final canvas
	$final_Canvas = $main_Canvas;
}

?>