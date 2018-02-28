<?php

class Magalter_Crossup_Adminhtml_Magaltercrossupadmin_RulesController extends Mage_Adminhtml_Controller_Action {

    public function indexAction() {

        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('magalter_crossup/adminhtml_rules', 'magalter_crossup_block'));
        $this->renderLayout();
    }

    public function newAction() {

        $this->_forward('edit');
    }

    public function editAction() {
 
        $upsell = $this->_initUpsell();        
        if($upsell->getId()) {
            $upsell->getConditions()->setJsFormObject('rule_conditions_fieldset');
            $upsell->getActions()->setJsFormObject('rule_actions_fieldset');
        }
        
        Mage::register('magalter_crossup_registry', $upsell, true);

        $this->loadLayout();              
        $this->renderLayout();
    }

    public function saveAction() {

        $req = $this->getRequest();
         
        try {
            $data = $req->getPost();
            /* Convert dates to MYSQL fromat */
            $this->_prepareForSave($data);
            $this->_convertDateParts($data);
            
            $data['conditions'] = $data['rule']['conditions'];
            $data['actions'] = $data['rule']['actions'];

            $model = Mage::getModel('magalter_crossup/crossup');
            if ($id = $req->getParam('id')) {
                $model->load($id);
            } 
          
            $model->addData($data)->save();  
            $model->loadPost($data);
            $this->_handleMedia($model);
            $model->save();
            $model->getResource()->reindexAll($model);
   
            Mage::getSingleton('adminhtml/session')->addSuccess('Upsell rule has been successfully saved');
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage())->setElementData($data);
            return $this->_redirect('*/*/edit', array('id' => $req->getParam('id')));
        }
      
        if($req->getParam('back') == 'edit') {            
            return $this->_redirect('*/*/edit', array('id' => $model->getId(),'tab' => $this->getRequest()->getParam('tab')));
        }

        $this->_redirect('*/*');
    }
    
    protected function _helper()
    {
        return Mage::helper('magalter_crossup');
    }
    
     protected function _handleMedia($flag)
    {
        $files = new Varien_Object($_FILES);
        
        $mediaParams = $this->getRequest()->getParam('media');        
        /* delete media files */
        if(isset($mediaParams['delete']) && $mediaParams['delete'] == 1) {
            $flag->setMedia(null);     
            $media = $this->_helper()->getUploadDir("{$flag->getId()}"); 
            return $this->_helper()->removeRecursive($media);
        }
        
        if ((!$file = $files->getData('media')) ||
                !$files->getData('media/name')
                || $files->getData('media/error')) { 
            
            $flag->setMedia(null);
            if($flag->getOrigData('media')) {
                $flag->setMedia($flag->getOrigData('media'));
            }           
           return;           
        }
       
        $uploader = new Varien_File_Uploader($file);
        $destination = $this->_helper()->getUploadDir($flag->getId());
        $data = $uploader->save($destination);
        /*
        if (is_array($data) && isset($data['path']) && isset($data['file'])) {
            $img = $data['path'] . DS . $data['file'];
        }
    
        if (file_exists($img)) {          
            $resize = $this->_helper()->isResize();
            $w = $this->_helper()->getThumbnailWidth();
            $width = (!$w || !$resize) ? null : $w;          
            $h = $this->_helper()->getThumbnailHeight();
            $height = (!$h || !$resize) ? null : $h;
           
            if (!$width && !$height) {
                if (!copy($img, $data['path'] . DS . "thumbnail_{$data['file']}")) {                   
                    throw new Exception("Failed to make tumbnail for {$img}");           
                }               
            }            
            else {               
                $imgProcessor = new Varien_Image($img);
                $imgProcessor->quality(100);
                $imgProcessor->keepAspectRatio(false);
                $imgProcessor->keepTransparency(true);
                $imgProcessor->keepFrame(false);
                $imgProcessor->resize($width, $height);
                $imgProcessor->save($data['path'], "thumbnail_{$data['file']}");         
            }         
        }*/

        $flag->setMedia(isset($data['file']) ? $data['file'] : null);
        
        return this;
    }

    /* Product grid action */

    public function gridAction() {
        
        Mage::app()->getRequest()->setParam('internal_product', 
                Mage::app()->getRequest()->getPost('internal_product')
        );
        
        $this->loadLayout();
        
        $this->getResponse()->setBody($this->getLayout()->createBlock('magalter_crossup/adminhtml_rules_products_grid')->toHtml());
    }
    
    public function crossupGridAction() { 
         
        Mage::app()->getRequest()->setParam('internal_product', 
                Mage::app()->getRequest()->getPost('internal_product')
        );
        
        $this->loadLayout();
         
        $this->getResponse()->setBody($this->getLayout()->createBlock('magalter_crossup/adminhtml_rules_products_upsellgrid')->toHtml());
    }

    public function ordersGridAction() {

        $this->getResponse()->setBody($this->getLayout()->createBlock('magalter_crossup/adminhtml_crossup_orders_grid')->toHtml());
    }
   
    public function exportCsvAction() {
        $fileName = 'crossup.csv';
        $content  = $this->getLayout()->createBlock('magalter_crossup/adminhtml_crossup_grid')->getCsvFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function exportXmlAction() {
        $fileName   = 'crossup.xml';
        $content    = $this->getLayout()->createBlock('magalter_crossup/adminhtml_crossup_grid')->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    protected function _initUpsell() {

        $id = (int) $this->getRequest()->getParam('id');
        
        $upsell = Mage::getModel('magalter_crossup/crossup')->load($id);
        
        $upsell->getConditions()->setJsFormObject('rule_conditions_fieldset');
        $upsell->getActions()->setJsFormObject('rule_actions_fieldset');

        return Mage::getModel('magalter_crossup/crossup')->load($id);
 
    }
  
    protected function _convertDateParts(&$data) {

        $locale = Mage::app()->getLocale();
        $format = Mage::app()->getLocale()->getTranslation(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM, 'datetime');

        if (!empty($data['available_to'])) {
            $dateTo = $locale->date($data['available_to'], $format);
            $data['available_to'] = gmdate(Magalter_Crossup_Helper_Data::MYSQL_DATE_TIME_FROMAT, $dateTo->get(Zend_Date::TIMESTAMP) - $dateTo->get(Zend_Date::TIMEZONE_SECS));
        } else {
            $data['available_to'] = null;
        }
        if (!empty($data['available_from'])) {
            $dateFrom = $locale->date($data['available_from'], $format);
            $data['available_from'] = gmdate(Magalter_Crossup_Helper_Data::MYSQL_DATE_TIME_FROMAT, $dateFrom->get(Zend_Date::TIMESTAMP) - $dateFrom->get(Zend_Date::TIMEZONE_SECS));
        } else {
            $data['available_from'] = null;
        }
        return $data;
    }

    protected function _prepareForSave(&$data) {

        foreach ($data as $key => $info) {

            if (preg_match('#^magalter_(.+)$#is', $key, $match)) {

                $data[$match[1]] = $info;
            }   
        }
       
        if(!isset($data['store_ids']) || !is_array($data['store_ids'])) {
            $data['stores'] = 0;
        }
        else {
            $data['stores'] = implode(',', $data['store_ids']);
        }
         
        if(!isset($data['customer_group_ids'])) {
            $data['groups'] = false;
        }
        else {
            $data['groups'] = implode(',', $data['customer_group_ids']); 
        }
       
        return $data;
    }

    public function deleteAction() {

        if ($id = $this->getRequest()->getParam('id')) {
            try {
                $model = Mage::getModel('magalter_crossup/crossup');
                $model->setId($id);
                $model->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Upsell rule was successfully deleted'));
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Unable to find upsell to delete.'));
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {

        $crossup = $this->getRequest()->getParam('magalter_crossup');
        if (!is_array($crossup)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select any elements'));
        } else {
            if (!empty($crossup)) {
                try {
                    foreach ($crossup as $upsell) {
                        Mage::getSingleton('magalter_crossup/crossup')->setId($upsell)->delete();
                    }
                    $this->_getSession()->addSuccess(
                            $this->__('Total of %d record(s) have been deleted.', count($crossup))
                    );
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                }
            }
        }
        $this->_redirect('*/*/index');
    } 
    
    public function conditionsAction($prefix = 'conditions')
    {
        $id = $this->getRequest()->getParam('id');
        $typeArr = explode('|', str_replace('-', '/', $this->getRequest()->getParam('type')));
        $type = $typeArr[0];

        $model = Mage::getModel($type)
                ->setId($id)
                ->setType($type)
                ->setRule(Mage::getModel('magalter_crossup/crossup'))
                ->setPrefix($prefix);
        
        if (!empty($typeArr[1])) {
            $model->setAttribute($typeArr[1]);
        }

        if ($model instanceof Mage_Rule_Model_Condition_Abstract) {
            $model->setJsFormObject($this->getRequest()->getParam('form'));
            $html = $model->asHtmlRecursive();
        } else {
            $html = '';
        }
        $this->getResponse()->setBody($html);
    }
    
    public function actionsAction()
    {
        return $this->conditionsAction('actions');
    }
    
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('promo/magalter_crossup/magalter_crossup');
    }

}