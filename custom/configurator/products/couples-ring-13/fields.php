<?php
	
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<fields>
	  <fields type="metal" default="$def_value" label="Select Metal" assoc_step_group="0" assoc_step="0" selector_type="combo"></fields>
      <fields type="size" default="" label="Select Size" assoc_step_group="0" assoc_step="0" selector_type="combo"></fields>
	  <fields type="text" default="ANDREA" label="Left Engraving" assoc_step_group="0" assoc_step="0" assoc_layers="1" selector_type="text" chars="A-Z0-9 "></fields>
      <fields type="text" default="JONATHAN" label="Right Engraving" assoc_step_group="0" assoc_step="0" assoc_layers="2" selector_type="text" chars="A-Z0-9 "></fields>
</fields>
EOT;
?>