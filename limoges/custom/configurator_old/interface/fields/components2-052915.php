<?php
/*
	Designer Components Selector
*/   

$text_area_on = false;

if(isset($b3['interface'])){
	
	if($b3['interface'] == 'text_area'){
		$text_area_on = true;
	}; 

}

?>
<?php if($b3['comp_type'] != 'none'): ?>

<?php if($b3['comp_type'] == 'wording'): ?>
<div class="wording-more-info"></div><div class="wording_note"><div>Choose the font style for your message engraving, edit the text or select from the Message Engraving Ideas by clicking on the link below.</div><span class="addonPrice">(+ $<?php echo $engrave_array[strtolower($current_side).'_engraving']['text']['price']; ?>)</span></div>

<?php if(!$text_area_on): ?>
<div class="clear"></div>
<div style="min-width: 180px; float: left; padding: 4px 10px 10px 0; font-weight: bold">
    Message Engraving Ideas:
</div>
<?php endif; ?>

<?php elseif($b3['comp_type'] == 'cliparts'): ?>
<div id="clipart_note" style="float: left; padding-top: 5px;">Select the design by clicking on the button below.</div>
<div class="clear"></div>
<label style="width: 100%; padding-top: 5px;">
    <?php echo $bf['label']; ?>: 
</label>
<?php endif; ?>

<?php if(($b3['comp_type'] == 'cliparts' or $b3['comp_type'] == 'wording') and !$text_area_on): ?>

<?php 

$go_sel_btn = true;

if(isset($b3['selector_type']) && $b3['selector_type'] == 'inline' && $b3['comp_type'] == 'cliparts') {
	include $site_root.'/custom/AJAX_designer/interface/fields/inline_selector.php';
	$go_sel_btn = false;
}else{
	$comp_str = $comp_def;
	if($b3['comp_type'] == 'cliparts' ) {
		$clip_dat = FUNC_get_XML('cliparts', $comp_def);
		$comp_str = $clip_dat['name'];
	}
}
?>

<?php if($go_sel_btn) : ?>

<?php

$words = preg_split("/\s+/",$bf['label']);
$acronym = "";

foreach ($words as $w) {
  $acronym .= $w[0];
}

$optvarid = '';
$optid = '';
if(isset($engrave_array[strtolower($acronym).'_engraving']['optvarid']))
	$optvarid = $engrave_array[strtolower($acronym).'_engraving']['optvarid'];
	
if(isset($engrave_array[strtolower($acronym).'_engraving']['optid']))
	$optid = $engrave_array[strtolower($acronym).'_engraving']['optid'];

?>

<div class="select-btn" onclick="javascript: func_selector('<?php echo $b3['comp_type']; ?>','<?php echo $a.'-'.$a2.'-'.$a3; ?>','<?php echo $comp_def; ?>','<?php echo $optid; ?>'); tmb_focus('<?php echo $bf['assoc_step_group']; ?>'); " id="selector_<?php echo $a.'-'.$a2.'-'.$a3; ?>" <?php if(isset($b3['cat_list'])): ?>cat_list="<?php echo $b3['cat_list']; ?>"<?php endif; ?>>
    <span class="label-title_<?php echo $a.'-'.$a2.'-'.$a3; ?> label-title">
    <?php echo $comp_str; ?>
    </span>
</div>
<?php endif; ?>

<?php endif; ?>


