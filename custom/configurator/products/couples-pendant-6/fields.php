<?php
	
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<fields>
	  <fields type="metal" default="$def_value" label="Select Metal" assoc_step_group="0" assoc_step="0" selector_type="combo"></fields>
	  <fields type="text" default="ALEXIS" label="Left Engraving" assoc_step_group="0" assoc_step="0" assoc_layers="3" selector_type="text" chars="A-Z0-9 "></fields>
      <fields type="stones" default="JAN" label="Birthstone 1" assoc_step_group="0" assoc_step="0" assoc_layers="1" selector_type="combo"></fields>
      <fields type="text" default="DANIEL" label="Right Engraving" assoc_step_group="0" assoc_step="0" assoc_layers="4" selector_type="text" chars="A-Z0-9 "></fields>
      <fields type="stones" default="OCT" label="Birthstone 2" assoc_step_group="0" assoc_step="0" assoc_layers="2" selector_type="combo"></fields>
</fields>
EOT;
?>