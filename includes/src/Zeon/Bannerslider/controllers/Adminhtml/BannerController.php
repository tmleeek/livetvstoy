<?php
class Zeon_Bannerslider_Adminhtml_BannerController
    extends Mage_Adminhtml_Controller_Action
{
    /**
     * init layout and set active for current menu
     *
     * @return Zeon_Bannerslider_Adminhtml_BannersliderController
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('bannerslider/banner')
            ->_addBreadcrumb(
                Mage::helper('bannerslider')->__('Items Manager'),
                Mage::helper('bannerslider')->__('Item Manager')
            );
        return $this;
    }

    /**
     * index action
     */
    public function indexAction()
    {
        $this->_initAction()
            ->renderLayout();
    }

    /**
     * view and edit item action
     */
    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $store = $this->getRequest()->getParam('store');
        $model = Mage::getModel('bannerslider/banner')
            ->setStoreId($store)
            ->load($id);

        if ($model->getId() || $id == 0) {

            $bannerStores = Mage::getSingleton('bannerslider/bannerstores')
                ->getCollection()
                ->addFieldToFilter('banner_id', array('eq', $id));
            $stores = array();
            foreach ($bannerStores as $bannerStore) {
                $stores[] = $bannerStore['store_id'];
            }

            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            $model->setData('store_id', $stores);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::register('banner_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('bannerslider/bannerslider');

            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Item Manager'),
                Mage::helper('adminhtml')->__('Item Manager')
            );
            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Item News'),
                Mage::helper('adminhtml')->__('Item News')
            );

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent(
                $this->getLayout()
                ->createBlock('bannerslider/adminhtml_banner_edit')
            )
            ->_addLeft(
                $this->getLayout()
                ->createBlock('bannerslider/adminhtml_banner_edit_tabs')
            );

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')
                ->addError(
                    Mage::helper('bannerslider')
                    ->__('Item does not exist')
                );
            $this->_redirect('*/*/');
        }
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * save item action
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $data['store_id'] = $store;
            $model = Mage::getModel('bannerslider/banner');
            if (isset($data['image']['delete'])) {
                Mage::helper('bannerslider')
                    ->deleteImageFile($data['image']['value']);
            }
            if (isset ($_FILES['image']['name'])
                && !empty ($_FILES['image']['name'])) {
                $file = $_FILES;
                //cehcking for banner dimension
                list($width, $height, $type, $attr)
                    = getimagesize($file['image']['tmp_name']);
                if ($height != 320 || $width != 1000) {
                    Mage::getSingleton('adminhtml/session')
                        ->addError(
                            Mage::helper('bannerslider')
                            ->__(
                                'Banner image resolution must be
                                1000x320.'
                            )
                        );
                    Mage::getSingleton('adminhtml/session')
                        ->setBannerData($this->getRequest()->getPost());

                    $this->_redirect(
                        '*/*/edit',
                        array(
                            'id' => $this->getRequest()
                                ->getParam('id')
                        )
                    );
                    return;
                }
            }
            $image = Mage::helper('bannerslider')->uploadBannerImage();
            if ($image || (isset($data['image']['delete'])
                && $data['image']['delete'])) {
                $data['image'] = $image;
            } else {
                unset($data['image']);
            }
            $times = explode(" ", now());
            if ($data['end_time'] && $data['start_time']) {
                $data['start_time'] = $data['start_time']. " " . $times[1];
                $data['end_time'] = $data['end_time'] . " " . $times[1];
            }

            $data['click_url'] = $this->getRequest()->getPost('click_url');

            $model->setData($data)
                    //->setStoreId($store)
                    ->setData('banner_id', $this->getRequest()->getParam('id'));
            try {

                $model->save();
                $lastInsertedId = $model->getId();

                $connection = Mage::getSingleton('core/resource')
                    ->getConnection('core_write');
                $condition = array(
                    $connection->quoteInto(
                        'banner_id=?',
                        $this->getRequest()->getParam('id')
                    )
                );
                $connection->delete('bannerslider_banner_stores', $condition);

                foreach ($data['stores'] as $store) {
                    $bannersliderStores =
                        Mage::getModel('bannerslider/bannerstores');
                    $bannersliderStores->setBannerId($lastInsertedId)
                        ->setStoreId($store)
                        ->save();
                }

                Mage::getSingleton('adminhtml/session')
                    ->addSuccess(
                        Mage::helper('bannerslider')
                        ->__('Banner was successfully saved')
                    );
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect(
                        '*/*/edit',
                        array(
                            'id' => $model->getId(),
                            'store' => $this->getRequest()->getParam("store")
                        )
                    );
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')
                    ->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect(
                    '*/*/edit',
                    array(
                        'id' => $this->getRequest()->getParam('id')
                    )
                );
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')
            ->addError(
                Mage::helper('bannerslider')
                ->__('Unable to find banner to save')
            );
        $this->_redirect('*/*/');
    }

    /**
     * This method is used to convert invalid url to valid url
     *
     * @return string
     */
    protected function manageUrl($urlKey)
    {
        // convert non-alphanumeric characters
        $urlKey = Mage::helper('catalog/product_url')->format($urlKey);

        // replace remaining non-alphanumeric characters with dashes
        $urlKey = preg_replace('#[^0-9a-z]+#i', '-', $urlKey);

        // make it lowercase
        $urlKey = strtolower($urlKey);

        // trim dashes on the left and right
        $urlKey = trim($urlKey, '-');

        return $urlKey;
    }

    /**
     * delete item action
     */
    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('bannerslider/banner');
                $model->setId($this->getRequest()->getParam('id'))
                        ->delete();
                Mage::getSingleton('adminhtml/session')
                    ->addSuccess(
                        Mage::helper('adminhtml')
                        ->__('Banner was successfully deleted')
                    );
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')
                    ->addError($e->getMessage());
                $this->_redirect(
                    '*/*/edit',
                    array(
                        'id' => $this->getRequest()->getParam('id'),
                        'store' => $this->getRequest()->getParam("store")
                    )
                );
            }
        }
        $this->_redirect('*/*/');
    }

    /**
     * mass delete item(s) action
     */
    public function massDeleteAction()
    {
        $bannersliderIds = $this->getRequest()->getParam('banner');
        if (!is_array($bannersliderIds)) {
            Mage::getSingleton('adminhtml/session')
                ->addError(
                    Mage::helper('adminhtml')->__('Please select item(s)')
                );
        } else {
            try {
                foreach ($bannersliderIds as $bannersliderId) {
                    $bannerslider = Mage::getModel('bannerslider/banner')
                        ->load($bannersliderId);
                    $bannerslider->delete();
                }
                Mage::getSingleton('adminhtml/session')
                    ->addSuccess(
                        Mage::helper('adminhtml')
                        ->__(
                            'Total of %d record(s) were successfully deleted',
                            count($bannersliderIds)
                        )
                    );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')
                    ->addError($e->getMessage());
            }
        }
        $this->_redirect(
            '*/*/index',
            array(
                'store' => $this->getRequest()->getParam("store")
            )
        );
    }

    /**
     * mass change status for item(s) action
     */
    public function massStatusAction()
    {
        $bannerIds = $this->getRequest()->getParam('banner');
        $status = $this->getRequest()->getParam('status');
        if (!is_array($bannerIds)) {
            Mage::getSingleton('adminhtml/session')
                ->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($bannerIds as $bannerId) {
                    $banner = Mage::getSingleton('bannerslider/banner')
                            ->load($bannerId)
                            ->setStatus($status)
                            ->save();
                }
                $this->_getSession()
                    ->addSuccess(
                        $this->__(
                            'Total of %d record(s) were successfully updated',
                            count($bannerIds)
                        )
                    );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect(
            '*/*/index',
            array('store' => $this->getRequest()->getParam("store"))
        );
    }

    /**
     * export grid item to CSV type
     */
    public function exportCsvAction()
    {
        $fileName = 'bannerslider.csv';
        $content = $this->getLayout()
            ->createBlock('bannerslider/adminhtml_banner_grid')->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export grid item to XML type
     */
    public function exportXmlAction()
    {
        $fileName = 'bannerslider.xml';
        $content = $this->getLayout()
            ->createBlock('bannerslider/adminhtml_banner_grid')->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('zextension/banner/banner_listing');
    }

}