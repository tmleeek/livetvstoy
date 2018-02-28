<?php
// ImageMagick Rendering Engine for AJAX Designer

// Imagick Class

$upl_y = (isset($_GET['upl_y']) && !empty($_GET['upl_y'])) ? $_GET['upl_y'] : NULL;
$upl_x = (isset($_GET['upl_x']) && !empty($_GET['upl_x'])) ? $_GET['upl_x'] : NULL;

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
	
	// Render image type
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
		if(isset($layer['value']) || (isset($layer['uploader']) && is_array($layer['uploader']))) {
			
			// For image
			$img_str = $PATH_components.$layer['value'];
			$var_up = false;
			
			// For photo upload
			if(isset($layer['uploader']) && count($layer['uploader']) <= 0) {
				$var_up = true;
			}
			
			if(isset($layer['uploader']) && is_array($layer['uploader']) && is_file($PATH_uploader.'medium/'.$layer['uploader']['new_filename'])) {
				$img_str = $PATH_uploader.'files/'.$layer['uploader']['new_filename'];
				$img_str2 = $PATH_uploader.'medium/'.$layer['uploader']['new_filename'];
				$var_up = true;
			}
			
			// Render uploaded photo
			if($var_up) {
				// Temporary Canvas for uploaded photo
				$tmp_img_Canvas = new Imagick($img_str) or die('ERROR: No uploaded image found in server - ID: '.$id);

				// Cropping values
				if(isset($layer['crop_scale_factor'])) {
					$crop_width = $layer['crop_to_width'];
					$crop_height = $layer['crop_to_height'];
					$crop_x = $layer['crop_to_x'];
					$crop_y = $layer['crop_to_y'];
					$crop_rotate = $layer['crop_to_rotate'];
				}
	
				// Set dimensions
				$tmp_final_width = isset($layer['width']) ? $layer['width'] : $crop_width;
				$tmp_final_height = isset($layer['height']) ? $layer['height'] : $crop_height;
				
				$img_res_width = $crop_width;
				$img_res_height = $crop_height;
				
				// Position offset
				$x_off = ($crop_x - 28);//($tmp_final_width - $img_res_width) / 2;
				$y_off = $crop_y - 10;//($tmp_final_height - $img_res_height) / 2;
				
				// Rotate image
				if($crop_rotate != 0) {
					$tmp_img_Canvas->rotateimage("transparent", $crop_rotate);
				}
				
				// Resize image
				$tmp_img_Canvas->scaleImage($img_res_width, $img_res_height, false);
				
				// Composite to image canvas
				$tmp_Canvas = new Imagick();  
				$tmp_Canvas->newImage($tmp_final_width, $tmp_final_height, new ImagickPixel('white'));
				$tmp_Canvas->compositeImage($tmp_img_Canvas, imagick::COMPOSITE_DEFAULT, $x_off, $y_off);
				//$tmp_Canvas->compositeImage($tmp_img_Canvas, imagick::COMPOSITE_DEFAULT,($crop_width-$w2)/2 + $x_off, ($crop_height-$h2)/2 + $y_off );
				
				$img_x = 0;
				$img_y = 0;
			
			// Render image
			}else{
				// Check if image exists
				
				try	{
				 	$tmp_Canvas = new Imagick($img_str) ;
				}catch (Exception $e){
					die('ERROR: No image found in server - ID: '.$id);
				}
				
				// Get dimensions
				$tmp_GEO = $tmp_Canvas->getImageGeometry();  			
	
				$img_width = isset($layer['width']) ? $layer['width'] : $tmp_GEO['width'];
				$img_height = isset($layer['height']) ? $layer['height'] :  $tmp_GEO['height'];

				if($img_width != 0 or $img_height != 0)
					// Resize image
					$tmp_Canvas->scaleImage($img_width, $img_height, false);
			}

		}
		
		// For birthstones
		if(isset($layer['stones'])) {
			// Change color of birthstone using Imagick
			if($layer['stone_style'] == "colorized") {
				$img_color = $layer['stones']['color'];
				$img_brightness = $layer['stones']['brightness'];
				$img_saturation = $layer['stones']['saturation'];
				$img_color_style = "modulate";//for imagick 3.1.2 ImageMagick 6.9.0-9 Q16
				//$img_color_style = "sigmodial";	//for imagick 3.1.0RC2 ImageMagick 6.7.6-8 2012-05-02 Q16
			}else{
				//Use image URL for each birthstone
				$img_str = $PATH_components.'stones/'.$layer['stones']['file_name'];
				$tmp_Canvas = new Imagick($img_str);  
			}
			
			// Get dimensions
			$tmp_GEO = $tmp_Canvas->getImageGeometry();  
			
			$clip_width = $tmp_GEO['width'];
			$clip_height = $tmp_GEO['height'];
			
			// Resize birthstone
			if(isset($layer['resize'])) {
				$clip_width = $clip_width * $layer['resize'];
				$clip_height = $clip_height * $layer['resize'];
				$tmp_Canvas->scaleImage($clip_width, $clip_height, false);
			}
		
		// For cliparts	
		}else if(isset($layer['cliparts'])) {
			
			//$layer['rotation'] = $mv_itm_rt;
			$layer['rz'] = $mv_itm_rz;
			
			// create transparent layer canvas
			$tmp_Canvas = new Imagick();  
			$tmp_Canvas->newImage($core_width, $core_height, new ImagickPixel("none"));
			
			// set path to clipart image
			$img_str = $PATH_components.'/cliparts/files/png/'.$layer['cliparts']['file_name'];
			// put clipart to temporay canvas
			$tmp_clip_Canvas = new Imagick($img_str);
			
			// set dimensions
			$tmp_GEO = $tmp_clip_Canvas->getImageGeometry();
			$clip_width = $tmp_GEO['width'];
			$clip_height = $tmp_GEO['height'];
			
			$maxSize = 150;
			
			// Resize
			if(isset($layer['resize'])) {
				$clip_width = $clip_width * $layer['resize'];
				$clip_height = $clip_height * $layer['resize'];
				$tmp_clip_Canvas->scaleImage($clip_width, $clip_height, false);
				$maxSize = 150 * $layer['resize'];
			}
			
			if($clip_height <= $clip_width)
			{
				$tmp_clip_Canvas->resizeImage(0,$maxSize + $layer['rz'] ,Imagick::FILTER_LANCZOS,1);
				$tmp_adjust = $tmp_clip_Canvas->getImageGeometry();
				$adjustZone = clone $tmp_clip_Canvas;		// Cloning an Imagick object from 3.1.0 on
				//$adjustZone = $tmp_clip_Canvas->clone();
				$adjust = ( $tmp_adjust['width']  - $maxSize ) / 2; 
				}else{
				$tmp_clip_Canvas->resizeImage($maxSize + $layer['rz'],0,Imagick::FILTER_LANCZOS,1);
				$tmp_adjust = $tmp_clip_Canvas->getImageGeometry();
				$adjustZone = clone $tmp_clip_Canvas;		// Cloning an Imagick object from 3.1.0 on
				//$adjustZone = $tmp_clip_Canvas->clone();
				$adjust = ( $maxSize - $tmp_adjust['height'] ) / 2; 
			}
			
			if(isset($layer['xscale'])) {
				$clip_width = $clip_width * $layer['xscale'];
				$tmp_clip_Canvas->scaleImage($clip_width, $clip_height, false);
			}
			
			if(isset($layer['yscale'])) {
				$clip_height = $clip_height * $layer['yscale'];
				$tmp_clip_Canvas->scaleImage($clip_width, $clip_height, false);
			}
			// Rotate
			if(isset($layer['rotation'])) {
				$tmp_clip_Canvas->rotateimage("transparent", $layer['rotation']);
			}
			// Skew
			if((isset($layer['skewx']) && $layer['skewx'] != 0) || (isset($layer['skewy']) && $layer['skewy'] != 0)) {
				$tmp_clip_Canvas->shearImage(new ImagickPixel('none'), $layer['skewx'], $layer['skewy']); 
			}
			
			$tmp_GEO = $tmp_clip_Canvas->getImageGeometry();  	
			
			$w2=$tmp_GEO['width'];
			$h2=$tmp_GEO['height'];
			
			$tmp_upl_Canvas = new Imagick();
			$tmp_upl_Canvas->newImage($img_width, $img_height , new ImagickPixel("transparent"));
			$canvas_geo=$tmp_upl_Canvas->getImageGeometry();
			$w1=$canvas_geo['width'];
			$h1=$canvas_geo['height'];
			
			// Merge clipart to layer canvas
			// Center clipart on canvas, then add offset to position clipart on item
			$tmp_Canvas->compositeImage($tmp_clip_Canvas, imagick::COMPOSITE_DEFAULT,($w1-$w2)/2 + $img_x + $mv_itm_x, ($h1-$h2)/2 + $img_y + $mv_itm_y );
			
			// Set position to 0,0
			$img_x = 0;
			$img_y = 0;		
				
			if(isset($tmp_clip_Canvas))
				$tmp_clip_Canvas->destroy();

		}
	
	// For Text/Monogram
	}else if(isset($layer['type']) && $layer['type'] == "text") {
		
		$text_engraved = isset($layer['text_engraved']) ? $layer['text_engraved'] : false;
		
		$text_angle = isset($layer['text_angle']) ? $layer['text_angle'] : 180;
		
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
			} else if($layer['text_style'] == 'wave') {
				$tmp_arr = array();
				$tmp_arr = FUNC_text_Wave($layer);
				$tmp_Canvas->compositeImage($tmp_arr['txt_canvas'], imagick::COMPOSITE_DEFAULT, 0, 0);
			} else if($layer['text_style'] == 'arc2i') {
				$tmp_arr = array();
				$tmp_arr = FUNC_text_Arc_2i($layer);
				$tmp_Canvas->compositeImage($tmp_arr['txt_canvas'], imagick::COMPOSITE_DEFAULT, 0, 0);
				//$tmp_Canvas->compositeImage($tmp_arr['txt_canvas'], imagick::COMPOSITE_BLEND, 0, 0);
			} else if($layer['text_style'] == 'top_text_1') {
				$tmp_arr = array();
				$tmp_arr = FUNC_top_text_1($layer);
				$tmp_Canvas->compositeImage($tmp_arr['txt_canvas'], imagick::COMPOSITE_DEFAULT, $tmp_arr['_x'], $tmp_arr['_y']);
			} else if($layer['text_style'] == 'top_text_upper') {
				$tmp_arr = array();
				$tmp_arr = FUNC_top_text_upper($layer, $text_engraved, $text_angle);
				$tmp_Canvas->compositeImage($tmp_arr['txt_canvas'], imagick::COMPOSITE_DEFAULT, $tmp_arr['_x'], $tmp_arr['_y']);
			} else if($layer['text_style'] == 'top_text_lower') {
				$tmp_arr = array();
				$tmp_arr = FUNC_top_text_lower($layer, $text_engraved);
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
			} else if($layer['text_style'] == 'arcnew1') {
				 $tmp_arr = array();
				 $tmp_arr = FUNC_text_Arc_New_1($layer);
				 $tmp_Canvas->compositeImage($tmp_arr['txt_canvas'], imagick::COMPOSITE_DEFAULT, 0, 0);
				 //$tmp_Canvas->compositeImage($tmp_arr['txt_canvas'], imagick::COMPOSITE_BLEND, 0, 0);
			// For Monogram
			} else if($layer['text_style'] == 'monogram') {
				$tmp_arr = array();
				// Create monogram text using function in text.php
				$tmp_arr = FUNC_text_monogram($layer);
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
	
	//For Wordings			
	}else if(isset($layer['type']) && $layer['type'] == "wording") {
		// Create transparent layer canvas
		$tmp_Canvas = new Imagick();  
		$tmp_Canvas->newImage($core_width, $core_height, new ImagickPixel("none"));
		
		$tmp_arr = array();
		// Render wording using function found in text.php
		$tmp_arr = FUNC_wording_1($layer, $id); 
		
		// Put wording in layer canvas
		$tmp_Canvas->compositeImage($tmp_arr['txt_canvas'], imagick::COMPOSITE_DEFAULT, 0, 0);
		// Set position
		$img_x = $tmp_arr['_x'];
		$img_y = $tmp_arr['_y'];

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
			if(isset($layer['cliparts']) || $layer['type'] == "text") {
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
		
		// Crop and Merge Locket Sides
		if(isset($data_LAYERS['step'][$stepid]['merge_adjust']))
			$merge_adjust = $data_LAYERS['step'][$stepid]['merge_adjust'];
		
		if(strpos($text, 'Left Side') !== false) {
			$tmp_S_Canvas->cropImage($final_width - $merge_adjust, $final_height, 0, 0);
			$append_x += $merge_adjust;
			//$tmp_S_Canvas->cropImage($final_width - 100, $final_height, 0, 0);
			//$append_x += 100;
		}
		
		if(strpos($text, 'Right Side') !== false) {
			$tmp_S_Canvas->cropImage($final_width - $merge_adjust, $final_height, $merge_adjust, 0);
			$append_x -= $merge_adjust - 4;
			//$append_x -= 106;
		}
		
		
		if(strpos($text, 'Inside - Page 2') !== false) {
			$tmp_S_Canvas->cropImage($final_width - $merge_adjust, $final_height, $merge_adjust, 0);
			$append_x -= $merge_adjust - 4;
			//$append_x -= 106;
		}
		
		if(strpos($text, 'Inside - Page 3') !== false) {
			$tmp_S_Canvas->cropImage($final_width - $merge_adjust, $final_height, 0, 0);
			$append_x += $merge_adjust;
			//$tmp_S_Canvas->cropImage($final_width - 100, $final_height, 0, 0);
			//$append_x += 100;
		}
	}
	
	$main_Canvas = $tmp_S_Canvas;

	$VAR_border = false;
}else{
	// Resize main product image
	$main_Canvas->scaleImage($final_width, $final_height, false);
}

// Add border to image
if($VAR_border) {
	//$main_Canvas->borderImage($col_black,$VAR_border,$VAR_border);
}

if(isset($img_append) && $img_append) {
	// Merge thumbnail image to thumbnails canvas
	$final_Canvas->compositeImage($main_Canvas, imagick::COMPOSITE_DEFAULT, $append_x, $append_y);
}else{
	// Main product image is set as final canvas
	$final_Canvas = $main_Canvas;
}

?>