<?php
/**
 * Zeon Solutions, Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Zeon Solutions License
 * that is bundled with this package in the file LICENSE_ZE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.zeonsolutions.com/license/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zeonsolutions.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension
 * to newer versions in the future. If you wish to customize this extension for
 * your needs please refer to http://www.zeonsolutions.com for more information.
 *
 * @category    Zeon
 * @package     Zeon_Attributemapping
 * @copyright   Copyright (c) 2012 Zeon Solutions, Inc. All Rights Reserved.
 *    (http://www.zeonsolutions.com)
 * @license     http://www.zeonsolutions.com/license/
 */

class Zeon_Attributemapping_Model_Urlcron extends Mage_Core_Model_Abstract
{
    protected $_urlKey = 'attribute';
    protected $_urlSuffix = '';
    protected $_attributeUrl = 'attributemapping/index/index/id/%d/';
    protected $_logfile = 'attribute_mapping.log';

    /**
     *
     * cron to set attribute option url
     */
    public function setAttributeUrls()
    {
        $this->_setPersonalizedProductUrls();
        $this->_attributeUpdateByStores();
    }
    
    public function getCharacterBannerLeftImage($url_key)
    {
        try {    
                $_resource  = Mage::getSingleton('core/resource');
                $read       = $_resource->getConnection('core_read');
                //echo "vtriotesting11";
                $table = "zeon_attribute_flat_1";
                $storeId = Mage::app()->getStore()->getId();
                if($storeId == 1 || $storeId == 2) {
                    $table = "zeon_attribute_flat_".$storeId;
                }
            
                $value=$read->query("Select page_banner_left_image, dest_url from $table where url_key='$url_key'");
                $row = $value->fetch();
                //echo "<pre>";print_r($row);echo "</pre>";
                $imageHtml = '';
                if ($row['page_banner_left_image'] != '') {
                    $_imageUrl = Mage::getBaseUrl('media') . $row['page_banner_left_image'];
                    $imageHtml = '<p>
                        <a href="'.$row['dest_url'].'"><img src="'.$_imageUrl.'"
                            alt="'.$option['label'].'"
                                title="'.$option['label'].'" /></a>
                        </p>';
                }
                return $imageHtml;
            
                
        } catch (Exception $e) {
            // Exception goes here.
        }     
        
    }

    /**
     * Method used to set the rewrite url of personalized products.
     */
    protected function _setPersonalizedProductUrls()
    {
        try {
            // Create the object of Resource model.
            $_resource       = Mage::getSingleton('core/resource');
            $_readConnection = $_resource->getConnection('core_read');

            // Query to fetch the attribute details.
            $_tableEavAttribute = $_resource->getTableName('eav_attribute');
            $_queryEav = sprintf(
                'SELECT attribute_id, backend_type, attribute_code FROM %s '
                . ' WHERE entity_type_id = "4" '
                . ' AND attribute_code IN ("personalize", "name")'
                . ' ORDER BY attribute_id ASC', $_tableEavAttribute
            );
            $_eavAttributeData = $_readConnection->fetchAll($_queryEav);

            // Qyery to fetch the personalized product data from tables.
            $_queryProduct = 'SELECT CPEV.value, CPEV.entity_id, CPEUK.value AS url '
                . 'FROM catalog_product_entity_' . $_eavAttributeData[1]['backend_type'] . ' AS CPEI '
                . 'LEFT JOIN catalog_product_entity_' . $_eavAttributeData[0]['backend_type'] . ' AS CPEV '
                . 'ON CPEI.entity_id = CPEV.entity_id '
                . 'AND CPEV.attribute_id = "'.$_eavAttributeData[0]['attribute_id'].'" '
                . 'LEFT JOIN `catalog_product_entity_url_key` AS CPEUK ON CPEUK.entity_id = CPEI.entity_id '
                . 'WHERE CPEI.value = 1 AND CPEI.attribute_id = "'.$_eavAttributeData[1]['attribute_id'].'"';
            $_productData = $_readConnection->fetchAll($_queryProduct);

            // Set the store-id to "1" (Ty's Toy Box site)
            $_storeId = '1';

            // Check, if there is any related products or not.
            if ($_productData && count($_productData) > 0) {
                // If yes, then loop on them.
                foreach ($_productData as $_product) {
                    // Set the identifier url and target path url
                    $_url = 'personalized-' . Mage::getSingleton('catalog/product')->formatUrlKey($_product['url']);
                    $_targetPath = 'personalize/index/index/?id=' . $_product['entity_id'];

                    // Check, if the rewrite url is already exists or not.
                    $_isUrlExists = Mage::getModel('enterprise_urlrewrite/redirect')
                        ->getCollection()
                        ->addFieldToFilter('target_path', $_targetPath)
                        ->addFieldToFilter('store_id', $_storeId)
                        ->getFirstItem();
                    $_isUrlExistsData = $_isUrlExists->getData();

                    // Set the default values for rewrite url.
                    $_rewriteUrlData = array(
                        'store_id'    => $_storeId,
                        'options'     => 'personalized_id:' . $_product['entity_id'],
                        'identifier'  => $_url,
                        'target_path' => $_targetPath,
                        'description' => 'Poptropica Personalised Product'
                    );

                    // If rewrite url is already exists then only update the identifier url.
                    if ($_isUrlExistsData && $_isUrlExistsData['identifier'] != $_url) {
                        $_rewriteUrlData = $_isUrlExistsData;
                        $_rewriteUrlData['identifier'] = $_url;
                    }

                    // Call the function to update url rewrite table.
                    $this->_updateUrlRewrite($_rewriteUrlData);
                }
            }
        } catch (Exception $e) {
            // Exception goes here.
        }
    }

