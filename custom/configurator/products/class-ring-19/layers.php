<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front View" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="rings/F.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" value="stones/pear2/FRONT_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="140" height="14" top="46" left="192" right="0" align="center" size="14" rotation="0" direction="CCW" arc_args="30-112" xscale="0.8" yscale="1" skewy="10" mask="masks/F_top1.png"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="140" height="14" top="148" left="90" right="0" align="center" size="14" rotation="0" direction="CCW" arc_args="30-111" xscale="0.8" yscale="1" skewy="10" mask="masks/F_top2.png"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="150" height="8" top="74" left="28" right="0" align="right" size="8" rotation="0" direction="CW" arc_args="5-320" xscale="0.55" yscale="1" skewx="20" mask="masks/F_left1.png"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="150" height="8" top="202" left="-5" right="0" align="right" size="8" rotation="0" direction="CW" arc_args="20-294" xscale="0.55" yscale="0.9" skewy="10" mask="masks/F_left2.png"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="100" height="8" top="174" left="158" right="0" align="left" size="8" rotation="0" direction="CW" arc_args="80-20" perspective="1" xscale="0.8" yscale="1" skewx="0" mask="masks/F_right.png"></layer>
	</step>
</steps>
EOT;
?>
