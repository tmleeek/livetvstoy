<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Top View" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="rings/T.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" value="stones/square2/TOP_STONE.png" resize="1" top="42" left="-31" stone_style="colorized"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="0" syoffset="-3" sdepth="3" width="140" height="16" fit="1" top="80" left="25" right="0" align="center" size="16" rotation="270" direction="CCW" arc_args="30" perspective="3" yscale="0.9"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="0" syoffset="-3" sdepth="3" width="140" height="16" fit="1" top="81" left="233" right="0" align="center" size="16" rotation="90" direction="CCW" arc_args="30" perspective="3" yscale="0.9"></layer>
		  <layer type="text" font="Serifa BT" text_style="top_text_1" color="#dddddd" scolor="#555555" sdepth="5" width="160" height="188" top="41" left="55" size="28" side_pad="1" star_size="0.8" star_y="6" arc_top_rad="170" arc_bot_rad="120" rect="15"></layer>
		  <layer>
              <option type="image" selector_label="CLIPART" comp_type="cliparts" init_value="1" resize="1" cat_list="all" default="true" top="5" left="-128" color="Grey" rotation="90" color_style="emboss" sxoffset="4" syoffset="0" xscale="1.2" yscale="0.2" mask="masks/T_left.png"></option>
		  </layer>
          <layer>
              <option type="image" selector_label="CLIPART" comp_type="cliparts" init_value="1" resize="1" cat_list="all" default="true" top="-5" left="128" color="Grey" rotation="-90" color_style="emboss" sxoffset="-4" syoffset="0" xscale="1.2" yscale="0.2" mask="masks/T_right.png"></option>
		  </layer>
	</step>
	<step name="Step 2: Left View" to_js="true" replace_preview="3" merge_adjust="80">
		  <layer type="image" color="Black" value="rings/S.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" color="Black" value="stones/square2/SIDE_STONE.png" top="5" left="63" stone_style="colorized"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="0" syoffset="1" sdepth="1" width="94" height="20" fit="1" top="85" left="92" right="0" align="center" size="20" rotation="0" direction="CW" arc_args="1" yscale="1.4"></layer>
		  <layer type="text" font="Serifa BT" text_style="top_side_text" color="#dddddd" scolor="#555555" sdepth="3" width="250" height="300" top="32" left="22" size="24" side_pad="3" star_size="0.85" star_y="5" view_side="L" perspective="40" yscale="1.1" arc_args="1-180" mask="masks/S_upper.png"></layer>
          <layer>
              <option type="image" selector_label="CLIPART" comp_type="cliparts" init_value="1" cat_list="8-9-10-11-12" default="true" top="3" left="3" color="Grey" color_style="sigmodial" resize="0.5" mask="masks/S_lower.png"></option>
		  </layer>
	</step>
	<step name="Step 3: Right View" to_js="true" replace_preview="1" merge_adjust="90">
		  <layer type="image" color="Black" value="rings/S.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" color="Black" value="stones/square2/SIDE_STONE.png" top="5" left="63" stone_style="colorized"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="0" syoffset="1" sdepth="1" width="94" height="20" fit="1" top="85" left="92" right="0" align="center" size="20" rotation="0" direction="CW" arc_args="1" yscale="1.4"></layer>
		  <layer type="text" font="Serifa BT" text_style="top_side_text" color="#dddddd" scolor="#555555" sdepth="3" width="250" height="300" top="32" left="22" size="24" side_pad="3" star_size="0.85" star_y="5" view_side="R" perspective="50" yscale="1.1" arc_args="1-180" mask="masks/S_upper.png"></layer>
          <layer>
              <option type="image" selector_label="CLIPART" comp_type="cliparts" init_value="1" cat_list="8-9-10-11-12" default="true" top="3" left="3" color="Grey" color_style="sigmodial" resize="0.5" mask="masks/S_lower.png"></option>
		  </layer>
	</step>
	<step name="Step 4: Front View" to_js="true" replace_preview="2">
		  <layer type="image" color="Black" value="rings/F.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" value="stones/square2/FRONT_STONE.png" top="20" left="10" stone_style="colorized"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="2" syoffset="-2" sdepth="2" width="75" height="16" fit="1" top="73" left="18" right="0" align="center" size="16" rotation="-95" direction="CCW" arc_args="10" xscale="1.1" yscale="1.1" skewx="-28" skewy="8" mask="masks/F_left.png"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="-2" syoffset="-2" sdepth="2" width="75" height="16" fit="1" top="73" left="210" right="0" align="center" size="16" rotation="95" direction="CCW" arc_args="10" xscale="1.1" yscale="1.1" skewx="28" skewy="-8" mask="masks/F_right.png"></layer>
		  <layer type="text" font="Arial" text_style="arc2i" color="#555555" talpha="0.9" scolor="black" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" width="260" height="20" top="195" left="15" right="0" align="center" size="20" rotation="0" direction="CCW" arc_args="78-180" perspective="1.5" xscale="0.95" yscale="1" mask="masks/F_inside.png"></layer>
		  <layer type="text" font="Serifa BT" text_style="top_text_1" color="#dddddd" scolor="#555555" sdepth="5" width="194" height="142" top="10" left="31" size="26" side_pad="1" star_size="0.8" star_y="6" arc_top_rad="170" arc_bot_rad="121" rect="18" perspective="65" rotation="-1.3" mask="masks/F_top.png"></layer>
          <layer>
              <option type="image" selector_label="CLIPART" comp_type="cliparts" init_value="1" resize="1" cat_list="all" default="true" top="14" left="-123" color="Grey" color_style="emboss" sxoffset="4" syoffset="0" rotation="95" xscale="0.6" yscale="0.4" skewx="0" skewy="-55" mask="masks/F_left.png"></option>
		  </layer>
          <layer>
              <option type="image" selector_label="CLIPART" comp_type="cliparts" init_value="1" resize="1" cat_list="all" default="true" top="10" left="125" color="Grey" color_style="emboss" sxoffset="-4" syoffset="0" rotation="-90" xscale="0.6" yscale="0.4" skewx="0" skewy="55" mask="masks/F_right.png"></option>
		  </layer>
	</step>
</steps>
EOT;
?>
