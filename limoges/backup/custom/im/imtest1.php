<?php

$image = exec("/usr/local/bin/convert test.png -sepia-tone 50% final_image.jpg");

/* Output the image with headers */
//header('Content-type: image/jpeg');
echo "<img src='http://www.limogesjewelry.com/custom/im/final_image.jpg'/>";
?>