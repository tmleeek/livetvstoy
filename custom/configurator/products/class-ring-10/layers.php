<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Top View" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="rings/T.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" value="stones/marquise2/TOP_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="120" height="12" top="115" left="-42" right="0" align="center" size="12" rotation="0" direction="CW" arc_args="30-310" perspective="1" xscale="0.35" yscale="2" skewx="40" mask="masks/T_left1.png"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="120" height="12" top="148" left="-45" right="0" align="center" size="12" rotation="0" direction="CW" arc_args="30-310" perspective="1" xscale="0.35" yscale="2" skewx="40" mask="masks/T_left2.png"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="120" height="12" top="126" left="227" right="0" align="center" size="12" rotation="0" direction="CW" arc_args="20-16" perspective="1" xscale="0.45" yscale="1.8" skewx="-15" mask="masks/T_right1.png"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="60" height="12" top="162" left="232" right="0" align="center" size="12" rotation="0" direction="CW" arc_args="10-20" perspective="1" xscale="0.45" yscale="1.8" skewx="-18" mask="masks/T_right2.png"></layer>
	</step>
	<step name="Step 2: Front View" to_js="true" replace_preview="2">
		  <layer type="image" color="Black" value="rings/F.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" value="stones/marquise2/FRONT_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="120" height="16" top="128" left="7" right="0" align="center" size="16" rotation="0" direction="CW" arc_args="30-285" perspective="1" xscale="0.9" yscale="1" skewy="35" mask="masks/F_left1.png"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="120" height="16" top="148" left="30" right="0" align="center" size="16" rotation="0" direction="CW" arc_args="20-285" perspective="1" xscale="0.85" yscale="1" skewy="35" mask="masks/F_left2.png"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="150" height="18" top="12" left="185" right="0" align="center" size="18" rotation="0" direction="CW" arc_args="60-335" perspective="1" xscale="0.5" yscale="1" skewx="-20" mask="masks/F_right1.png"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="100" height="18" top="55" left="225" right="0" align="center" size="18" rotation="0" direction="CW" arc_args="60-370" perspective="1" xscale="0.5" yscale="1" skewx="-30" mask="masks/F_right2.png"></layer>
	</step>
	<step name="Step 3: Left View" to_js="true" replace_preview="3" merge_adjust="80">
		  <layer type="image" color="Black" value="rings/S.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" color="Black" value="stones/marquise2/SIDE_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="170" height="24" top="70" left="110" right="0" align="center" size="20" rotation="0" direction="CW" arc_args="1-267" perspective="1" xscale="0.7" yscale="1" skewx="0" mask="masks/S_left.png"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="170" height="24" top="65" left="140" right="0" align="center" size="20" rotation="0" direction="CW" arc_args="1-267" perspective="1" xscale="0.7" yscale="1" skewx="0" mask="masks/S_right.png"></layer>
	</step>
	<step name="Step 4: Right View" to_js="true" replace_preview="1" merge_adjust="90">
		  <layer type="image" color="Black" value="rings/S.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" color="Black" value="stones/marquise2/SIDE_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="160" height="24" top="47" left="133" right="0" align="center" size="20" rotation="0" direction="CW" arc_args="20-86" perspective="1" xscale="0.7" yscale="1" skewx="0"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="100" height="24" top="70" left="110" right="0" align="center" size="20" rotation="0" direction="CW" arc_args="1-88" perspective="1" xscale="0.7" yscale="1" skewx="0"></layer>
	</step>
</steps>
EOT;
?>
