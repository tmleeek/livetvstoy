<?php
// AJAX Selector output

// This script generates the interface of the AJAX selector

global $designer_engine, $site_root, $site_url;

$site_root = $_SERVER['DOCUMENT_ROOT'];
$site_url = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';

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
	
}else if($_GET['com_type'] == 'stones') {
	
	list($arr_list, $arr_cats, $go_categorized) = FUNC_AD_selector_prep($G_ARR_stones, $_GET['com_type'], $_GET['def_val'], $_GET['cat_list']);
		
}else if($_GET['com_type'] == 'font') {
	
	list($arr_list, $arr_cats, $go_categorized) = FUNC_AD_selector_prep($G_ARR_fonts['font'], $_GET['com_type'], $_GET['def_val'], $_GET['cat_list']);	

}

if(isset($_GET['sid'])) {
	$sid = $_GET['sid'];
}

$com_type = $_GET['com_type'];

include('interface/ajax_selector.php');

?>