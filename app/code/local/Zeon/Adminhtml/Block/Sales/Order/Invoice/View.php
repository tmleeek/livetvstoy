<?php
/**
 * Adminhtml invoice create
 *
 * @category   Zeon
 * @package    Zeon_Adminhtml
 */
class Zeon_Adminhtml_Block_Sales_Order_Invoice_View extends Mage_Adminhtml_Block_Sales_Order_Invoice_View
{
    /**
     * Extend the construct method of the class.
     */
    public function __construct()
    {
        // First call the construct of the parent class.
        parent::__construct();

        // As per the requirement, we are removing the "Print Invoice" feature.
        $this->_removeButton('print');
    }
}
