<?php

$image = exec("/usr/local/bin/convert test.png -font arial.ttf -pointsize 120 -draw \"gravity center fill red text 0,0 'Arial' \" output.png");

/* Output the image with headers */
//header('Content-type: image/jpeg');
echo "<img src='http://www.limogesjewelry.com/custom/im/output.png'/>";
?>