<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front View" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="rings/F.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" value="stones/oval7/FRONT_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Times New Roman" text_style="arcnew2" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#555555" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" usecolor="true" width="75" height="24" top="88" left="-4" right="0" align="center" size="20" rotation="0" direction="CW" arc_args="20-350" xscale="0.9" yscale="1" skewx="20" skewy="-15" mask="masks/F_left.png" split_year="1"></layer>
		  <layer type="text" font="Times New Roman" text_style="arcnew2" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#555555" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" usecolor="true" width="75" height="24" top="100" left="188" right="0" align="center" size="20" rotation="0" direction="CW" arc_args="20-22" xscale="0.9" yscale="0.9" skewx="15" skewy="0" mask="masks/F_right.png" split_year="2"></layer>
		  <layer type="text" font="Times New Roman" text_style="arcnew1" ucolor="#ffffff" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dddddd" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" usecolor="true" stroke="true" strokecolor="#555555" strokewidth="1" width="240" height="20" top="188" left="14" right="0" align="center" size="14" rotation="0" direction="CCW" arc_args="76-181" perspective="1.5" xscale="1" yscale="1" mask="masks/F_inside.png"></layer>
		  <layer type="text" font="Serifa BT" text_style="top_text_1" color="#dddddd" scolor="#555555" sdepth="4" width="132" height="121" top="19" left="77" size="20" side_pad="1" star_size="0.7" star_y="0" arc_top_rad="121" arc_bot_rad="64" rect="6" perspective="25" rotation="0" mask="masks/F_top.png"></layer>
	</step>
</steps>
EOT;
?>
