<?php
/*
	Ajax Selector for Ajax Designer
*/
?>

<?php


global $G_ARR_cliparts, $G_ARR_component_categories, $G_ARR_chains;

FUNC_get_XML('component_categories');


$arr_list = array();
$arr_cats = array();
$go_categorized = false;
$__fin_id = '';


if($b3['comp_type'] == 'cliparts') {
	$__fin_id = $a.'-'.$a2.'-'.$a3; 
	$_title = $b3['comp_type'];
	$__comp_type = $b3['comp_type'];
	
	FUNC_get_XML('cliparts');
	$__cat_list = '';
	if(isset($b3['cat_list'])) {
		$__cat_list = $b3['cat_list'];
	}
	list($arr_list, $arr_cats, $go_categorized) = FUNC_AD_selector_prep($G_ARR_cliparts['clipart'], $__comp_type, $comp_def, $__cat_list, 'title');	

}else if($bf['selector_type'] == 'chains') {
	
	$__fin_id = $a.'-'.$af;
	
	$__comp_type = $bf['selector_type'];

	$def_split = explode("-", $def);

	foreach($arr_list as $cca => $ccb){
		
		if($ccb['id'] == $def_split[1]) {
			$arr_list[$cca]['def'] = true;
		}
		
		foreach($ccb['options'] as $ccc => $ccd){
			if($ccd['id'] == $def_split[2]) {
				$arr_list[$cca]['options'][$ccc]['def'] = true;
			}
		}
	}
	
	foreach($arr_cats as $cca => $ccb){
		if($ccb['id'] == $def_split[0]) {
			$arr_cats[$cca]['def'] = true;
		}
	}
	
	if(count($def_split) <= 1) {
		$no_def = true;
	}
}
$cat_arr_main = $G_ARR_component_categories[$__comp_type];
?>

<div id="mover_<?php echo $__fin_id; ?>" style="clear: both; <?php if($__comp_type == 'chains'): ?>height: 440px; <?php else: ?> padding: 10px 0;<?php endif; ?>">

<?php

$showed = 'show';
$ul_height = 350;
$ul_width = 400;
$cat_ul_width = 0;

//Display if category is 1 or more  
if($go_categorized && count($arr_cats) > 0) {
	if($__comp_type == 'chains') {
		$ul_height = 370;
		$ul_width = 422;
		$cat_ul_width = 415;
		$showed = 'hide';
	}
	
}else{
	$ul_width = 419;
}

$active_group = 'none';

//Display if category is 1 or more
if($go_categorized && count($arr_cats) > 0) :

	if($__comp_type == 'chains') :
?>


    <div id="selector-category" style="padding: 0;">
      <ul style=" <?php if($cat_ul_width != 0): ?> width: <?php echo $cat_ul_width; ?>px; <?php endif; ?> <?php if($cat_ul_height != 0): ?> height: <?php echo $cat_ul_height; ?>px; <?php endif; ?> <?php if($__comp_type == 'chains'): ?>margin: 5px 0 0 0; <?php endif; ?>" >
            
            <?php foreach($arr_cats as $cca => $ccb): ?>
            	<?php if (!(($VAR_product_code == '70938'|| $VAR_product_code == '71838' || $VAR_product_code == '71541' || $VAR_product_code == '71544' || $VAR_product_code == '76634') && preg_match("/Chains for Men/i", $ccb['title']))): ?>
                <li class="categorylist <?php if(($no_def&& preg_match("/Chains for Women/i", $ccb['title'])) || $ccb['default'] || $ccb['def']): $active_group = $ccb['group']; ?>active<?php endif; ?>" 
                	listid="<?php echo $ccb['group']; ?>" style="height:30px; <?php if($__comp_type == 'chains'): ?> float: left; width: auto;<?php endif; ?>"
                    ajd_type="<?php echo $__comp_type; ?>"
					<?php if(isset($ccb['id'])): ?> ajd_id="<?php echo $ccb['id']; ?>" <?php endif; ?>
                    <?php if(isset($ccb['optid'])): ?> ajd_catid="<?php echo $ccb['optid']; ?>" <?php endif; ?>
                    ><?php echo $ccb['title']; ?></li>
            	<?php endif; ?>
			<?php endforeach; ?>
            
            <?php if($cat_arr_main[0]['show_none_button']): ?>            
                <li class="categorylist" listid="none" style="height:30px; <?php if($__comp_type == 'chains'): ?>float: left; width: auto; font-size: 13px; font-weight: bold; <?php endif; ?>">NO CHAIN</li>
            <?php endif; ?>
            
            <?php if($cat_arr_main[0]['show_all_button']): ?>   
                <li class="categorylist <?php if(!$cat_arr_main[0]['show_none_button']): ?>active<?php endif; ?>" listid="all" style="height:30px; <?php if($__comp_type == 'chains'): ?> float: left; width: auto;<?php endif; ?>">Show all</li>
            <?php endif; ?>
      </ul>
    </div>  
    <?php else: ?>

		<script type="text/javascript"> 
			cbo_cats['<?php echo $__fin_id; ?>'] = <?php echo json_encode($arr_cats); ?>;
		</script>
	<?php 
	
	if(is_array($arr_cats)) : ?>
    	<div style="font-size: 14px;">Select Category:</div>
        <div style="float: left;">
		<select style="margin: 5px 15px 5px 0; width: 170px;"  id="cat_<?php echo $__fin_id; ?>" onchange="javascript: sub_cat(this);">
			 <?php foreach($arr_cats as $cca => $ccb): 
			 
			 $go_sub = false;
			 $subs = '';
			 
			 if(is_array($ccb['sub_cats']) && isset($ccb['def'])) {
				 $go_sub = true;
				 $subs = $ccb['sub_cats'];
			 }
			 
			 ?>
                <option style="padding: 2px;" <?php if(isset($ccb['id'])): ?> ajd_id="<?php echo $ccb['id']; ?>" <?php endif; ?> <?php if(isset($ccb['optid'])): ?> ajd_catid="<?php echo $ccb['optid']; ?>" <?php endif; ?> ajd_type="<?php echo $__comp_type; ?>" listid="<?php echo $ccb['group']; ?>" value="<?php echo $ccb['id']; ?>" <?php if(isset($ccb['default']) || isset($ccb['def'])): $active_group = $ccb['group']; ?> selected="selected"<?php endif; ?>><?php echo $ccb['title'];  ?></option>
              <?php endforeach; ?>
			  <?php if($cat_arr_main[0]['show_all_button']): ?>
             <option style="padding: 2px;" listid="all" value="all" selected="selected">Show All</option>
             <?php endif; ?>
              
        </select>
        </div>
        <div style="float: left;" id="subcat_<?php echo $__fin_id; ?>_holder"></div>
	<?php
	endif;
