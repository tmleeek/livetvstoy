<?php
/**
 * Extended Customer account navigation sidebar
 *
 * @category   Zeon
 * @package    Zeon_Customer
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Zeon_Customer_Block_Account_Navigation
    extends Mage_Customer_Block_Account_Navigation
{
    /**
     * Prepare layout
     *
     * @return Mage_CatalogSearch_Block_Result
     */
    public function _prepareLayout()
    {
        $title = $this->__("My Account");

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
}
