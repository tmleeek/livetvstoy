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
class Aitoc_Aitexporter_Helper_Version extends Mage_Core_Helper_Abstract
{
    public function collectionMainTableAlias()
    {
        if (version_compare(Mage::getVersion(), '1.4.1.0') < 0)
        {
            return 'e';
        }

        return 'main_table';
    }
    
    public function isPaymentTransactionsExist()
    {
        if (version_compare(Mage::getVersion(), '1.4.1.0') < 0)
        {
            return false;
        }

        return true;
    }
}