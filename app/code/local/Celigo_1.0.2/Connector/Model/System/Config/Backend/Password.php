<?php
class Celigo_Connector_Model_System_Config_Backend_Password extends Mage_Core_Model_Config_Data
{
    const XML_PATH_NETSUITE_USER_PASSWORD        = 'connector/nsdetails/nspassword';
    const PASSWORDS_REQUIRED_MSG   = 'Password fields are required';
    const PASSWORDS_MISMATCH_MSG   = 'Please make sure your Passwords match';
	const VALID_NETSUITE_CREDENTIAL_MSG = 'Congratulations! Your NetSuite details are correct';
	const MISSING_DETAILS_MSG = 'Please enter NetSuite Details (Email / Password / Account ID / Role ID / Evinronment)';
		
    /**
     * Decrypt value after loading
     *
     */
    protected function _afterLoad()
    {
        $value = (string)$this->getValue();
        if (!empty($value) && ($decrypted = Mage::helper('core')->decrypt($value))) {
            $this->setValue($decrypted);
        }
    }
	
    /**
     * Validate and Encrypt value before saving
     *
     */
    protected function _beforeSave()
    {
        $value     = $this->getValue();
        $password = $this->getData('groups/nsdetails/fields/nspassword/value');
		
		if (trim($value) == '' || trim($password) == '') {
			throw new Exception(Mage::helper('connector')->__(self::PASSWORDS_REQUIRED_MSG));
		}
		
		if ($value != $password) {
			throw new Exception(Mage::helper('connector')->__(self::PASSWORDS_MISMATCH_MSG));
			$this->setValue('');
			$this->setData('groups/nsdetails/fields/nspassword/value', '');
		} else {
		
			$value = (string)$this->getValue();
			// don't change value, if an obscured value came
			if (preg_match('/^\*+$/', $this->getValue())) {
				$value = $this->getOldValue();
			}
			if (!empty($value) && ($encrypted = Mage::helper('core')->encrypt($value))) {
				$this->setValue($encrypted);
			}
		}
		
		$isactive = $this->getData('groups/magentoconnector/fields/active/value');

		if ($isactive) {
			$result = self::MISSING_DETAILS_MSG;
			$nsemail = $this->getData('groups/nsdetails/fields/nsemail/value');
			$nspassword = $value; //$this->getData('groups/nsdetails/fields/nspassword/value');
			$nsrole = $this->getData('groups/nsdetails/fields/nsrole/value');
			$nsaccount = $this->getData('groups/nsdetails/fields/nsaccountid/value');
			$nsenvironment = $this->getData('groups/nsdetails/fields/nsenvironment/value');
			
			if ($nsemail != '' && $nspassword != '' 
					&& $nsaccount != '' && $nsrole != '' && $nsenvironment != '') {
				
				$result = Mage::helper('connector')->validateNetsuiteCredentials($nsemail, $nspassword, $nsaccount, $nsrole, $nsenvironment, '', '');
			}
			
			if ($result === true) {
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('connector')->__(self::VALID_NETSUITE_CREDENTIAL_MSG));		
			} else {
				throw new Exception(Mage::helper('connector')->__($result));
			}
			
		}		
		
        return $this;
    }
	
	
    /**
     * Get & decrypt old value from configuration
     *
     * @return string
     */
    public function getOldValue()
    {
        return Mage::helper('core')->decrypt(parent::getOldValue());
    }
	
    /**
     * Make the password field balnk if password doesn't match with confirm password
     *
     * @return Celigo_Connector_Model_System_Config_Backend_Password
     */
    public function _afterSave()
    {
        $value = $this->getValue();

		if ($value == "") {

            Mage::getConfig()->saveConfig(
                self::XML_PATH_NETSUITE_USER_PASSWORD,
                '',
                $this->getScope(),
                $this->getScopeId()
            );
		}

        return $this;
    }
}