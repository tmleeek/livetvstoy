<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front View" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="rings/F.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" value="stones/marquise3/FRONT_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Myriad Web" text_style="arc2i" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="1" syoffset="-1" sdepth="1" width="95" height="26" fit="2" top="54" left="50" right="0" align="center" size="26" rotation="0" direction="CCW" arc_args="30-266" xscale="0.95" yscale="1" skewx="0" skewy="-15" mask="masks/F_left.png"></layer>
		  <layer type="text" font="Myriad Web" text_style="arc2i" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" width="95" height="26" fit="2" top="33" left="188" right="0" align="center" size="26" rotation="0" direction="CCW" arc_args="50-91" xscale="1.2" yscale="1" skewx="0" skewy="15" mask="masks/F_right.png"></layer>
		  <layer type="text" font="Myriad Web" text_style="arc2i" color="#555555" talpha="0.9" scolor="black" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" width="250" height="20" top="193" left="2" right="0" align="center" size="20" rotation="0" direction="CCW" arc_args="96-180" perspective="1.5" xscale="1.1" yscale="1" mask="masks/F_inside.png"></layer>
		  <layer type="text" font="Serifa BT" text_style="top_text_upper2" color="#dddddd" scolor="#555555" sdepth="5" width="168" height="64" top="-16" left="83" size="70" side_pad="1" rotation="-94" direction="CW" arc_top_rad="130" arc_bot_rad="60" perspective="25" perspective2="2" skewx="6" skewy="0" mask="masks/F_top1.png"></layer>          
		  <layer type="text" font="Serifa BT" text_style="top_text_upper2" color="#dddddd" scolor="#555555" sdepth="5" width="168" height="64" top="-15" left="134" size="70" side_pad="1" rotation="86" direction="CW" arc_top_rad="120" arc_bot_rad="50" perspective="30" skewx="0" skewy="0" mask="masks/F_top2.png"></layer>
	</step>
</steps>
EOT;
?>
