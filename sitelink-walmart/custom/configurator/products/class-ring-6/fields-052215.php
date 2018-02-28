<?php
	
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<fields>
	  <fields type="metal" default="$def_value" label="Select Metal &amp; Size" assoc_step_group="0" assoc_step="0-1-2-3" selector_type="combo"></fields>
      <fields type="size" default="" label="Size" assoc_step_group="0" assoc_step="0" selector_type="combo"></fields>
      <fields type="stones" default="JAN" label="Birthstone" assoc_step_group="0" assoc_step="0-3" assoc_layers="0|1-3|1" selector_type="combo"></fields>
      <fields type="text" default="CLASS OF" label="Top Text 3" code="Top Text 3" assoc_step_group="0" assoc_step="0-3" assoc_layers="0|2-3|2" selector_type="text" chars="A-Z0-9 "></fields>
      <fields type="text" default="2012" label="Top Text 4" code="Top Text 4" assoc_step_group="0" assoc_step="0-3" assoc_layers="0|3-3|3" selector_type="text" chars="A-Z0-9 "></fields>
      <fields type="text" default="CALIFORNIA" label="Top Text 1" code="Top Text 1" assoc_step_group="0" assoc_step="0-1-2-3" assoc_layers="0|6-1|2-2|2-3|7" selector_type="text" chars="A-Z0-9 "></fields>
	  <fields type="text_2" default="UNIVERSITY" label="Top Text 2" code="Top Text 2" assoc_step_group="0" assoc_step="0-1-2-3" assoc_layers="0|6-1|2-2|2-3|7" selector_type="text" chars="A-Z0-9 "></fields>
	  <fields type="text" default="TROJANS" label="Left Text" assoc_step_group="1" assoc_step="0-1-3" assoc_layers="0|4-1|1-3|4" selector_type="text" chars="A-Z0-9 "></fields>
      <fields type="comp_selector" default="0~1" label="Left Image" assoc_step_group="1" assoc_step="0-1-3" assoc_layers="0|7-1|3-3|8" selector_type="comp_selector"></fields>
	  <fields type="text" default="2010" label="Right Text" assoc_step_group="2" assoc_step="0-2-3" assoc_layers="0|5-2|1-3|5" selector_type="text" chars="A-Z0-9 "></fields>
      <fields type="comp_selector" default="0~1" label="Right Image" assoc_step_group="2" assoc_step="0-2-3" assoc_layers="0|8-2|3-3|9" selector_type="comp_selector"></fields>
	  <fields type="text" default="YOU DID A GREAT JOB" label="Inside Text" assoc_step_group="3" assoc_step="3" assoc_layers="6" selector_type="text" chars="A-Z0-9 "></fields>
</fields>
EOT;
?>