    /**
     *
     * set attribute url by stores
     */
    protected function _attributeUpdateByStores()
    {
        // check for enable cron
        $enableCron = Mage::helper('zeon_attributemapping')
        ->getConfigDetails('active', 1);
        if (!$enableCron) {
            return true;
        }
        $stores = Mage::getModel('core/store')->getCollection()
        ->addFieldToFilter('store_id', array('gt'=>0))->getAllIds();

        foreach ($stores as $store) {
            //set url suffix
            $this->_urlSuffix = Mage::helper('zeon_attributemapping')
            ->getConfigDetails('url_prefix', 1);
            if ($this->_urlSuffix) {
                $this->_urlSuffix = '.'.$this->_urlSuffix;
            }
            $this->_setOtherUrl($store);
            $this->_setStoreUrls($store);
        }
    }

    /**
     *
     * update other urls then attribute
     * @param unknown_type $store
     */
    protected function _setOtherUrl($store)
    {
        try{
            $otherUrls[] = array(
                'url_key' => Mage::helper('zeon_attributemapping')
                ->getConfigDetails('characterpage_url', 1),
                'targate_path' => 'attributemapping/index/view/',
                'option' => ''
            );
            $urlData = '';

            $attributsCodes = Mage::helper('zeon_attributemapping')
                ->getConfigDetails('attribute_list');
            $attributsCodes = @explode(',', $attributsCodes);
            // get all attribute data
            $entityTypeId = Mage::getModel('eav/entity_type')
                ->loadByCode('catalog_product')->getEntityTypeId();
            $attributes = Mage::getModel('eav/entity_attribute')
                ->getCollection()
                ->addFieldToFilter('entity_type_id', $entityTypeId)
                ->addFieldToFilter(
                    'attribute_code',
                    array('in' => $attributsCodes)
                )
                ->setOrder('attribute_id', 'ASC');

            foreach ($attributes as $attributeData) {
                $otherUrls[] = array(
                    'url_key' => 'all-'.$attributeData->getAttributeCode(),
                    'targate_path' => 'attributemapping/index/option/id/'
                        .$attributeData->getId(),
                    'option' => 'attribute:'.$attributeData->getId()
                );
            }

            foreach ($otherUrls as $setUrl) {
                $checkUrl = Mage::getModel('enterprise_urlrewrite/redirect')
                    ->getCollection()
                    ->addFieldToFilter('target_path', $setUrl['targate_path'])
                    ->addFieldToFilter('store_id', $store)
                    ->getFirstItem();

                if (!$checkUrl->getData()) {
                    $urlData = array(
                        'store_id' => $store,
                        'options' => $setUrl['option'],
                        'identifier' => $setUrl['url_key'].$this->_urlSuffix,
                        'target_path' => $setUrl['targate_path'],
                        'description' => 'Attribute listing Page'
                    );
                    $this->_updateUrlRewrite($urlData);
                } elseif ($checkUrl->getData()
                && $checkUrl->getData('identifier') !=
                $setUrl['url_key'].$this->_urlSuffix) {
                    $urlData = $checkUrl->getData();
                    $urlData['identifier'] = $setUrl['url_key']
                        .$this->_urlSuffix;
                    $this->_updateUrlRewrite($urlData);
                }
            }

        } catch (Exception $e) {
            Mage::log(
                'get url data: '. implode(', ', $urlData)
                .' message: '. $e->getMessage(),
                null,
                $this->_logfile
            );

        }
    }

