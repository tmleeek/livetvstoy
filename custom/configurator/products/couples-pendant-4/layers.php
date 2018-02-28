<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front Side" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="/pendants/couples/F.png" resize="1" img_type="jpg" jpg_quality="90" left="0" top="0" width="330" height="330" border="1"></layer>
          <layer type="image" value="stones/couples-pendant-4/FRONT_STONE1.png" top="0" left="0" stone_style="colorized"></layer>
          <layer type="image" value="stones/couples-pendant-4/FRONT_STONE2.png" top="0" left="0" stone_style="colorized"></layer>
          <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#999999" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" usecolor="true" stroke="true" strokecolor="#ffffff" strokewidth="1" width="70" height="10" top="204" left="0" align="right" size="10" rotation="0" direction="CW" arc_args="1-48" perspective="0" xscale="0.65" yscale="1" skewx="0" mask="masks/F_left.png"></layer>
          <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#999999" salpha="1" 
          sxoffset="1" syoffset="1" sdepth="1" usecolor="true" stroke="true" strokecolor="#ffffff" strokewidth="1" width="80" height="10" top="250" left="48" align="left" size="10" rotation="0" direction="CW" arc_args="1-40" perspective="0" xscale="0.65" yscale="1" skewx="0"></layer>
	</step>
</steps>
EOT;
?>
