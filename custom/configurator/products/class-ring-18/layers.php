<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front View" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="rings/F.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" value="stones/pear1/FRONT_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="60" height="16" top="110" left="57" right="0" align="center" size="16" rotation="0" direction="CCW" arc_args="40-236" perspective="1" xscale="1.1" yscale="1" skewx="15" mask="masks/F_top1.png"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="140" height="16" top="84" left="114" right="0" align="center" size="16" rotation="0" direction="CCW" arc_args="50-118" perspective="1" xscale="1" yscale="1" skewx="-5" mask="masks/F_top2.png" fit="2"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="150" height="12" top="131" left="-32" right="0" align="center" size="12" rotation="0" direction="CW" arc_args="90-358" perspective="1" xscale="0.7" yscale="1" skewx="25" mask="masks/F_left.png"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="100" height="12" top="120" left="218" right="0" align="left" size="12" rotation="0" direction="CW" arc_args="20-41" perspective="1" xscale="0.8" yscale="1" skewx="0" mask="masks/F_right.png"></layer>
	</step>
</steps>
EOT;
?>
