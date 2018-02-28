<?php
/**
 *
 * @category    Zeon
 * @package     Zeon_Zendcache
 * @author      Zeon Magento Team <kamlesh.kamble@zeonsolutions.com>
 */
class Zeon_Zendcache_Model_Resource_Crawler extends Enterprise_PageCache_Model_Resource_Crawler
{
    /**
     * Internal constructor
     *
     */
    protected function _construct()
    {
        parent::_construct();
    }

    /**
     * Retrieve Custom URLs paths that must be visited by crawler
     *
     * @param  $storeId
     * @return array
     */
    public function getCustomUrlsPaths($storeId)
    {
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
            ->from($this->getTable('core/url_rewrite'), array('request_path'))
            ->where('store_id=?', $storeId)
            ->where('is_system = 0')
            ->where("target_path LIKE '%attributemapping/index/view/id/%'");
        return $adapter->fetchCol($select);
    }
}