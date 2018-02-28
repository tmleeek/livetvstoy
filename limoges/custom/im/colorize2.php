<?php
$im = new imagick($_SERVER["DOCUMENT_ROOT"]."/im/images/opossum.jpg");
// resize by 200 width and keep the ratio
$im->thumbnailImage( 200, 0);
// write to disk
$im->writeImage( $_SERVER["DOCUMENT_ROOT"].'/im/images/a_thumbnail.jpg' );
?>