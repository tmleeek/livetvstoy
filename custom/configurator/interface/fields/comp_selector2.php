<?php
/*
	Component Selector Field
*/
?>

<?php
// Function to get add-on prices for Engraving options
$engrave_array = FUNC_CUSTOM_engraving($VAR_product_code, $bf['type'], $def);

$current_side = '';

if(strpos(strtolower($bf['label']), 'front') !==false){
	$current_side = 'F'; 
} else if(strpos(strtolower($bf['label']), 'reverse') !==false) {
	$current_side = 'B';
} else if(strpos(strtolower($bf['label']), 'left') !==false) {
	$current_side = 'L';
} else if(strpos(strtolower($bf['label']), 'right') !==false) {
	$current_side = 'R';
} else if(strpos(strtolower($bf['label']), 'insert 1') !==false) {
	$current_side = 'I1';
} else if(strpos(strtolower($bf['label']), 'insert 2') !==false) {
	$current_side = 'I2';
} else if(strpos(strtolower($bf['label']), 'left image') !==false) {
	$current_side = 'LI';
} else if(strpos(strtolower($bf['label']), 'right image') !==false) {
	$current_side = 'RI';
}

$words = preg_split("/\s+/",$bf['label']);
$acronym = "";

foreach ($words as $w) {
  $acronym .= $w[0];
}

if(isset($engrave_array[strtolower($acronym).'_engraving'])) :

?>
<div style="max-width: 430px;/* margin: 0 auto;*/">
<ul class="comp-sel-main" style="display: none;">

<?php 
// Split to get value for default or selected option/sub-option 
if($def=='' && isset($bf['default']))
	$def = $bf['default'];

$sel_arr = explode("~", $def);
$def_val = '';
$def_opt = '';
$def_sopt = '';
$def_sopt2 = '';

if(is_array($sel_arr)) {
	// Selected Component Option
	if(isset($sel_arr[0])) {
		$def_opt = $sel_arr[0];
	}
	// Selected Component Option Value
	if(isset($sel_arr[1])) {
		$def_val = $sel_arr[1];
	}
	// Selected Component Sub-Option Value
	if(isset($sel_arr[2])) {
		$def_sopt = $sel_arr[2];
	}
	// Selected Component Sub-Option 2 Value
	if(isset($sel_arr[3])) {
		$def_sopt2 = $sel_arr[3];
	}
}
		
// Display options for Component Selector
foreach($b['content']['layer'] as $a2 => $b2){ 
	
	if(isset($b2['option']) && is_array($b2['option'])) { 
	
		foreach($b2['option'] as $a3 => $b3) {
			
			// Set value of selected component
			if($def_opt == $a3) {
				$comp_def = $def_val;
			}else{
				$comp_def = isset($b3['init_value']) ? $b3['init_value'] : '';
			}
			
			$side_price = '0';
			$side_optid = 0;
			$side_optvarid = 0;
			
			if($b3['comp_type'] == 'wording') {
				$side_price = $engrave_array[strtolower($current_side).'_engraving']['text']['price'];
				$side_optid = $engrave_array[strtolower($current_side).'_engraving']['text']['optid'];
				$side_optvarid = $engrave_array[strtolower($current_side).'_engraving']['text']['optvarid'];
			}else if($b3['comp_type'] == 'text') {
				$side_price = $engrave_array[strtolower($current_side).'_engraving']['mono']['price'];
				$side_optid = $engrave_array[strtolower($current_side).'_engraving']['mono']['optid'];
				$side_optvarid = $engrave_array[strtolower($current_side).'_engraving']['mono']['optvarid'];
			}else if($b3['comp_type'] == 'cliparts') {
				
				if(isset($engrave_array[strtolower($current_side).'_engraving']['clipart']['price']))
					$side_price = $engrave_array[strtolower($current_side).'_engraving']['clipart']['price'];
					
				if(isset($engrave_array[strtolower($current_side).'_engraving']['clipart']['optid']))	
					$side_optid = $engrave_array[strtolower($current_side).'_engraving']['clipart']['optid'];
				
				if(isset($engrave_array[strtolower($current_side).'_engraving']['clipart']['optvarid']))
					$side_optvarid = $engrave_array[strtolower($current_side).'_engraving']['clipart']['optvarid'];

				if(isset($engrave_array[strtolower($acronym).'_engraving']['optvarid']))
					$optvarid = $engrave_array[strtolower($acronym).'_engraving']['optvarid'];
					
				if(isset($engrave_array[strtolower($acronym).'_engraving']['optid']))
					$optid = $engrave_array[strtolower($acronym).'_engraving']['optid'];
				
			}else if($b3['comp_type'] == 'none') {
				$side_price = $engrave_array[strtolower($current_side).'_engraving']['none']['price'];
				$side_optid = $engrave_array[strtolower($current_side).'_engraving']['none']['optid'];
				$side_optvarid = $engrave_array[strtolower($current_side).'_engraving']['none']['optvarid'];
			}
			
// Display radio buttons for component selector	
?>

<li class="comp-selector<?php if($def_opt == $a3) :?> active<?php endif; ?>">
<a href="#nogo" onclick="javascript: comp_selector(this,'<?php echo $b3['comp_type']; ?>','<?php echo $comp_def; ?>');" id="cosel_<?php echo $a.'-'.$a2.'-'.$a3; ?>">
<div class="cs-bg-<?php echo $b3['comp_type']; ?> cs-bg"></div>
<input type="radio" ajd_optid="<?php echo $side_optid; ?>" ajd_optvarid="<?php echo $side_optvarid; ?>" ajd_price="<?php echo $side_price; ?>" comp_type="<?php echo $b3['comp_type'] ; ?>" side="<?php echo $current_side ; ?>" name="comp_opt_<?php echo $a; ?>" id="coselrad_<?php echo $a.'-'.$a2.'-'.$a3; ?>" value="<?php echo $a3; ?>" <?php if($def_opt == $a3) :?> checked="checked" <?php endif; ?>>
<span style="font-size: 11px; display: block"><?php echo $b3['selector_label']; ?></span>
</a>
</li>
<?php 
		}
	}
}

