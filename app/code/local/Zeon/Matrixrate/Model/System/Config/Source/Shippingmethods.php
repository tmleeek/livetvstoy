<?php
/**
 * Zeon Matrixrate Model System Config Source Shippingmethods
 *
 * @author      Zeon Team <aniket.nimje@zeonsolutions.com>
 */
class Zeon_Matrixrate_Model_System_Config_Source_Shippingmethods
{
    public function toOptionArray()
    {
        if (strlen($code = Mage::getSingleton('adminhtml/config_data')->getWebsite())) {
            $websiteId = Mage::getModel('core/website')->load($code)->getId();
        } else {
            $websiteId = 0;
        }
        $optionArray = array();
        $collection = Mage::getResourceModel('matrixrate_shipping/carrier_matrixrate_collection');
        $collection->addFieldToFilter('website_id', array('eq' => $websiteId));
        $collection->getSelect()->group(array('delivery_type'));

        foreach ($collection as $rate) {
            $option = $rate->getData('delivery_type');
            $optionArray[] = array(
                'label' => $option,
                'value' => $option
            );
        }
        return $optionArray;
    }

}
