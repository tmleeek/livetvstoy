<?php
	
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<fields>
	  <fields type="metal" default="$def_value" label="Select Metal &amp; Size" assoc_step_group="0" assoc_step="0" selector_type="combo"></fields>
      <fields type="size" default="" label="Size" assoc_step_group="0" assoc_step="0" selector_type="combo"></fields>
      <fields type="stones" default="FEB" label="Birthstone" assoc_step_group="0" assoc_step="0" assoc_layers="1" selector_type="combo"></fields>
      <fields type="text" default="JULIE" label="eLeft Text" assoc_step_group="0" assoc_step="0" assoc_layers="2" selector_type="text" chars="A-Z0-9 "></fields>
      <fields type="text" default="2019" label="Graduation Year" assoc_step_group="0" assoc_step="0" assoc_layers="3-4" selector_type="text" chars="0-9"></fields>
      <fields type="text" default="HANOVER HS" label="School Name" assoc_step_group="0" assoc_step="0" assoc_layers="5" selector_type="text" chars="A-Z0-9 "></fields>
      <fields type="text" default="BAND" label="Inside Text" assoc_step_group="0" assoc_step="0" assoc_layers="6" selector_type="text" chars="A-Z0-9 "></fields>
	  
</fields>
EOT;
?>