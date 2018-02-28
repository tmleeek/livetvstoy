<?php
class Kwanso_CategoryCsv_Block_Config_Campaign
    extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    public function __construct()
    {
        $this->addColumn('campains444', array(
            'label' => Mage::helper('adminhtml')->__('Upload bulk Images (20)'),
        ));
        $this->_addAfter = false;

       parent::__construct();
        $this->setTemplate('kwanso/configCharm.phtml');

    }

    protected function _renderCellTemplate($columnName)
    {
        if (empty($this->_columns[$columnName])) {
            throw new Exception('Wrong column name specified.');
        }
        $column     = $this->_columns[$columnName];
        $inputName  = $this->getElement()->getName() . '[#{_id}][' . $columnName . ']';
        $value      = $this->getElement()->getValue();
        if($columnName == 'campains444') {
            $rendered = '<input onchange="braceletsFiles()" id="campains444" type="file" name="'.$inputName.'[]" multiple/>';
            return $rendered;
        }
        else
            return parent::_renderCellTemplate($columnName);
    }
}
?>

