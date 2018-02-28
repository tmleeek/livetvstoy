<?php
header("Content-type: image/jpeg");
$image = new Imagick($_SERVER["DOCUMENT_ROOT"]."/im/images/opossum.jpg"); 
$image->chopImage( 20,20, 88,62 );
echo $image;
?> 