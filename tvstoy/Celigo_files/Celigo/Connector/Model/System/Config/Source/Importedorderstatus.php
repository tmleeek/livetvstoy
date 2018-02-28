<?php
class Celigo_Connector_Model_System_Config_Source_Importedorderstatus extends Mage_Core_Model_Abstract
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
		$statuses = Mage::getSingleton('sales/order_config')->getStatuses();
		$excludeArray =  $options = array();
		$options[] = array('value' => ' ', 'label' => '');
        foreach ($statuses as $code=>$label) {
			if (in_array($code, $excludeArray)) continue;
            $options[] = array(
               'value' => $code,
               'label' => $label
            );
        }
		return $options;
    }
}