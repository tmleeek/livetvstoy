<?php
if (class_exists('Mage_Sales_Model_Order_Creditmemo_Api')) {
	
	class Celigo_Connectorplus_Model_Creditmemo_Api extends Mage_Sales_Model_Order_Creditmemo_Api
	{
		const EXPECTED_ARGUMENT_MISSING_MSG = 'Expected argument is_imported is missing';
		const INVALID_ARGUMENT_VALUE_MSG 	= 'The argument is_imported accepts 0 and 1 only. 0 for not imported and 1 for imported.';
		/**
		 * Update credit memo information
		 *
		 * @param string $creditmemoIncrementId
		 * @param array credit memo data
		 * @return string
		 */
		public function update($creditmemoIncrementId, $data)
		{
		
			if (!isset($data['is_imported'])) {
				$this->_fault('data_invalid', self::EXPECTED_ARGUMENT_MISSING_MSG);
			}
		
			if ($data['is_imported'] != 0 && $data['is_imported'] != 1) {
				$this->_fault('data_invalid', self::INVALID_ARGUMENT_VALUE_MSG);
			}
		
			try {
			
				$is_imported = $data['is_imported'];
				$creditmemo = $this->_getCreditmemo($creditmemoIncrementId);
				if($is_imported != $creditmemo->getIsImported()) {
					$creditmemo->setIsImported($is_imported);
					$creditmemo->save();
				}
				
			} catch (Exception $e) {
				$this->_fault('data_invalid', $e->getMessage());
			}
			return $creditmemoIncrementId;
		}
		
		/**
		 * Retrieve credit memos by filters
		 *
		 * @param array|null $filter
		 * @return array
		 */
		public function items($filter = null)
		{
			return parent::items($filter);
		}
		
		/**
		 * Retrieve credit memo information
		 *
		 * @param string $creditmemoIncrementId
		 * @return array
		 */
		public function info($creditmemoIncrementId)
		{
			return parent::info($creditmemoIncrementId);
		}
		
	}
	
} else {

	class Celigo_Connectorplus_Model_Creditmemo_Api extends Mage_Sales_Model_Api_Resource
	{
		const EXPECTED_ARGUMENT_MISSING_MSG = 'Expected argument is_imported is missing';
		const INVALID_ARGUMENT_VALUE_MSG 	= 'The argument is_imported accepts 0 and 1 only. 0 for not imported and 1 for imported.';
		/**
		 * Update credit memo information
		 *
		 * @param string $creditmemoIncrementId
		 * @param array credit memo data
		 * @return string
		 */
		public function update($creditmemoIncrementId, $data)
		{
		
			if (!isset($data['is_imported'])) {
				$this->_fault('data_invalid', self::EXPECTED_ARGUMENT_MISSING_MSG);
			}
		
			if ($data['is_imported'] != 0 && $data['is_imported'] != 1) {
				$this->_fault('data_invalid', self::INVALID_ARGUMENT_VALUE_MSG);
			}
		
			try {
			
				$is_imported = $data['is_imported'];
				$creditmemo = $this->_getCreditmemo($creditmemoIncrementId);
				if($is_imported != $creditmemo->getIsImported()) {
					$creditmemo->setIsImported($is_imported);
					$creditmemo->save();
				}
				
			} catch (Exception $e) {
				$this->_fault('data_invalid', $e->getMessage());
			}
			return $creditmemoIncrementId;
		}
		
		/**
		 * Initialize attributes' mapping
		 */
		public function __construct()
		{
			$this->_attributesMap['creditmemo'] = array(
				'creditmemo_id' => 'entity_id'
			);
			$this->_attributesMap['creditmemo_item'] = array(
				'item_id'    => 'entity_id'
			);
			$this->_attributesMap['creditmemo_comment'] = array(
				'comment_id' => 'entity_id'
			);
		}
		
		/**
		 * Retrieve credit memos by filters
		 *
		 * @param array|null $filter
		 * @return array
		 */
		public function items($filter = null)
		{
			$filter = $this->_prepareListFilter($filter);
			try {
				$result = array();
				/** @var $creditmemoModel Mage_Sales_Model_Order_Creditmemo */
				$creditmemoModel = Mage::getModel('sales/order_creditmemo');
				
				// map field name entity_id to creditmemo_id
				//foreach ($creditmemoModel->getFilteredCollectionItems($filter) as $creditmemo) {
				$collection = $creditmemoModel->getResourceCollection();
				if (is_array($filter)) {
					foreach ($filter as $field => $value) {
						$collection->addFieldToFilter($field, $value);
					}
				}
				//foreach ($creditmemoModel->getResourceCollection()->getFiltered($filter) as $creditmemo) {
				foreach ($collection as $creditmemo) {
					$result[] = $this->_getAttributes($creditmemo, 'creditmemo');
				}
			} catch (Exception $e) {
				$this->_fault('invalid_filter', $e->getMessage());
			}
			return $result;
		}
		
		/**
		 * Make filter of appropriate format for list method
		 *
		 * @param array|null $filter
		 * @return array|null
		 */
		protected function _prepareListFilter($filter = null)
		{
			// prepare filter, map field creditmemo_id to entity_id
			if (is_array($filter)) {
				foreach ($filter as $field => $value) {
					if (isset($this->_attributesMap['creditmemo'][$field])) {
						$filter[$this->_attributesMap['creditmemo'][$field]] = $value;
						unset($filter[$field]);
					}
				}
			}
			return $filter;
		}
		
		/**
		 * Retrieve credit memo information
		 *
		 * @param string $creditmemoIncrementId
		 * @return array
		 */
		public function info($creditmemoIncrementId)
		{
		
			$creditmemo = $this->_getCreditmemo($creditmemoIncrementId);
			// get credit memo attributes with entity_id' => 'creditmemo_id' mapping
			$result = $this->_getAttributes($creditmemo, 'creditmemo');
			$result['order_increment_id'] = $creditmemo->getOrder()->load($creditmemo->getOrderId())->getIncrementId();
			// items refunded
			$result['items'] = array();
			foreach ($creditmemo->getAllItems() as $item) {
				$result['items'][] = $this->_getAttributes($item, 'creditmemo_item');
			}
			// credit memo comments
			$result['comments'] = array();
			foreach ($creditmemo->getCommentsCollection() as $comment) {
				$result['comments'][] = $this->_getAttributes($comment, 'creditmemo_comment');
			}
			return $result;
		}
	
		/**
		 * Load CreditMemo by IncrementId
		 *
		 * @param mixed $incrementId
		 * @return Mage_Core_Model_Abstract|Mage_Sales_Model_Order_Creditmemo
		 */
		protected function _getCreditmemo($incrementId)
		{
			/** @var $creditmemo Mage_Sales_Model_Order_Creditmemo */
			$creditmemo = Mage::getModel('sales/order_creditmemo')->load($incrementId, 'increment_id');
			if (!$creditmemo->getId()) {
				$this->_fault('not_exists');
			}
			return $creditmemo;
		}
	}
}
?>