<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Top View" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="rings/T.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" value="stones/square5/TOP_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dddddd" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="200" height="18" top="119" left="-50" right="0" align="right" size="18" rotation="0" direction="CW" arc_args="20-332" perspective="1" xscale="0.5" yscale="1" skewx="25" mask="masks/T_left.png"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dddddd" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="200" height="18" top="172" left="-40" right="0" align="right" size="18" rotation="0" direction="CW" arc_args="20-352" perspective="1" xscale="0.55" yscale="1" skewx="2" mask="masks/T_left.png"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dddddd" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="200" height="18" top="121" left="198" right="0" align="left" size="18" rotation="0" direction="CW" arc_args="15-25" perspective="1" xscale="0.5" yscale="1" skewx="-20" mask="masks/T_right.png"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dddddd" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="200" height="20" top="175" left="216" right="0" align="left" size="20" rotation="0" direction="CW" arc_args="10-6" perspective="1" xscale="0.6" yscale="1" skewx="0" mask="masks/T_right.png"></layer>
	</step>
	<step name="Step 2: Front View" to_js="true" replace_preview="2">
		  <layer type="image" color="Black" value="rings/F.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" value="stones/square5/FRONT_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dddddd" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="160" height="14" top="80" left="0" right="0" align="right" size="14" rotation="0" direction="CW" arc_args="25-295" perspective="1" xscale="0.9" yscale="1" skewy="20" mask="masks/F_left.png"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dddddd" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="160" height="14" top="142" left="5" right="0" align="right" size="14" rotation="0" direction="CW" arc_args="30-311" perspective="1" xscale="0.8" yscale="1.2" skewy="0" mask="masks/F_left.png"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dddddd" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="140" height="14" top="48" left="178" right="0" align="left" size="14" rotation="0" direction="CW" arc_args="25-17" perspective="1" xscale="0.7" yscale="0.9" skewx="-45" mask="masks/F_right.png"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dddddd" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="100" height="16" top="83" left="214" right="0" align="left" size="16" rotation="0" direction="CW" arc_args="30-7" perspective="1" xscale="0.7" yscale="1" skewx="-30" mask="masks/F_right.png"></layer>
	</step>
	<step name="Step 3: Left View" to_js="true" replace_preview="3" merge_adjust="80">
		  <layer type="image" color="Black" value="rings/S.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" color="Black" value="stones/square5/SIDE_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dddddd" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="170" height="16" top="70" left="100" right="0" align="right" size="16" rotation="-98" direction="CCW" arc_args="15-184" perspective="1" xscale="0.6" yscale="1" skewx="0" mask="masks/S_mask.png"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dddddd" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="170" height="16" top="70" left="144" right="0" align="right" size="16" rotation="0" direction="CW" arc_args="15-276" perspective="1" xscale="0.6" yscale="1" skewx="0" mask="masks/S_mask.png"></layer>
	</step>
	<step name="Step 4: Right View" to_js="true" replace_preview="1" merge_adjust="90">
		  <layer type="image" color="Black" value="rings/S.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" color="Black" value="stones/square5/SIDE_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dddddd" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="160" height="16" top="70" left="140" right="0" align="left" size="16" rotation="96" direction="CCW" arc_args="10-181" perspective="1" xscale="0.6" yscale="1" skewx="0"></layer>
		  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dddddd" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="100" height="16" top="70" left="108" right="0" align="left" size="16" rotation="0" direction="CW" arc_args="10-85" perspective="1" xscale="0.6" yscale="1" skewx="0" mask="masks/S_mask.png"></layer>
	</step>
</steps>
EOT;
?>
