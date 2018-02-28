<?php
        // initialize Magento environment
        include_once "app/Mage.php";
        Mage::app('admin')->setCurrentStore(0);
        Mage::app('default');

        $indexes = array("catalog_product_attribute", "catalogsearch_fulltext", "tag_summary", "mana_db_replicator", "mana_db", "mana_seo_url");

        $processes = array();
        $indexer = Mage::getSingleton('index/indexer');
        foreach ($indexer->getProcessesCollection() as $process) {
           //store current process mode
                $processes[$process->getIndexerCode()] = $process->getMode();

                //echo $process->getIndexerCode(); print "\n";

                //set it to manual, if not manual yet.


                if($process->getMode() != Mage_Index_Model_Process::MODE_MANUAL){
                        $process->setData('mode','manual')->save();
                }

        }
        //save transaction (I was using transactions, do your stuff, call another method, etc...)
        //$transaction->save();



        /*catalog_product_attribute     Product Attributes
        catalogsearch_fulltext        Catalog Search Index
        tag_summary                   Tag Aggregation Data
        mana_db_replicator            Default Values (MANAdev)
        mana_db                       SEO Schemas (MANAdev)
        mana_seo_url                  SEO URL Rewrites (MANAdev)*/

