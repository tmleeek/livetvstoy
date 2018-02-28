<?php
	
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<fields>
	  <fields type="metal" default="$def_value" label="Select Metal" assoc_step_group="0" assoc_step="0" selector_type="combo"></fields>
      <fields type="size" default="" label="Select Size" assoc_step_group="0" assoc_step="0" selector_type="combo"></fields>
	  <fields type="text" default="DOUGLAS" label="Top Text 1" assoc_step_group="0" assoc_step="0" assoc_layers="1" selector_type="text"></fields>
      <fields type="text" default="2018" label="Top Text 2" assoc_step_group="0" assoc_step="0" assoc_layers="2" selector_type="text"></fields>
      <fields type="text" default="LINCOLN PARK" label="Top Text 3" code="Top Text 3" assoc_step_group="0" assoc_step="0" assoc_layers="3" selector_type="text"></fields>
</fields>
EOT;
?>