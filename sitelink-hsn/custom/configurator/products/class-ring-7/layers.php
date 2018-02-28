<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front View" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="rings/F.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" value="stones/oval4/FRONT_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Times New Roman" text_style="arc2i" color="#555555" talpha="0.9" scolor="black" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" width="240" height="20" top="203" left="18" right="0" align="center" size="12" rotation="0" direction="CCW" arc_args="64-179" perspective="1.5" xscale="1" yscale="1" mask="masks/F_inside.png"></layer>
		  <layer type="text" font="Arial Black" text_style="top_text_1" color="#dddddd" scolor="#555555" sdepth="4" width="112" height="118" top="51" left="87" size="26" side_pad="1" star_size="1" star_y="2" arc_top_rad="118" arc_bot_rad="66" rect="2" perspective="15" rotation="0"></layer>
	</step>
</steps>
EOT;
?>
