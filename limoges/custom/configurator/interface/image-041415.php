<?php
/*
	Image HTML
*/
?>

<div id="images_holder">
<div id="loading_holder"><div style="margin-top: 145px; margin-bottom: 110px;"><img src="<?php echo $site_url; ?>custom/configurator/interface/assets/images/pre-loader.gif"/></div><div style="color: #555">Please wait... loading image</div></div>
<div id="covers_holder" style="position: absolute;"></div>
<div id="designer_display"></div>
<div class="clear" style="padding-top: 15px;"></div>
<?php if(count($product_layers['step']) > 1) : ?>
<div id="designer_thumbs_holder"><div id="designer_thumbs"></div></div>
<?php endif; ?>
</div>