?>
	
     
<?php endif; 
endif; ?>

<div id="selector-list" style="padding: 0; margin-top: 5px;">
    <ul style=" <?php if($ul_width > 0): ?>width: <?php echo $ul_width; ?>px;<?php endif; ?> <?php if($ul_height > 0): ?>height: <?php echo $ul_height; ?>px;<?php endif; ?> <?php if($__comp_type == 'chains'): ?>margin: 5px 0 0 0; <?php endif; ?> min-height: 330px;" >

            <?php foreach($arr_list as $cca => $ccb): ?>
                <li>
                <?php 
				if($__comp_type == 'chains') {
					if($ccb['group'] === $active_group) {
						$showed = 'show';
					}else{
						$showed = 'hide';
					}
				}
				?>
                
                <div ajd_type="<?php echo $__comp_type; ?>" id="<?php echo $__comp_type.'-'.$cca; ?>" class="selector-item <?php echo $showed; ?> selector-item-<?php echo $__comp_type; ?> " <?php if(isset($ccb['def'])) :?> sel="true" style="border-color: <?php  if($__comp_type == 'chains'): ?>#a9c4d8<?php else: ?>red<?php endif; ?>; background:  <?php  if($__comp_type == 'chains'): ?>#e3eff7<?php endif; ?>;"<?php endif; ?> title="<?php echo $ccb['title']; ?>" <?php if(isset($ccb['value'])) : ?>value="<?php echo $ccb['id']; ?>"<?php endif; ?> <?php if($go_categorized) :?>cat_list="<?php if($__comp_type == 'chains') { echo $ccb['group']; }else{ echo $ccb['cat']; } ?>"<?php endif; ?> <?php if(isset($ccb['pid'])) :?>pid="<?php echo $ccb['pid']; ?>"<?php endif; ?>  <?php if(isset($ccb['price'])) :?>price="<?php echo $ccb['price']; ?>"<?php endif; ?> <?php if(isset($ccb['id'])) :?>ajd_id="<?php echo $ccb['id']; ?>"<?php endif; ?>>
                <?php if($__comp_type == 'chains'): ?>
                    <div>
                    <p><b><?php echo $ccb['title']; ?></b></p>
                    </div>
                    <div>
                    <div style="width:170px; float:left; position: relative; padding-right: 5px;">
                    <?php if(empty($adjust4Mobile) && !empty($chainImgZoom)): ?>
                    	<?php echo '<span class="zoomie">'; ?>
					<?php endif; ?>
                    <img src="<?php echo $site_url.'/gifts/'.$ccb['image']; ?>" width="170">
                    <?php if(empty($adjust4Mobile) && !empty($chainImgZoom)): ?>
                    	<?php echo '</span><br />'; ?>
                        <?php echo '<div class="zoomIns">Move mouse over to zoom in</div>'; ?>
					<?php endif; ?>
                    </div>
                    <div style="padding: 4px; text-align: justify; float:left; width: 200px"><?php echo $ccb['desc']; ?></div>
                    </div>
                    <div class="clear"></div>
                    <?php if(is_array($ccb['options'])): ?>
                    <div style="margin: 3px; padding-top: 3px;">
                        <select class="chain_opts" style="width: 350px;">
                          <?php foreach($ccb['options'] as $cca2 => $ccb2): ?>
                            <option ajd_price="<?php echo $ccb2['price']; ?>" style="padding: 2px;" value="<?php echo $ccb2['id']; ?>" <?php if(isset($ccb2['def'])): ?> selected="selected"<?php endif; ?>>
                                  <?php echo '+ $'.$ccb2['price'].' - '.$ccb2['title'];  ?>
                            </option>
                          <?php endforeach; ?>                 
                        </select>
                    </div>
                    <?php endif; ?>
                <?php else: ?>
                	<img class="clip_load" src="<?php echo $site_url.'/custom/configurator/interface/assets/images/transparent_bg.png'; ?>" data-original="<?php echo $ccb['image']; ?>" main-category="<?php echo $ccb['main_cat']; ?>" width="55" height="55">
                <?php endif; ?>
                </div>
                </li>
            <?php endforeach; ?>
    </ul>
</div>
<div class="clear"></div>
</div>

<script type="text/javascript"> 
JQ_AJD(document).ready(function() {
	func_selector_js(JQ_AJD('#mover_<?php echo $__fin_id; ?>'), '<?php echo $__comp_type; ?>', '<?php echo $__fin_id; ?>', '<?php echo $comp_def; ?>');
});
</script>