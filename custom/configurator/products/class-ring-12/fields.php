<?php
	
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<fields>
	  <fields type="metal" default="$def_value" label="Select Metal &amp; Size" assoc_step_group="0" assoc_step="0-1-2-3" selector_type="combo"></fields>
      <fields type="size" default="" label="Size" assoc_step_group="0" assoc_step="0" selector_type="combo"></fields>
      <fields type="stones" default="JAN" label="Birthstone" assoc_step_group="0" assoc_step="0-1-2-3" assoc_layers="0|1-1|1-2|1-3|1" selector_type="combo"></fields>
      <fields type="text" default="PAYTON" label="Top Text 1 Engraving" code="Top Text 1" assoc_step_group="0" assoc_step="0-1-2-3" assoc_layers="0|4-1|3-2|3-3|5" selector_type="text" chars="A-Z0-9 "></fields>
	  <fields type="text_2" default="HIGH SCHOOL" label="Top Text 2 Engraving" code="Top Text 2" assoc_step_group="0" assoc_step="0-1-2-3" assoc_layers="0|4-1|3-2|3-3|5" selector_type="text" chars="A-Z0-9 "></fields>
	  <fields type="text" default="SUSAN" label="Left Text" assoc_step_group="1" assoc_step="0-1-3" assoc_layers="0|2-1|2-3|2" selector_type="text" chars="A-Z0-9 "></fields>
      <fields type="comp_selector" default="0~38" label="Left Image" assoc_step_group="1" assoc_step="0-1-3" assoc_layers="0|5-1|4-3|6" selector_type="comp_selector"></fields>
	  <fields type="text" default="2012" label="Right Text" assoc_step_group="2" assoc_step="0-2-3" assoc_layers="0|3-2|2-3|3" selector_type="text" chars="A-Z0-9 "></fields>
      <fields type="comp_selector" default="0~110" label="Right Image" assoc_step_group="2" assoc_step="0-2-3" assoc_layers="0|6-2|4-3|7" selector_type="comp_selector"></fields>
	  <fields type="text" default="WITH LOVE DAVID" label="Inside Engraving" assoc_step_group="3" assoc_step="3" assoc_layers="4" selector_type="text" chars="A-Z0-9 "></fields>
</fields>
EOT;
?>