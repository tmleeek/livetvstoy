<?php
/**
 * Orders Export and Import
 *
 * @category:    Aitoc
 * @package:     Aitoc_Aitexporter
 * @version      10.1.7
 * @license:     G0SwOduKhxI2ppsFsSxbJansCjYrTVcLKLwIiB2Xt7
 * @copyright:   Copyright (c) 2015 AITOC, Inc. (http://www.aitoc.com)
 */
class Aitoc_Aitexporter_Model_Import_Type_Rma extends Aitoc_Aitexporter_Model_Import_Type_Complex implements Aitoc_Aitexporter_Model_Import_Type_Interface
{
    /**
     * 
     * @param SimpleXMLElement $orderXml
     * @param string $itemXpath
     * @param array $config
     * @param string $orderIncrementId
     * @return boolean is valid 
     */
    public function validate(SimpleXMLElement $orderXml, $itemXpath, array $config, $orderIncrementId = '')
    {
        if(version_compare(Mage::getVersion(),'1.11.0.0','<'))
            return true;
			
        $isValid     = true;
        $rmaXml = current($orderXml->xpath($itemXpath));

        // empty items from CSV 
        if (!trim(strip_tags($rmaXml->asXML())))
        {
            return $isValid;
        }

        if (empty($rmaXml->increment_id))
        {
            Mage::getModel('aitexporter/import_error')
                ->setOrderIncrementId($orderIncrementId)
                ->setError(Mage::helper('aitexporter')->__('"increment_id" field does not exist in path %s. RMA will not be created', $itemXpath))
                ->save();
            $isValid = false;
        }
        else 
        {
            $incrementId = (string)$rmaXml->increment_id;
			
			$rma = Mage::getModel('aitexporter/enterprise_rma_rma')->load($incrementId, 'increment_id');
			
            if ($rma->getId())
            {
                $order = Mage::getModel('sales/order')->load($rma->getOrderId());

                if ($order->getIncrementId() != $orderIncrementId)
                {
                    Mage::getModel('aitexporter/import_error')
                        ->setOrderIncrementId($orderIncrementId)
                        ->setError(Mage::helper('aitexporter')->__('RMA with increment_id "%s" already exists for order %s. RMA will not be created', $incrementId, $order->getIncrementId()))
                        ->save();
                    $isValid = false;
                }
            }
        }

        $isChildredValid = $this->_validateChildren($orderXml, $itemXpath, $config, $orderIncrementId);
        if (!$isChildredValid)
        {
            $isValid = false;
        }          
  
        return $isValid;
    }

    /**
     * @param SimpleXMLElement $orderXml
     * @param string $itemXpath
     * @param array $importConfig
     * @param Mage_Core_Model_Abstract $parentItem
     */
    public function import(SimpleXMLElement $orderXml, $itemXpath, array $config, Mage_Core_Model_Abstract $parentItem = null)
    {
	    if(version_compare(Mage::getVersion(),'1.11.0.0','<'))
		    return;

        $rma = Mage::getModel('aitexporter/enterprise_rma_rma');
        /* @var $rma Aitoc_Aitexporter_Model_Enterprise_Rma_Rma */
        $rmaXml = current($orderXml->xpath($itemXpath));
        /* @var $rmaXml SimpleXMLElement */

        // empty items from CSV 
        if (!trim(strip_tags($rmaXml->asXML())))
        {
            return false;
        }

        $incrementId = (string)$rmaXml->increment_id;
        if ($incrementId) 
        {
		    $rmaCheck = $rma->load($incrementId, 'increment_id');

            // Shipment with increment Id exists for another order
            if ($rmaCheck->getId())
            {
                //$incrementId = null;
                return Aitoc_Aitexporter_Model_Import_Type_Order::STATE_FAIL;
            }
        }

        $incrementPrefix = false;
        $incrementId = false;
        foreach ($rmaXml->children() as $field)
        {
            switch ($field->getName())
            {
                case 'entity_id':
                case 'order_id':
                case 'order_increment_id':
                    break;
                
                case 'increment_id':
                    $incrementId = (string)$field;
                    $rma->setData($field->getName(), (string)$field);
                    break;
                    
                case 'increment_prefix':
                    $incrementPrefix = (string)$field;
                    break;

                case 'store_id':
                    $rma->setStoreId($parentItem->getStoreId());
                    break;

                case 'customer_id':
                    $rma->setCustomerId($parentItem->getCustomerId());
                    break;

                default:
                    $rma->setData($field->getName(), (string)$field);
                    break;
            }
        }

        $importRmas = $parentItem->getImportRmas();
        if (empty($importRmas))
        {
            $importRmas = array();
        }
        $importRmas[] = $rma;
        $parentItem->setImportRmas($importRmas);

        $this->_importChildren($orderXml, $itemXpath, $config, $rma);
        
        $this->_workWithIncrement($parentItem, 'rma_item', $incrementPrefix, $incrementId);
    }

