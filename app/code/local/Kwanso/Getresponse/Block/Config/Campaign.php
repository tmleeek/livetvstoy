<?php
class Kwanso_Getresponse_Block_Config_Campaign
    extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{

    public function __construct()
    {
        $this->addColumn('website', array(
            'label' => Mage::helper('adminhtml')->__('Websites'),
        ));

        $this->addColumn('key', 
            array(
                'label' => Mage::helper('adminhtml')->__('API Key'),
                'style' => 'width:80px',
            ));
        
        $this->addColumn('name', 
            array(
                'label' => Mage::helper('adminhtml')->__('Campaign Name'),
                'style' => 'width:80px',
            ));

        

        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('adminhtml')->__('Add More');

        parent::__construct();
        $this->setTemplate('kwanso/config.phtml');
    }
    
    protected function _renderCellTemplate($columnName)
    {
        if (empty($this->_columns[$columnName])) {
            throw new Exception('Wrong column name specified.');
        }
        $column     = $this->_columns[$columnName];
        $inputName  = $this->getElement()->getName() . '[#{_id}][' . $columnName . ']';
        $value      = $this->getElement()->getValue();

       /* $cron = Mage::getModel('adminhtml/system_config_source_cron_frequency')->toOptionArray();
        if($columnName == 'cron') {
            $rendered = '<select style="width:100px" id="#{_id}-cron" name="'.$inputName.'">';
            foreach ($cron as $key => $value) {
                 $rendered .= '<option style="width:100px"  value="'.$value['value'].'">'.$value['label'].'</option>';
            }
            $rendered .= '</select>';
            return $rendered;
        }*/
                
        if($columnName == 'website') {
            $rendered = '<select style="width:170px" id="#{_id}-website" name="'.$inputName.'">';
            $rendered .= '<option value="limogesjewelry.com">Limoges Jewelry</option>';
            $rendered .= '<option value="tvstoybox.com">Tvstoybox</option>';
            $rendered .= '<option value="shop.pbskids.org">Shop Pbskids</option>';
            $rendered .= '<option value="personalizedplanet.com">Personalized Planet</option>';
            $rendered .= '</select>';
            return $rendered;
        }
        else
            return parent::_renderCellTemplate($columnName);
    }

}
?>

