<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front Side" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="/pendants/birthstone/F.png" resize="1" img_type="jpg" jpg_quality="90" left="0" top="0" width="330" height="330" border="1"></layer>
          <layer type="image" value="stones/birthstone-pendant-1/FRONT_STONE.png" top="0" left="0" stone_style="colorized"></layer>
          <layer type="text" font="Alison" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#999999" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" usecolor="true" stroke="true" strokecolor="#ffffff" strokewidth="1" width="180" height="18" top="196" left="161" align="center" size="18" rotation="0" direction="CCW" arc_args="30-136" perspective="1" xscale="0.65" yscale="1" skewx="0" masks="masks/F_mask.png"></layer>
	</step>
</steps>
EOT;
?>
