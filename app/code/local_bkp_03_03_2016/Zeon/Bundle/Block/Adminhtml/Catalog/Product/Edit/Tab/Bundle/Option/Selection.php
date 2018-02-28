<?php
/**
 * Bundle selection renderer
 *
 * @category   Zeon
 * @package    Zeon_Bundle
 * @author     Suhas Dhoke <suhas.dhoke@zeonsolutions.com>
 */
class Zeon_Bundle_Block_Adminhtml_Catalog_Product_Edit_Tab_Bundle_Option_Selection
    extends Mage_Bundle_Block_Adminhtml_Catalog_Product_Edit_Tab_Bundle_Option_Selection
{
    /**
     * Retrieve is-size-attribute type select html
     *
     * @return string
     */
    public function getIsSizeAttributeSelectHtml()
    {
        $select = $this->getLayout()->createBlock('adminhtml/html_select')
            ->setData(array(
                'id' => $this->getFieldId().'_{{index}}_is_size_attribute',
                'class' => 'select'
            ))
            ->setName($this->getFieldName().'[{{parentIndex}}][{{index}}][selection_is_size_attribute]')
            ->setOptions(Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray())
            ->setValue(0);

        return $select->getHtml();
    }

}
