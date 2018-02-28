<?php
class Celigo_Connector_Helper_Extensionconflict extends Mage_Core_Helper_Abstract
{
	const COFLICTS_ERROR_MSG = 'There are conflicts with other extensions. Please contact Celigo Support.';
	/**
	 * Refresh list
	 *
	 */
	public function RefreshList()
	{
		//retrieve all config.xml
		$tConfigFiles = $this->getConfigFilesList();
		
		//parse all config.xml
		$rewrites = array();
		foreach ($tConfigFiles as $configFile) {
			$rewrites = $this->getRewriteForFile($configFile, $rewrites);
		}
		
		$conflicts = array();
		$description = '<table cellspacing="0" id="ExtensionConflictGrid_table" class="data">
  <colgroup>
  <col>
  <col>
  <col>
  </colgroup>
  <thead>
    <tr class="headings">
      <th><span class="nobr"><span class="sort-title">Core Module</span></span></th>
      <th><span class="nobr"><span class="sort-title">Core Class</span></span></th>
      <th><span class="nobr"><span class="sort-title">Rewrite Classes</span></span></th>
    </tr>
  </thead>
  <tbody>';
  
		$isEnterpriseEdition = false;
		$mageObj = new Mage();
		if (method_exists($mageObj, 'getEdition')) {
			$edition = Mage::getEdition();
			if ($edition == Mage::EDITION_ENTERPRISE) {  
				$isEnterpriseEdition = true;
			}
		}
  
		foreach ($rewrites as $key => $value) {
			$t = explode('/', $key);
			$moduleName = $t[0];
			$className = $t[1];
			$value = array_unique($value);

			if (count($value) > 1) {
				switch($key) {
					case 'catalog/product_api':
					case 'sales/order_invoice_api':
					//case 'adminhtml/sales_order_view':
						$conflicts[$key] = $value;	
						$description .= '<tr title="#" class="even pointer"><td class=" "> '. $moduleName .' </td>';
						$description .= '<td class=" "> '. $className .' </td>';
						$description .= ' <td class=" "> '. join('<br/> ', $value) .'</td></tr>';
						break;
					case 'adminhtml/sales_order_grid':
						if($isEnterpriseEdition) {
							$conflicts[$key] = $value;	
							$description .= '<tr title="#" class="even pointer"><td class=" "> '. $moduleName .' </td>';
							$description .= '<td class=" "> '. $className .' </td>';
							$description .= ' <td class=" "> '. join('<br/>', $value) .'</td></tr>';
							break;
						}
				}
			}
		}

		if (count($conflicts) > 0) {
			$description .= '</tbody></table><br/>';
			$title = self::COFLICTS_ERROR_MSG;
			Mage::getModel('adminnotification/inbox')->add(4, $title, $description, '', true);
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('connector')->__($title));
			return true;
		}
		
		return false;
	}

	/**
	 * create an array with all config.xml files
	 *
	 */
	private function getConfigFilesList()
	{
		$retour = array();
		$codePath = Mage::getBaseDir('app') . '/code';
		
		$locations = array();
		$locations[] = $codePath.'/local/';
		$locations[] = $codePath.'/community/';
		
		foreach ($locations as $location) {
			//parse every sub folders (means extension folders)
			$poolDir = opendir($location);
			while($namespaceName = readdir($poolDir)) {
				if (!$this->directoryIsValid($namespaceName))
					continue;
					
				//parse modules within namespace
				$namespacePath = $location.$namespaceName.'/';
				try {
					$namespaceDir = opendir($namespacePath);
				} catch (Exception $e) {
					continue;
				}
				while($moduleName = readdir($namespaceDir))
				{
					if (!$this->directoryIsValid($moduleName))
						continue;
					
					$modulePath = $namespacePath.$moduleName.'/';
					$configXmlPath = $modulePath.'etc/config.xml';
					
					if (file_exists($configXmlPath))
						$retour[] = $configXmlPath;
				}
				closedir($namespaceDir);
				
			}
			closedir($poolDir);
		}
		
		return $retour;
	}
	
	/**
	 * 
	 *
	 * @param unknown_type $dirName
	 * @return unknown
	 */
	private function directoryIsValid($dirName)
	{
		switch ($dirName) {
			case '.':
			case '..':
			case '':
				return false;
				break;		
			default:
				return true;
				break;
		}
	}
	
	private function manageModule($moduleName)
	{
		switch ($moduleName) {
			case 'global':
				return false;
				break;		
			default:
				return true;
				break;
		}		
	}
	
	/**
	 * Return all rewrites for a config.xml
	 *
	 * @param unknown_type $configFilePath
	 */
	private function getRewriteForFile($configFilePath, $results)
	{
		try {
			//load xml
			$xmlcontent = file_get_contents($configFilePath);
			$domDocument = new DOMDocument();
			if(trim($xmlcontent) == "") {
				return $results;
			}
			$domDocument->loadXML($xmlcontent);
			
			$moduleFullName = '';
			
			foreach ($domDocument->documentElement->getElementsByTagName('version') as $markup) {
				$moduleFullName = $markup->parentNode->tagName;
			}
			
			foreach ($domDocument->documentElement->getElementsByTagName('rewrite') as $markup)
			{
				//parse child nodes
				$moduleName = $markup->parentNode->tagName;
				if ($this->manageModule($moduleName)) {
					foreach($markup->getElementsByTagName('*') as $childNode) {
						//get information
						$className = $childNode->tagName;
						$rewriteClass = $childNode->nodeValue; 
						
						if($moduleFullName == "" ) {
							$classNames = explode("_", $rewriteClass, Zend_Log::ERR);
							if(isset($classNames[2])) {
								unset($classNames[2]);
							}
							$moduleFullName = implode("_", $classNames);
						}
						
						if (!$this->isModuelEnabled($moduleFullName)) {
							continue;
						}
						
						//add to result
						$key = $moduleName.'/'.$className;
						if (!isset($results[$key]))
							$results[$key] = array();
						$results[$key][] = $rewriteClass;
						
					}
				}
			}
		} catch (Exception $e) {
			// Mage::log($e->getMessage());
		}
		return $results;
	}
	
	private function isModuelEnabled($moduleFullName = '')
	{
		if ($moduleFullName != '') {
			$modules = (array)Mage::getConfig()->getNode('modules')->children();
			
			if (isset($modules[$moduleFullName]) && $modules[$moduleFullName]->is('active')) {
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Refresh list
	 *
	 */
	public function getConflictListByCalssName($calssName = '')
	{
		//retrieve all config.xml
		$tConfigFiles = $this->getConfigFilesList();
		
		//parse all config.xml
		$rewrites = array();
		foreach($tConfigFiles as $configFile) {
			$rewrites = $this->getRewriteForFile($configFile, $rewrites);
		}
		
		if (isset($rewrites[$calssName])) {
			return $rewrites[$calssName];
		}
		
		return $rewrites;
	}
	
}
?>