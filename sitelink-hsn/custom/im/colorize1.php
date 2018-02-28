<?php
//header("Content-type: image/jpeg");
$image = new Imagick($_SERVER["DOCUMENT_ROOT"]."/im/images/opossum.jpg"); 
# using color object
$color = new ImagickPixel();
$color->setcolor("#0000ff");
$image->colorizeImage($color, 1.5);
header('Content-type: image/jpeg');
echo $image;
?> 