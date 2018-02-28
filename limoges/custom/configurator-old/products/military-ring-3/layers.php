<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Top View" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="rings/T.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" value="stones/oval1/TOP_STONE.png" left="-2" top="18" resize="1" stone_style="colorized"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="0" syoffset="-3" sdepth="3" width="142" height="16" fit="1" top="80" left="34" right="0" align="center" size="12" rotation="-89.5" direction="CCW" arc_args="50" perspective="3" xscale="1" yscale="0.7" mask="masks/T_left.png"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="0" syoffset="-3" sdepth="3" width="142" height="16" fit="1" top="82" left="225" right="0" align="center" size="14" rotation="90" direction="CCW" arc_args="50" perspective="3" xscale="1" yscale="0.7" mask="masks/T_right.png"></layer>
		  <layer type="text" font="Serifa BT" text_style="top_text_1" color="#dddddd" scolor="#555555" sdepth="6" width="170" height="197" top="51" left="65" size="32" side_pad="1" star_size="0.7" star_y="4" arc_top_rad="170" arc_bot_rad="140" rect="1"></layer>
		  <layer>
              <option type="image" selector_label="CLIPART" comp_type="cliparts" init_value="1" resize="1" cat_list="all" default="true" top="5" left="-122" color="Grey" rotation="90" color_style="emboss" sxoffset="4" syoffset="0" xscale="1.3" yscale="0.2" mask="masks/T_left.png"></option>
		  </layer>
          <layer>
              <option type="image" selector_label="CLIPART" comp_type="cliparts" init_value="1" resize="1" cat_list="all" default="true" top="5" left="127" color="Grey" rotation="-90" color_style="emboss" sxoffset="-4" syoffset="0" xscale="1.3" yscale="0.2" mask="masks/T_right.png"></option>
		  </layer>
	</step>
	<step name="Step 2: Left View" to_js="true" replace_preview="3" merge_adjust="80">
		  <layer type="image" color="Black" value="rings/S.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" color="Black" value="stones/oval1/SIDE_STONE.png" top="-13" left="8" stone_style="colorized"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="0" syoffset="1" sdepth="1" width="96" height="20" fit="1" top="90" left="94" right="0" align="center" size="23" rotation="0" direction="CCW" arc_args="6-180" perspective="6" yscale="1.1" mask="masks/S_lower.png"></layer>
		  <layer type="text" font="Serifa BT" text_style="top_side_text" color="#dddddd" scolor="#555555" sdepth="3" width="250" height="300" top="34" left="22" size="25" side_pad="3" star_size="0.85" star_y="5" view_side="L" perspective="40" yscale="1.15" arc_args="1-180" mask="masks/S_upper.png"></layer>
          <layer>
              <option type="image" selector_label="CLIPART" comp_type="cliparts" init_value="1" cat_list="8-9-10-11-12" default="true" top="4" left="8" color="Grey" color_style="sigmodial" resize="0.5" mask="masks/S_lower.png"></option>
		  </layer>
	</step>
	<step name="Step 3: Right View" to_js="true" replace_preview="1" merge_adjust="90">
		  <layer type="image" color="Black" value="rings/S.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" color="Black" value="stones/oval1/SIDE_STONE.png" top="-13" left="8"  stone_style="colorized"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="0" syoffset="1" sdepth="1" width="96" height="20" fit="1" top="90" left="94" right="0" align="center" size="23" rotation="0" direction="CCW" arc_args="6-180" perspective="6" yscale="1.1" mask="masks/S_lower.png"></layer>
		  <layer type="text" font="Serifa BT" text_style="top_side_text" color="#dddddd" scolor="#555555" sdepth="3" width="250" height="300" top="34" left="22" size="25" side_pad="3" star_size="0.85" star_y="5" view_side="R" perspective="40" yscale="1.15" arc_args="1-180" mask="masks/S_upper.png"></layer>
          <layer>
              <option type="image" selector_label="CLIPART" comp_type="cliparts" init_value="1" cat_list="8-9-10-11-12" default="true" top="4" left="8" color="Grey" color_style="sigmodial" resize="0.5" mask="masks/S_lower.png"></option>
		  </layer>
	</step>
	<step name="Step 4: Front View" to_js="true" replace_preview="2">
		  <layer type="image" color="Black" value="rings/F.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" value="stones/oval1/FRONT_STONE.png" top="10" left="-5" stone_style="colorized"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dbbe8d" talpha="1" scolor="#5d3510" salpha="1" sxoffset="1" syoffset="-3" sdepth="2" width="110" height="16" fit="1" top="68" left="4" right="0" align="center" size="16" rotation="-100" direction="CCW" arc_args="34" xscale="0.9" yscale="1.1" skewx="-28" skewy="8" mask="masks/F_left.png"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dbbe8d" talpha="1" scolor="#5d3510" salpha="1" sxoffset="-1" syoffset="-3" sdepth="2" width="110" height="16" fit="1" top="65" left="200" right="0" align="center" size="16" rotation="95" direction="CCW" arc_args="34" xscale="0.9" yscale="1.1" skewx="28" skewy="-8" mask="masks/F_right.png"></layer>
		  <layer type="text" font="Arial" text_style="arc2i" color="black" talpha="0.9" scolor="#dbbe8d" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" width="260" height="18" top="188" left="8" right="0" align="center" size="18" rotation="-2" direction="CCW" arc_args="80-180" perspective="1.5" yscale="1" mask="masks/F_inside.png"></layer>
		  <layer type="text" font="Serifa BT" text_style="top_text_1" color="#dbbe8d" scolor="#5d3510" sdepth="5" width="228" height="175" top="5" left="20" size="30" side_pad="1" star_size="0.8" star_y="6" arc_top_rad="150" arc_bot_rad="110" rect="10" perspective="70" rotation="-1.3" mask="masks/F_top.png"></layer>
          <layer>
              <option type="image" selector_label="CLIPART" comp_type="cliparts" init_value="1" resize="1" cat_list="all" default="true" top="26" left="-130" color="Grey" color_style="emboss" sxoffset="4" syoffset="0" rotation="90" xscale="1" yscale="0.4" skewx="0" skewy="-55" mask="masks/F_left.png"></option>
		  </layer>
          <layer>
              <option type="image" selector_label="CLIPART" comp_type="cliparts" init_value="1" resize="1" cat_list="all" default="true" top="14" left="123" color="Grey" color_style="emboss" sxoffset="-4" syoffset="0" rotation="-98" xscale="0.8" yscale="0.3" skewx="0" skewy="55" mask="masks/F_right.png"></option>
		  </layer>
	</step>
</steps>
EOT;
?>
