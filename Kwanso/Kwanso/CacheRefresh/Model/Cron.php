<?php

class Kwanso_CacheRefresh_Model_Cron
{
	static public function cacheRefresh()
	{
		$moduleStatus = Mage::getStoreConfig('cacherefresh/general/enable');

		if($moduleStatus):
			$cacheTypes = Mage::getStoreConfig('cacherefresh/general/cache_types');
        	$cacheTypes = explode(",",$cacheTypes);
			$invalidatedTypes = Mage::app()->getCacheInstance()->getInvalidatedTypes();

			foreach($invalidatedTypes as $value) {
			    $invalidCache[] = $value["id"];
			}

        	foreach ($cacheTypes as $key => $type):
        		if(in_array($type, $invalidCache)) {
        			if($type == 'full_page') {
	        			Enterprise_PageCache_Model_Cache::getCacheInstance()->cleanType($type);
	        		} else {
	        			Mage::app()->getCacheInstance()->cleanType($type);
	        		}
        		}
        	endforeach;

		endif;
	}
}