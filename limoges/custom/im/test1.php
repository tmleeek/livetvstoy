<?php
/* Create some objects */
$image = new Imagick();
$pixel = new ImagickPixel( 'gray' );

/* New image */
$image->newImage(400, 200, $pixel);

/* Chop image */
//$image->chopImage(200, 200, 0, 0);

/* Give image a format */
$image->setImageFormat('jpeg');

/* Output the image with headers */
header('Content-type: image/jpeg');
echo $image;
?>