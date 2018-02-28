<?php
        //Following 3 lines will load the magento admin environment.
        require_once 'app/Mage.php';
        $app = Mage::app('admin');
        umask(0);

        // to reindex the processes we require their ids
        // for default magento there are 9 processes to reindex, numbered 1 to 9. 
        //$ids = array(1,2,3,4,5,6,7,8,9);

        // Sometimes there are processes from our custom modules that also require reindexing
        // We need to add those ids to our existing array of ids
        // To know the id of process, just hover on each process in your
        // admin panel-> System-> Index Management
        // You will get a url : admin/process/some_id/......
        // this id corresponds to the process
        $success = false;
        //$ids = array(1,2,3,4,5,6,7,8,9,390,391,478);
        $i = 0;
        //$ids = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,31,32,33);
        $ids = array(1,2,3,4,5,6,7,8,9,31,32,33);
        foreach($ids as $id) {
                //load each process through its id
                try
                {
                        $process = Mage::getModel('index/process')->load($id);
                        $process->reindexAll();
                        echo "Indexing for Process ID # ".$id." Done\n";
                        $i++;
                }
                catch(Exception $e)
                {
                        echo $e->getMessage();
                }
        }

