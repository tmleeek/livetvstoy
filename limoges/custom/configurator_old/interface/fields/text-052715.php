<?php
/*
	Component Selector Field
*/

?>

<?php
// Function to get add-on prices for Engraving options
$engrave_array = FUNC_CUSTOM_engraving($VAR_product_code, $bf['type'], $def);

	//echo '<pre>';
	//print_r($engrave_array);
	//echo '<pre>';
	//exit;

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
} else if(strpos(strtolower($bf['label']), 'inside') !==false) {
	$current_side = 'I';
} else if(strpos(strtolower($bf['label']), 'top text 1') !==false) {
	$current_side = 'T1';
} else if(strpos(strtolower($bf['label']), 'top text 2') !==false) {
	$current_side = 'T2';
} else if(strpos(strtolower($bf['code']), 'top text 3') !==false) {
	$current_side = 'T3';
} else if(strpos(strtolower($bf['code']), 'top text 4') !==false) {
	$current_side = 'T4';
}

if(isset($engrave_array[strtolower($current_side).'_engraving'])) :

?>

<?php 
	
$comp_def = $def != '' ? $def : (isset($bf['default']) ? $bf['default'] : 'Your Text Here');

$side_price = $engrave_array[strtolower($current_side).'_engraving']['price'];
$side_optid = $engrave_array[strtolower($current_side).'_engraving']['optid'];
$side_title = $engrave_array[strtolower($current_side).'_engraving']['title'];
$side_maxchars = $engrave_array[strtolower($current_side).'_engraving']['max_chars'];

?>

<?php if(isset($engrave_array['data_holder'])) : ?>
<div id="AJD-data-holder" ajd_optid="<?php echo $engrave_array['data_holder']['optid']; ?>"></div>
<?php endif; ?>

<div class="clear"></div>

    <div class="clear"></div>
    <div>
    <label style="padding: 15px 0 5px 0;">
        <?php echo $side_title; ?>
    </label>
	<span class="note limit-text limit<?php echo $current_side;?>">Limit <?php echo $side_maxchars; ?> characters</span>
    <div style="float: left;" class="designerInput">
    
<?php  $chars_allow = isset($bf['chars']) ? $bf['chars'] : 'A-Z a-z 0-9 & ' ;  ?>
	
	<?php if ( ( $current_side == 'T1' || $current_side == 'T2') && ($VAR_product_code !== '22489' && $VAR_product_code !== '22490') ) : ?>
		<?php $cl = 'frontRField'; ?>
	<?php else: ?>
		<?php $cl = ''; ?>
	<?php endif; ?>

    <input class="textDesigner f-<?php echo $current_side.' '.$cl; ?>" id="text-<?php echo $side_optid; ?>" type="text2" style="width: 250px" onfocus="javascript: tmb_focus('<?php echo $bf['assoc_step_group']; ?>');" onkeypress="javascript: check_char(this, '<?php echo $chars_allow; ?>', '<?php echo $bf['assoc_step_group']; ?>', event);" onkeyup="javascript: check_char(this, '<?php echo $chars_allow; ?>', '<?php echo $bf['assoc_step_group']; ?>', event);" onblur="javascript: check_char(this, '<?php echo $chars_allow; ?>', '<?php echo $bf['assoc_step_group']; ?>', event);" maxlength="<?php echo $side_maxchars; ?>" style="width: 50px;" ajd_optid="<?php echo $side_optid; ?>" name="AJD_fields[<?php echo $af; ?>]" value="<?php echo $comp_def; ?>" required />
    </div>
    <div class="clear"></div>
    </div>
<?php endif; ?>