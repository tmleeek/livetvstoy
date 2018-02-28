<?php
class Zeon_Attributemapping_Adminhtml_IndexController
    extends Mage_Adminhtml_Controller_Action
{
    protected $_storeId;

    /**
     * Method used to display the list of all products to select featured items.
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('zextension/attributemapping');
        $this->renderLayout();
    }

    /**
     * Method used to display the list of all products to select featured items.
     */
    public function listAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('zextension/attributemapping');
        $this->renderLayout();
    }

    /**
     *
     * add/edit/delete attribute option
     */
    public function addoptionsAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     *
     * edit attribute mapping
     */
    public function editAction()
    {
        $attributeId     = $this->getRequest()->getParam('attribute_id');
        $optionId     = $this->getRequest()->getParam('option_id');
        $storeId     = $this->getRequest()->getParam('store', 0);

        $model = Mage::getModel('zeon_attributemapping/attributemapping')
            ->getCollection()
            ->addFilter('attribute_id', $attributeId)
            ->addFilter('option_id', $optionId)
            ->getFirstItem();

        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $model->addData($data);
        }

        Mage::register('zeon_attributemapping', $model);

        $this->loadLayout();
        $this->_setActiveMenu('zextension/attributemapping');

        $this->renderLayout();
    }

    /**
     * Save action option url and menu data
     */
    public function saveAction()
    {
        $redirectBack = $this->getRequest()->getParam('back', false);
        //if data present
        if ($data = $this->getRequest()->getPost()) {

            $attributeId     = $this->getRequest()->getParam('attribute_id');
            $optionId     = $this->getRequest()->getParam('option_id');
            $storeId     = $this->getRequest()->getParam('store', 0);

            $model = Mage::getModel('zeon_attributemapping/attributemapping');
            $modelAdmin = Mage::getModel(
                'zeon_attributemapping/attributemapping'
            )->getCollection()
            ->addFilter('attribute_id', $attributeId)
            ->addFilter('option_id', $optionId)
            ->getFirstItem();
            if ($id = $modelAdmin->getMappingId()) {
                //the parameter name may be different
                $model->load($id);
            }

            if (isset($data['url_key'])) {
                $data['url_key'] = $this->formatUrlKey($data['url_key']);
            }

            if (isset($_FILES['logo_image']['name'])
                || isset($data['logo_image']['delete'])) {
                if (!empty($_FILES['logo_image']['name'])
                    || @$data['logo_image']['delete']) {
                    $data['logo_image'] = $this->saveFileData(
                        'logo_image',
                        $data
                    );
                } else {
                    unset($data['logo_image']);
                }
            }

            if (isset($_FILES['slider_image']['name'])
                || isset($data['slider_image']['delete'])) {
                if (!empty($_FILES['slider_image']['name'])
                    || @$data['slider_image']['delete']) {
                    $data['slider_image'] = $this->saveFileData(
                        'slider_image',
                        $data
                    );
                } else {
                    unset($data['slider_image']);
                }
            }

            if (isset($_FILES['page_background_image']['name'])
                || isset($data['page_background_image']['delete'])) {
                if (!empty($_FILES['page_background_image']['name'])
                    || @$data['page_background_image']['delete']) {
                    $data['page_background_image'] = $this->saveFileData(
                        'page_background_image',
                        $data
                    );
                } else {
                    unset($data['page_background_image']);
                }
            }
            
            if (isset($_FILES['page_banner_left_image']['name'])
                || isset($data['page_banner_left_image']['delete'])) {
                if (!empty($_FILES['page_banner_left_image']['name'])
                    || @$data['page_banner_left_image']['delete']) {
                    $data['page_banner_left_image'] = $this->saveFileData(
                        'page_banner_left_image',
                        $data
                    );
                } else {
                    unset($data['page_banner_left_image']);
                }
            }

            $model->addData($data);
            try{
                //try to save it
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('zeon_attributemapping')->__(
                        'Attribute option data is saved.'
                    )
                );
                //redirect to grid.
                $this->_redirect('*/list/');
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
                $redirectBack = true;
            } catch (Exception $e) {
                $this->_getSession()->addError(
                    Mage::helper('zeon_attributemapping')->__(
                        'Unable to save attribute option data. '.
                        $e->getMessage()
                    )
                );
                $redirectBack = true;
                Mage::logException($e);
            }
            if ($redirectBack) {
                $this->_redirect(
                    '*/*/edit',
                    array(
                        'attribute_id' => $model->getAttributeId(),
                        'option_id'    => $model->getOptionId(),
                        'store'        => $data['store']
                )
                );
                return;
            } else {
                $this->_redirect(
                    '*/*/list',
                    array(
                        'id'           => $model->getAttributeId(),
                        'store'        => $data['store']
                )
                );
                return;
            }
        }
    }

    /**
     * function to add/edit/delete image in folder
     */
    public function saveFileData($uploadedFile, $data)
    {
        $imageUploded = '';
        $charPath = Zeon_Attributemapping_Model_Attributemapping::FILE_PATH;
        $path = Mage::getBaseDir('media').DS.$charPath;
        $path = $path.$uploadedFile.DS;
        try {
            if (!empty($_FILES[$uploadedFile]['name'])) {

                $uploader = new Varien_File_Uploader($_FILES[$uploadedFile]);
                $uploader->setAllowedExtensions(
                    array("jpg", "jpeg", "gif", "png")
                );
                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);
                $ext = $uploader->getFileExtension(
                    $_FILES[$uploadedFile]['name']
                );
                $fname = $uploadedFile.'_'.$data['option_id']
                    .'_'.$data['store'].'.'. $ext;
                $uploader->save($path, $fname);
                $imageUploded = $charPath.$uploadedFile.'/'.$fname;
            }

            if (isset($data[$uploadedFile]['delete'])
                && $data[$uploadedFile]['delete'] == 1) {
                $path = Mage::getBaseDir('media').'/'
                    .$data[$uploadedFile]['value'];
                @unlink($path);
                $imageUploded = '';
            }
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')
                ->addError($e->getMessage());
            $this->_redirect(
                '*/*/edit',
                array(
                    'id' => $this->getRequest()->getParam('id')
                )
            );
            return;
        }

        return $imageUploded;
    }

    /**
     *
     * add/edit/delete attribute option from table
     */
    public function saveoptionAction()
    {
        $post = $this->getRequest()->getParams();
        $optionId = $this->getRequest()->getParam('optionid', 0);
        if (empty($post)) {
            $this->getResponse()
                ->setBody(Mage::helper('core')->jsonEncode(false));
        }
        try {
            $attributeId = $post['id'];
            $attributeOption = $post['option'];
            $attribute = Mage::getModel('eav/entity_attribute')
                ->load($attributeId);
            foreach ($attributeOption['value'] as $optionValues) {
                $attributeOp =
                    Mage::getModel('zeon_attributemapping/attributemapping');
                if ($this->getRequest()->getParam('delete', 0)) {
                    $options['delete'][$optionId] = true;
                    $options['value'][$optionId] = true;
                    $attributeOp->deleteAttrData($attributeId, $optionId);
                    $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
                    $setup->addAttributeOption($options);
                    Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('core')->__(
                            'Options Of '.$attribute->getData('frontend_label')
                            .' Deleted Successfully'
                        )
                    );
                    return;
                }
                if (!$attributeOp->attributeValueExists(
                    $attribute->getAttributeCode(),
                    $optionValues,
                    $optionId
                )
                ) {
                    $attributeOption['option'][] =
                        array_map('trim', $optionValues);
                    $result = array('order'=>$attributeOption['order']);
                    $attribute->setData('option', $attributeOption);
                    $attribute->save();
                    if (isset($post['default']) && $post['default']) {
                        $attributeOp->setOptionasDefault(
                            $optionValues[0],
                            $attributeId
                        );
                    }
                    Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('core')->__(
                            'Option Successfully Get saved for '.
                            $attribute->getData('frontend_label')
                        )
                    );
                } else {
                    $this->getResponse()->setBody(
                        Mage::helper('core')->jsonEncode(
                            array('error'=>true)
                        )
                    );
                    return;
                }
            }

        } catch (Exception $e) {
            Mage::logException($e);
        }

        $this->getResponse()->setBody(
            Mage::helper('core')->jsonEncode(
                array('success'=>true)
            )
        );
        return;
    }

/**
     *
     * get formatted string for url
     * @param string $str
     * @return string
     */
    public function formatUrlKey($str)
    {
        $urlKey = preg_replace(
            '#[^0-9a-z]+#i', '-',
            Mage::helper('catalog/product_url')->format($str)
        );
        $urlKey = strtolower($urlKey);
        $urlKey = trim($urlKey, '-');

        return $urlKey;
    }
}