<?php

Mage::helper('ewcore/cache')->clean();
$installer = $this;
$installer->startSetup();

$command = "";
$command = preg_replace_callback('/(EXISTS\s+`)([a-z0-9\_]+?)(`)/i',
                                 function ($m) { return $m[1] . $this->getTable($m[2]) . $m[3];}, $command);
$command = preg_replace_callback('/(ON\s+`)([a-z0-9\_]+?)(`)/i',
                                 function ($m) { return $m[1] . $this->getTable($m[2]) . $m[3];}, $command);
$command = preg_replace_callback('/(REFERENCES\s+`)([a-z0-9\_]+?)(`)/i',
                                 function ($m) { return $m[1] . $this->getTable($m[2]) . $m[3];}, $command);
$command = preg_replace_callback('/(TABLE\s+`)([a-z0-9\_]+?)(`)/i',
                                 function ($m) { return $m[1] . $this->getTable($m[2]) . $m[3];}, $command);
$command = preg_replace_callback('/(INTO\s+`)([a-z0-9\_]+?)(`)/i',
                                 function ($m) { return $m[1] . $this->getTable($m[2]) . $m[3];}, $command);
$command = preg_replace_callback('/(FROM\s+`)([a-z0-9\_]+?)(`)/i',
                                 function ($m) { return $m[1] . $this->getTable($m[2]) . $m[3];}, $command);


if ($command) $installer->run($command);
$installer->endSetup(); 

// [[if normal]]
try {
	$incompatModules = array('Netzarbeiter_NicerImageNames', 'Fooman_Speedster', 'GT_Speed', 'Magefox_Minify', 'Apptrian_Minify', 'Fooman_SpeedsterEnterprise', 'Fooman_SpeedsterAdvanced', 'Diglin_UIOptimization', 'Jemoon_Htmlminify');
	foreach ($incompatModules as $module) {
		$model = Mage::getSingleton('ewcore/module');
		if (!$model) continue;
		
		$module = $model->load($module);
		if ($module->isActive() === false) continue;
		
		Mage::getModel('compiler/process')->registerIncludePath(false);
		$configTools = Mage::helper('ewcore/config_tools');
		if ($configTools) $configTools->disableModule($module->getId());
	}
} catch (Exception $e) {}
// [[/if]]