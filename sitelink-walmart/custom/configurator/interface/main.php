<?php
/*
	051414
	Main Designer Interface
*/

?>

<?php    

global $product_layers, $product_fields;


// Get XML values and put to array
// Fonts
FUNC_get_XML('font');
// Cliparts
FUNC_get_XML('cliparts');
// Component Categories
FUNC_get_XML('component_categories');

?>

<form id="AJD_form" class="AJD_form" action="#" enctype="multipart/form-data" method="POST">
<!-- Product Name -->
<div>
<div id="main-product-name"><?php echo $_helper->productAttribute($_product, $_product->getName(), 'name')."\n"; ?></div>
<div class="main-product-sku">SKU: <?php echo $pid;  ?></div>
</div>
<!-- Product Image and Thumbnails -->
<div id="main-image-holder2"><?php //include 'image.php'; ?></div>
<!-- Form and Produst Fields -->
<div id="interface-holder2"><?php //include 'interface.php'; ?></div>
</form>
<!-- Photo Uploader -->
<div id="selector-mover"></div>
<div id="bottom-button-holder"><div id="total-price-bottom"></div><div id="keynote-button">Item Keynotes</div></div>
<div id="main-image-bg"></div>