<?php if(isset($b3['content']['option']) && is_array($b3['content']['option'])): ?>

	<?php if($b3['comp_type'] == 'text' || $b3['comp_type'] == 'wording'): ?>
    	<div class="comp-lbl" style="min-width: 80px; padding: 15px 0 5px 0;">Font Style:</div>
        <div style="max-width: 300px; float: left;">
        <ul class="comp-sel-holder">
        <?php foreach($b3['content']['option'] as $a4 => $b4): ?>
                    <?php
                    $sel = false;
                    if($def_sopt != '') {
                        if($def_sopt == $a4) {
                            $sel = true;
                        }
                    }else{
                        if(isset($b4['default']) && $b4['default'] == true){
                            $sel = true;
                        }
                    }
                    ?>
                <li class="fontstyle-selector<?php if($sel) :?> active<?php endif; ?>">
                <a href="#nogo" onclick="javascript: sopt_selector(this,'<?php echo $a4; ?>',true);" id="soptsel_<?php echo $a.'-'.$a2.'-'.$a3.'-'.$a4; ?>">
                <div class="fs-bg-<?php echo strtolower($b4['opt_label']); ?>"></div>
				<input type="radio" name="sopt_<?php echo $a.'-'.$a2.'-'.$a3; ?>" id="sopt_rb_<?php echo $a4; ?>" value="<?php echo $a4; ?>" <?php if(isset($b4['ajd_id'])) : ?>ajd_id="<?php echo $b4['ajd_id']; ?>"<?php endif; ?> <?php if($sel) :?> checked="checked" <?php endif; ?>>
				</a>        
                </li>  
        <?php endforeach; ?>
        </ul>
        </div>
        <div class="clear"></div>
    
    <?php endif; ?>
    
    <?php if($b3['comp_type'] == 'uploader'): ?>
    
    	<?php foreach($b3['content']['option'] as $a4 => $b4): ?>

        	<?php if($a4 != 0): ?> 			
                
            <label style="padding: 10px 10px 5px 0;">
                <?php echo $b4['opt_label']; ?>: 
            </label>
            
       	    <div class="clear"></div>
            <div>
            <ul class="comp-sel-holder">
        	<?php foreach($b4['content']['option'] as $a5 => $b5): ?>
			<?php
                $sel = false;
                if($def_sopt2 != '') {
                    if($def_sopt2 == $a5) {
                        $sel = true;
                    }
                }else{
                    if(isset($b5['default']) && $b5['default'] == true){
                        $sel = true;
                    }
                }
				
				$add_str = "";
				
				if(strpos(strtolower($b5['opt_label']), 'color') !==false) {
					$insert_type = "color";
				} elseif(strpos(strtolower($b5['opt_label']), 'paper') !==false) {
					$insert_type = "paper";
				} else {
					$insert_type = "laser";
				}
				
				$add_str = $engrave_array[strtolower($current_side).'_engraving']['photo_'.$insert_type]['price'];
				$side_optid = $engrave_array[strtolower($current_side).'_engraving']['photo_'.$insert_type]['optid'];
				$side_optvarid = $engrave_array[strtolower($current_side).'_engraving']['photo_'.$insert_type]['optvarid'];
				/*
				// Price for Color Laser Engraving or Laser Engraving
				if($insert_type == "color" || $insert_type == "laser") {
					if($current_side == 'L') {
						//$add_str =  $bc[$ac]['pic3_left'];
					} else if($current_side == 'R') {
						//$add_str =  $bc[$ac]['pic4_right'];
					} else if($current_side == 'I1') {
						//$add_str =  $bc[$ac]['pic5_inside1'];
					} else if($current_side == 'I2') {
						//$add_str =  $bc[$ac]['pic6_inside2'];
					}
				} else {
					// Price for Paper Photo
					$add_str =  "13.95";
				}*/
				
				$_price = $add_str;
				
				$add_str = "<span class='addonPrice'>(+ $".$add_str.")</span>";

				?>
				<li class="insert-selector<?php if($sel) :?> active<?php endif; ?>">
				<a href="#nogo" onclick="javascript: sopt2_selector(this,'<?php echo $a5; ?>',true);" id="soptsel2_<?php echo $a.'-'.$a2.'-'.$a3.'-'.$a5; ?>">
				<div class="is-bg-<?php echo $insert_type; ?>"></div>
				<input type="radio" ajd_optid="<?php echo $side_optid; ?>" ajd_optvarid="<?php echo $side_optvarid; ?>" ajd_price="<?php echo $_price; ?>" name="sopt2_<?php echo $a.'-'.$a2.'-'.$a3; ?>" id="sopt2_rb_<?php echo $a5; ?>" value="<?php echo $a5; ?>" <?php if(isset($b5['ajd_id'])) : ?>ajd_id="<?php echo $b5['ajd_id']; ?>"<?php endif; ?> <?php if($sel) :?> checked="checked" <?php endif; ?>><br />
				<span style="font-size: 11px;"><?php echo $b5['opt_label']; ?><br /><?php echo $add_str; ?></span>
				</a><br /><span class='question-mark qm-paper'></span>        
				</li>  
				<?php endforeach; ?> 
			 </ul>
			 </div>
             <div class="insert-note-holder"><a class="fancybox-close" href="javascript:;"></a>
        	 <div class="paperphotofield" id="paperphoto-<?php echo $a.'-'.$a2.'-'.$a3; ?>" style="display: block;"><div style="padding: 10px; font-size: 13px; font-style: italic; background-color: #EEE; border: #CCC solid 1px; margin: 10px 0; width: 90%;"><h6 style="width: 90%; color: #000; border-color: #666; border-style: solid; border-width: 0 0 1px; margin-bottom: 5px;">REGULAR PAPER PHOTO</h6><img vspace="3" hspace="3" border="0" align="right" src="/h/custom/images/colorP.gif">We size your picture down and print it from our Enhanced Definition Printing Press on High Quality Photo Paper. We then run it through our Laser Cutter to cut around your picture to fit perfectly inside your Locket.<br /><strong>Note:</strong> You can always order your Locket without a photos and then send it back later to have the photos lasered in.</div></div>
			</div>
       		<?php endif; ?>
        
        <?php endforeach; ?>
            
            
        <?php foreach($b3['content']['option'] as $a4 => $b4): ?>
       		<?php if($a4 == 0): ?>
                
            <label style="min-width: 140px;padding: 10px 10px 5px 0;">
                <?php echo $b4['opt_label']; ?>: 
            </label>
            
       		<div class="clear"></div>
            <div class="comp-send-container">
                <ul class="comp-send-holder">
                <?php foreach($b4['content']['option'] as $a5 => $b5): ?>
                    <?php
                    $sel = false;
                    if($def_sopt != '') {
                        if($def_sopt == $a5) {
                            $sel = true;
                        }
                    }else{
                        if(isset($b5['default']) && $b5['default'] == true){
                            $sel = true;
                        }
                    }
                    ?>
                    <li class="send-selector<?php if($sel) :?> active<?php endif; ?>">
                    <div>
                    <input type="radio" onclick="javascript: sopt_update('<?php echo $a.'-'.$a2.'-'.$a3; ?>', true);" name="sopt_<?php echo $a.'-'.$a2.'-'.$a3; ?>" id="sopt_rb_<?php echo $a5; ?>" value="<?php echo $a5; ?>" <?php if(isset($b5['ajd_id'])) : ?>ajd_id="<?php echo $b5['ajd_id']; ?>"<?php endif; ?> <?php if($sel) :?> checked="checked" <?php endif; ?>>
                    <span style="font-size: 11px;">
                    <?php
                    if($b5['ajd_id'] == "upload" || $b5['ajd_id'] == "email") {
                        echo "<strong>".strtoupper($b5['ajd_id'])."</strong> - for digital pictures";
                    } else {
                        echo "<strong>".strtoupper($b5['ajd_id'])."</strong> - ship your paper pictures";
                    }
                    ?>
                    </span>
                    </div>
                    </li>   
                <?php endforeach; ?>
                 </ul>
            </div>
            <div id="photo-thumb-<?php echo $a; ?>"></div>
            <div class="clear"></div>
            
            <?php
			// Setup Link for Photo Uploader
			$up_data = explode("|", $def_val);
		
			$upload_title = 'Click to Add Photo'; 
			$arr = '';
			if(count($up_data) > 1) {
				$arr = FUNC_UPLOAD_get_img($up_data[0]);	// custom/upload/func.php
				$upload_title = 'Click to Change';
				
			}
			// Display Link for Photo Uploader and Photo Insertion field
			?>
            <div class="uploadfield <?php if($def_sopt == 0) :?>show <?php else : ?>hide <?php endif; ?>" id="uphol-<?php echo $a.'-'.$a2.'-'.$a3; ?>">
            <div class="comp-lbl" style="min-width: 120px; padding: 10px 10px 10px 0;">
                Upload Photo:
            </div>
            <div id="uppoint_<?php echo $a.'-'.$a2.'-'.$a3; ?>"></div>
            <div class="add-photo" style="position: relative; float: left; padding: 10px 0; display: none;"><a href="#nogo" orig_name="<?php echo isset($arr['orig_filename']) ? $arr['orig_filename'] : ''; ?>" new_name="<?php echo isset($arr['new_filename']) ? $arr['new_filename'] : ''; ?>" onclick="javascript: func_uploader(1, '<?php echo $a.'-'.$a2.'-'.$a3; ?>');"  id="upload-<?php echo $a.'-'.$a2.'-'.$a3; ?>"><?php echo $upload_title; ?></a></div>
  
            <div class="clear"></div>
            <div id="uploader-spacer" style="height: 85px; width: 90%; background-color: none;"></div>
            <div id="send-note-holder-1">
	            <div style="padding: 10px; font-size: 13px; font-style: italic; background-color: #EEE; border: #CCC solid 1px; width: 90%;"><h6 style="width: 90%; color: #000; border-color: #666; border-style: solid; border-width: 0 0 1px; margin-bottom: 5px;">Digital Files Requirements</h6>Supported files formats - GIF, JPG, PNG<br /><strong>You can upload files up to a total files size of 5MB</strong><br /><br />Upload Your Pictures through our web-site. (if you do not have digital files on your computer, please choose the mailing us your picture option) Please make sure you give us good clear images.<br />If you have other formats such as PDF, DOC, TIFF or any other file please select e-mail option.</div>
            </div>
            </div>
            <div id="send-note-holder-2">	
            	<div class="emailfield <?php if($def_sopt == 1) :?>show <?php else : ?>hide <?php endif; ?>" id="email-<?php echo $a.'-'.$a2.'-'.$a3; ?>"><div style="padding: 10px; font-size: 13px; font-style: italic; background-color: #EEE; border: #CCC solid 1px; margin: 10px 0; width: 90%;"><h6 style="width: 90%; color: #000; border-color: #666; border-style: solid; border-width: 0 0 1px; margin-bottom: 5px;">Send Pictures by E-Mail</h6>You can e-mail us your digital pictures. You select this option then go through the checkout process. Once you finish you can e-mail your pictures along with the <strong>Order Number</strong> given directly to us. You'll get an e-mail confirmation once we receive your pictures. 99% of the time, the quality of the digital images are fine. If we run into a quality issue we'll contact you.<br /><br />Please e-mail your pictures to: <a href="mailto:orders@picturesongold.com"><strong>orders@picturesongold.com</strong></a><br /><br />Please put the ORDER NUMBER in the subject line.</div></div>
            	<div class="mailfield <?php if($def_sopt == 2) :?>show <?php else : ?>hide <?php endif; ?>" id="mail-<?php echo $a.'-'.$a2.'-'.$a3; ?>"><div style="padding: 10px; font-size: 13px; font-style: italic; background-color: #EEE; border: #CCC solid 1px; margin: 10px 0; width: 90%;"><h6 style="width: 90%; color: #000; border-color: #666; border-style: solid; border-width: 0 0 1px; margin-bottom: 5px;">Send Pictures by Mail</h6>You Can Physically Send us your pictures through the US Mail, UPS, Or Fed-Ex. Please make sure to include the <strong>order number</strong> that you receive when you are through with checkout. You will get an e-mail confirmation once we get your pictures in the mail.<br /><br />Mail your pictures along with order number to:<br />PicturesOnGold.com<br />1639 Richmond Road<br />Staten Island, NY 10304<br />Att: Order Number: XXXXXX</div></div>          
            </div>
            <div class="clear"></div>
         	<?php endif; ?>
        
         <?php endforeach; ?>
    
    <?php endif; ?>
    
