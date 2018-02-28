<?php
$installer = $this;
$installer->startSetup();

	$installer->run(" 
        DROP TABLE IF EXISTS {$this->getTable('onestepcheckout_survey')}; 			    			
		CREATE TABLE {$this->getTable('onestepcheckout_survey')}(
			`survey_id` int(11) unsigned NOT NULL auto_increment,
			`question` varchar(255) default '',			 
			`answer` varchar(255) default '',			 
		    `order_id` int(10) unsigned NOT NULL,		   			  		   
		    PRIMARY KEY (`survey_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	");
$installer->getConnection()->addColumn(
    $this->getTable('sales_flat_order'), 'onestepcheckout_giftwrap_amount', "DECIMAL( 12, 4 )");
$installer->endSetup(); 