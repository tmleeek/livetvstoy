<?php
	
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<fields>
	  <fields type="metal" default="$def_value" label="Select Metal" assoc_step_group="0" assoc_step="0" selector_type="combo"></fields>
      <fields type="size" default="" label="Select Size" assoc_step_group="0" assoc_step="0" selector_type="combo"></fields>
      <fields type="stones" default="MAY" label="Dad's Birthstone" assoc_step_group="0" assoc_step="0" assoc_layers="1" selector_type="combo"></fields>
      <fields type="stones" default="JUN" label="Daughter's Birthstone" assoc_step_group="0" assoc_step="0" assoc_layers="2" selector_type="combo"></fields>
      <fields type="stones" default="FEB" label="Mom's Birthstone" assoc_step_group="0" assoc_step="0" assoc_layers="3" selector_type="combo"></fields>
      <fields type="text" default="AVA" label="Left Engraving" assoc_step_group="0" assoc_step="0" assoc_layers="4" selector_type="text" chars="A-Z "></fields>
      <fields type="text" default="10-13-85" label="Right Engraving" assoc_step_group="0" assoc_step="0" assoc_layers="5" selector_type="text" chars="0-9"></fields>
</fields>
EOT;
?>