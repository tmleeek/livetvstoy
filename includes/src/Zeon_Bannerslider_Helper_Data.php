<?php
class Zeon_Bannerslider_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function deleteImageFile($image)
    {
        if (!$image) {
            return;
        }
        $name = $this->reImageName($image);
        $bannerImagePath = Mage::getBaseDir('media') . DS . 'bannerslider' .
            DS . $name;

        if (!file_exists($bannerImagePath)) {
            return;
        }

        try {
            unlink($bannerImagePath);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public static function uploadBannerImage()
    {
        $bannerImagePath = Mage::getBaseDir('media') . DS . 'bannerslider';
        $image = "";
        $fileName = '';
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
            try {
                /* Starting upload */
                $uploader = new Varien_File_Uploader('image');

                // Any extention would work
                $uploader->setAllowedExtensions(
                    array('jpg', 'jpeg', 'gif', 'png')
                );
                $uploader->setAllowRenameFiles(false);

                $uploader->setFilesDispersion(true);

                $charArray = array(' ', '(', ')');
                $fileName = rand().'-'.str_replace(
                    $charArray, '-', $_FILES['image']['name']
                );

                $uploader->save($bannerImagePath, $fileName);
            } catch (Exception $e) {

            }
            $image = $fileName;
        }
        return $image;
    }

    public function reImageName($imageName)
    {
        $subname = substr($imageName, 0, 2);
        $array = array();
        $subDir1 = substr($subname, 0, 1);
        $subDir2 = substr($subname, 1, 1);
        $array[0] = $subDir1;
        $array[1] = $subDir2;
        $name = $array[0] . '/' . $array[1] . '/' . $imageName;

        return strtolower($name);
    }

    public function getBannerImage($image)
    {
        $name = $this->reImageName($image);
        return Mage::getBaseUrl('media') . 'bannerslider' . '/' . $name;
    }

    /**
     * Method used to get the config setting of the passed field.
     *
     * @param String $configName
     *
     * @return Array
     */
    public function getConfigDetails($configName)
    {
        return Mage::getStoreConfig('bannerslider/' . $configName);
    }

}