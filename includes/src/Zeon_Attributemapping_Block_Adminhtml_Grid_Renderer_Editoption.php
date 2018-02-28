<?php
/**
 * Adminhtml low stock inventory product notifcation report grid block
 *
 * @category   Fqs
 * @package    Fqs_Adminhtml
 * @author     Zeon Magento Team <amrita.singh@zeonsolutions.com>
 */
class Zeon_Attributemapping_Block_Adminhtml_Grid_Renderer_Editoption
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * To render thubnail image for the grid view
     * @see Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract::render()
     */
    public function render(Varien_Object $row)
    {
        $value =  $row->getData($this->getColumn()->getIndex());
        $attributeInfo = Mage::getModel('eav/entity_attribute')
            ->load($row->getAttributeId());
        $title = "'Edit Options For ".$attributeInfo
            ->getData('frontend_label')."'";
        $url  = "'".$this->getUrl(
            '/index/addoptions/',
            array(
                'id'=>$row->getAttributeId(),
                'optionid'=>$row->getOptionId(),
                'store'=>$this->_getStoreId()
            )
        )."'";
        $html = '<a href="javascript:void(0);"'
            . ' onclick="openMyPopup('.$url.','.$title.')"'
            . 'title="Edit/Delete Option '.$value.'">';
        $html .= $value;
        $html .= '</a>';
        return $html;
    }

    /**
     * get store data
     */
    protected function _getStoreId()
    {
        $defaultStore = $storeId = Mage::helper('zeon_attributemapping')
            ->getTopStore();
        $storeId = (int) $this->getRequest()->getParam('store', $defaultStore);
        return $storeId;
    }
}