<?php
	
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<fields>
	  <fields type="metal" default="$def_value" label="Select Metal" assoc_step_group="0" assoc_step="0" assoc_layers="0" selector_type="combo"></fields>

      <fields type="size" default="" label="Sizes" assoc_step_group="0" assoc_step="0" assoc_layers="0" selector_type="combo"></fields>
	  
      <fields type="ddstones" default="" label="Number of Stones" assoc_step_group="0" assoc_layers="0" assoc_step="0" selector_type="combo"></fields>
     
      <fields type="stonesd" default="SEP" label="Birthstone 1" assoc_step_group="0" assoc_step="0" assoc_layers="1" selector_type="combo"></fields>
      <fields type="stonesd" default="JAN" label="Birthstone 2" assoc_step_group="0" assoc_step="0" assoc_layers="2" selector_type="combo"></fields>
      <fields type="stonesd" default="MAR" label="Birthstone 3" assoc_step_group="0" assoc_step="0" assoc_layers="3" selector_type="combo"></fields>
      <fields type="stonesd" default="NOV" label="Birthstone 4" assoc_step_group="0" assoc_step="0" assoc_layers="4" selector_type="combo"></fields>
       <fields type="stonesd" default="DEC" label="Birthstone 5" assoc_step_group="0" assoc_step="0" assoc_layers="5" selector_type="combo"></fields>
	  
</fields>
EOT;
?>