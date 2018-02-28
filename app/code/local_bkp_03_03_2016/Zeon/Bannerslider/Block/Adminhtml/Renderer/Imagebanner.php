<?php

class Zeon_Bannerslider_Block_Adminhtml_Renderer_Imagebanner
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /* Render Grid Column */
    public function render(Varien_Object $row)
    {
        $imageId  = $row->getId();
        $image     = Mage::getModel('bannerslider/banner')
            ->load($imageId)
            ->getImage();
        $imagename = Mage::helper('bannerslider')->getBannerImage($image);
        return '<img id="image_banner' . $imageId . '" src="' . $imagename .
            '" width="120px" height="50px"/>';
    }

}