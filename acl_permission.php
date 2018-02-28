<?php
/**
 * SupportDesk_FixAcl.php v1.1
 * SupportDesk (www.supportdesk.nu)
 * 10/7/2015
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * It is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @copyright  SupportDesk BV (http://www.supportdesk.nu)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
function getRecursiveList($data,&$list,$parent = '')
{
    foreach($data as $node)
    {
        if($data->getName() == 'children')
        {
            $list[] = $parent . $node->getName();
            getRecursiveList($node,$list,$parent . $node->getName() . '/');
        }else{
            getRecursiveList($node,$list,$parent);
        }
    }
}
function getAclList($adminHtml)
{
    $data = file_get_contents($adminHtml);
    if($data === false)
    {
        echo 'Adminhtml unreadable!' . PHP_EOL;
        return false;
    }
    $data = simplexml_load_string($data);
    if($data === false)
    {
        echo 'Could not parse ' . $adminHtml . PHP_EOL;
        return false;
    }
    $list = array();
    if(!isset($data->acl))
    {
        return $list;
    }
    getRecursiveList($data,$list);
    return $list;
}
function lensort($a,$b)
{
    return strlen($b) - strlen($a);
}
function getAclString($adminHtml,$file)
{
    $controllerName = getControllerName($file);
    $vendorName = getVendorName($file);
    $moduleName = getModuleName($file);
    $list = getAclList($adminHtml);
    
    if(sizeof($list) == 0)
    {
        echo '    No ACL config found!' . PHP_EOL;
        return false;
    }
    
    $result = array();
    $backup = array();
    foreach($list as $item)
    {
        if(strpos($item,$controllerName) !== false)
        {
            $result[] = $item;
        }
    }
    if(sizeof($result) == 1)
    {
        return $result[0];
    }else if(sizeof($result) > 1)
    {
        $list = $result;
        $backup = $result;
        $result = array();
    }
    foreach($list as $item)
    {
        if(strpos($item,$moduleName) !== false)
        {
            $result[] = $item;
        }
    } 
    if(sizeof($result) == 0)
    {
        $list = $backup;
    }else if(sizeof($result) == 1)
    {
        return $result[0];
    }else
    {
        $list = $result;
        $backup = $result;
        $result = array();
    }
    foreach($list as $item)
    {    
        if(strpos($item,$vendorName) !== false)
        {
            $result[] = $item;
        }
    } 
    
    if(sizeof($result) == 0){
        $result = $backup;
        foreach($result as $item)
        {
            if($item == $controllerName)
            {
                return $item;
            }
        }
    }
    if(sizeof($result) == 1){
        return $result[0];
    }else if(sizeof($result) > 1)
    {
        usort($result,'lensort');
        return $result[0];
    }
    return false;
}
function getControllerName($file)
{
    $needle = 'controllers/Adminhtml';
    $marker = strpos($file,$needle);
    $name = substr($file,$marker + strlen($needle) + 1);
    $needle = 'Controller.php';
    $marker = strpos($name,$needle);
    $name = substr($name,0,$marker);
    return strtolower($name);
}
function getModuleName($file)
{
    return strtolower(preg_replace('/app\/code\/.*?\/.*?\/(.*?)\/.*/','$1',$file));
}
function getVendorName($file)
{
    return strtolower(preg_replace('/app\/code\/.*?\/(.*?)\/.*/','$1',$file));
}
function getPatchString($file,$adminHtml)
{
    $patchString = false;
    if($adminHtml !== null)
    {
        $patchString = getAclString($adminHtml,$file);
    }else
    {
         echo '    adminhtml.xml missing' . PHP_EOL;
    }
    if($patchString === false)
    {
        echo '    ' . 'could not determine ACL, using default (aka, allow everyone)' . PHP_EOL;
        $patchString = 'return true;';
    }else
    {
        $patchString = "return Mage::getSingleton('admin/session')->isAllowed('".$patchString."');";
    }
    echo '  Patching with: ' . $patchString . PHP_EOL;
    $patchString = PHP_EOL .
        '    //Added by quickfix script. Take note when upgrading this module! Powered by SupportDesk (www.supportdesk.nu)' . PHP_EOL .
        '    function _isAllowed()' . PHP_EOL .
        '    {' . PHP_EOL .
        '        ' . $patchString . PHP_EOL .
        '    }';
        
    return $patchString;
}
function patch($file,$adminHtml)
{
    echo ' ' . $file . '...' . PHP_EOL;
    /*$patchString = getPatchString($file,$adminHtml);
    $data = file_get_contents($file);
    
    if($data === false)
    {
        echo '    Could not open file for writing!' . PHP_EOL;
        return;
    }
    if(file_put_contents($file . '.orig',$data) === false)
    {
        echo '    Could not create backup file!' . PHP_EOL;
        return;
    }
 
    $marker = strrpos($data,'}');
    $result = substr($data,0,$marker-1) . $patchString . substr($data,$marker-1);
    
    if(file_put_contents($file,$result) === false)
    {
        echo '    Could not write to file!' . PHP_EOL;
    }*/
    echo '   ' . PHP_EOL;
    }
function hasAllowed($file)
{
    if(substr($file,-1) == '~'){return true;}
    if(substr($file,-5) == '.orig'){return true;}
	
    $content = file_get_contents($file);
 
    if($content === false)
    {
        echo $file . ' is unreadable!';
        return true;
    }
    if(strpos($content,'extension_loaded(\'ionCube Loader') !== false){
        echo $file . ' has been encoded by ionCube, skipping..' . PHP_EOL;    
	return true;
    }
    if(strpos($content,'function _isAllowed(') === false)
    {
        return false;
    }
    return true;
}
function patchController($file,$adminHtml)
{
    if(!hasAllowed($file))
    {
        patch($file,$adminHtml);
    }
}
function traverseController($dir,$adminHtml)
{
    $folder = opendir($dir);
    while($new = readdir($folder))
    {
        if($new=='.' || $new=='..') {continue;}
        $file = $dir . '/' . $new;
        if(is_dir($file))
        {
            traverseController($file,$adminHtml);
        }else if(is_file($file)){
            patchController($file,$adminHtml);
        }
    }
}
function scanModule($file)
{
    $dir = $file . '/controllers/Adminhtml';
    if(!is_dir($dir)){return;}
 
    $adminHtml = $file . '/etc/adminhtml.xml';
    if(!is_file($adminHtml))
    {
        $adminHtml = null;
    }
    
    traverseController($dir,$adminHtml);
}
function traverseModule($dir)
{

    $module = opendir($dir);
    $new = '';
    while($new = readdir($module))
    {
        if($new=='.' || $new=='..') {continue;}
        $file = $dir . '/' . $new;
        if(is_dir($file))
        {
            scanModule($file);
        }
    }
    closedir($module);
}
 
function traverseVendor($dir)
{
    $vendor = opendir($dir);
    $new = '';
    while($new = readdir($vendor))
    {
        if($new=='.' || $new=='..') {continue;}
        $file = $dir . '/' . $new;
        if(is_dir($file))
        {
            traverseModule($file);
        }
    }
    closedir($vendor);
}
echo 'This script has been developed by SupportDesk (www.supportdesk.nu)' . PHP_EOL .
    '  Always make certain this is tested on a development environment first, preferably with GIT available' . PHP_EOL .
    '  And save the output from this script to a file, for your own administration' . PHP_EOL .
    '  This script is provided as is, and under no circumstances is there any warranty' . PHP_EOL .
    '  Usage at own discretion and risk' . PHP_EOL . PHP_EOL;
traverseVendor('app/code/community');
traverseVendor('app/code/local');
