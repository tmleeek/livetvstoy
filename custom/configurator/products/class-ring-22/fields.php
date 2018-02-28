<?php
	
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<fields>
	  <fields type="metal" default="$def_value" label="Select Metal &amp; Size" assoc_step_group="0" assoc_step="0" selector_type="combo"></fields>
      <fields type="size" default="" label="Size" assoc_step_group="0" assoc_step="0" selector_type="combo"></fields>
      <fields type="stones" default="JUL" label="Birthstone" assoc_step_group="0" assoc_step="0" assoc_layers="1" selector_type="combo"></fields>
      <fields type="text" default="2018" label="Year" assoc_step_group="0" assoc_step="0" assoc_layers="2" selector_type="text" chars="0-9"></fields>
      <fields type="text" default="PARK" label="Left Text" assoc_step_group="0" assoc_step="0" assoc_layers="3" selector_type="text" chars="A-Z0-9 "></fields>
	  <fields type="text" default="KELLY" label="Right Text" assoc_step_group="0" assoc_step="0" assoc_layers="4" selector_type="text" chars="A-Z0-9 "></fields>
	  
</fields>
EOT;
?>