<?php
/**
 * Zeon
 * Characters module
 *
 * @category   Zeon
 * @package    Zeon_Characters
 * @copyright  Copyright (c) 2008 Zeon Solutions.
 * @license    http://www.opensource.org/licenses/gpl-3.0.html GNU General Public License version 3
 */
class Zeon_Attributemapping_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Method used to get the config setting of the passed field.
     *
     * @param String $configName
     *
     * @return Array
     */
    public function getConfigDetails($configName, $urlmanage = 0)
    {
        if ($urlmanage) {
            return Mage::getStoreConfig(
                'zeon_attributemapping/url_rewritemanage/' . $configName
            );
        }
        return Mage::getStoreConfig(
            'zeon_attributemapping/top_menu/' . $configName
        );
    }

    /**
     * Method used to get the config setting of the passed field.
     *
     * @param String $configName
     *
     * @return Array
     */
    public function getConfigData($configName)
    {
        return Mage::getStoreConfig('zeon_attributemapping/' . $configName);
    }

    public function getBlockNames()
    {
        return array(
            'category',
            'character',
            'shop_by_age',
            'brand',
            'personalized_gifts',
            'clearance'
        );
    }

    public function getBlockIdentifier($blockName)
    {
        return 'topmenu_'.$blockName.'_adv_block';
    }

    public function getTopStore()
    {
        $stores = Mage::app()->getStores();
        $storeData = array();
        foreach ($stores as $store) {
            $storeData[] = $store->getId();
        }
        sort($storeData);
        return reset($storeData);
    }

    public function getCharactersListAll()
    {
        $characterlist =  Mage::getSingleton(
            'zeon_attributemapping/attributemapping'
        )->getScrollerData();
        return $characterlist;
    }

    public function getAttributeUrl($optionId, $requestPath='')
    {
        if (!$requestPath) {
            return Mage::getUrl(
                'attributemapping/index/index',
                array('id'=> $optionId)
            );
        } else {
            return Mage::getBaseUrl().$requestPath;
        }
    }
}
