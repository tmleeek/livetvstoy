<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front Side" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="/rings/couples/F.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
          <layer type="image" value="stones/couples-ring-7/FRONT_STONE1.png" top="0" left="0" stone_style="colorized"></layer>
          <layer type="image" value="stones/couples-ring-7/FRONT_STONE2.png" top="0" left="0" stone_style="colorized"></layer>
          <layer type="text" font="Arial" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#999999" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" stroke="true" strokecolor="#ffffff" strokewidth="1" width="95" height="10" top="68" left="-6" align="center" size="10" rotation="0" direction="CW" arc_args="50-315" perspective="1" xscale="0.7" yscale="1.2" skewx="15" mask="masks/F_left.png"></layer>
          <layer type="text" font="Arial" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#999999" salpha="1" sxoffset="1" 
          syoffset="1" sdepth="1" usecolor="true" stroke="true" strokecolor="#ffffff" strokewidth="1" width="95" height="10" top="138" left="237" align="center" size="10" rotation="0" direction="CW" arc_args="60-30" perspective="1" xscale="0.7" yscale="1.2" skewx="0" mask="masks/F_right.png"></layer>
	</step>
</steps>
EOT;
?>
