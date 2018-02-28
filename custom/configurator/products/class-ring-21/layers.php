<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front View" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="rings/F.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="330" height="330" border="1"></layer>
		  <layer type="image" value="stones/square8/FRONT_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="100" height="14" top="92" left="-5" right="0" align="right" size="14" rotation="0" direction="CW" arc_args="45-320" xscale="0.7" yscale="1" skewx="10" mask="masks/F_left.png"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew2" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="50" height="18" top="72" left="80" right="0" align="center" size="18" rotation="0" direction="CW" arc_args="10-270" xscale="0.7" yscale="1" skewy="-5" masks="masks/F_top1.png" split_year="1"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew2" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="50" height="18" top="72" left="179" right="0" align="center" size="18" rotation="0" direction="CW" arc_args="10-270" xscale="0.7" yscale="1" skewy="5" masks="masks/F_top2.png" split_year="2"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="120" height="14" top="90" left="217" right="0" align="left" size="14" rotation="0" direction="CW" arc_args="35-25" xscale="0.7" yscale="1" skewx="0" mask="masks/F_right.png"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="160" height="14" top="160" left="100" right="0" align="center" size="14" rotation="0" direction="CW" arc_args="22-1" xscale="0.7" yscale="1" skewy="0" mask="masks/F_bottom.png"></layer>
	</step>
</steps>
EOT;
?>