<?php endif; ?>

<?php if($b3['comp_type'] == 'text'): ?>
    <div class="clear"></div>
    <div>
    <label style="min-width: 90px; padding: 15px 0 5px 0;">
        Enter Text:
    </label>
    <div style="float: left; padding: 10px 0;">
	
    <input id="txt-<?php echo $a.'-'.$a2.'-'.$a3; ?>" type="text" value="<?php echo $comp_def; ?>" onkeypress="return alpha(event);" onkeyup="javascript: txt_update('<?php echo $a.'-'.$a2.'-'.$a3; ?>');"maxlength="3" style="width: 50px;" />
    </div>
    <div class="clear"></div>
    <div class="monogram-preview-button"><span class="color">Click to Preview</span></div>
    </div>
<?php endif; ?>
<?php 

if($b3['comp_type'] == 'wording'): 

$txt_area_col = ( isset($b3['txt_area_col']) && $b3['txt_area_col'] != '' ) ? $b3['txt_area_col'] : '26';
$txt_area_row =  ( isset($b3['txt_area_row']) && $b3['txt_area_row'] != '' ) ? $b3['txt_area_row'] : '15';
$default_value = "Enter your message here.";

?>

<div id="wordingfields_<?php echo $a.'-'.$a2; ?>" style="max-width: 365px; <?php if($text_area_on): ?> display: none; <?php endif; ?>"></div> 
<?php if(isset($AJAX_designer_VARS['product'][$VAR_product_code]['layers'][$a][$a2]['wording']) && is_array($AJAX_designer_VARS['product'][$VAR_product_code]['layers'][$a][$a2]['wording'])) : ?>
<script type="text/javascript"> 
JQ_AJD(document).ready(function() {
        W_ARR_list['<?php echo $a.'-'.$a2; ?>'] = <?php echo json_encode($AJAX_designer_VARS['product'][$VAR_product_code]['wording'][$a.'-'.$a2]); ?>;
});
</script>
<?php endif; ?>
<?php if($text_area_on): ?>

