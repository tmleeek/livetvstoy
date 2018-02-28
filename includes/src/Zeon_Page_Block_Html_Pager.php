<?php
/**
 * Html page block
 *
 * @category   Zeon
 * @package    Zeon_Page
 */
class Zeon_Page_Block_Html_Pager extends Mage_Page_Block_Html_Pager
{
    /**
     * Override the method to add "ALL" option in the limit.
     *
     * @return Array
     */
    public function getAvailableLimit()
    {
        // Get the accessing module, controller & action name in vars.
        $module     = strtolower(Mage::app()->getRequest()->getModuleName());
        $controller = strtolower(Mage::app()->getRequest()->getControllerName());
        $action     = strtolower(Mage::app()->getRequest()->getActionName());

        // If user is on Order History page, then add one more option "ALL" in limit dropdown.
        if ('sales' == $module && 'order' == $controller && 'history' == $action) {
            return $this->_availableLimit + array('all' => 'all');
        } else {
            return $this->_availableLimit;
        }

    }

}
