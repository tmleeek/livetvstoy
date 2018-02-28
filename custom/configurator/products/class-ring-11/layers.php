<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Top View" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="rings/T.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" value="stones/square5/TOP_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="200" height="14" top="117" left="-54" right="0" align="right" size="14" rotation="0" direction="CW" arc_args="20-332" perspective="1" xscale="0.5" yscale="1.1" skewx="25" mask="masks/T_left1.png"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="200" height="14" top="158" left="-40" right="0" align="right" size="14" rotation="0" direction="CW" arc_args="20-355" perspective="1" xscale="0.55" yscale="1.1" skewx="-3" mask="masks/T_left2.png"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="200" height="14" top="116" left="200" right="0" align="left" size="14" rotation="0" direction="CW" arc_args="15-23" perspective="1" xscale="0.5" yscale="1.1" skewx="-15" mask="masks/T_right1.png"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="200" height="14" top="158" left="210" right="0" align="left" size="14" rotation="0" direction="CW" arc_args="10-360" perspective="1" xscale="0.65" yscale="1.1" skewx="2" mask="masks/T_right2.png"></layer>
	</step>
	<step name="Step 2: Front View" to_js="true" replace_preview="2">
		  <layer type="image" color="Black" value="rings/F.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" value="stones/square5/FRONT_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="140" height="12" top="100" left="22" right="0" align="right" size="12" rotation="0" direction="CW" arc_args="10-291" perspective="1" xscale="0.9" yscale="1" skewy="20" mask="masks/F_left1.png"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="140" height="12" top="144" left="27" right="0" align="right" size="12" rotation="0" direction="CW" arc_args="20-310" perspective="1" xscale="0.8" yscale="1.2" skewy="0"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="140" height="10" top="41" left="173" right="0" align="left" size="10" rotation="0" direction="CW" arc_args="25-9" perspective="1" xscale="0.7" yscale="0.9" skewx="-45" mask="masks/F_right1.png"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="100" height="12" top="54" left="196" right="0" align="left" size="12" rotation="0" direction="CW" arc_args="30-349" perspective="1" xscale="0.7" yscale="1" skewx="-30" mask="masks/F_right2.png"></layer>
	</step>
	<step name="Step 3: Left View" to_js="true" replace_preview="3" merge_adjust="80">
		  <layer type="image" color="Black" value="rings/S.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" color="Black" value="stones/square5/SIDE_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="170" height="13" top="60" left="114" right="0" align="right" size="13" rotation="-98" direction="CCW" arc_args="15-180" perspective="1" xscale="0.6" yscale="1" skewx="0" mask="masks/S_left.png"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="170" height="13" top="60" left="141" right="0" align="right" size="13" rotation="0" direction="CW" arc_args="15-277" perspective="1" xscale="0.6" yscale="1" skewx="0" mask="masks/S_right.png"></layer>
	</step>
	<step name="Step 4: Right View" to_js="true" replace_preview="1" merge_adjust="90">
		  <layer type="image" color="Black" value="rings/S.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" color="Black" value="stones/square5/SIDE_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="160" height="13" top="60" left="136" right="0" align="left" size="13" rotation="96" direction="CCW" arc_args="10-180" perspective="1" xscale="0.6" yscale="1" skewx="0"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dfc796" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="100" height="13" top="60" left="114" right="0" align="left" size="13" rotation="0" direction="CW" arc_args="10-80" perspective="1" xscale="0.6" yscale="1" skewx="0" mask="masks/S_left.png"></layer>
	</step>
</steps>
EOT;
?>
