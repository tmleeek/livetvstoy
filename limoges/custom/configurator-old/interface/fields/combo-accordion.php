<?php
/*
	Dropdown Field
*/

?>

<?php
// Prepare combo box values from product fields and database using custom functions (custom.php)
// For metal
if($bf['type'] == 'metal') {
	$cbo_values = FUNC_CUSTOM_metals($VAR_product_code, $bf['type'], $def);
// For Birthstone
}else if($bf['type'] == 'stones') {
	$cbo_values = FUNC_CUSTOM_stones($VAR_product_code, $bf['type'], $def);
}else{
	//$cbo_values = FUNC_cbo_ready(FUNC_get_XML($bf['type']), $bf['type'], $def != '' ? $def : NULL);
}


if($cbo_values != 'disabled') :
?>
<div class="comp-lbl"><?php echo $bf['label']; ?></div>
<div style="position: relative; padding-bottom: 20px; float: left;">
<select ajd_type="<?php echo $bf['type']; ?>" name="AJD_fields[<?php echo $af; ?>]" id="cbo-<?php echo $a.'-'.$af; ?>" onclick="javascript: tmb_focus('<?php echo $a; ?>');" onchange="javascript: <?php if($bf['type'] == 'metal'): ?>metal_update(this);<?php else: ?>delayed_update(1);<?php endif; ?>" style="width: 260px;">
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
<script type="text/javascript">
combo_list["cbo-<?php echo $a.'-'.$af; ?>"] = '<?php echo $bf['type']; ?>';
</script>
</div>
<?php endif; ?>