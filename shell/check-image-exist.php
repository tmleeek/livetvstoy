<?php
/**
 * Script for Checking Whether Product Image is exist or not on server
 *
 * @author Nilesh Tighare <nilesh.tighare@perficient.com>
 */
require_once 'abstract.php';
class Perficient_Check_ImageExist extends Mage_Shell_Abstract
{
    /** @var Mage_Eav_Model_Entity_Attribute */
    protected $_attr;
    /** @var string */
    protected $_table;
    /** @var string */
    protected $_entityTable;
    /** @var Mage_Eav_Model_Entity_Attribute */
    protected $_attr1;
    /** @var string */
    protected $_table1;
    /** @var string */
    protected $_entityTable1;
    /** @var Mage_Eav_Model_Entity_Attribute */
    protected $_attr2;
    /** @var string */
    protected $_table2;
    /** @var string */
    protected $_entityTable2;
    protected $_websiteId = 5; //only for limoges store
    protected $_limit = 21000;//limiting for 21000 products


    protected $_connection;
    /** @var array */
    protected $_skus;
    /** @var int */
    protected $_sentry;
    /** @var array */
    protected $_newKeys;
    public function run()
    {
        //$this->_showHelp();
        $imagesProd = $this->_getImagesInfo('products');
    }
    protected function _initMode($mode='products') {
        if ($mode === 'categories') {
            $this->_attr = Mage::getSingleton('eav/config')->getAttribute(Mage_Catalog_Model_Category::ENTITY, 'image');
            $this->_table = Mage::getSingleton('core/resource')->getTableName('catalog_category_entity_varchar');
            $this->_entityTable = Mage::getSingleton('core/resource')->getTableName('catalog_category_entity');
        } else {
            $this->_attr = Mage::getSingleton('eav/config')->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'image');
            $this->_table = Mage::getSingleton('core/resource')->getTableName('catalog_product_entity_varchar');
            $this->_entityTable= Mage::getSingleton('core/resource')->getTableName('catalog_product_entity');
            $this->_attr1 = Mage::getSingleton('eav/config')->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'small_image');
            $this->_table1 = Mage::getSingleton('core/resource')->getTableName('catalog_product_entity_varchar');
            $this->_entityTable1= Mage::getSingleton('core/resource')->getTableName('catalog_product_entity');
            $this->_attr2 = Mage::getSingleton('eav/config')->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'thumbnail');
            $this->_table2 = Mage::getSingleton('core/resource')->getTableName('catalog_product_entity_varchar');
            $this->_entityTable2= Mage::getSingleton('core/resource')->getTableName('catalog_product_entity');
        }
    }
    protected function _getImagesInfo($mode)
    {
        $this->_initMode($mode);
        $this->_connection = Mage::getSingleton('core/resource')->getConnection('eav_write');
        $select = $this->_connection->select()
            ->from(array('e'=>$this->_entityTable), array('e.sku'))
            ->joinInner(array('cpw'=> Mage::getSingleton('core/resource')->getTableName('catalog_product_website')), 'e.entity_id = cpw.product_id and cpw.website_id ='.$this->_websiteId)
            ->joinLeft(array('u'=>$this->_table), 'e.entity_id = u.entity_id and u.attribute_id ='.$this->_attr->getId(), array($this->_attr->getAttributeCode() => 'value'))
            ->joinLeft(array('v'=>$this->_table1), 'e.entity_id = v.entity_id and v.attribute_id ='.$this->_attr1->getId(), array($this->_attr1->getAttributeCode() => 'value'))
            ->joinLeft(array('w'=>$this->_table2), 'e.entity_id = w.entity_id and w.attribute_id ='.$this->_attr2->getId(), array($this->_attr2->getAttributeCode() => 'value'));
        Mage::log(__METHOD__, null, 'urlkeysfix.log', true);Mage::log((String)$select, null, 'urlkeysfix.log', true);
        $prodDetails = $this->_connection->fetchAll($select);
        if(empty($prodDetails)) {
            return;
        }
        Mage::log($prodDetails, null, 'urlkeysfix.log', true);
        $productDataArr = array();
        $imageDir = Mage::getBaseDir('media').DS.'catalog'.DS.'product'.DS;
        $imageFound = ' Image Found';
        $imageNotFound = ' Image Not Found';
        $set_limit = 0;$limit = $this->_limit;
        //BOF create csv file
        $ioDVObj = new Varien_Io_File();
        $path = Mage::getBaseDir('var') . DS . 'export' . DS;
        $name = 'check-image-exist';
        $file = $path .''.$name.'.csv';
        $ioDVObj->open(array('path' => $path));
        $ioDVObj->streamOpen($file, 'w+');
        $ioDVObj->streamLock(true);
        //csv header display
        $outputHeader = array(
            'sku', 'image', 'small_image', 'thumbnail'
        );
        $ioDVObj->streamWriteCsv($outputHeader);
        foreach($prodDetails as $prodDetail) {
            if( $set_limit == $limit )    break;

            $imageFoundFlag = $imageNotFound;
            $smallImageFoundFlag = $imageNotFound;
            $thumbnailFoundFlag = $imageNotFound;
            $image = $prodDetail['image'];
            $smallImage = $prodDetail['small_image'];
            $thumbnail = $prodDetail['thumbnail'];
            $sku = $prodDetail['sku'];
            echo "Verifying Product Images for Sku - $sku\n";
            if (!empty($image) && (@getimagesize($imageDir.$image))) {
                $imageFoundFlag = $imageFound;
            }
            //$image = $imageDir.$image;
            if (!empty($smallImage) && (@getimagesize($imageDir.$smallImage))) {
                $smallImageFoundFlag = $imageFound;
            }
            //$smallImage = $imageDir.$smallImage;
            if (!empty($thumbnail) && (@getimagesize($imageDir.$thumbnail))) {
                $thumbnailFoundFlag = $imageFound;
            }
            //$thumbnail = $imageDir.$thumbnail;

            $productDataArr = array(
                'sku' => $sku,
                'image'=> $image.$imageFoundFlag,
                'small_image' => $smallImage.$smallImageFoundFlag,
                'thumbnail' => $thumbnail.$thumbnailFoundFlag,
            );
            $ioDVObj->streamWriteCsv($productDataArr);
            $set_limit++;
        }

        $ioDVObj->streamUnlock();
        $ioDVObj->streamClose();
        //EOF create csv
    }
}
$shell = new Perficient_Check_ImageExist();
$shell->run();