?>
</ul>

<div class="field-holder" id="coselhol-<?php echo $a; ?>">
<?php 
// Create options for active/inactive components
foreach($b['content']['layer'] as $a2 => $b2){ 
	if(isset($b2['option']) && is_array($b2['option'])) { 
	
		foreach($b2['option'] as $a3 => $b3) {
			// Set value of selected component
			if($def_opt == $a3) {
				$comp_def = $def_val;
			}else{
				$comp_def = isset($b3['init_value']) ? $b3['init_value'] : '';
			}
// Create fields for active/inactive components
?>

<?php $side_optid = $engrave_array[strtolower($current_side).'_engraving']['optid']; ?>

<div class="cfields <?php if($def_opt == $a3) :?>show <?php endif; ?>" id="cfield_<?php echo $a.'-'.$a2.'-'.$a3; ?>">  
      <?php include 'components2.php'; ?>
</div>
<?php 
		}
	}
}

// Create hidden field to store values for each step
?>
</div>
    <input id="coselhi-<?php echo $a.'-'.$a2; ?>" type="hidden" name="AJD_fields[<?php echo $af; ?>]" value="<?php echo $def; ?>" />
	<input id="clip-<?php echo $optid; ?>" type="hidden" ajd_type="<?php echo $bf['selector_type']; ?>" ajd_optid="<?php echo $optid; ?>" value="<?php echo $comp_str; ?>" clip_name="<?php echo $comp_str; ?>" clip_cat="" >
	
<?php if($a == 0 && !$hide_step): ?>
    <input class="mv_itm_rt" type="hidden" name="AJD_fields[mv_itm_rt_F]" value="<?php echo $mv_itm_rt_F; ?>" />
    <input class="mv_itm_y" type="hidden" name="AJD_fields[mv_itm_y_F]" value="<?php echo $mv_itm_y_F; ?>" />
    <input class="mv_itm_x" type="hidden" name="AJD_fields[mv_itm_x_F]" value="<?php echo $mv_itm_x_F; ?>" />
    <input class="mv_itm_rz" type="hidden" name="AJD_fields[mv_itm_rz_F]" value="<?php echo $mv_itm_rz_F; ?>" />
<?php elseif($a == 3 || $a == 5) : ?>
    <input class="mv_itm_rt" type="hidden" name="AJD_fields[mv_itm_rt_B]" value="<?php echo $mv_itm_rt_B; ?>" />
    <input class="mv_itm_y" type="hidden" name="AJD_fields[mv_itm_y_B]" value="<?php echo $mv_itm_y_B; ?>" />
    <input class="mv_itm_x" type="hidden" name="AJD_fields[mv_itm_x_B]" value="<?php echo $mv_itm_x_B; ?>" />
    <input class="mv_itm_rz" type="hidden" name="AJD_fields[mv_itm_rz_B]" value="<?php echo $mv_itm_rz_B; ?>" />
<?php endif; ?>

</div>
<?php else : ?>
Click on the Next Step button to proceed to the next step.
<?php endif; ?>

<?php if(isset($engrave_array['data_holder'])) : ?>
<div id="AJD-data-holder" ajd_optid="<?php echo $engrave_array['data_holder']['optid']; ?>"></div>
<?php endif; ?>
