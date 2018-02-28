<?php
	// initialize Magento environment
	include_once "app/Mage.php";
	Mage::app('admin')->setCurrentStore(0);
	Mage::app('default');
	
	$processes = array();
	$indexer = Mage::getSingleton('index/indexer');
	foreach ($indexer->getProcessesCollection() as $process) {
    	//store current process mode
    	$processes[$process->getIndexerCode()] = $process->getMode();

    	echo $process->getIndexerCode(); print "\n";

    	//set it to manual, if not manual yet.
    	/*if($process->getMode() != Mage_Index_Model_Process::MODE_MANUAL){
        	$process->setData('mode','manual')->save();
    	}*/
	}
	//save transaction (I was using transactions, do your stuff, call another method, etc...)
	//$transaction->save();