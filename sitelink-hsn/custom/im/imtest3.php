<?php

$image = exec("/usr/local/bin/convert test.png -font ../configurator/components/fonts/serifa.ttf -pointsize 150 -draw \"gravity center fill blue text 0,0 'Serifa BT' \" serifa.png");

/* Output the image with headers */
//header('Content-type: image/jpeg');
echo "<img src='http://www.limogesjewelry.com/custom/im/serifa.png'/>";
?>