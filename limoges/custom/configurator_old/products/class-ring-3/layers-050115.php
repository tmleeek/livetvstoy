<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Top View" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="rings/T.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" value="stones/square3/TOP_STONE.png" resize="1" stone_style="colorized"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="0" syoffset="-4" sdepth="4" width="165" height="16" fit="1" top="71" left="25" right="0" align="center" size="20" rotation="-89.5" direction="CCW" arc_args="50" perspective="3" yscale="0.54"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="0" syoffset="-4" sdepth="4" width="165" height="16" fit="1" top="72" left="236" right="0" align="center" size="16" rotation="90" direction="CCW" arc_args="50" perspective="3" yscale="0.5"></layer>
		  <layer type="text" font="Serifa BT" text_style="top_text_1" color="#dddddd" scolor="#555555" sdepth="8" width="194" height="218" top="24" left="38" size="60" side_pad="5" star_size="2.5" star_y="16" arc_top_rad="80" arc_bot_rad="62" rect="16"></layer>
		  <layer>
              <option type="image" selector_label="CLIPART" comp_type="cliparts" init_value="1" resize="1" cat_list="all" default="true" top="5" left="-129" color="Grey" rotation="90" color_style="emboss" sxoffset="4" syoffset="0" xscale="1.4" yscale="0.12" mask="masks/T_left.png"></option>
		  </layer>
          <layer>
              <option type="image" selector_label="CLIPART" comp_type="cliparts" init_value="1" resize="1" cat_list="all" default="true" top="-5" left="129" color="Grey" rotation="-90" color_style="emboss" sxoffset="-4" syoffset="0" xscale="1.4" yscale="0.16" mask="masks/T_right.png"></option>
		  </layer>
	</step>
	<step name="Step 2: Left View" to_js="true" replace_preview="3" merge_adjust="80">
		  <layer type="image" color="Black" value="rings/S.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" color="Black" value="stones/square3/SIDE_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="0" syoffset="1" sdepth="1" width="132" height="42" fit="1" top="103" left="73" right="0" align="center" size="23" rotation="0" direction="CCW" arc_args="6-180" perspective="6" yscale="1.1" mask="masks/S_lower.png"></layer>
		  <layer type="text" font="Serifa BT" text_style="top_side_text" color="#dddddd" scolor="#555555" sdepth="5" width="250" height="300" top="38" left="23" size="34" side_pad="3" star_size="1.3" star_y="5" view_side="L" perspective="50" yscale="0.95" arc_args="4-180" mask="masks/S_upper.png"></layer>
          <layer>
              <option type="image" selector_label="CLIPART" comp_type="cliparts" init_value="1" cat_list="0-1-2-3-4-5-6-7" default="true" top="20" left="6" color="Grey" color_style="sigmodial" resize="0.65" mask="masks/S_lower.png"></option>
		  </layer>
	</step>
	<step name="Step 3: Right View" to_js="true" replace_preview="1" merge_adjust="90">
		  <layer type="image" color="Black" value="rings/S.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" color="Black" value="stones/square3/SIDE_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="0" syoffset="1" sdepth="1" width="132" height="42" fit="1" top="103" left="73" right="0" align="center" size="23" rotation="0" direction="CCW" arc_args="6-180" perspective="6" yscale="1.1"></layer>
		  <layer type="text" font="Serifa BT" text_style="top_side_text" color="#dddddd" scolor="#555555" sdepth="5" width="250" height="300" top="38" left="23" size="32" side_pad="3" star_size="1.3" star_y="5" view_side="R" perspective="50" yscale="0.95" arc_args="4-180" mask="masks/S_upper.png"></layer>
          <layer>
              <option type="image" selector_label="CLIPART" comp_type="cliparts" init_value="1" cat_list="0-1-2-3-4-5-6-7" default="true" top="20" left="6" color="Grey" color_style="sigmodial" resize="0.65" mask="masks/S_lower.png"></option>
		  </layer>
	</step>
	<step name="Step 4: Front View" to_js="true" replace_preview="2">
		  <layer type="image" color="Black" value="rings/F.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" value="stones/square3/FRONT_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dbbe8d" talpha="1" scolor="#5d3510" salpha="1" sxoffset="1" syoffset="-3" sdepth="2" width="75" height="16" fit="1" top="66" left="-4" right="0" align="center" size="16" rotation="-107" direction="CCW" arc_args="64" xscale="1" yscale="1.2" skewx="-28" skewy="8" mask="masks/F_left.png"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dbbe8d" talpha="1" scolor="#5d3510" salpha="1" sxoffset="-1" syoffset="-3" sdepth="2" width="75" height="16" fit="1" top="68" left="190" right="0" align="center" size="16" rotation="106" direction="CCW" arc_args="64" xscale="1" yscale="1.2" skewx="28" skewy="-8" mask="masks/F_right.png"></layer>
		  <layer type="text" font="Arial" text_style="arc2i" color="black" talpha="0.9" scolor="#dbbe8d" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" width="260" height="18" top="196" left="8" right="-10" align="center" size="18" rotation="0" direction="CCW" arc_args="105-180" perspective="1.5" yscale="1" mask="masks/F_inside.png"></layer>
		  <layer type="text" font="Serifa BT" text_style="top_text_1" color="#dbbe8d" scolor="#5d3510" sdepth="8" width="246" height="170" top="-12" left="14" size="100" side_pad="5" star_size="3" star_y="16" arc_top_rad="205" arc_bot_rad="141" rect="15" perspective="72" rotation="0" mask="masks/F_top.png"></layer>
          <layer>
              <option type="image" selector_label="CLIPART" comp_type="cliparts" init_value="1" resize="1" cat_list="all" default="true" top="20" left="-123" color="Grey" color_style="emboss" sxoffset="4" syoffset="0" rotation="75" xscale="0.9" yscale="0.3" skewx="0" skewy="-55" mask="masks/F_left.png"></option>
		  </layer>
          <layer>
              <option type="image" selector_label="CLIPART" comp_type="cliparts" init_value="1" resize="1" cat_list="all" default="true" top="18" left="123" color="Grey" color_style="emboss" sxoffset="-4" syoffset="0" rotation="-90" xscale="0.7" yscale="0.3" skewx="0" skewy="55" mask="masks/F_right.png"></option>
		  </layer>
	</step>
</steps>
EOT;
?>
