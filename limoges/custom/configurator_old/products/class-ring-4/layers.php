<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Top View" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="rings/T.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" value="stones/oval3/TOP_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="0" syoffset="-3" sdepth="3" width="100" height="16" fit="1" top="128" left="-1" right="0" align="center" size="16" rotation="258" direction="CCW" arc_args="30" perspective="3" yscale="0.4" mask="masks/T_left.png"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="0" syoffset="-3" sdepth="3" width="100" height="16" fit="1" top="80" left="251" right="0" align="center" size="16" rotation="79" direction="CCW" arc_args="30" perspective="3" yscale="0.4" mask="masks/T_right.png"></layer>
		  <layer type="text" font="Serifa BT" text_style="top_text_1" color="#dddddd" scolor="#555555" sdepth="5" width="146" height="176" top="53" left="71" size="30" side_pad="1" star_size="0.8" star_y="6" arc_top_rad="120" arc_bot_rad="80" rect="6"></layer>
	</step>
	<step name="Step 2: Left View" to_js="true" replace_preview="3" merge_adjust="80">
		  <layer type="image" color="Black" value="rings/S.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" color="Black" value="stones/oval3/SIDE_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Arial Black" text_style="wave" wave_height="-6" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="0" syoffset="1" sdepth="1" width="90" height="20" fit="1" top="100" left="100" right="0" align="center" size="20" rotation="-36" direction="CW" yscale="1" mask="masks/S_lower.png"></layer>
		  <layer type="text" font="Serifa BT" text_style="top_side_text" color="#dddddd" scolor="#555555" sdepth="4" width="250" height="300" top="12" left="25" size="24" side_pad="3" star_size="1" star_y="3" view_side="L" perspective="50" yscale="1.1" arc_args="60-180" mask="masks/S_upper.png"></layer>
	</step>
	<step name="Step 3: Right View" to_js="true" replace_preview="1" merge_adjust="90">
		  <layer type="image" color="Black" value="rings/S.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" color="Black" value="stones/oval3/SIDE_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Arial Black" text_style="wave" wave_height="-6" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="0" syoffset="1" sdepth="1" width="90" height="20" fit="1" top="100" left="100" right="0" align="center" size="20" rotation="-36" direction="CW" yscale="1" mask="masks/S_lower.png"></layer>
		  <layer type="text" font="Serifa BT" text_style="top_side_text" color="#dddddd" scolor="#555555" sdepth="4" width="250" height="300" top="12" left="20" size="24" side_pad="3" star_size="1" star_y="3" view_side="R" perspective="50" yscale="1.1" arc_args="60-180" mask="masks/S_upper.png"></layer>
	</step>
	<step name="Step 4: Front View" to_js="true" replace_preview="2">
		  <layer type="image" color="Black" value="rings/F.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" value="stones/oval3/FRONT_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="2" syoffset="-2" sdepth="2" width="75" height="16" fit="1" top="111" left="-28" right="0" align="center" size="16" rotation="-108" direction="CCW" arc_args="60" xscale="0.8" yscale="1" skewx="-28" skewy="8" mask="masks/F_left.png"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="-2" syoffset="-2" sdepth="2" width="75" height="16" fit="1" top="68" left="230" right="0" align="center" size="16" rotation="76" direction="CCW" arc_args="40" xscale="1.1" yscale="0.4" skewx="28" skewy="-8" mask="masks/F_right.png"></layer>
		  <layer type="text" font="Arial" text_style="arc2i" color="#555555" talpha="0.9" scolor="black" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" width="240" height="20" top="208" left="15" right="0" align="center" size="24" rotation="0" direction="CCW" arc_args="78-180" perspective="1.5" xscale="1" yscale="1" mask="masks/F_inside.png"></layer>
		  <layer type="text" font="Serifa BT" text_style="top_text_1" color="#dddddd" scolor="#555555" sdepth="8" width="210" height="145" top="20" left="30" size="60" side_pad="1" star_size="2" star_y="12" arc_top_rad="210" arc_bot_rad="135" rect="10" perspective="65" rotation="-1.3" mask="masks/F_top.png"></layer>
	</step>
</steps>
EOT;
?>