<div style="margin: 10px 10px 10px; float: left;">

<?php //if(empty($adjust4Mobile)) :?>
<div style="float:left; margin: 10px 10px 10px 0;" class="textcount-holder">
	<?php for( $i=1 ; $i <= $txt_area_row ; $i++ ) : ?>
    <p class='textcount'><?php echo "Line ". $i; ?></p>
    <?php endfor; ?>
</div>
<?php //endif; ?>

<div style = "float: left; text-align: center;">             
<textarea placeholder="<?php echo $default_value; ?>" style="font-family: Arial; font-size: 12px; padding-top: 6px;" wrap="off" rows="<?php echo $txt_area_row; ?>" cols="<?php echo $txt_area_col; ?>" class="tarea_<?php echo $a.'-'.$a2.'-'.$a3; ?> w-txt-area" id="tarea_<?php echo $a.'-'.$a2.'-'.$a3; ?>"></textarea>
<div class="clear"></div>
<div class="text-update-button"><span class="color">Click to Preview</span></div>
</div>
<div class="counter" style="float:left;"></div>
<div class="clear"></div>
<a class="message-engraving-ideas" <?php if(empty($adjust4Mobile)) :?>style="margin-left: 42px; font-size: 14px; text-decoration: underline" <?php endif; ?>href="#nogo" onclick="javascript: func_selector('<?php echo $b3['comp_type']; ?>','<?php echo $a.'-'.$a2.'-'.$a3; ?>','<?php echo $comp_def; ?>');"  id="selector_<?php echo $a.'-'.$a2.'-'.$a3; ?>" <?php if(isset($b3['cat_list'])): ?>cat_list="<?php echo $b3['cat_list']; ?>"<?php endif; ?>>Click for Message Engraving Ideas</a>
</div>
<script type="text/javascript">
	JQ_AJD(document).ready(function(){
		JQ_AJD('#tarea_<?php echo $a.'-'.$a2.'-'.$a3; ?>').taLc({ 
			zHeight: <?php echo $txt_area_row; ?>,
			zWidth: <?php echo $txt_area_col; ?>,
			noNewLine: false
		});	
	});
