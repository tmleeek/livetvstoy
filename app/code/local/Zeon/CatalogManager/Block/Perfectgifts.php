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
 * Do not edit or add to this file if you wish to upgrade this extension to
 * newer versions in the future. If you wish to customize this extension for
 * your needs please refer to http://www.zeonsolutions.com for more information.
 *
 * @category    Zeon
 * @package     Zeon_CatalogManager
 * @author      Suhas Dhoke <suhas.dhoke@zeonsolutions.com>
 * @copyright   Copyright (c) 2014 Zeon Solutions, Inc. All Rights Reserved.
 *              (http://www.zeonsolutions.com)
 * @license     http://www.zeonsolutions.com/license/
 */
class Zeon_CatalogManager_Block_Perfectgifts
    extends Mage_Core_Block_Template
{
    /**
     * Class variables.
     */
    protected $_categoryCollection;

    /**
     * Method used to get perfect gifts category collection.
     *
     * @return Object
     */
    public function getPerfectGiftsCategoriesCollection()
    {
        // Check, whether the feature is enable or disabled.
        $isActive = Mage::getStoreConfig(
            'zeon_catalogmanager/perfect_gifts/active'
        );

        // If the feature is disabled then return blank.
        if ($isActive) {
            return $this->_getCategoryCollection();
        }
    }

    /**
     * Method to load the category collection.
     * @return type
     */
    protected function _getCategoryCollection()
    {
        $categoryArray = array();
        // Check, if the collection var is null then query to database.
        if (is_null($this->_categoryCollection)) {

            $recordLimit = Mage::getStoreConfig(
                'zeon_catalogmanager/perfect_gifts/number_of_items'
            );
            $categories = Mage::getSingleton('catalog/category')->getCollection()
                ->addAttributeToSelect(
                    array(
                        'name',
                        'entity_id',
                        'is_active',
                        'image',
                        'thumbnail',
                        'perfect_gift_category',
                        'best_seller_category'
                    )
                )
                ->addAttributeToFilter('level', 2)//2 is actually the first level
                ->addAttributeToFilter('perfect_gift_category', 1)
                ->addAttributeToFilter('is_active', 1)
                ->setPageSize($recordLimit);
            $catindex = 0;
            foreach ($categories as $category) {

                $category->setUrl($category->getURL());
                $categoryArray[$catindex]['parent'] = $category->getData();

                //getting child categories
                /*Returns comma separated ids*/
                $subcats = $category->getChildren();
                $subCatIndex = 0;
                foreach(explode(',',$subcats) as $subCatid)
                {
                    $_subCategory = Mage::getModel('catalog/category')->load($subCatid);
                    if($_subCategory->getIsActive())
                    {
                        $caturl     = $_subCategory->getURL();
                        $catname     = $_subCategory->getName();
                        $categoryArray[$catindex]['child'][$subCatIndex]['name'] = $catname;
                        $categoryArray[$catindex]['child'][$subCatIndex]['url']  = $caturl;

                        $subCatIndex++;
                    }
                }
                $catindex++;
            }
        }
        // Return the best seller product collection.
        return $categoryArray;
    }

}