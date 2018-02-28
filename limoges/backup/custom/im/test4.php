<?php

try
{
/* Read the image */
$im = new Imagick($_SERVER["DOCUMENT_ROOT"]."/im/test.png");

$mask = new Imagick($_SERVER["DOCUMENT_ROOT"]."/im/mask.png");
/*
if (!$im->getImageAlphaChannel()) {
    $im->setImageAlphaChannel(Imagick::ALPHACHANNEL_SET);
}
*/

/* Thumbnail the image */
$im->thumbnailImage(200, null);

$mask->thumbnailImage(200, null);


/* Create an empty canvas */
$canvas = new Imagick();

/* Canvas needs to be large enough to hold the both images */
$width = $im->getImageWidth() + 40;
$height = $im->getImageHeight() + 40;
$canvas->newImage($width, $height, new ImagickPixel("black"));
$canvas->setImageFormat("png");

/* Composite the original image and the reflection on the canvas */
$im->compositeImage($mask, imagick::COMPOSITE_DSTIN, 0, 0, Imagick::CHANNEL_ALPHA);

$canvas->compositeImage($im, imagick::COMPOSITE_DEFAULT, 0, 0);

/* Output the image*/
header("Content-Type: image/png");
echo $canvas;
}
catch(Exception $e)
{
        echo $e->getMessage();
}

?>