</script>
<?php endif; ?>
<?php endif; ?>

<?php if($b3['comp_type'] != 'uploader' && $b3['comp_type'] != 'wording' && $b3['comp_type'] != 'cliparts'): ?>
<div class="clear"></div>
<div class="engraving_note" style="padding: 10px; font-size: 13px; font-style: italic; background-color: #EEE; border: #CCC solid 1px; margin: 10px 0; width: 90%;">
We use the highest definition lasers to engrave your artwork on your locket. Laser Engraving is deep and will never scratch off.
</div>
<?php endif; ?>

<?php else : ?>
<div class="step_note">
	<?php if($current_side == 'F'): ?>
    Customize the FRONT SIDE section of the Locket by choosing from the options above.<br /><br />Then click on the Next Step button to proceed to the next step.
    <?php elseif($current_side == 'L'): ?>
    Customize the INSIDE LEFT section of the Locket by choosing from the options above.
    <?php elseif($current_side == 'R'): ?>
    Customize the INSIDE RIGHT section of the Locket by choosing from the options above.
    <?php elseif($current_side == 'I1'): ?>
    Customize the INSERT 1 section of the Locket by choosing from the options above.
    <?php elseif($current_side == 'I2'): ?>
    Customize the INSERT 2 section of the Locket by choosing from the options above.
    <?php elseif($current_side == 'B'): ?>
    Customize the REVERSE SIDE of the Locket by choosing from the options above.<br /><br />Then click on the Next Step button to select a chain.
    <?php endif; ?>
</div>
<?php endif; ?>