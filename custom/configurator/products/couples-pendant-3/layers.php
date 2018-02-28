<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front Side" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="/pendants/couples/F.png" resize="1" img_type="jpg" jpg_quality="90" left="0" top="0" width="330" height="330" border="1"></layer>
          <layer type="image" value="stones/couples-pendant-3/FRONT_STONE1.png" top="0" left="0" stone_style="colorized"></layer>
          <layer type="image" value="stones/couples-pendant-3/FRONT_STONE2.png" top="0" left="0" stone_style="colorized"></layer>
          <layer type="text" font="Script 33" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="2" uscolor="#999999" salpha="1" sxoffset="-1" syoffset="-1" sdepth="2" usecolor="true" stroke="true" strokecolor="#ffffff" strokewidth="1" width="130" height="18" top="137" left="169" align="center" size="18" rotation="0" direction="CCW" arc_args="100-100" perspective="1" xscale="1" yscale="1" skewx="0" mask="masks/F_left.png"></layer>
          <layer type="text" font="Script 33" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="2" uscolor="#999999" salpha="1" 
          sxoffset="-1" syoffset="-1" sdepth="2" usecolor="true" stroke="true" strokecolor="#ffffff" strokewidth="1" width="130" height="18" top="148" left="242" align="center" size="18" rotation="0" direction="CCW" arc_args="100-105" perspective="1" xscale="1" yscale="1" skewx="0" mask="masks/F_right.png"></layer>
	</step>
</steps>
EOT;
?>
