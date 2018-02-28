<?php

class Magalter_Crossup_Model_Mysql4_Crossup extends Mage_Core_Model_Mysql4_Abstract
{

    public $loadRelated = true;
    protected $_serializableFields = array(
        'additional_settings' => array(array(), array())
    );
   
    protected function _construct()
    {
        $this->_init('magalter_crossup/crossup', 'upsell_id');       
    }

    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        if ($this->loadRelated && $object->getId()) {

            $select = $this->_getReadAdapter()->select()->distinct()->from($this->getTable('magalter_crossup/upsell_item'), array('anchor_product_id'))
                    ->where('upsell_id = ?', $object->getId());

            $object->setAnchorProductIds($this->_getReadAdapter()->fetchCol($select));

            $select = $this->_getReadAdapter()->select()->distinct()->from($this->getTable('magalter_crossup/upsell_item'), array('related_id'))
                    ->where('upsell_id = ?', $object->getId());

            $object->setRelatedProductIds($this->_getReadAdapter()->fetchCol($select));

            $object->setUpsellTemplate($object->getTemplate());

            foreach ($object->getAdditionalSettings() as $key => $value) {
                $object->{"setAdditionalSettings{$key}"}($value);
            }
        }
    }

    public function getRelatedUpsells(Magalter_Crossup_Model_Upsells $model, $items, $data = array())
    {
        $select = $this->_getReadAdapter()->select()
                ->from($this->getTable('magalter_crossup/upsell_item'), array('upsell_id'))
                ->distinct()
                ->where('anchor_product_id IN(?)', $items);
 
        $crossup = $this->_getReadAdapter()->fetchAll($select);

        if (empty($crossup)) {
            return false;
        }

        $data['crossup'] = $crossup;
        $data['min_qty'] = count($items);

        return $model->getCollection()->getRelatedUpsellRule($data);
    }
   
    public function reindexAll($model = null)
    {      
        $stm = $this->_getWriteAdapter()
                ->getConnection()
                ->prepare("DELETE FROM `{$this->getTable('magalter_crossup/rule_actions')}` WHERE `rule_id` = ? AND `store_id` = ?");
         
        $rules = Mage::getModel('magalter_crossup/crossup')->getCollection();
        if ($model instanceof Varien_Object && $model->getId()) {            
            $rules->addFieldToFilter('upsell_id', array('eq' => $model->getId()));
        }
        set_time_limit(1800); 
        foreach (Mage::getModel('core/store')->getCollection() as $store) {           
            foreach ($rules as $model) {
                $actions = null;
                /* transition to multiple delete */
                $stm->execute(array($model->getId(), $store->getId()));
                
                $model->load($model->getId());
                
                /* reindex only for active stores for model */
                $activeStores = explode(',', $model->getStores());   
                
                if(!in_array(0, $activeStores) && !in_array($store->getId(), $activeStores)) { 
                    continue;
                }
                
                $attributes = $this->getProductAttributes($model->getActionsSerialized());
                
                $products = Mage::getModel('catalog/product')
                        ->getCollection()
                        ->setStore($store)
                        ->addAttributeToSelect($attributes);  
              
                Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($products);
                Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);   
                Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
               
                foreach ($products as $product) { 
                    if ($model->getActions()->validate($product)) {                         
                        $actions .= "{$product->getId()},";
                    }
                }  
              
                /* transition to insert multiple should be here */
                if ($actions) {  
                    Mage::getModel('magalter_crossup/ruleactions')
                            ->setRuleId($model->getId())
                            ->setStoreId($store->getId())
                            ->setProductIds($actions)
                            ->save();               
                }
            }
        }      
    }

    public function getProductAttributes($serializedString)
    {
        $result = array();
        if (preg_match_all('~s:34:"catalogrule/rule_condition_product";s:9:"attribute";s:\d+:"(.*?)"~s', $serializedString, $matches)) {
            foreach ($matches[1] as $offset => $attributeCode) {
                $result[] = $attributeCode;
            }
        }
        if (preg_match_all('~s:33:"magalter_crossup/conditionProduct";s:9:"attribute";s:\d+:"(.*?)"~s', $serializedString, $matches)) {
            foreach ($matches[1] as $offset => $attributeCode) {
                $result[] = $attributeCode;
            }
        }

        return $result;
    }

    public function saveRelated($data, $model)
    {
        $anchorProducts = array_filter(explode(',', $data['hidden_upsell_anchor_ids']));
        $relatedUpsells = array_filter(explode(',', $data['hidden_upsell_ids']));

        $return = null;
        if (empty($relatedUpsells)) {
            Mage::getSingleton('adminhtml/session')->addNotice('Please specify at least on upsell');
            $return = true;
        }
        if (empty($anchorProducts)) {
            Mage::getSingleton('adminhtml/session')->addNotice('Please specify at least on related product');
            $return = true;
        }

        if ($return)
            return;

        $combinations = count($anchorProducts) * count($relatedUpsells);
        if ($combinations > 10000) {
            throw new Exception(Mage::helper('magalter_crossup')->__('You cannot specify more than 10000 combinations per rule. Current combination count is %d', $combinations));
        }

        // use pdo implementation to speed up saving process        
        $pdo = $this->_getWriteAdapter()->getConnection();

        $statement = $pdo->prepare("
                INSERT INTO {$this->getTable('magalter_crossup/upsell_item')} 
                (`upsell_id`,`anchor_product_id`,`related_id`) 
                VALUES({$model->getId()},?,?)"
        );

        $pdo->prepare("DELETE FROM {$this->getTable('magalter_crossup/upsell_item')} WHERE `upsell_id` = {$model->getId()}")->execute();

        foreach ($anchorProducts as $anchor) {
            foreach ($relatedUpsells as $upsell) {
                if ($anchor == $upsell) {
                    continue;
                }
                $statement->execute(array($anchor, $upsell));
            }
        }
    }

}