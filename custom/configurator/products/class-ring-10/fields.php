<?php
	
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<fields>
	  <fields type="metal" default="$def_value" label="Select Metal &amp; Size" assoc_step_group="0" assoc_step="0-1-2-3" selector_type="combo"></fields>
      <fields type="size" default="" label="Size" assoc_step_group="0" assoc_step="0" selector_type="combo"></fields>
      <fields type="stones" default="JAN" label="Birthstone" assoc_step_group="0" assoc_step="0-1-2-3" assoc_layers="0|1-1|1-2|1-3|1" selector_type="combo"></fields>
	  <fields type="text" default="Panthers" label="Left Text" assoc_step_group="1" assoc_step="0-1-2" assoc_layers="0|2-1|2-2|2" selector_type="text" chars="namecase"></fields>
	  <fields type="text" default="Northbrook" label="School" assoc_step_group="2" assoc_step="0-1-2" assoc_layers="0|3-1|3-2|3" selector_type="text" chars="namecase"></fields>
	  <fields type="text" default="Lisa" label="Right Text" assoc_step_group="3" assoc_step="0-1-3" assoc_layers="0|4-1|4-3|2" selector_type="text" chars="namecase"></fields>
	  <fields type="text" default="2018" label="Year" assoc_step_group="3" assoc_step="0-2-3" assoc_layers="0|5-1|5-3|3" selector_type="text" chars="0-9"></fields>
</fields>
EOT;
?>