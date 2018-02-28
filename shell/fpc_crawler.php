<?php
$timeStart = microtime(true);

require_once '/var/www/CPS/public_html/app/Mage.php';
//require '../app/Mage.php';

Mage::app('admin')->setUseSessionInUrl(false);

Mage::log('Start time: ' . $timeStart, null, 'pagecache_crawler.log');

//Clean Cache before running the FPC crawler
//Clean FPC
Enterprise_PageCache_Model_Cache::getCacheInstance()->clean(Enterprise_PageCache_Model_Processor::CACHE_TAG);

Mage::getModel('enterprise_pagecache/crawler')->crawl();

$timeEnd = microtime(true);
Mage::log('End time: ' . $timeEnd, null, 'pagecache_crawler.log');
$time = $timeEnd - $timeStart;
Mage::log('Total execution time for ENTERPRISE_PAGECACHE_CRAWLERL: ' . $time, null, 'pagecache_crawler.log');
