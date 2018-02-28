<?php
/**
 * Zend Cache
 * @category    Zeon
 * @package     Zeon_Zendcache
 * @author      Zeon Magento Team <kamlesh.kamble@zeonsolutions.com>
 */
class Zeon_Zendcache_Helper_Data extends Mage_Core_Helper_Abstract
{
    const BACKEND = 'memcached';
    const FRONTEND = 'Output';
    const FRONTEND_OPTION = 'File';
    const CACHE_PATH = 'cache';
    const CACHE_DIRECTORY = 'zcache';
    const CACHE_LIFETIME = 86400;
    const CACHE_CONFIG = 'global/full_page_cache';

    public function generateCacheId()
    {
        $moduleName = Mage::app()->getRequest()->getModuleName();
		$controllerName = Mage::app()->getRequest()->getControllerName();
		$actionName = Mage::app()->getRequest()->getActionName();
		$value = strtoupper($moduleName.$controllerName.$actionName);

        // front end options, cache for 1 minute
        $frontendOptions = array('lifetime' => self::CACHE_LIFETIME);

        $cacheConfiguration = Mage::getConfig()->getNode(self::CACHE_CONFIG);
        $backend     = (string) $cacheConfiguration->backend;

        if ($backend == self::BACKEND) {
            $host        = (string) $cacheConfiguration->memcached->servers->server->host;
            $port        = (string) $cacheConfiguration->memcached->servers->server->port;
            $weight      = (string) $cacheConfiguration->memcached->servers->server->weight;
            $compression = (string) $cacheConfiguration->memcached->compression;

            $backendOptions = array(
            	'servers' =>array(
                    array(
                    	'host'   => $host,
                    	'port'   => $port,
                    	'weight' => $weight)
                    ),
                'compression' => $compression
            );

            $cache = Zend_Cache::factory(self::FRONTEND, self::BACKEND, $frontendOptions, $backendOptions);
        } else {
            $baseDir = Mage::getBaseDir(self::CACHE_PATH);
            if (!is_dir($baseDir . DS . self::CACHE_DIRECTORY)) {
                mkdir($baseDir . DS . self::CACHE_DIRECTORY, 0777);
            }

            // backend options
            $backendOptions = array(
            	'cache_dir' => Mage::getBaseDir(self::CACHE_PATH) . DS . self::CACHE_DIRECTORY // Directory where to put the cache files
            );

            $cache = Zend_Cache::factory(self::FRONTEND, self::FRONTEND_OPTION, $frontendOptions, $backendOptions);
        }

        $cacheSubKey = Mage::app()->getStore()->getStoreId()
            . '_' . Mage::getSingleton('customer/session')->getCustomerGroupId()
            . '_' . Mage::app()->getRequest()->getRequestUri();

        if ($params = Mage::app()->getRequest()->getParams()) {
            foreach ($params as $k=>$v) {
                $urlParams[] = $k . '=' . urlencode($v);
            }
            $cacheSubKey .= '_' . implode('_', $urlParams);
        }

        //$cacheId = 'CATEGORYLIST_' . md5($cacheSubKey);
        $cacheId = $value.'_' . md5($cacheSubKey);

        if (Mage::app()->getRequest()->getParam('REFRESH_'.strtoupper($value))) {
            $cache->remove($cacheId);
        }

        return array ('cache' => $cache, 'cache_id' => $cacheId);
    }
}