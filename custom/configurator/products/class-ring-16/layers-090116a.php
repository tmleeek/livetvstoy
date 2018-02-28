<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front View" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="rings/F.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" value="stones/marquise3/FRONT_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Myriad Web" text_style="arc2i" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="1" syoffset="-1" sdepth="1" width="75" height="26" top="86" left="50" right="0" align="center" size="26" rotation="0" direction="CCW" arc_args="20-265" xscale="0.8" yscale="1" skewx="0" skewy="0"></layer>
		  <layer type="text" font="Myriad Web" text_style="arc2i" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" width="150" height="34" fit="2" top="70" left="160" right="0" align="center" size="34" rotation="0" direction="CCW" arc_args="70-102" xscale="0.6" yscale="1" skewx="-15" skewy="0"></layer>
		  <layer type="text" font="Myriad Web" text_style="arc2i" color="#555555" talpha="0.9" scolor="black" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" width="240" height="20" top="205" left="18" right="0" align="center" size="20" rotation="0" direction="CCW" arc_args="82-180" perspective="1.5" xscale="1" yscale="1" mask="masks/F_inside.png"></layer>
          <layer type="text" font="Serifa BT" text_style="top_text_upper" color="#ffffff" scolor="#555555" sdepth="5" width="150" height="60" top="-8" left="98" size="30" side_pad="1" rotation="-90" direction="CW" arc_top_rad="60" arc_bot_rad="30" text_angle="180" mask="masks/F_top1.png"></layer>
		  <layer type="text" font="Serifa BT" text_style="top_text_upper" color="#ffffff" scolor="#555555" sdepth="5" width="150" height="60" top="-7" left="145" size="30" side_pad="1" rotation="90" direction="CW" arc_top_rad="60" arc_bot_rad="30" text_angle="180" mask="masks/F_top2.png"></layer>
	</step>
</steps>
EOT;
?>
