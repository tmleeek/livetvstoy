<?php
error_reporting(E_ALL | E_STRICT);
// The Custom Index script for Resolving Homepage | Option3 block issue - require to run catalog_product_flat indexing
require_once('../app/Mage.php');
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
$write = Mage::getSingleton('core/resource')->getConnection('core_write');
$read = Mage::getSingleton('core/resource')->getConnection('core_read');
$indexer = Mage::getSingleton('index/indexer');
$process = $indexer->getProcessByCode('catalog_product_flat');
try {
    $process->reindexEverything();
    Mage::dispatchEvent($process->getIndexerCode() . '_shell_reindex_after');
    echo $process->getIndexer()->getName() . " index was rebuilt successfully\n";
    Mage::dispatchEvent('shell_reindex_finalize_process');
} catch (Mage_Core_Exception $e) {
    echo $e->getMessage() . "\n";
} catch (Exception $e) {
    Mage::dispatchEvent('shell_reindex_finalize_process');
    echo $process->getIndexer()->getName() . " index process unknown error:\n";
    echo $e . "\n";
}
die("DONE");
?>