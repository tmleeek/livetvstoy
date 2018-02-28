<?php

/*
$serialized_data = serialize(array(6) {["info_buyRequest"]=>array(5) {["uenc"]=>string(56) "aHR0cDovLzEyNy4wLjAuMS90ZXN0LWNhdGVnb3J5L3Rlc3QuaHRtbA,,"["product"]=>string(1) "1"["related_product"]=>string(0) ""["options"]=>array(2) {[2]=>string(1) "2"[1]=>array(1) {[0]=>string(1) "1"}}["qty"]=>string(1) "1"}["options"]=>array(2) {[0]=>array(7) {["label"]=>string(25) "Custom Option for Product"["value"]=>string(16) "Custom Option 1 "["print_value"]=>string(16) "Custom Option 1 "["option_id"]=>string(1) "2"["option_type"]=>string(9) "drop_down"["option_value"]=>string(1) "2"["custom_view"]=>bool(false)}[1]=>array(7) {["label"]=>string(27) "Custom Option for Product 2"["value"]=>string(15) "Custom Option 3"["print_value"]=>string(15) "Custom Option 3"["option_id"]=>string(1) "1"["option_type"]=>string(8) "checkbox"["option_value"]=>string(1) "1"["custom_view"]=>bool(false)}}["giftcard_lifetime"]=>NULL["giftcard_is_redeemable"]=>int(0)["giftcard_email_template"]=>NULL["giftcard_type"]=>NULL});
echo  $serialized_data . '<br>';
$var1 = unserialize($serialized_data);
var_dump ($var1);
*/


$serialized_data='a:6:{s:15:"info_buyRequest";a:9:{s:8:"form_key";s:16:"WD69eJoRdSfoHza4";s:7:"product";s:5:"41209";s:13:"child_product";s:5:"41209";s:15:"related_product";s:0:"";s:7:"options";a:1:{i:25501;s:9:"ALEXANDRAa";}s:3:"qty";s:1:"1";s:14:"required_check";s:2:"on";s:2:"id";s:5:"41209";s:11:"wishlist_id";s:1:"0";}s:7:"options";a:1:{i:0;a:7:{s:5:"label";s:4:"Name";s:5:"value";s:9:"ALEXANDRA";s:11:"print_value";s:9:"ALEXANDRA";s:9:"option_id";s:5:"25501";s:11:"option_type";s:5:"field";s:12:"option_value";s:9:"ALEXANDRA";s:11:"custom_view";b:0;}}s:17:"giftcard_lifetime";N;s:22:"giftcard_is_redeemable";i:0;s:23:"giftcard_email_template";N;s:13:"giftcard_type";N;}';
echo  $serialized_data;
$var1 = unserialize($serialized_data);
print_r ($var1);


?>
