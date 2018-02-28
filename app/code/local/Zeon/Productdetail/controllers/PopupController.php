<?php
class Zeon_Productdetail_PopupController
    extends Mage_Core_Controller_Front_Action
{
    public function getpageAction()
    {
        $pageId  = $this->getRequest()->getParam('id');
        $identifier = Mage::helper('zeon_productdetail')
            ->getConfigDetails('productdetails/popup_identifier');
        $this->loadLayout();
        $cmsBlock = $this->getLayout()->createBlock('cms/block')
            ->setBlockId($identifier.$pageId)->toHtml();
        echo $cmsBlock;
       //http://127.0.0.1/projects/cps_dev/productdetail/popup/getpage/id/size/
    }
}