<?php
/*
	Dropdown Field
*/

?>

<?php
	
// set default value for combo box
$def = ( !empty($def) ) ? $def : $bf['default'];
	
$cbo_values = 'disabled';

// Prepare combo box values from product fields and database using custom functions (custom.php)
// For metal
/*if($bf['type'] == 'metal') {
	$cbo_values = FUNC_CUSTOM_metals($VAR_product_code, $bf['type'], $def);
// For Birthstone
}else */
if($bf['type'] == 'stones') {
	$cbo_values = FUNC_CUSTOM_stones($VAR_product_code, $bf['type'], $def, $bf['label']);
// For Sizes
}else if($bf['type'] == 'size') {
	$cbo_values = FUNC_CUSTOM_sizes($VAR_product_code, $bf['type'], $def, $bf['label']);
//}else{
	//$cbo_values = FUNC_cbo_ready(FUNC_get_XML($bf['type']), $bf['type'], $def != '' ? $def : NULL);
}


if($cbo_values != 'disabled') :
?>
<label style="margin-right: 5px; padding-top: 10px;"><?php echo $bf['label']; ?></label>

	<?php if($bf['type'] == 'size') : ?>
		<a class="size-chart" href="#size-chart-content" target="_blank">Size Chart</a>
	<?php endif; ?>

<div style="position: relative; ">
<select required ajd_type="<?php echo $bf['type']; ?>" name="AJD_fields[<?php echo $af; ?>]" id="cbo-<?php echo $a.'-'.$af; ?>"<?php if($bf['type'] == 'stones') : ?> onclick="javascript: tmb_focus('<?php echo $a; ?>');" onchange="javascript: delayed_update(1);"<?php endif; ?> style="width: 260px;">
	<option value="">Choose an option...</option>
  <?php foreach($cbo_values as $ac => $bc) : ?>
    <option value="<?php echo $bc['value']; ?>" 
	<?php if(isset($bc['selected'])): ?>selected="selected"<?php endif; ?> 
	<?php if(isset($bc['img'])): ?>title="<?php echo $bc['img']; ?>"<?php endif; ?>
    <?php if(isset($bc['price'])): ?>ajd_price="<?php echo $bc['price']; ?>"<?php endif; ?>
    <?php if(isset($bc['optid'])): ?>ajd_optid="<?php echo $bc['optid']; ?>"<?php endif; ?>
    <?php if(isset($bc['optvarid'])): ?>ajd_optvarid="<?php echo $bc['optvarid']; ?>"<?php endif; ?>
    <?php if(isset($bc['id'])): ?>ajd_id="<?php echo $bc['id']; ?>"<?php endif; ?>
    ><?php echo trim(($bc['title'])); ?></option>
  <?php endforeach; ?>                 
</select>
</div>
<?php endif; ?>