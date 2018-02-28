<?php
// Designer Main Script - STANDALONE

global $site_root, $site_url;

// For Basic and Configurable Products
include_once '../../render.php';	

global $core_width, $core_height, $designer_engine, $AJAX_designer_VARS;

// Width/Height of product thumbnail image
$thumbnail_size = 90;

// POSTED VARIABLES --------------------------------------------------------------------------------------------------------------------------
$g_mode = isset($_GET['GM']) ? $_GET['GM'] : 'prod';
//$id = isset($_GET['id']) ? $_GET['id'] : 1;
$productid = isset($_GET['pid']) ? $_GET['pid'] : "";
$id_A = isset($_GET['id_A']) ? $_GET['id_A'] : 0;
$stepid = isset($_GET['stid']) ? $_GET['stid'] : 0;
$aid = isset($_GET['aid']) ? $_GET['aid'] : 0;
$aname = isset($_GET['aname']) ? $_GET['aname'] : "";

if($productid == "")
	exit('Error Product!');
// POSTED VARIABLES --------------------------------------------------------------------------------------------------------------------------

$data_LAYERS = $product_layers;

$hide_step = false;

foreach ($data_LAYERS['step'] as $a => $b) {
	if(isset($b['place_holder']) && $b['place_holder'] == true) {
		unset($data_LAYERS['step'][$a]);
	}
	
	if(isset($b['hide_step']) && $b['hide_step'] == 'true') {
		$hide_step = true;
	}
}

if(isset($_GET['SZ']) && $_GET['SZ'] == "S" ) {
	
	if(isset($_GET['TS'])) {
		$thumbnail_size = $_GET['TS'];
	} else {
		$thumbnail_size = 90;
	}

	if(is_array($data_LAYERS['step']) and count($data_LAYERS['step']) > 0) {
		
			
		$final_Canvas = new Imagick();
	
		$final_Canvas->newImage(($thumbnail_size + 5) * count($data_LAYERS['step']), $thumbnail_size, new ImagickPixel("white"));
		
		$img_append = true;
		$append_x = 0;
		$append_y = 0;
		
		
		foreach ($data_LAYERS['step'] as $a => $b) {
			$stepid = $a;
			include 'data_loop.php';	
			$append_x = $append_x + $thumbnail_size + 5;
			
				
			$main_Canvas->destroy();
			$col_black->destroy();
			
			$col_black->clear();
			$main_Canvas->clear();
		}
	}
}elseif(isset($_GET['SZ']) && $_GET['SZ'] == "T" ) {
	
	if(isset($_GET['TS'])) {
		$thumbnail_size = $_GET['TS'];
	} else {
		$thumbnail_size = 90;
	}

	include 'data_loop.php';

}elseif(isset($_GET['SZ']) && $_GET['SZ'] == "P") {
	
	$thumbnail_size = 400;
	$padding = 3;
	
	if(is_array($data_LAYERS['step']) and count($data_LAYERS['step']) > 0) {
		
			
		$final_Canvas = new Imagick();
		if(count($data_LAYERS['step']) == 1) {
			$space_below = 250;
		} else {
			$space_below = 30;
		}
		$space_top = 30;
				
		$img_append = true;
		$append_x = $padding;
		$append_y = $padding + $space_top;
	
		if(count($data_LAYERS['step']) == 1) {
			$final_Canvas->newImage(($thumbnail_size + $padding * 2), ($thumbnail_size + $padding * 2), new ImagickPixel("white"));
		} else {
			$final_Canvas->newImage(($thumbnail_size * 2) + $padding * 3, ($thumbnail_size + $padding) * (count($data_LAYERS['step']) / 2) + $padding + ((count($data_LAYERS['step']) / 2) * $space_below) + $space_top, new ImagickPixel("white"));
		}

		

		foreach ($data_LAYERS['step'] as $a => $b) {
			$stepid = $b['replace_preview'];
			include 'data_loop.php';	
			
			
			if(($append_x + $thumbnail_size + $padding) > $thumbnail_size * 2) {
				$append_x = $padding;
				$append_y = $append_y + $thumbnail_size + $padding + $space_below;
			}else{
				$append_x = $append_x + $thumbnail_size + $padding;	
			}
			
				
			$main_Canvas->destroy();
			$col_black->destroy();
			
			$col_black->clear();
			$main_Canvas->clear();
		}
		
		// LOGO	
		//$logo_Canvas =new Imagick($site_root.'/custom/logo.png');
		//$l_geo = $logo_Canvas->getImageGeometry(); 
		//$x_logo = ((($thumbnail_size * 2) + $padding * 3) / 2) -($l_geo['width'] / 2);
		//$final_Canvas->compositeImage($logo_Canvas, imagick::COMPOSITE_DEFAULT, $x_logo, 8);
		// LOGO	
	}
	
}else{
	include 'data_loop.php';	
}

if((isset($_GET['SZ']) && $_GET['SZ'] == "P") && (isset($_GET['MP']) && $_GET['MP'] == "PF")) {
	
	$pidint = intval($productid);
	$avint = intval($avatar_id);
	$g_name = md5($_COOKIE['frontend'].mt_rand(1,$pidint).$avint).'.'.$VAR_img_type;
	//$g_name = md5($_COOKIE['frontend'].rand()).'.'.$VAR_img_type;
	
	switch($VAR_img_type) {
		case "gif" :
			$final_Canvas->setImageFormat("gif");
			break;
		case "png" :
			$final_Canvas->setImageFormat("png");
			break;
		case "jpg" :
			$final_Canvas->setImageFormat("jpeg");
			$final_Canvas->setImageCompressionQuality($VAR_jpg_quality);
			break;
	} 
	
	
	$final_Canvas->writeImage($site_root.'/custom/proofs/'.$g_name); 
	$final_Canvas->destroy();
	
	echo $g_name;


}else{
	
	if(isset($_GET['MP'])) {
		if($_GET['MP'] == "PS") {
			$final_Canvas->scaleImage(0, 120, false);
		} else {
			$final_Canvas->scaleImage(0, 166, false);
		}
		//$final_Canvas->scaleImage(0, 200, false);
		//$final_Canvas->cropImage($final_Canvas->getImageWidth(), $final_Canvas->getImageHeight() / 2, 0, 0);
	}
	switch($VAR_img_type) {
		case "gif" :
			header("Content-type: image/gif");
			$final_Canvas->setImageFormat("gif");
			break;
		case "png" :
			header("Content-type: image/png");
			$final_Canvas->setImageFormat("png");
			break;
		case "jpg" :
			header("Content-type: image/jpeg");
			$final_Canvas->setImageFormat("jpeg");
			$final_Canvas->setImageCompressionQuality($VAR_jpg_quality);
			break;
	} 
	
	echo $final_Canvas;
		
	$final_Canvas->destroy();
}

?>