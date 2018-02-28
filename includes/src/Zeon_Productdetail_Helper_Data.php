<?php
/**
 * Zeon
 * Characters module
 *
 * @category   Zeon
 * @package    Zeon_Productdetail
 * @copyright  Copyright (c) 2008 Zeon Solutions.
 * @license    http://www.opensource.org/licenses/gpl-3.0.html GNU General Public License version 3
 */
class Zeon_Productdetail_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Method used to get the config setting of the passed field.
     *
     * @param String $configName
     *
     * @return Array
     */
    public function getConfigDetails($configName)
    {
        return Mage::getStoreConfig('zeon_productdetail/' . $configName);
    }

}
