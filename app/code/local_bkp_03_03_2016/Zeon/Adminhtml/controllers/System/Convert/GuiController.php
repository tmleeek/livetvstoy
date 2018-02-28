<?php
require_once 'Mage'.DS.'Adminhtml'.DS.'controllers'.DS.'System'.DS.'Convert'.DS.'GuiController.php';
class Zeon_Adminhtml_System_Convert_GuiController
    extends Mage_Adminhtml_System_Convert_GuiController
{
    public function runAction()
    {
        ini_set('memory_limit','1024M');
        ini_set('max_execution_time', '36000');

        $this->_initProfile();
        $this->loadLayout();
        $this->renderLayout();
    }
     public function addNewColumnAction()
    {
       $post = $this->getRequest();
       $wId = $post->getParam('wid');
       $eId = $post->getParam('eid');
       $oId = $post->getParam('oid');
       $resource = Mage::getSingleton('core/resource');
      $writeConnection = $resource->getConnection('core_write');
            if($wId=='')
            {
                 $query_del = "delete FROM vtrio_warehouse_details WHERE order_id = '$oId' and entity_id='$eId'";
                 if($writeConnection->query($query_del))
                echo "success";
                else
                echo "error";
            }
            else
            {
            $query_sel = "SELECT *  FROM vtrio_warehouse_details WHERE 	order_id = '$oId' and entity_id='$eId'";
            $results = $writeConnection->fetchAll($query_sel);
            
	if(count($results)==0)
        $query = "INSERT INTO vtrio_warehouse_details(order_id,entity_id,warehouse_id) values($oId,$eId,$wId)";
        else
        $query = "update vtrio_warehouse_details set warehouse_id=$wId WHERE order_id = '$oId' and entity_id='$eId'";
        if($writeConnection->query($query))
        echo "success";
        else
        echo "error";
            }
    }
}
