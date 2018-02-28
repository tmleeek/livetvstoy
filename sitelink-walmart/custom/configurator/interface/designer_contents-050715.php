<?php
/*
	Accordion Contents
*/
?>

<div class="accordion-contents" style="min-height: 20px !important;">
<?php foreach($product_fields['fields'] as $af => $bf) : ?>
	
    <?php $def = '';
	// Create accordion content for each field for current step (fields.xml)
	if(isset($AJAX_designer_VARS['product'][$VAR_product_code]['values']))
		// Get AJAX designer values from session or default values
		$def = $AJAX_designer_VARS['product'][$VAR_product_code]['values'][$af];
		
		if($a == 0 && !$hide_step) {
			$mv_itm_rt_F = (isset($AJAX_designer_VARS['product'][$VAR_product_code]['values']['mv_itm_rt_F'])) ? $AJAX_designer_VARS['product'][$VAR_product_code]['values']['mv_itm_rt_F'] : 0;
			$mv_itm_x_F = (isset($AJAX_designer_VARS['product'][$VAR_product_code]['values']['mv_itm_x_F'])) ? $AJAX_designer_VARS['product'][$VAR_product_code]['values']['mv_itm_x_F'] : 0;
			$mv_itm_y_F = (isset($AJAX_designer_VARS['product'][$VAR_product_code]['values']['mv_itm_y_F'])) ? $AJAX_designer_VARS['product'][$VAR_product_code]['values']['mv_itm_y_F'] : 0;
			$mv_itm_rz_F = (isset($AJAX_designer_VARS['product'][$VAR_product_code]['values']['mv_itm_rz_F'])) ? $AJAX_designer_VARS['product'][$VAR_product_code]['values']['mv_itm_rz_F'] : 0;
		} else if($a == 3 || $a == 5) {
			$mv_itm_rt_B = (isset($AJAX_designer_VARS['product'][$VAR_product_code]['values']['mv_itm_rt_B'])) ? $AJAX_designer_VARS['product'][$VAR_product_code]['values']['mv_itm_rt_B'] : 0;
			$mv_itm_x_B = (isset($AJAX_designer_VARS['product'][$VAR_product_code]['values']['mv_itm_x_B'])) ? $AJAX_designer_VARS['product'][$VAR_product_code]['values']['mv_itm_x_B'] : 0;
			$mv_itm_y_B = (isset($AJAX_designer_VARS['product_B'][$VAR_product_code]['values']['mv_itm_y_B'])) ? $AJAX_designer_VARS['product'][$VAR_product_code]['values']['mv_itm_y_B'] : 0;
			$mv_itm_rz_B = (isset($AJAX_designer_VARS['product_B'][$VAR_product_code]['values']['mv_itm_rz_B'])) ? $AJAX_designer_VARS['product'][$VAR_product_code]['values']['mv_itm_rz_B'] : 0;
		}
        
		/*
		$mv_itm_rt = (isset($AJAX_designer_VARS['product'][$VAR_product_code]['values']['mv_itm_rt'])) ? $AJAX_designer_VARS['product'][$VAR_product_code]['values']['mv_itm_rt'] : 0;
		$mv_itm_x = (isset($AJAX_designer_VARS['product'][$VAR_product_code]['values']['mv_itm_x'])) ? $AJAX_designer_VARS['product'][$VAR_product_code]['values']['mv_itm_x'] : 0;
		$mv_itm_y = (isset($AJAX_designer_VARS['product'][$VAR_product_code]['values']['mv_itm_y'])) ? $AJAX_designer_VARS['product'][$VAR_product_code]['values']['mv_itm_y'] : 0;
		$mv_itm_rz = (isset($AJAX_designer_VARS['product'][$VAR_product_code]['values']['mv_itm_rz'])) ? $AJAX_designer_VARS['product'][$VAR_product_code]['values']['mv_itm_rz'] : 0;
		*/
	?>
    
    <?php if($bf['assoc_step_group'] == $a) : ?>  
        <?php if($bf['selector_type'] == 'combo') : ?>
        	<!-- Combo box -->
        	<?php include 'fields/combo.php'; ?>
        <?php elseif($bf['selector_type'] == 'text') : ?>
        	<!-- Text -->
			<?php include 'fields/text.php'; ?>
        <?php elseif($bf['selector_type'] == 'comp_selector') : ?>
        	<!-- Component Selector -->
			<?php include 'fields/comp_selector2.php'; ?>
        <?php elseif($bf['selector_type'] == 'chains') : ?>
        	<!-- Chains -->
        	<?php include 'fields/chains.php'; ?>
        <?php elseif($bf['selector_type'] == 'instructions') : ?>
        	<!-- Instructions -->
			<?php include 'fields/instructions.php'; ?>
        <?php endif; ?>
	<?php endif; ?>
<?php endforeach; ?>

</div>