<?php
/**
 * Resource connection
 *
 * @category    Zeon
 * @package     Zeon_Performance
 * @copyright   Zeon Solutions. (http://www.zeonsolutions.com)
 * @author      Mukesh Patel <mukesh.patel@zeonsolutions.com>
 * @since       Tuesday 18 Jan, 2014
 */

/**
 * Resources and connections registry and factory
 *
 */
class Zeon_Performance_Model_Resource extends Mage_Core_Model_Resource
{
       
    /**
     * Creates a connection to resource whenever needed
     *
     * @param string $name
     * @return Varien_Db_Adapter_Interface
     */
    public function getConnection($name)
    {
        
        $connection = parent::getConnection($name);
        $connection->getProfiler()->setEnabled(true);
        return $connection;
        
    }

}
