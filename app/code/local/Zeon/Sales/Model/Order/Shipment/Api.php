<?php
/**
 * Sales order shippment API
 *
 * @category   Mage
 * @package    Mage_Sales
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Zeon_Sales_Model_Order_Shipment_Api
    extends Mage_Sales_Model_Order_Shipment_Api
{
    /**
     * Create new shipment for order
     *
     * @param string $orderIncrementId
     * @param array $itemsQty
     * @param string $comment
     * @param booleam $email
     * @param boolean $includeComment
     * @return string
     */
    public function create(
        $orderIncrementId,
        $itemsQty = array(),
        $comment = null,
        $email = false,
        $includeComment = false)
    {
        $order = Mage::getModel('sales/order')
            ->loadByIncrementId($orderIncrementId);
        /**
          * Check order existing
          */
        if (!$order->getId()) {
             $this->_fault('order_not_exists');
        }

        /**
         * Check shipment create availability
         */
        if (!$order->canShip()) {
             $this->_fault(
                 'data_invalid',
                 Mage::helper('sales')->__('Cannot do shipment for order.')
             );
        }

         /* @var $shipment Mage_Sales_Model_Order_Shipment */
        $shipment = $order->prepareShipment($itemsQty);
        if ($shipment) {
            $shipment->register();
            $shipment->addComment($comment, $email && $includeComment);
            if ($email) {
                $shipment->setEmailSent(true);
            }
            $shipment->getOrder()->setIsInProcess(true);
            try {
                $transactionSave = Mage::getModel('core/resource_transaction')
                    ->addObject($shipment)
                    ->addObject($shipment->getOrder())
                    ->save();
                /*$shipment->sendEmail(
                 * $email,
                 * ($includeComment ? $comment : '')
                 * );*/
            } catch (Mage_Core_Exception $e) {
                $this->_fault('data_invalid', $e->getMessage());
            }
            return $shipment->getIncrementId();
        }
        return null;
    }

    /**
     * Add tracking number to order
     *
     * @param string $shipmentIncrementId
     * @param string $carrier
     * @param string $title
     * @param string $trackNumber
     * @return int
     */
    public function addTrack(
        $shipmentIncrementId,
        $carrier,
        $title,
        $trackNumber)
    {
        $shipment = Mage::getModel('sales/order_shipment')
            ->loadByIncrementId($shipmentIncrementId);
        /* @var $shipment Mage_Sales_Model_Order_Shipment */
        if (!$shipment->getId()) {
            $this->_fault('not_exists');
        }

        $carriers = $this->_getCarriers($shipment);

        if (!isset($carriers[$carrier])) {
            $this->_fault(
                'data_invalid',
                Mage::helper('sales')->__('Invalid carrier specified.')
            );
        }

        $track = Mage::getModel('sales/order_shipment_track')
                    ->setNumber($trackNumber)
                    ->setCarrierCode($carrier)
                    ->setTitle($title);

        $shipment->addTrack($track);

        try {
            $shipment->save();
            $track->save();

            $email=true;
            $shipment->sendEmail($email, '');
        } catch (Mage_Core_Exception $e) {
            $this->_fault('data_invalid', $e->getMessage());
        }

        return $track->getId();
    }
} // Class Mage_Sales_Model_Order_Shipment_Api End
