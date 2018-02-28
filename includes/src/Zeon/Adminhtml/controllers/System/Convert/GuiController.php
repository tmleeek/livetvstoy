<?php
require_once 'Mage'.DS.'Adminhtml'.DS.'controllers'.DS.'System'.DS.'Convert'.DS.'GuiController.php';
class Zeon_Adminhtml_System_Convert_GuiController
    extends Mage_Adminhtml_System_Convert_GuiController
{
    public function runAction()
    {
        ini_set('memory_limit','1024M');
        ini_set('max_execution_time', '36000');

        $this->_initProfile();
        $this->loadLayout();
        $this->renderLayout();
    }
}
