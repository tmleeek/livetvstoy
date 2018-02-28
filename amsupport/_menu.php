<?php
    require_once('_check_ip.php');
    require_once('_check_auth.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    </head>
    <body>
        <a href="index.php?logout" target="_parent">[logout]</a>
        <ul>
            <li><a href="amasty.php" target="tool_frame">Amasty script</a></li>
            <li><a href="amlogin.php" target="tool_frame">Create magento admin</a></li>
            <li><a href="amlogin.php?delete" target="tool_frame">Delete magento admin</a></li>
            <li><a href="flush-magento-cache.php" target="tool_frame">Flush magento cache</a></li>
            <li><a href="adminer.php" target="_blank">Adminer</a></li>
            <li><a href="phpminiadmin.php" target="_blank">phpMiniAdmin</a></li>
            <li><a href="cmd.php" target="tool_frame">PHPShell</a></li>
            <li><a href="eatmem.php" target="tool_frame">EatMem</a></li>
            <li><a href="phpinfo.php" target="tool_frame">PHP Info</a></li>
            <li><a href="apc.php" target="tool_frame">APC</a></li>
            <li><a href="opcache.php" target="tool_frame">Zend Opcache</a></li>
            <li><a href="pwhash.php" target="tool_frame">Create password</a></li>
        </ul>
    </body>
</html>
