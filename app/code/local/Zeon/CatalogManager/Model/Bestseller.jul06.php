<?php
/**
 * Zeon Solutions, Inc.
 * @category    Fqs
 * @package     Fqs_AutomationScripts
 * @copyright   Copyright (c) 2012 Zeon Solutions, Inc. All Rights Reserved.(http://www.zeonsolutions.com)
 * @license     http://www.zeonsolutions.com/license/
 * @author      Zeon Team <vidisha.ganjre@zeonsolutions.com>
 */
class Zeon_CatalogManager_Model_Bestseller
{
    protected $_debugOn = true;
    private $_weekSelect;
    private $_limitProducts;

    /**
     *
     * set Bestsellers for products
     */
    public function setBestsellers()
    {
        try {
            if (!$this->getIsEnable()) {
                return;
            }

            //set as admin store
            Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
            $sellingCode = $this->getSellingCode();

            $this->_weekSelect =  Mage::helper('zeon_catalogmanager')->getConfigDetails('bestseller/select_week');
            $this->_limitProducts =  Mage::helper('zeon_catalogmanager')->getConfigDetails('bestseller/update_limit');

            $entityTypeId = Mage::getModel('eav/entity_type')->loadByCode('catalog_product')->getEntityTypeId();
            $attribute = Mage::getModel('eav/entity_attribute')->getCollection()
            ->addFieldToFilter('entity_type_id', $entityTypeId)
            ->addFieldToFilter('attribute_code', $sellingCode)
            ->getFirstItem();
            $allStores = Mage::app()->getStores();
            //clean previous values
            $this->_cleanProductAttribute($attribute, $sellingCode, '0');
            $storeId = Mage_Core_Model_App::ADMIN_STORE_ID;
            // update new values
            $this->_updateProductTopSelling($attribute, $storeId);

        } catch (Mage_Core_Exception $e) {
            Mage::log('Bestsellers :: '.$e->getMessage(), null, 'site_errors.log');
        }
    }


    /**
     * insert top selling value into product
     * @param object $attribute
     * @param string $storeId
     */
    protected function _updateProductTopSelling($attribute, $storeId = '0')
    {
        try {
            $entityTypeId = $attribute->getEntityTypeId();
            $dbConnection = Mage::getResourceModel('core/config')->getReadConnection();
            $resource = Mage::getSingleton('core/resource');
            $readConnection = $resource->getConnection('core_read');
            $orderItemTable = $resource->getTableName('sales_flat_order_item');
            $flatOrderTable = $resource->getTableName('sales_flat_order');
            $productTable = $resource->getTableName('catalog_product_entity');
            $firstDate = date('Y-m-d', strtotime("-1 week"));
            $lastDate = date('Y-m-d', Mage::getModel('core/date')->timestamp(time()));

            if ($this->_weekSelect) {
                $firstDate = date('Y-m-d', strtotime("-".$this->_weekSelect." week"));
                $lastDate = date('Y-m-d', Mage::getModel('core/date')->timestamp(time()));
            }

            $query = "SELECT SUM(order_items.qty_ordered*order_items.price) AS `ordered_qty`,
            `order_items`.`name` AS `order_items_name`,
            `order_items`.`product_id` AS `entity_id`,
            `e`.`entity_type_id`, `e`.`attribute_set_id`,
            `e`.`type_id`, `e`.`sku`, `e`.`has_options`,
            `e`.`required_options`, `e`.`created_at`,
            `e`.`updated_at`
            FROM `{$orderItemTable}` AS `order_items`

            INNER JOIN `{$flatOrderTable}` AS `order` ON `order`.entity_id = order_items.order_id
            AND `order`.state <> 'canceled'
            AND (`order`.created_at BETWEEN '{$firstDate}' AND '{$lastDate    }')

            LEFT JOIN `$productTable` AS `e` ON e.entity_id = order_items.product_id
            AND e.entity_type_id = {$entityTypeId}

            WHERE (parent_item_id IS NULL) GROUP BY `order_items`.`product_id`
            HAVING (SUM(order_items.qty_ordered) > 0) ORDER BY `ordered_qty` DESC";

            //update limit set
            if ($this->_limitProducts > 0) {
                $query .= ' LIMIT '.$this->_limitProducts;
            }

            $query .= ' ;';
            $orderItemsquery = $readConnection->fetchAll($query);

            $binds = NULL; $ins = NULL;
            $totalUpdateCount = 0;

            foreach ($orderItemsquery as $_item) {
                //$_item = $_item->getData();
                // data array for top sellings
                if ($_item['ordered_qty'] > 0) {
                    $itemQty = $_item['ordered_qty'];

                    //for admin store
                    $binds[] = $entityTypeId;
                    $binds[] = $attribute->getAttributeId();
                    $binds[] = Mage_Core_Model_App::ADMIN_STORE_ID;
                    $binds[] = $_item['entity_id'];
                    $binds[] = $itemQty;
                    $ins[] = "(?,?,?,?,?)";

                    $totalUpdateCount++;
                }
            }

            if (is_array($binds) && count($ins) > 0 ) {
                try {
                    $productAttTable = $resource
                        ->getTableName('catalog_product_entity_'.trim($attribute->getBackendType()));
                    $db = $resource->getConnection('core_write');
                    $db->query("SET FOREIGN_KEY_CHECKS = 0");
                    $sql = "INSERT INTO `" . $productAttTable . "` "
                    . " (`entity_type_id`,`attribute_id`,`store_id`,`entity_id`,`value`) "
                    . " VALUES "
                    . implode(",", $ins)
                    . " ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)";
                    $db->query($sql, $binds);
                    $db->query("SET FOREIGN_KEY_CHECKS = 1");

                } catch (Exception $e) {
                    Mage::log('UpdateData '.$backendType.':: '.$e->getMessage(), null, 'site_errors.log');
                    throw new Exception($e->getMessage());
                }
            }

            if ($this->_debugOn) {
                Mage::log('Bestsellers Done :: '.$message, null, 'automation_script.log');
            }
        } catch (Exception $e) {
            Mage::log('Bestsellers getData :: '.$e->getMessage(), null, 'site_errors.log');
        }
        return;
    }

    protected function _cleanProductAttribute($attribute, $attributeCode, $storeId)
    {
        $resource = Mage::getSingleton('core/resource');
        $productAttTable = $resource->getTableName('catalog_product_entity_'.trim($attribute->getBackendType()));
        $db = $resource->getConnection('core_write');
        $sql = "Delete from `" . $productAttTable . "` "
        . " WHERE attribute_id = '".$attribute->getAttributeId()."'"
        . " AND entity_type_id = '" .$attribute->getEntityTypeId(). "'";

        $db->query($sql);
        return ;
    }

    /**
     * get admin setting menu prefix before attributes
     */
    public function getIsEnable($storeId = '0')
    {
        return Mage::app()->getStore($storeId)->getConfig('zeon_catalogmanager/bestseller/enable_bestseller');
    }

    /**
     * get Selling code
     */
    public function getSellingCode($storeId = '0')
    {
        return Mage::app()->getStore($storeId)->getConfig('zeon_catalogmanager/bestseller/bestseller_code');
    }

}
