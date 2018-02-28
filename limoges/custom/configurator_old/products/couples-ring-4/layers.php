<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front Side" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="/rings/couples/F.png" resize="1" img_type="jpg" jpg_quality="90" left="0" top="0" width="330" height="330" border="1"></layer>
          <layer type="image" value="stones/couples-ring-4/FRONT_STONE1.png" top="0" left="0" stone_style="colorized"></layer>
          <layer type="image" value="stones/couples-ring-4/FRONT_STONE2.png" top="0" left="0" stone_style="colorized"></layer>
          <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dddddd" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" usecolor="true" stroke="true" strokecolor="#ffffff" strokewidth="2" width="180" height="20" top="173" left="-17" align="center" size="24" rotation="0" direction="CW" arc_args="40-350" perspective="1" xscale="0.5" yscale="1.1" skewx="5" mask="masks/F_left.png"></layer>
          <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dddddd" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" usecolor="true" stroke="true" strokecolor="#ffffff" strokewidth="2" width="180" height="26" top="168" left="218" align="center" size="26" rotation="0" direction="CW" arc_args="40-22" perspective="1.3" xscale="0.52" yscale="1.1" skewx="-15" mask="masks/F_right.png"></layer>
	</step>
</steps>
EOT;
?>
