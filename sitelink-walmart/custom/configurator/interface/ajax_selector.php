<?php
/*
	Ajax Selector for Ajax Designer
*/
?>

<?php

if($com_type == 'wording') {
	$_title = "message";	
}else{
	$_title = $com_type;
}

?>

<h1 class="selectorheader">Choose your Artwork</h1>
<div style="padding: 0 20px 10px 10px;">Select from the list of artwork below and click on <strong>CONTINUE</strong>.</div>
<div <?php if($com_type == 'chains'): ?>style="height: 400px;"<?php endif; ?>>

<?php

$showed = 'show';
$ul_height = 500;
$ul_width = 0;
$cat_ul_width = 100;
$cat_ul_height = 500;
  
if($go_categorized && count($arr_cats) > 1) {
	if($com_type == 'wording') {
		$ul_width = 260;
		$ul_height = 400;
		$cat_ul_width = 150;
	}else if($com_type == 'chains') {
		$ul_height = 300;
		$ul_width = 447;
		$cat_ul_width = 440;
		$showed = 'hide';
	}
	
}else{
	$ul_width = 449;
}

$active_group = 'none';

if($go_categorized && count($arr_cats) > 1) :?>
    <div id="selector-category">
      <ul style=" <?php if(isset($cat_ul_width) && $cat_ul_width != 0): ?> width: <?php echo $cat_ul_width; ?>px; <?php endif; ?> <?php if($cat_ul_height != 0): ?> height: <?php echo $cat_ul_height; ?>px; <?php endif; ?> <?php if($com_type == 'chains'): ?>margin: 5px 0 0 0; <?php endif; ?>" >
            <?php if(isset($cat_arr_main[0]['show_none_button']) && $cat_arr_main[0]['show_none_button']): ?>            
                <li class="categorylist <?php if($no_def): ?>active<?php endif; ?>" listID="none" style="height:30px; <?php if($com_type == 'chains'): ?>float: left; width: auto; <?php endif; ?>">None</li>
            <?php endif; ?>
            
            <?php foreach($arr_cats as $a => $b): ?>
                <li class="categorylist <?php if(isset($b['default']) || isset($b['def'])): $active_group = $b['group']; ?>active<?php endif; ?>" 
                	listID="<?php echo $b['group']; ?>" style="height:30px; <?php if($com_type == 'chains'): ?> float: left; width: auto;<?php endif; ?>"
                    ajd_type="<?php echo $com_type; ?>"
					val="<?php echo $b['title']; ?>"
					<?php if(isset($b['id'])): ?> ajd_id="<?php echo $b['id']; ?>" <?php endif; ?>
                    <?php if(isset($b['optid'])): ?> ajd_catid="<?php echo $b['optid']; ?>" <?php endif; ?>
                    ><?php echo $b['title']; ?></li>
            <?php endforeach; ?>
            <?php if($cat_arr_main[0]['show_all_button']): ?>   
                <li class="categorylist <?php if(!$cat_arr_main[0]['show_none_button']): ?>active<?php endif; ?>" listID="all" style="height:30px; <?php if($com_type == 'chains'): ?> float: left; width: auto;<?php endif; ?>">Show all</li>
            <?php endif; ?>
      </ul>
    </div>   
<?php endif; ?>


<div id="ajax-selector-list">
    <ul style=" <?php if($ul_width > 0): ?>width: <?php echo $ul_width; ?>px;<?php endif; ?> <?php if($ul_height > 0): ?>height: <?php echo $ul_height; ?>px;<?php endif; ?> <?php if($com_type == 'chains'): ?>margin: 5px 0 0 0; <?php endif; ?>" >
        <?php if($com_type == 'wording'): ?>
        
        	 <?php
			if($sid == '3' or $sid == '5') {
				$current_side = 'R';
			} else if($sid == '0') {
				$current_side = 'F';
			} else {
				$current_side = 'I';
			}
			?>
                      
            <?php foreach($arr_list as $a => $b): ?>
            	<?php if(($current_side == "R" && $b['max_linechars'] <= 25) || ($current_side == "F" && $b['max_linechars'] <= 22) || ($current_side == "I" && $b['max_linechars'] <= 20 && $b['total_chars'] <= 60) ): ?>
                <li class="<?php echo $com_type; ?> ">
                    <div class="selector-item <?php echo $showed; ?> " style="width: <?php if(count($arr_cats) > 1) :?>250px<?php else: ?>400px<?php endif; ?>;<?php if(isset($b['def'])) :?>border-color: #4094b4;<?php endif; ?>" title="<?php echo $b['title']; ?>" value="<?php echo $b['title']; ?>" <?php if($go_categorized) :?>cat_list="<?php echo $b['cat']; ?>"<?php endif; ?>>
                        <p class="messageDetail">
                        <?php foreach($b['line'] as $a2 => $b2): ?>
                            <?php echo $b2; ?> <br />
                        <?php endforeach; ?>
                        </p>
                    </div>
                </li>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            
            <?php foreach($arr_list as $a => $b): ?>
                <li>
                <?php 
				if($com_type == 'chains') {
					if($b['group'] === $active_group) {
						$showed = 'show';
					}else{
						$showed = 'hide';
					}
				}
				?>
                
                <div ajd_type="<?php echo $com_type; ?>" id="<?php echo $com_type.'-'.$a; ?>" class="selector-item <?php echo $showed; ?> selector-item-<?php echo $com_type; ?> " <?php if(isset($b['def'])) :?>style="border-color: #4094b4;"<?php endif; ?> title="<?php echo $b['title']; ?>" <?php if(isset($b['value'])) : ?>value="<?php echo $b['id']; ?>"<?php endif; ?> <?php if($go_categorized) :?>cat_list="<?php if($com_type == 'chains') { echo $b['group']; }else{ echo $b['cat']; } ?>"<?php endif; ?> <?php if(isset($b['pid'])) :?>pid="<?php echo $b['pid']; ?>"<?php endif; ?>  <?php if(isset($b['price'])) :?>price="<?php echo $b['price']; ?>"<?php endif; ?> <?php if(isset($b['id'])) :?>ajd_id="<?php echo $b['id']; ?>"<?php endif; ?>>
                <?php if($com_type == 'chains'): ?>
                    <div>
                    <p><b><?php echo $b['title']; ?></b></p>
                    </div>
                    <div><img src="<?php echo $site_url.'/gifts/'.$b['image']; ?>" style="width:150px; float:left; position: relative; padding-right: 3px;"><div style="padding: 4px; text-align: justify;"><?php echo $b['desc']; ?></div></div>
                    <div class="clear"></div>
                    <?php if(is_array($b['options'])): ?>
                    <div style="margin: 3px; float: right;">
                        <select class="chain_opts" style="width: 350px;">
                          <?php foreach($b['options'] as $a2 => $b2): ?>
                            <option value="<?php echo $b2['id']; ?>" <?php if($b2['def']): ?> selected="selected"<?php endif; ?>>
                                  <?php echo '+ $'.$b2['price'].' - '.$b2['title'];  ?>
                            </option>
                          <?php endforeach; ?>                 
                        </select>
                    </div>
                    <?php endif; ?>
                <?php else: ?>
                	<img  class="clip_load" data-original="<?php echo $b['image']; ?>"  src="<?php echo $b['image']; ?>">
                <?php endif; ?>
                </div>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>
<div class="clear"></div>
</div>
<div class="selector-back-btn" onclick="javascript: func_selector('close');">CONTINUE</div>