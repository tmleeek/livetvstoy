<?php
	
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<fields>
	  <fields type="metal" default="$def_value" label="Select Metal &amp; Size" assoc_step_group="0" assoc_step="0" selector_type="combo"></fields>
      <fields type="size" default="" label="Size" assoc_step_group="0" assoc_step="0" selector_type="combo"></fields>
      <fields type="stones" default="JAN" label="Birthstone" assoc_step_group="0" assoc_step="0" assoc_layers="1" selector_type="combo"></fields>
      <fields type="text" default="HIGHLAND PARK" label="Top Text 1 Engraving" code="Top Text 1" assoc_step_group="0" assoc_step="0" assoc_layers="5" selector_type="text" chars="A-Z0-9 "></fields>
	  <fields type="text_2" default="HIGH" label="Top Text 2 Engraving" code="Top Text 2" assoc_step_group="0" assoc_step="0" assoc_layers="5" selector_type="text" chars="A-Z0-9 "></fields>
	  <fields type="text" default="2012" label="Left Text" assoc_step_group="0" assoc_step="0" assoc_layers="2" selector_type="text" chars="A-Z0-9 "></fields>
	  <fields type="text" default="Susan" label="Right Text" assoc_step_group="0" assoc_step="0" assoc_layers="3" selector_type="text" chars="A-Z0-9 "></fields>
	  <fields type="text" default="GREAT JOB SUSAN" label="Inside Engraving" assoc_step_group="0" assoc_step="0" assoc_layers="4" selector_type="text" chars="A-Z0-9 "></fields>
</fields>
EOT;
?>