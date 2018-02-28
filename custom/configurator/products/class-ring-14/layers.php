<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Top View" default_view="true" to_js="true" replace_preview="0">
		<layer type="image" color="Black" value="rings/T.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		<layer type="text" font="Nueva" text_style="3_text_star" color="#dddddd" scolor="#333333" sdepth="2" width="500" height="46" top="104" left="-12" size="36" side_pad="3" star_size="1.7" star_y="7" rotation="0" direction="CW" arc_args="35-2.5" align="center" mask="masks/T_mask.png" text_offset="20" perspective="0.5"></layer>
	</step>
	<step name="Step 2: Front View" to_js="true" replace_preview="2">
		<layer type="image" color="Black" value="rings/F.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		<layer type="text" font="Nueva" text_style="3_text_star" color="#dddddd" scolor="#333333" sdepth="2" width="500" height="66" top="-82" left="-90" size="56" side_pad="3" star_size="2.5" star_y="10" rotation="-44" direction="CW" arc_args="100" perspective="1" text_offset="100" mask="masks/F_mask.png"></layer>
	</step>
	<step name="Step 3: Side View" to_js="true" replace_preview="3" merge_adjust="80">
		<layer type="image" color="Black" value="rings/S.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		<layer type="text" font="Nueva" text_style="3_text_star" color="#dddddd" scolor="#333333" sdepth="2" width="550" height="46" top="0" left="-128" size="36" side_pad="3" star_size="1.7" star_y="7" rotation="0" direction="CW" arc_args="1-90" align="center" text_offset="120" mask="masks/S_mask.png"></layer>
	</step>
</steps>
EOT;
?>
