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
 * @category    Fqs
 * @package     Fqs_Shopby
 * @copyright   Copyright (c) 2012 Zeon Solutions, Inc.
 * All Rights Reserved.(http://www.zeonsolutions.com)
 * @license     http://www.zeonsolutions.com/license/
 */

/**
 * Zeon Attributemapping Model System Config Source Maincategory
 *
 * @author      Zeon Team <vidisha.ganjre@zeonsolutions.com>
 */
class Zeon_Attributemapping_Model_System_Config_Source_Maincategory
{
    /**
     * get category listing
     */
    public function toOptionArray()
    {
        $collection = Mage::getModel('catalog/category')->getCollection()
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('url_key')
            ->addAttributeToFilter('level', '2')
            ->addAttributeToFilter('is_active', array('eq'=>true))
            ->load();

        $options = array();

        $options[] = array(
            'label' => Mage::helper('adminhtml')->__(
                '-- Please Select a Category --'
            ),
            'value' => ''
        );

        foreach ($collection as $category) {
            $options[] = array(
               'label' => $category->getName(),
               'value' => $category->getId()
            );
        }

        return $options;
    }
}