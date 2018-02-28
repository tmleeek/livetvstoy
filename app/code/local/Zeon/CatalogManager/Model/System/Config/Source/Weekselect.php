<?php
/**
* Select week for topseller
*
* @category FQS
* @author Zeon Team <amrita.singh@zeonsolutions.com>
*/
class Zeon_CatalogManager_Model_System_Config_Source_Weekselect
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 1, 'label'=>Mage::helper('adminhtml')->__('1 Week')),
            array('value' => 2, 'label'=>Mage::helper('adminhtml')->__('2 Weeks')),
            array('value' => 3, 'label'=>Mage::helper('adminhtml')->__('3 Weeks')),
            array('value' => 4, 'label'=>Mage::helper('adminhtml')->__('4 Weeks')),
            array('value' => 6, 'label'=>Mage::helper('adminhtml')->__('6 Weeks')),
            array('value' => 8, 'label'=>Mage::helper('adminhtml')->__('8 Weeks')),
        );
    }
}