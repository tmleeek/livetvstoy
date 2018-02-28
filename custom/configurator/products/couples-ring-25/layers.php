<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front Side" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="/rings/couples/F.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="330" height="330" border="1"></layer>
          <layer type="image" value="stones/couples-ring-25/FRONT_STONE1.png" top="0" left="0" stone_style="colorized"></layer>
          <layer type="image" value="stones/couples-ring-25/FRONT_STONE2.png" top="0" left="0" stone_style="colorized"></layer>
          <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#999999" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" stroke="true" strokecolor="#ffffff" strokewidth="1" width="130" height="11" top="164" left="-21" align="right" size="11" rotation="0" direction="CW" arc_args="20-338" perspective="1.5" xscale="0.75" yscale="1" skewx="0" skewy="0" mask="masks/F_left.png"></layer>
          <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#999999" salpha="1" sxoffset="1" 
          syoffset="1" sdepth="1" usecolor="true" stroke="true" strokecolor="#ffffff" strokewidth="1" width="130" height="11" top="117" left="224" align="left" size="11" rotation="0" direction="CW" arc_args="40-30" perspective="1.5" xscale="0.7" yscale="1" skewx="-10" skewy="0" mask="masks/F_right.png"></layer>
	</step>
</steps>
EOT;
?>
