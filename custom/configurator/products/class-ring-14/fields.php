<?php
	
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<fields>
	  <fields type="metal" default="$def_value" label="Select Metal" assoc_step_group="0" assoc_step="0-1-2" selector_type="combo"></fields>
      <fields type="size" default="" label="Select Size" assoc_step_group="0" assoc_step="0" selector_type="combo"></fields>
	  <fields type="text" default="DOUGLAS" label="Front Text 1" assoc_step_group="0" assoc_step="0-1-2" assoc_layers="0|1-1|1-2|1" selector_type="text" chars="A-Z0-9 "></fields>
      <fields type="text_2" default="2018" label="Front Text 2" assoc_step_group="1" assoc_step="0-1-2" assoc_layers="0|1-1|1-2|1" selector_type="text" chars="0-9"></fields>
      <fields type="text_3" default="LINCOLN PARK" label="Front Text 3" code="Front Text 3" assoc_step_group="2" assoc_step="0-1-2" assoc_layers="0|1-1|1-2|1" selector_type="text" chars="A-Z0-9 "></fields>
</fields>
EOT;
?>