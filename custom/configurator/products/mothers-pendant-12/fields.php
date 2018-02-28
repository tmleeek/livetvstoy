<?php
	
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<fields>
	  <fields type="metal" default="$def_value" label="Select Metal" assoc_step_group="0" assoc_step="0" selector_type="combo"></fields>
	  
      <fields type="ddstones" usesize="true" default="5 birthstones" label="Number of Stones" assoc_step_group="0" assoc_step="0" selector_type="combo"></fields>

	  <fields type="textd" default="Mason" label="Front Text 1" assoc_step_group="0" assoc_step="0" assoc_layers="6" selector_type="text" chars="namecase"></fields>
      <fields type="stonesd" default="MAY" label="Birthstone 1" assoc_step_group="0" assoc_step="0" assoc_layers="1" selector_type="combo"></fields>
	  <fields type="textd" default="Sophia" label="Front Text 2" assoc_step_group="0" assoc_step="0" assoc_layers="7" selector_type="text" chars="namecase"></fields>
      <fields type="stonesd" default="JAN" label="Birthstone 2" assoc_step_group="0" assoc_step="0" assoc_layers="2" selector_type="combo"></fields>
	  <fields type="textd" default="Jackson" label="Front Text 3" assoc_step_group="0" assoc_step="0" assoc_layers="8" selector_type="text" chars="namecase"></fields>
      <fields type="stonesd" default="AUG" label="Birthstone 3" assoc_step_group="0" assoc_step="0" assoc_layers="3" selector_type="combo"></fields>
	  <fields type="textd" default="Addison" label="Front Text 4" assoc_step_group="0" assoc_step="0" assoc_layers="9" selector_type="text" chars="namecase"></fields>
      <fields type="stonesd" default="JUN" label="Birthstone 4" assoc_step_group="0" assoc_step="0" assoc_layers="4" selector_type="combo"></fields>
	  <fields type="textd" default="Madison" label="Front Text 5" assoc_step_group="0" assoc_step="0" assoc_layers="10" selector_type="text" chars="namecase"></fields>
      <fields type="stonesd" default="NOV" label="Birthstone 5" assoc_step_group="0" assoc_step="0" assoc_layers="5" selector_type="combo"></fields>
</fields>
EOT;
?>