    public function getConfigPath()
    {
        return 'order/rma';
    }

    public function getXpath()
    {
        return '/rmas/rma';
    }

    /**
     * @see Aitoc_Aitexporter_Model_Import_Type_Interface::getErrorType()
     * return string
     */
    public function getErrorType()
    {
        return false;
    }

    /**
     * @param Mage_Sales_Model_Order $order
     */
    public function postProcess(Mage_Sales_Model_Order $order)
    {
	    if(version_compare(Mage::getVersion(),'1.11.0.0','<'))
		    return;
			
        $importRmas = $order->getImportRmas();
        if (empty($importRmas))
        {
            $importRmas = array();
        }

        $orderItems    = $order->getItemsCollection();
        $orderItemsIds = array();
        foreach ($orderItems as $orderItem)
        {
            if ($orderItem->getOldItemId())
            {
                $orderItemsIds[$orderItem->getOldItemId()] = $orderItem;
            }
        }
        
		$customerName = '';
		if($order->getCustomerId() > 0)
		{
		    $customerName = Mage::getModel('customer/customer')->load($order->getCustomerId())->getName();
		}
		
        foreach ($importRmas as $rma)
        {
            $rma->setCustomerId($order->getCustomerId());
			$rma->setOrderIncrementId($order->getIncrementId());
            
            foreach ($rma->getItemsCollection() as $item)
            {
                if ($item->getOldOrderItemId() && isset($orderItemsIds[$item->getOldOrderItemId()]))
                {
                    $item->setOrderItemId($orderItemsIds[$item->getOldOrderItemId()]->getId());
                }
            }
			
			$rma
				->setCustomerName($customerName)
				->setOrderDate($order->getCreatedAt())
				->setOrderId($order->getId())
				->save();
				
            foreach($rma->getShippingLabelCollection() as $shippingLabel)
            {
                $shippingLabel->setRmaEntityId($rma->getId())->save();
            }
			
			
			$createdRmaStatusHistoryCollection = Mage::getModel('enterprise_rma/rma_status_history')->getCollection()->addFieldToFilter('rma_entity_id', $rma->getId());
			$oldRmaStatusHistoryCollection = $rma->getStatusHistoryCollection();
			$k = 0;
            foreach ($createdRmaStatusHistoryCollection as $createdStatusHistory)
			{
			    if(!isset($oldRmaStatusHistoryCollection[$k]))
				    continue;

			    $data = $oldRmaStatusHistoryCollection[$k]->getData();
				$k++;
				
				$createdId = $createdStatusHistory->getId();
				$createdRmaEntityId = $createdStatusHistory->getRmaEntityId();
				$createdStatusHistory->setData($data);
				$createdStatusHistory->setId($createdId);
				$createdStatusHistory->setRmaEntityId($createdRmaEntityId);
                $createdStatusHistory->save();
			}
        }
    }
}