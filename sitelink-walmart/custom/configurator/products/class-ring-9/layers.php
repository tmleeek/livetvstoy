<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front View" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="rings/F.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" value="stones/oval6/FRONT_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Myriad Web" text_style="arc2i" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="1" syoffset="-1" sdepth="1" width="75" height="20" fit="1" top="86" left="-30" right="0" align="center" size="20" rotation="0" direction="CW" arc_args="20-322" xscale="0.7" yscale="1.8" skewx="30" skewy="-12" mask="masks/F_left.png"></layer>
		  <layer type="text" font="Myriad Web" text_style="arc2i" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" width="75" height="30" fit="1" top="146" left="198" right="0" align="center" size="28" rotation="0" direction="CW" arc_args="30-7" xscale="0.9" yscale="1.4" skewx="-10" skewy="0"></layer>
		  <layer type="text" font="Times New Roman" text_style="arc2i" color="#555555" talpha="0.9" scolor="black" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" width="240" height="20" top="202" left="18" right="0" align="center" size="14" rotation="0" direction="CCW" arc_args="58-180" perspective="1.5" xscale="1" yscale="1" mask="masks/F_inside.png"></layer>
		  <layer type="text" font="Arial Black" text_style="top_text_1" color="#dddddd" scolor="#555555" sdepth="4" width="112" height="119" top="58" left="92" size="26" side_pad="1" star_size="1" star_y="2" arc_top_rad="119" arc_bot_rad="63" rect="2" perspective="15" rotation="0"></layer>
	</step>
</steps>
EOT;
?>
