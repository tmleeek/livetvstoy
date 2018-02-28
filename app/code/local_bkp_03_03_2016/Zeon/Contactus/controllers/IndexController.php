<?php
class Zeon_Contactus_IndexController extends Mage_Core_Controller_Front_Action
{
    // Admin Email
    const XML_PATH_EMAIL_RECIPIENT  = 'contactus/email/recipient_email';
    const XML_PATH_EMAIL_SENDER     = 'contactus/email/sender_email_identity';
    const XML_PATH_EMAIL_TEMPLATE   = 'contactus/email/email_template';

    // User Email
    const XML_PATH_USER_EMAIL_TEMPLATE = 'contactus/email/user_email_template';
    const XML_PATH_CONFIRM			   = 'contactus/email/confirm';

    public function indexAction()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle('Contact Us');
        $this->getLayout()->getBlock('contactus')
            ->setFormAction(Mage::getUrl('*/*/post'));
        $this->_initLayoutMessages('contactus/session');
        $this->renderLayout();
    }

    protected function _getSession()
    {
        return Mage::getSingleton('contactus/session');
    }

    public function postAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $session = $this->_getSession();
            $model = Mage::getModel('contactus/contactus');
            $model->setData($data)
                ->setId($this->getRequest()->getParam('id'));

            try {
                $validationResult = $model->validate();

                if (true === $validationResult) {
                    //Set the default time zone
                    @date_default_timezone_set(
                        Mage::app()->getStore()
                        ->getConfig('general/locale/timezone')
                    );

                    $model->setContactedOn(now());

                    $model->save();

                    // Email
                    $translate = Mage::getSingleton('core/translate');
                    $translate->setTranslateInline(false);

                    $postObject = new Varien_Object();
                    $postObject->setData($data);

                    $mailTemplate = Mage::getModel('core/email_template');
                    $mailTemplate->setTemplateSubject('Contact us email');
                    $mailTemplate->setDesignConfig(
                        array(
                            'area' => 'frontend'
                        )
                    )
                    ->sendTransactional(
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE),
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT),
                        null,
                        array('data' => $postObject)
                    );
                    unset($mailTemplate);
                    $mailTemplate = Mage::getModel('core/email_template');
                    $mailTemplate->setTemplateSubject('Contact us email');
                    // Send Email To User
                    if (Mage::getStoreConfig(self::XML_PATH_CONFIRM)) {
                        $mailTemplate->setDesignConfig(
                            array('area' => 'frontend')
                        )
                        ->sendTransactional(
                            Mage::getStoreConfig(
                                self::XML_PATH_USER_EMAIL_TEMPLATE
                            ),
                            Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                            $data['email'],
                            null,
                            array('data' => $postObject)
                        );
                    }

                    $translate->setTranslateInline(true);

                    $session->addSuccess(
                        Mage::helper('contactus')
                        ->__(
                            'Your request was submitted and will be responded
                            to as soon as possible.'
                        )
                    );
                    $session->setContactusFormData(false);

                    // Redirect to a success page, at the moment it goes
                    // back to the form.
                    $this->_redirect('*/*/');
                    return;
                } else {
                    $session->setContactusFormData($data);

                    if (is_array($validationResult)) {
                        foreach ($validationResult as $errorMessage) {
                           $session->addError($errorMessage);
                        }
                    } else {
                        $session->addError(
                            $this->__('Invalid contactus form data')
                        );
                    }
                }
            } catch (Exception $e) {
                $session->addError($e->getMessage());
                $session->setContactusFormData($data);
                $this->_redirect(
                    '*/*/',
                    array(
                        'id' => $this->getRequest()->getParam('id')
                    )
                );
                return;
            }
        } else {
            $session->addError(
                Mage::helper('contactus')
                ->__(
                    'We were unable to process your request,
                    please try again.'
                )
            );
        }
        $this->_redirect('*/*/');
    }
}