<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front View" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="rings/F.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" value="stones/marquise3/FRONT_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Myriad Web" text_style="arc2i" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="1" syoffset="-1" sdepth="1" width="100" height="26" top="53" left="52" right="0" align="center" size="26" rotation="0" direction="CCW" arc_args="30-266" xscale="1" yscale="1" skewx="0" skewy="-15"></layer>
		  <layer type="text" font="Myriad Web" text_style="arc2i" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" width="100" height="26" fit="2" top="35" left="188" right="0" align="center" size="26" rotation="0" direction="CCW" arc_args="50-91" xscale="1.2" yscale="1" skewx="0" skewy="15"></layer>
		  <layer type="text" font="Myriad Web" text_style="arc2i" color="#555555" talpha="0.9" scolor="black" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" width="250" height="20" top="193" left="2" right="0" align="center" size="20" rotation="0" direction="CCW" arc_args="96-180" perspective="1.5" xscale="1.1" yscale="1" mask="masks/F_inside.png"></layer>
		  <layer type="text" font="Serifa BT" text_style="top_text_upper2" color="#dddddd" scolor="#555555" sdepth="5" width="168" height="64" top="-26" left="83" size="70" side_pad="1" rotation="-93" direction="CW" arc_top_rad="130" arc_bot_rad="60" perspective="25" skewx="12" skewy="5" mask="masks/F_top1.png"></layer>          
		  <layer type="text" font="Serifa BT" text_style="top_text_upper2" color="#dddddd" scolor="#555555" sdepth="5" width="168" height="60" top="-17" left="129" size="70" side_pad="1" rotation="86" direction="CW" arc_top_rad="120" arc_bot_rad="50" perspective="30" skewx="12" skewy="5" mask="masks/F_top2.png"></layer>
	</step>
</steps>
EOT;
?>
