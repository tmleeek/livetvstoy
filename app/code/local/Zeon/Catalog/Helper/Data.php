<?php
/**
 * Catalog data helper
 *
 * @category   Mage
 * @package    Mage_Catalog
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Zeon_Catalog_Helper_Data extends Mage_Catalog_Helper_Data
{
    /**
     * Function to return option label information
     *
     * @param int $_attributeCode
     *
     * @return array
     */
    public function getAllOptionLabels($_attributeCode)
    {
        $attributeId = Mage::getResourceModel('eav/entity_attribute')
            ->getIdByCode('catalog_product', $_attributeCode);
        $attribute = Mage::getModel('catalog/resource_eav_attribute')
            ->load($attributeId);
        $attributeOptions = $attribute->getSource()->getAllOptions();
        $allOptions = array();
        foreach ($attributeOptions as $option) {
            if ($option['value'] && $option['label']) {
                $allOptions[$option['value']] = array(
                    'image' => str_replace(' ', '-', strtolower($option['label'])) . '.png',
                    'label' => $option['label']
                );
            }
        }
        return $allOptions;
    }
}
