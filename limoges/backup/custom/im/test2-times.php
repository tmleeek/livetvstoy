<?php

try
{
/* Set width and height in proportion of genuine PHP logo */
$width = 400;
$height = 210;

/* Create an Imagick object with transparent canvas */
$img = new Imagick();
$img->newImage($width, $height, new ImagickPixel('transparent'));

/* New ImagickDraw instance for ellipse draw */
$draw = new ImagickDraw();
/* Set purple fill color for ellipse */
$draw->setFillColor('#777bb4');
/* Set ellipse dimensions */
$draw->ellipse($width / 2, $height / 2, $width / 2, $height / 2, 0, 360);
/* Draw ellipse onto the canvas */
$img->drawImage($draw);

/* Reset fill color from purple to black for text (note: we are reusing ImagickDraw object) */
$draw->setFillColor('black');
/* Set stroke border to white color */
$draw->setStrokeColor('white');
/* Set stroke border thickness */
$draw->setStrokeWidth(2);
/* Set font kerning (negative value means that letters are closer to each other) */
$draw->setTextKerning(-8);
/* Set font and font size used in PHP logo */
$draw->setFont('times.ttf');
$draw->setFontSize(150);
/* Center text horizontally and vertically */
$draw->setGravity(Imagick::GRAVITY_CENTER);

/* Add center "php" with Y offset of -10 to canvas (inside ellipse) */
$img->annotateImage($draw, 0, -10, 0, 'php');
$img->setImageFormat('png');

/* Set appropriate header for PNG and output the image */
header('Content-Type: image/png');
echo $img;
}
catch(Exception $e)
{
        echo $e->getMessage();
}

?>