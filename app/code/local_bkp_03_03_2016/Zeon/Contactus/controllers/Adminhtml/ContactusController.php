<?php
class Zeon_Contactus_Adminhtml_ContactusController
    extends Mage_Adminhtml_Controller_action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('contactus/items')
            ->_addBreadcrumb(
                Mage::helper('contactus')->__('Items Manager'),
                Mage::helper('contactus')->__('Item Manager')
            );
        return $this;
    }

    public function indexAction()
    {
        $this->_initAction()
            ->renderLayout();
    }

    public function viewAction()
    {
        $id     = $this->getRequest()->getParam('id');
        $model  = Mage::getModel('contactus/contactus')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('contactus/session')->getFormData(true);

            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('contactus_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('contactus/items');

            $this->_addBreadcrumb(
                Mage::helper('contactus')->__('Item Manager'),
                Mage::helper('contactus')->__('Item Manager')
            );

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent(
                $this->getLayout()
                ->createBlock('contactus/adminhtml_contactus_edit')
            )
            ->_addLeft(
                $this->getLayout()
                ->createBlock('contactus/adminhtml_contactus_edit_tabs')
            );

            $this->renderLayout();
        } else {
            Mage::getSingleton('contactus/session')->addError(
                Mage::helper('contactus')->__('Item does not exist')
            );
            $this->_redirect('*/*/');
        }
    }

    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0 ) {
            try {
                $model = Mage::getModel('contactus/contactus');

                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();

                Mage::getSingleton('contactus/session')->addSuccess(
                    Mage::helper('adminhtml')
                    ->__('Item was successfully deleted')
                );
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('contactus/session')
                    ->addError($e->getMessage());
                $this->_redirect(
                    '*/*/edit',
                    array('id' => $this->getRequest()->getParam('id'))
                );
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $contactusIds = $this->getRequest()->getParam('contactus');
        if (!is_array($contactusIds)) {
            Mage::getSingleton('contactus/session')->addError(
                Mage::helper('contactus')->__('Please select item(s)')
            );
        } else {
            try {
                foreach ($contactusIds as $contactusId) {
                    $contactus = Mage::getModel('contactus/contactus')
                        ->load($contactusId);
                    $contactus->delete();
                }
                Mage::getSingleton('contactus/session')->addSuccess(
                    Mage::helper('adminhtml')
                    ->__(
                        'Total of %d record(s) were successfully deleted',
                        count($contactusIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('contactus/session')
                    ->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }


    public function exportCsvAction()
    {
        $fileName   = 'contactus.csv';
        $content    = html_entity_decode(
            $this->getLayout()
            ->createBlock('contactus/adminhtml_contactus_report')
            ->getCsv(),
            ENT_NOQUOTES
        );
        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'contactus.xml';
        $content    = $this->getLayout()
            ->createBlock('contactus/adminhtml_contactus_report')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse(
        $fileName,
        $content,
        $contentType='application/octet-stream'
    )
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK', '');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader(
            'Cache-Control',
            'must-revalidate, post-check=0, pre-check=0',
            true
        );
        $response->setHeader(
            'Content-Disposition',
            'attachment; filename='.$fileName
        );
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
}