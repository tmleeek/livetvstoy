<?php
/**
 * Html page block
 *
 * @category   Mage
 * @package    Mage_Page
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Zeon_Page_Block_Html_Welcome extends Mage_Page_Block_Html_Welcome
{
    /**
     * Get block messsage
     *
     * @return string
     */
    protected function _toHtml()
    {
        //throw new Exception("stop");
        if (empty($this->_data['welcome'])) {
            if (Mage::isInstalled() &&
                Mage::getSingleton('customer/session')->isLoggedIn()) {
                $this->_data['welcome'] = $this->__(
                    'Hi %s',
                    $this->escapeHtml(
                        Mage::getSingleton('customer/session')
                        ->getCustomer()->getFirstname()
                    )
                );
            } else {
                $this->_data['welcome'] = Mage::getStoreConfig(
                    'design/header/welcome'
                );
            }
        }
        return $this->_data['welcome'];
    }
}
