<?php

class Kwanso_CacheRefresh_Model_System_Config_Source_Types
{
    public function toOptionArray()
    {
        $allCacheTypes = Mage::app()->getCacheInstance()->getTypes();
        $options = array();
        foreach ($allCacheTypes as $key => $cache) {
            $options[] = array(
                'value' => $cache->getId(),
                'label' => $cache->getCacheType()
            );
        }

        return $options;
    }
}
