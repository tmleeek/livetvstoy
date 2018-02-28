<?php
	
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<fields>
	  <fields type="metal" default="$def_value" label="Select Metal" assoc_step_group="0" assoc_step="0" selector_type="combo"></fields>
      <fields type="size" default="" label="Select Size" assoc_step_group="0" assoc_step="0" selector_type="combo"></fields>
	  <fields type="text" default="EMILY" label="Left Engraving" assoc_step_group="0" assoc_step="0" assoc_layers="3" selector_type="text" chars="A-Z "></fields>
      <fields type="stones" default="MAY" label="Birthstone 1" assoc_step_group="0" assoc_step="0" assoc_layers="1" selector_type="combo"></fields>
      <fields type="text" default="THOMAS" label="Right Engraving" assoc_step_group="0" assoc_step="0" assoc_layers="4" selector_type="text" chars="A-Z "></fields>
      <fields type="stones" default="JUN" label="Birthstone 2" assoc_step_group="0" assoc_step="0" assoc_layers="2" selector_type="combo"></fields>
</fields>
EOT;
?>