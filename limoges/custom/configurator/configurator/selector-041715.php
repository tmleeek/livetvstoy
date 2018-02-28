<?php
// AJAX Selector output

// This script generates the interface of the AJAX selector

//include_once '../../inc.php';	

global $designer_engine, $site_root, $site_url;

$site_root = $_SERVER['DOCUMENT_ROOT'];
$site_url = ($_SERVER['HTTPS'] ? 'https' : 'http') . '://' . 'test.tystoybox.com' . '/';
//$site_url = ($_SERVER['HTTPS'] ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';

include('custom.php');
include('func_common.php');


global $PATH_designer, $PATH_components, $G_ARR_cliparts, $G_ARR_component_categories, $G_ARR_chains;

FUNC_get_XML('component_categories');

$__cat = $_GET['com_type'];	

$cat_arr_main = $G_ARR_component_categories[$__cat];
$arr_list = array();
$arr_cats = array();
$go_categorized = false;


if($_GET['com_type'] == 'cliparts') {

	FUNC_get_XML('cliparts');

	list($arr_list, $arr_cats, $go_categorized) = FUNC_AD_selector_prep($G_ARR_cliparts['clipart'], $_GET['com_type'], $_GET['def_val'], $_GET['cat_list'], 'title');	

	if(isset($_GET['1st'])) {
		echo '<pre>';
		print_r($arr_list);
		echo '</pre>';
		exit;
	}
	
	if(isset($_GET['2nd'])) {
		echo '<pre>';
		print_r($arr_cats);
		echo '</pre>';
		exit;
	}

	
}else if($_GET['com_type'] == 'stones') {
	
	list($arr_list, $arr_cats, $go_categorized) = FUNC_AD_selector_prep($G_ARR_stones, $_GET['com_type'], $_GET['def_val'], $_GET['cat_list']);
		
}else if($_GET['com_type'] == 'font') {
	
	list($arr_list, $arr_cats, $go_categorized) = FUNC_AD_selector_prep($G_ARR_fonts['font'], $_GET['com_type'], $_GET['def_val'], $_GET['cat_list']);	
	
}else if($_GET['com_type'] == 'test') {

	$arr   = get_table_info2('product_size_price', "WHERE pid = '".$_GET['pid']."' ORDER BY price", "*");
	
	$eng   = get_table_info2('products_engraving', "WHERE pid = '".$_GET['pid']."'", "*");
	
	echo '<pre>';
	print_r($eng);
	echo '</pre>';
	exit;
	
}else if($_GET['com_type'] == 'chains') {
	
	list($arr_list, $arr_cats, $go_categorized) = FUNC_CUSTOM_chains($_GET['pid']);	
	

	$def_split = explode("-", $_GET['def_val']);

	foreach($arr_list as $a => $b){
		
		if($b['id'] == $def_split[1]) {
			$arr_list[$a]['def'] = true;
		}
		
		foreach($b['options'] as $c => $d){
			if($d['id'] == $def_split[2]) {
				$arr_list[$a]['options'][$c]['def'] = true;
			}
		}
	}
	
	foreach($arr_cats as $a => $b){
		if($b['id'] == $def_split[0]) {
			$arr_cats[$a]['def'] = true;
		}
	}
	
	if(count($def_split) <= 1) {
		$no_def = true;
	}
	
		
	if(isset($_GET['1st'])) {
		echo '<pre>';
		print_r($arr_list);
		echo '</pre>';
		exit;
	}
	
	if(isset($_GET['2nd'])) {
		echo '<pre>';
		print_r($arr_cats);
		echo '</pre>';
		exit;
	}


}else if($_GET['com_type'] == 'wording') {
	
	// Get XML Array values for wording - dynamic
	//include("components/wordings.php");
	//$XML_wording = new SimpleXmlIterator($xml_wording_data);
	$path = $PATH_components.'wordings.xml';
	$XML_wording = new SimpleXmlIterator($path, null, true);
	$NS_wording = $XML_wording->getNamespaces(true);
	$__ARR_W = FUNC_XML_to_Array($XML_wording,$NS_wording);
	
	
	if(isset($_GET['cat_list'])) {
		
		if($_GET['cat_list'] == 'all') {
			
			foreach($cat_arr_main[0]['content']['cat'] as $c => $d){
				$arr_cats[] = array('title' => $d['name'], 'id' => $c, 'group' => $c);
			}
			
		} else {
			
			$cat_list = explode("-", $_GET['cat_list']);
			
			foreach($cat_list as $a => $b){
				foreach($cat_arr_main[0]['content']['cat'] as $c => $d){
					if($b == $c) {
						$arr_cats[] = array('title' => $d['name'], 'id' => $b, 'group' => $b);
					}
				}
			}
		}
		$go_categorized = true;
	}
	
	foreach($__ARR_W['wording'] as $e => $f){
		
		$line_arr = array();
		
		$i = 0;
		
		// Add maximum chars per line and total chars
		//To use for displaying engraving ideas based on locket side
		$max_linechars = 0;
		$total_chars = 0;
		
		foreach($f['content']['line'] as $g => $h){
			if(is_array($h)){
				$line_arr[] = $h['content'];
				
				if(strlen($h['content']) > $max_linechars) {
					$max_linechars = strlen($h['content']);
				}
				$total_chars += strlen($h['content']);
			}else{
				$line_arr[] = $h;
				
				if(strlen($h) > $max_linechars) {
					$max_linechars = strlen($h);
				}
				$total_chars += strlen($h);
			}
			
			//if (++$i == 3) break;
		}
		
				
		$go_add = false;
		
				
		if(isset($f['name']))
			$title_str = $f['name'];
				
		if(isset($f['title']))
			$title_str = $f['title'];
		
		if(count($arr_cats) > 0) {
			
			foreach($arr_cats as $c => $d){
				if(in_array($d['id'], explode("-", $f['assoc_cat'])) and ($f['avail'] == 'true' or $f['active'] == 'Y')) {
					$go_add = true;
				}
			}
			
			if($go_add == true)
				$arr_list[] = array('title' => $title_str, 'cat' => $f['assoc_cat'], 'max_linechars' => $max_linechars, 'total_chars' => $total_chars, 'id' => $e, 'line' => $line_arr);
				
		}else{
			if($f['avail'] == 'true' or $f['active'] == 'Y') 
				$arr_list[] = array('title' => $title_str, 'id' => $e, 'line' => $line_arr);
		}
	}
	
	if(isset($_GET['def_val'])) {
		foreach($arr_list as $a => $b){
			if(strtolower($b['title']) == strtolower($_GET['def_val'])) {
				$arr_list[$a]['def'] = true;
			}
		}
	}
	
}

if(isset($_GET['sid'])) {
	$sid = $_GET['sid'];
}

$com_type = $_GET['com_type'];

include('interface/ajax_selector.php');

?>