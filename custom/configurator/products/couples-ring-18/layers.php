<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front Side" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="/rings/couples/F.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="330" height="330" border="1"></layer>
          <layer type="image" value="stones/couples-ring-18/FRONT_STONE1.png" top="0" left="0" stone_style="colorized"></layer>
          <layer type="image" value="stones/couples-ring-18/FRONT_STONE2.png" top="0" left="0" stone_style="colorized"></layer>
          <layer type="text" font="Alison" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#999999" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" stroke="true" strokecolor="#ffffff" strokewidth="1" width="150" height="18" top="158" left="-25" align="right" size="18" rotation="0" direction="CW" arc_args="50-340" perspective="1.5" xscale="0.8" yscale="1" skewx="0" skewy="0" mask="masks/F_left.png"></layer>
          <layer type="text" font="Alison" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#999999" salpha="1" sxoffset="1" 
          syoffset="1" sdepth="1" usecolor="true" stroke="true" strokecolor="#ffffff" strokewidth="1" width="170" height="18" top="122" left="205" align="left" size="18" rotation="0" direction="CW" arc_args="60-42" perspective="1.5" xscale="0.8" yscale="1" skewx="-20" skewy="0" mask="masks/F_right.png"></layer>
	</step>
</steps>
EOT;
?>
