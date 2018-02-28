<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front View" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="rings/F.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="330" height="330" border="1"></layer>
		  <layer type="image" value="stones/heart2/FRONT_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="140" height="24" top="84" left="120" right="0" align="center" size="24" rotation="0" direction="CCW" arc_args="20-124" perspective="1" xscale="1" yscale="1" skewx="-5" mask="masks/F_top.png" fit="2"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="170" height="20" top="135" left="-36" right="0" align="center" size="20" rotation="0" direction="CW" arc_args="40-335" perspective="1" xscale="0.7" yscale="1" skewx="18" mask="masks/F_left.png"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="160" height="20" top="140" left="208" right="0" align="center" size="20" rotation="0" direction="CW" arc_args="40-26" perspective="1" xscale="0.7" yscale="1" skewx="-18" mask="masks/F_right.png"></layer>
	</step>
</steps>
EOT;
?>
