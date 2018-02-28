<?php
/**
 *
 * @category    Zeon
 * @package     Zeon_Zendcache
 * @author      Zeon Magento Team <kamlesh.kamble@zeonsolutions.com>
 */
class Zeon_Zendcache_Model_Crawler extends Enterprise_PageCache_Model_Crawler
{
    /**
     * Set resource model
     */
    protected function _construct()
    {
       parent::_construct();
    }

    /**
     * Crawl all custom urls
     *
     * @return Zeon_Zendcache_Model_Crawler
     */
    public function customCrawl()
    {
        if (!Mage::app()->useCache('full_page')) {
            return $this;
        }

        $storesInfo  = $this->getStoresInfo();
        $adapter     = new Varien_Http_Adapter_Curl();

        foreach ($storesInfo as $info) {

            $options = array(CURLOPT_USERAGENT => parent::USER_AGENT);
            $storeId = $info['store_id'];
            $this->_visitedUrls = array();

            if (!Mage::app()->getStore($storeId)->getConfig(parent::XML_PATH_CRAWLER_ENABLED)) {
                continue;
            }

            $threads = (int)Mage::app()->getStore($storeId)->getConfig(parent::XML_PATH_CRAWLER_THREADS);
            if (!$threads) {
                $threads = 1;
            }
            if (!empty($info['cookie'])) {
                $options[CURLOPT_COOKIE] = $info['cookie'];
            }
            $urls       = array();
            $baseUrl    = $info['base_url'];
            $urlsCount  = $totalCount = 0;

            $urlsPaths  = Mage::getResourceModel('zendcache/crawler')->getCustomUrlsPaths($storeId);

            foreach ($urlsPaths as $urlPath) {
                $url = $baseUrl . $urlPath;
                $urlHash = md5($url);
                if (isset($this->_visitedUrls[$urlHash])) {
                    continue;
                }
                $urls[] = $url;
                $this->_visitedUrls[$urlHash] = true;
                $urlsCount++;
                $totalCount++;
                if ($urlsCount == $threads) {
                    $adapter->multiRequest($urls, $options);
                    $urlsCount = 0;
                    $urls = array();
                }
            }
            if (!empty($urls)) {
                $adapter->multiRequest($urls, $options);
            }
        }
        return $this;
    }
}