<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front View" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="rings/F.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" value="stones/oval7/FRONT_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Times New Roman" text_style="arcnew1" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="1" syoffset="-1" sdepth="1" width="75" height="24" top="86" left="0" right="0" align="center" size="24" rotation="0" direction="CW" arc_args="20-350" xscale="1" yscale="1" skewx="10" skewy="-12" mask="masks/F_left.png"></layer>
		  <layer type="text" font="Times New Roman" text_style="arc2i" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" width="150" height="24" fit="2" top="106" left="144" right="0" align="center" size="24" rotation="0" direction="CW" arc_args="70-12" xscale="1" yscale="1" skewx="-15" skewy="0"></layer>
		  <layer type="text" font="Times New Roman" text_style="arcnew1" ucolor="#ffffff" talpha="1"  txoffset="1" tyoffset="1" tdepth="1" uscolor="#dddddd" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" usecolor="true" stroke="true" strokecolor="#555555" strokewidth="1" width="240" height="20" top="188" left="14" right="0" align="center" size="14" rotation="0" direction="CCW" arc_args="76-181" perspective="1.5" xscale="1" yscale="1" mask="masks/F_inside.png"></layer>
		  <layer type="text" font="Arial Black" text_style="top_text_1" color="#dddddd" scolor="#555555" sdepth="4" width="164" height="130" top="21" left="66" size="26" side_pad="1" star_size="1" star_y="2" arc_top_rad="130" arc_bot_rad="76" rect="1" perspective="40" rotation="0" mask="masks/F_top.png"></layer>
	</step>
</steps>
EOT;
?>
