<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front View" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="rings/F.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" value="stones/heart1/FRONT_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="50" height="14" top="108" left="2" right="0" align="center" size="14" rotation="0" direction="CW" arc_args="60-340" perspective="1.5" xscale="0.9" yscale="1.1" skewx="15" mask="masks/F_left1.png"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="150" height="12" top="95" left="-2" right="0" align="center" size="12" rotation="0" direction="CCW" arc_args="10-150" perspective="1" xscale="0.8" yscale="1.1" skewx="-5" mask="masks/F_left2.png"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="150" height="12" top="101" left="168" right="0" align="center" size="12" rotation="0" direction="CW" arc_args="116-14" perspective="1" xscale="0.8" yscale="1.1" skewx="0" mask="masks/F_right1.png"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="100" height="12" top="126" left="212" right="0" align="left" size="12" rotation="0" direction="CCW" arc_args="30-203" perspective="1" xscale="0.8" yscale="1" skewx="0" mask="masks/F_right2.png"></layer>
	</step>
</steps>
EOT;
?>
