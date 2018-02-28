<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Top View" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="rings/T.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" value="stones/marquise1/TOP_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="0" syoffset="-2" sdepth="2" width="120" height="38" fit="1" top="87" left="-44" right="0" align="center" size="38" rotation="-31" direction="CW" arc_args="40" perspective="1" yscale="1" mask="masks/T_left.png"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="0" syoffset="-2" sdepth="2" width="120" height="38" fit="1" top="154" left="186" right="0" align="center" size="38" rotation="153" direction="CCW" arc_args="20" perspective="1" yscale="1" mask="masks/T_right.png"></layer>
		  <layer type="text" font="Serifa BT" text_style="top_text_upper" color="#dddddd" scolor="#555555" sdepth="5" width="182" height="66" top="54" left="90" size="60" side_pad="1" rotation="-89" direction="CW" arc_top_rad="108" arc_bot_rad="78"></layer>
		  <layer type="text" font="Serifa BT" text_style="top_text_upper" color="#dddddd" scolor="#555555" sdepth="5" width="182" height="66" top="55" left="138" size="60" side_pad="1" rotation="91" direction="CW" arc_top_rad="108" arc_bot_rad="78"></layer>
	</step>
	<step name="Step 2: Left View" to_js="true" replace_preview="3" merge_adjust="80">
		  <layer type="image" color="Black" value="rings/S.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" color="Black" value="stones/marquise1/SIDE_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="0" syoffset="1" sdepth="1" width="120" height="28" fit="1" top="90" left="81" right="0" align="center" size="28" rotation="76" direction="CW" arc_args="10" yscale="1"></layer>
          <layer type="text" font="Serifa BT" text_style="top_text_upper" text_angle="90" color="#dddddd" scolor="#555555" sdepth="5" width="185" height="26" top="33" left="56" size="60" side_pad="1" rotation="180" direction="CW" arc_top_rad="60" arc_bot_rad="30" mask="masks/S_upper.png"></layer>
	</step>
	<step name="Step 3: Right View" to_js="true" replace_preview="1" merge_adjust="90">
		  <layer type="image" color="Black" value="rings/S.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" color="Black" value="stones/marquise1/SIDE_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="0" syoffset="1" sdepth="1" width="120" height="28" fit="1" top="90" left="81" right="0" align="center" size="28" rotation="76" direction="CW" arc_args="10" yscale="1"></layer>
          <layer type="text" font="Serifa BT" text_style="top_text_upper" text_angle="90" color="#dddddd" scolor="#555555" sdepth="5" width="185" height="26" top="33" left="56" size="60" side_pad="1" rotation="180" direction="CW" arc_top_rad="60" arc_bot_rad="30" mask="masks/S_upper.png"></layer>
	</step>
	<step name="Step 4: Front View" to_js="true" replace_preview="2">
		  <layer type="image" color="Black" value="rings/F.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" value="stones/marquise1/FRONT_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="2" syoffset="-2" sdepth="2" width="120" height="28" fit="1" top="68" left="-25" right="0" align="center" size="28" rotation="-43" direction="CW" arc_args="45" xscale="1" yscale="0.5" skewx="28" skewy="0" mask="masks/F_left.png"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="-2" syoffset="-2" sdepth="2" width="120" height="32" fit="1" top="155" left="168" right="0" align="center" size="32" rotation="20" direction="CW" arc_args="65" xscale="1" yscale="1" skewx="-22" skewy="0"  mask="masks/F_right.png"></layer>
		  <layer type="text" font="Arial" text_style="arc2i" color="#555555" talpha="0.9" scolor="black" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" width="240" height="20" top="199" left="11" right="0" align="center" size="24" rotation="4" direction="CCW" arc_args="80-180" perspective="1.5" xscale="1" yscale="1" mask="masks/F_inside.png"></layer>
		  <layer type="text" font="Serifa BT" text_style="top_text_upper" color="#dddddd" scolor="#555555" sdepth="5" width="192" height="66" top="10" left="89" size="70" side_pad="1" rotation="-89" direction="CW" arc_top_rad="120" arc_bot_rad="50" perspective="40" mask="masks/F_top.png"></layer>
		  <layer type="text" font="Serifa BT" text_style="top_text_upper" color="#dddddd" scolor="#555555" sdepth="5" width="192" height="66" top="10" left="137" size="70" side_pad="1" rotation="91" direction="CW" arc_top_rad="120" arc_bot_rad="50" perspective="40" mask="masks/F_top.png"></layer>
	</step>
</steps>
EOT;
?>
