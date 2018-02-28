<?php
	
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<fields>
	  <fields type="metal" default="$def_value" label="Select Metal" assoc_step_group="0" assoc_step="0" selector_type="combo"></fields>
	  <fields type="text" default="Tifanny" label="Front Text 1" assoc_step_group="0" assoc_step="0" assoc_layers="1" selector_type="text" chars="namecase"></fields>
      <fields type="text" default="2012" label="Front Text 2" assoc_step_group="0" assoc_step="0" assoc_layers="2" selector_type="text" chars="0-9"></fields>
      <fields type="text" default="Jefferson HS" label="Front Text 3" assoc_step_group="0" assoc_step="0" assoc_layers="3" selector_type="text" chars="titlecase"></fields>
</fields>
EOT;
?>