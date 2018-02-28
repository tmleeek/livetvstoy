<?php
class Kwanso_Personalize_Block_Config_Costs 
extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
   public function __construct()
   {
    $this->addColumn('title', array(
        'label' => Mage::helper('adminhtml')->__('Shipment Method Title'),
        'size' => 15,
        ));
    $this->addColumn('val', array(
        'label' => Mage::helper('adminhtml')->__('Custom Text'),
        'size' => 15,
        ));
    $this->_addAfter = false;
    $this->_addButtonLabel = Mage::helper('adminhtml')->__('Add value');

    parent::__construct();
    }

    protected function _renderCellTemplate($columnName)
    {
        if (empty($this->_columns[$columnName])) {
            throw new Exception('Wrong column name specified.');
        }
        $column = $this->_columns[$columnName];
        $inputName = $this->getElement()->getName() . '[#{_id}][' . $columnName . ']';

        return '<input type="text" name="' . $inputName . '" value="#{' . $columnName . '}" ' . ($column['size'] ? 'size="' . $column['size'] . '"' : '') . '/>';

        return $rendered;
    }
}
?>

