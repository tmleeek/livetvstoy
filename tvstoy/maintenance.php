<?php

header('HTTP/1.1 503 Service IS Temporarily Unavailable',true,503);

header('Status: 503 Service Temporarily Unavailable');

header('Retry-After: 172800');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html>

<head>

<title> CPS | Undermaintenance</title>

</head>

<style>

 body {

 background: #a9b6bf;

 margin: 0;

 padding: 0;

 }

 .main_image {

 margin-top:230px;

 margin-left:125px;

 }

</style>

<body>

 <div class="main_image">

 <img src="http://sites.zeontest.com/ttb.jpg" />

 </div>

</body>

</html>
