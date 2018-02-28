<?php
/*
	Text Field
*/
?>

<?php
// Function to get add-on prices for Engraving options
$engrave_array = FUNC_CUSTOM_engraving($VAR_product_code, $bf['type'], $def);

$current_side = '';

if(strpos(strtolower($bf['label']), 'left') !==false) {
	$current_side = 'L';
} else if(strpos(strtolower($bf['label']), 'right') !==false) {
	$current_side = 'R';
} else if(strpos(strtolower($bf['label']), 'school') !==false) {
	$current_side = 'S';
} else if(strpos(strtolower($bf['label']), 'year') !==false) {
	$current_side = 'Y';
} else if(strpos(strtolower($bf['label']), 'inside') !==false) {
	$current_side = 'I';
//} else if(strpos(strtolower($bf['label']), 'top') !==false) {
	//$current_side = 'T';
} else if(strpos(strtolower($bf['label']), 'top text 1') !==false) {
	$current_side = 'T1';
} else if(strpos(strtolower($bf['label']), 'top text 2') !==false) {
	$current_side = 'T2';
} else if(strpos(strtolower($bf['code']), 'top text 3') !==false) {
	$current_side = 'T3';
} else if(strpos(strtolower($bf['code']), 'top text 4') !==false) {
	$current_side = 'T4';
} else if(strpos(strtolower($bf['label']), 'front text 1') !==false) {
	$current_side = 'F1';
} else if(strpos(strtolower($bf['label']), 'front text 2') !==false) {
	$current_side = 'F2';
} else if(strpos(strtolower($bf['label']), 'front text 3') !==false) {
	$current_side = 'F3';
} else if(strpos(strtolower($bf['label']), 'front text 4') !==false) {
	$current_side = 'F4';
} else if(strpos(strtolower($bf['label']), 'front text 5') !==false) {
	$current_side = 'F5';
} else if(strpos(strtolower($bf['label']), 'front text 6') !==false) {
	$current_side = 'F6';
} else if(strpos(strtolower($bf['label']), 'front text 7') !==false) {
	$current_side = 'F7';
} else if(strpos(strtolower($bf['label']), 'front text 8') !==false) {
	$current_side = 'F8';
}

if(isset($engrave_array[strtolower($current_side).'_engraving'])) :
?>

<?php 
	
$comp_def = $def != '' ? $def : (isset($bf['default']) ? $bf['default'] : 'Your Text Here');

$side_price = $engrave_array[strtolower($current_side).'_engraving']['price'];
$side_optid = $engrave_array[strtolower($current_side).'_engraving']['optid'];
$side_title = $engrave_array[strtolower($current_side).'_engraving']['title'];
$side_maxchars = $engrave_array[strtolower($current_side).'_engraving']['max_chars'];
$side_required = $engrave_array[strtolower($current_side).'_engraving']['is_required'];

?>

<div class="clear"></div>

    <div class="clear"></div>
    
    <div class='<?php if ( $bf['type'] == 'textd' ) { echo 'textfielditem'; } ?>' >
    
    <label style="padding: 15px 0 5px 0;">
        <?php echo $side_title; ?>
    </label>
	<span class="note limit-text limit<?php echo $current_side;?>">Limit <?php echo $side_maxchars; ?> characters</span>
    <div style="/*float: left;*/" class="designerInput">
    
<?php  $chars_allow = isset($bf['chars']) ? $bf['chars'] : '' ;  ?>
	
	<?php if ( ( $current_side == 'T1' || $current_side == 'T2') && ($VAR_product_code !== '22489' && $VAR_product_code !== '22490' && $VAR_product_code !== '41393' && $VAR_product_code !== '20950') ) : ?>
		<?php $cl = 'frontRField'; ?>
	<?php else: ?>
		<?php $cl = ''; ?>
	<?php endif; ?>

    <input class="textDesigner f-<?php echo $current_side.' '.$cl; ?>" id="text-<?php echo $side_optid; ?>" type="text2" style="width: 250px" onfocus="javascript: tmb_focus('<?php echo $bf['assoc_step_group']; ?>');" onkeypress="javascript: check_char(this, '<?php echo $chars_allow; ?>', '<?php echo $bf['assoc_step_group']; ?>', event);" onkeyup="javascript: check_char(this, '<?php echo $chars_allow; ?>', '<?php echo $bf['assoc_step_group']; ?>', event);" onblur="javascript: check_char(this, '<?php echo $chars_allow; ?>', '<?php echo $bf['assoc_step_group']; ?>', event);" maxlength="<?php echo $side_maxchars; ?>" style="width: 50px;" ajd_optid="<?php echo $side_optid; ?>" name="AJD_fields[<?php echo $af; ?>]" value="<?php echo $comp_def; ?>"<?php if($side_required) : ?> required<?php endif; ?> />
    </div>
    <div class="clear"></div>
    </div>
<?php endif; ?>