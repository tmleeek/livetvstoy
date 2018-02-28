<?php
class Kwanso_CategoryCsv_Model_Observer
{

public function processImages( $configArray , $group , $field , $IPath , $format )
{
  $_Images = $_FILES['groups'];
   $path = Mage::getBaseDir('media').DS.'BraceletandCharmImages'.DS.$IPath.DS ;
   $imagesName = '';
  foreach ($configArray as $key => $value) {
    $imgA = $_Images['type'][$group]['fields'][$field]['value'][$key][$field];
   $imgCount = count($imgA);
   while( $imgCount >= 0) {
   $imgCount--;
   $imgName = $_Images['name'][$group]['fields'][$field]['value'][$key][$field][$imgCount];
   if(!empty($imgName) && !is_null($imgName)) {
    try {
     $uploader = new Varien_File_Uploader(
      array(
       'name' => $imgName,
       'type' => $_Images['type'][$group]['fields'][$field]['value'][$key][$field][$imgCount],
       'tmp_name' => $_Images['tmp_name'][$group]['fields'][$field]['value'][$key][$field][$imgCount],
       'error' => $_Images['error'][$group]['fields'][$field]['value'][$key][$field][$imgCount],
       'size' => $_Images['size'][$group]['fields'][$field]['value'][$key][$field][$imgCount]
       )
      );
            $uploader->setAllowedExtensions(array($format)); // jpg png or pdf or anything
            $uploader->setAllowRenameFiles(false);
            $uploader->setFilesDispersion(false);
            echo $destFile = $path.$imgName;
            if (file_exists($destFile)) {
             unlink($destFile);
            }
            $filename = $uploader->getNewFileName($destFile);
            move_uploaded_file($filename, $path);
            $uploader->save($path, $filename);
            $data[main_images] = 'ram_images'. $filename;
            $imagesName .= $imgName." , ";

           }catch(Exception $e) {
            //Mage::getSingleton('core/session')->addError("$e");
            Mage::getSingleton('core/session')->addError("Uploading failed! Please check format before uploading images");
           }
          }
         }
         }
         if ($imagesName) {
          Mage::getSingleton('core/session')->addSuccess("Image $imagesName added to path $path");
         }
}

 public function adminSystemConfigChangedSection()
  {
    $group = 'bracelet_img_grp';
    $field = 'campains444';
    $ConfigArr = unserialize(Mage::getStoreConfig('cat_csv_sec/'.$group.'/'.$field, Mage::app()->getStore()));
    if (count($ConfigArr)) {
     $IPath = 'bracelets';
     $this->processImages($ConfigArr , $group , $field , $IPath , 'png' );
    }
    $group = 'charm_grp';
    $field = 'campains445';
    $ConfigArr = unserialize(Mage::getStoreConfig('cat_csv_sec/'.$group.'/'.$field, Mage::app()->getStore()));
    if (count($ConfigArr)) {
     $IPath = 'charms';
     $this->processImages($ConfigArr , $group , $field , $IPath , 'png'  );
    }
    $group = 'flyout_grp';
    $field = 'campains446';
    $ConfigArr = unserialize(Mage::getStoreConfig('cat_csv_sec/'.$group.'/'.$field, Mage::app()->getStore()));
    if (count($ConfigArr)) {
     $IPath = 'charms' . DS . 'flyout-icons';
     $this->processImages($ConfigArr , $group , $field , $IPath , 'svg' );
    }
  }
 }

  ?>