<?php
class Zeon_Contactus_Block_Contactus extends Mage_Directory_Block_Data
{
    public function getMetaKeywords()
    {
        if (empty($this->_data['meta_keywords'])) {
            $this->_data['meta_keywords'] = Mage::getStoreConfig(
                'contactus/frontend/meta_keywords'
            );
        }
        return $this->_data['meta_keywords'];
    }

    public function getMetaDescription()
    {
        if (empty($this->_data['meta_description'])) {
            $this->_data['meta_description'] = Mage::getStoreConfig(
                'contactus/frontend/meta_description'
            );
        }
        return $this->_data['meta_description'];
    }

    public function _prepareLayout()
    {
        $title = $this->__("Customer Service");

        // add Home breadcrumb
        if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbs->addCrumb(
                'home',
                array(
                    'label' => $this->__('Home'),
                    'title' => $this->__('Go to Home Page'),
                    'link'  => Mage::getBaseUrl()
                )
            )
            ->addCrumb(
                'contactus',
                array(
                    'label' => $title,
                    'title' => $title
                )
            );
        }
        if ( $head = $this->getLayout()->getBlock('head')) {
            $head->setTitle($title);
            $head->setKeywords($this->getMetaKeywords());
            $head->setDescription($this->getMetaDescription());
        }

        return parent::_prepareLayout();
    }

    public function getContactus()
    {
        if (!$this->hasData('contactus')) {
            $this->setData('contactus', Mage::registry('contactus'));
        }
        return $this->getData('contactus');

    }

    public function getFormData()
    {
        $data = $this->getData('form_data');
        if (is_null($data)) {
            $data = new Varien_Object(
                Mage::getSingleton('contactus/session')
                ->getContactusFormData(true)
            );
            $this->setData('form_data', $data);
        }
        return $data;
    }
}