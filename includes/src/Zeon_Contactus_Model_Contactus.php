<?php
class Zeon_Contactus_Model_Contactus extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('contactus/contactus');
    }

    public function validate()
    {
        $errors = array();
        $helper = Mage::helper('contactus');

        if (!Zend_Validate::is($this->getName(), 'NotEmpty')) {
            $errors[] = $helper->__('Name can\'t be empty');
        }

        if (!Zend_Validate::is($this->getEmail(), 'EmailAddress')) {
            $errors[] = $helper->__('Invalid email address');
        }

        if (empty($errors)) {
            return true;
        }
        return $errors;
    }

    function addError($error)
    {
        $this->_errors[] = $error;
    }

    function getErrors()
    {
        return $this->_errors;
    }

    function resetErrors()
    {
        $this->_errors = array();
    }

    function printError($error, $line = null)
    {
        if ($error == null) return false;
        $img = 'error_msg_icon.gif';
        $liStyle = 'background-color:#FDD; ';
        echo '<li style="'.$liStyle.'">';
        echo '<img src="'.Mage::getDesign()
            ->getSkinUrl('images/'.$img).'" class="v-middle"/>';
        echo $error;
        if ($line) {
            echo '<small>, Line: <b>'.$line.'</b></small>';
        }
        echo "</li>";
    }

    /**
    * function is used to retrive state information
    * @param	int		$id		State ID
    * @return	array			State Value
    */
    function getStateInformation($id)
    {
        $resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('core_read');

        $this->_regionTable = Mage::getSingleton('core/resource')
            ->getTableName('directory/country_region');

        $select = $read->select()
                ->from(array("s" => $this->_regionTable))
                ->where('region_id =(?)', $id);

        return $read->fetchAll($select);
    }

}