    /**
     *
     * set get data to save inurl management
     * @param unknown_type $store
     */
    protected function _setStoreUrls($store)
    {
        try{
            $ignoreIds = array();
            $resource = Mage::getSingleton('core/resource');
            $readConnection = $resource->getConnection('core_read');
            $attributeDataTable = $resource->getTableName(
                array('zeon_attributemapping/attributemapping', $store)
            );
            $urlRedirect = $resource
                ->getTableName('enterprise_urlrewrite/redirect');
            $urlConnect = $resource
                ->getTableName('enterprise_urlrewrite/redirect_rewrite');
            $urlRewrite = $resource
                ->getTableName('enterprise_urlrewrite/url_rewrite');

            $query = 'SELECT mapping_id '
                . ' FROM `'.$attributeDataTable.'` AS att_table'
                . ' LEFT JOIN `'.$urlRedirect.'` AS url_redirect '
                . ' ON url_redirect.options = att_table.mapping_id '
                . ' AND store_id = \''.$store.'\''
                . ' INNER JOIN `'.$urlConnect.'` AS url_connect '
                . ' ON url_redirect.redirect_id = url_connect.redirect_id '
                . ' INNER JOIN enterprise_url_rewrite AS url_rewrite '
                . ' ON url_rewrite.url_rewrite_id = url_connect.url_rewrite_id '
                . ' AND url_rewrite.is_system = \'0\'';

            $getAllMatchingIds = $query.' WHERE '
                . ' CONCAT(att_table.URL_KEY,\''.$this->_urlSuffix.'\')'
                . ' = url_rewrite.request_path ';

            $allMappIds = $readConnection->fetchAll($getAllMatchingIds);
            foreach ($allMappIds as $mappingId) {
                $ignoreIds[] = $mappingId['mapping_id'];
            }

            $getUpdateIds = 'SELECT mapping_id, attribute_id, option_id'
                . ', url_key, url_redirect.redirect_id '
                . ' FROM `'.$attributeDataTable.'` AS att_table'
                . ' LEFT JOIN `'.$urlRedirect.'` AS url_redirect '
                . ' ON url_redirect.options = att_table.mapping_id '
                . ' AND store_id = \''.$store.'\'';

            if (!empty($ignoreIds)) {
                $getUpdateIds = $getUpdateIds.' WHERE '
                    . ' att_table.mapping_id '
                    . ' NOT IN ('.@implode(', ', $ignoreIds).')';
            }

            $urlDataToUpdate = $readConnection->fetchAll($getUpdateIds);
            $updateCount = 0;
            foreach ($urlDataToUpdate as $mappingData) {
                $tUrl = sprintf(
                    $this->_attributeUrl,
                    $mappingData['option_id']
                );
                $urlData = array(
                    'store_id' => $store,
                    'options' => $mappingData['mapping_id'],
                    'identifier' => $mappingData['url_key'].$this->_urlSuffix,
                    'target_path' => $tUrl,
                    'description' => 'Attribute option mapping attribute_id: '
                        . $mappingData['attribute_id'] .' option_id: '
                        . $mappingData['option_id']
                );

                if (!empty($mappingData['redirect_id'])) {
                    $urlData['redirect_id'] = $mappingData['redirect_id'];
                }
                $this->_updateUrlRewrite($urlData);
                $updateCount++;
            }
        } catch (Exception $e) {
            Mage::log(
                'get url data: '. implode(', ', $urlData)
                .' message: '. $e->getMessage(),
                null,
                $this->_logfile
            );

        }
        Mage::log('Success: '.$updateCount, null, $this->_logfile);
        return $this;
    }

    /**
     *
     * set url save recursive function
     * @param array $urlData
     */
    protected function _updateUrlRewrite($urlData)
    {
        try{
            $results = Mage::getModel('enterprise_urlrewrite/redirect');
            if (!empty($urlData['redirect_id'])) {
                $results->load($urlData['redirect_id']);
            }
            $results->setStoreId($urlData['store_id'])
                ->setOptions($urlData['options'])
                ->setIdentifier($urlData['identifier'])
                ->setTargetPath($urlData['target_path'])
                ->setDescription($urlData['description'])
                ->save();

        } catch (Exception $e) {
            if ($e->getCode() == '23000' && isset($urlData['identifire'])) {
                $urlData['identifire'] = $urlData['options'].'_'
                    .$urlData['identifire'];
                $this->_updateUrlRewrite($urlData);
            }

            Mage::log(
                'cron url rewrite: '. implode(', ', $urlData)
                .' message: '. $e->getMessage(),
                null,
                $this->_logfile
            );

        }
    }




}
