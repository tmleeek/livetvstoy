<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Top View" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="rings/T.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" value="stones/square4/TOP_STONE.png" top="0" left="0" stone_style="colorized"></layer>
          <layer type="text" font="Arial Black" text_style="top_text_upper" text_engraved="true" text_angle="130" color="#555555" talpha="1" scolor="black" salpha="1" sxoffset="0" syoffset="2" sdepth="3" width="160" height="70" fit="1" top="42" left="60" right="0" align="center" size="50" rotation="0" direction="CW" arc_top_rad="100" arc_bot_rad="60" yscale="1" rect="10"></layer>
          <layer type="text" font="Arial Black" text_style="top_text_lower" text_engraved="true" text_angle="130" color="#555555" talpha="1" scolor="black" salpha="1" sxoffset="0" syoffset="2" sdepth="3" width="160" height="70" fit="1" top="165" left="61" right="0" align="center" size="50" rotation="0" direction="CW" arc_top_rad="100" arc_bot_rad="60" yscale="1" rect="10"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="0" syoffset="-3" sdepth="3" width="165" height="16" fit="1" top="71" left="20" right="0" align="center" size="20" rotation="-89.5" direction="CCW" arc_args="50" perspective="3" yscale="0.45"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="0" syoffset="-3" sdepth="3" width="165" height="16" fit="1" top="72" left="241" right="0" align="center" size="16" rotation="90" direction="CCW" arc_args="50" perspective="3" yscale="0.45"></layer>
		  <layer type="text" font="Serifa BT" text_style="top_text_1" color="#dddddd" scolor="#555555" sdepth="8" width="194" height="218" top="24" left="37" size="60" side_pad="5" star_size="2.5" star_y="16" arc_top_rad="80" arc_bot_rad="67" rect="16"></layer>
          <layer>
              <option type="image" selector_label="CLIPART" comp_type="cliparts" init_value="1" resize="1" cat_list="all" default="true" top="10" left="-130" color="Grey" rotation="90" color_style="emboss" sxoffset="2" syoffset="0" xscale="1.3" yscale="0.1" mask="masks/T_left.png"></option>
		  </layer>
          <layer>
              <option type="image" selector_label="CLIPART" comp_type="cliparts" init_value="1" resize="1" cat_list="all" default="true" top="0" left="130" color="Grey" rotation="-90" color_style="emboss" sxoffset="-4" syoffset="0" xscale="1.3" yscale="0.1" mask="masks/T_right.png"></option>
		  </layer>
	</step>
	<step name="Step 2: Left View" to_js="true" replace_preview="3" merge_adjust="80">
		  <layer type="image" color="Black" value="rings/S.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
          <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="0" syoffset="1" sdepth="1" width="132" height="42" fit="1" top="88" left="70.5" right="0" align="center" size="23" rotation="0" direction="CCW" arc_args="6-180" perspective="6" yscale="1.1" mask="masks/S_lower.png"></layer>
		  <layer type="text" font="Serifa BT" text_style="top_side_text" color="#dddddd" scolor="#555555" sdepth="4" width="250" height="300" top="21" left="25" size="32" side_pad="3" star_size="1.2" star_y="5" view_side="L" perspective="50" yscale="0.85" arc_args="10-180" mask="masks/S_upper.png"></layer>
          <layer>
              <option type="image" selector_label="CLIPART" comp_type="cliparts" init_value="1" cat_list="0-1-2-3-4-5-6-7" default="true" top="5" left="4" color="Grey" color_style="sigmodial" resize="0.65" mask="masks/S_lower.png"></option>
		  </layer>
	</step>
	<step name="Step 3: Right View" to_js="true" replace_preview="1" merge_adjust="90">
		  <layer type="image" color="Black" value="rings/S.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="0" syoffset="1" sdepth="1" width="132" height="42" fit="1" top="88" left="70.5" right="0" align="center" size="23" rotation="0" direction="CCW" arc_args="6-180" perspective="6" yscale="1.1" mask="masks/S_lower.png"></layer>
		  <layer type="text" font="Serifa BT" text_style="top_side_text" color="#dddddd" scolor="#555555" sdepth="5" width="250" height="300" top="21" left="25" size="32" side_pad="3" star_size="1.2" star_y="5" view_side="R" perspective="50" yscale="0.85" arc_args="10-180" mask="masks/S_upper.png"></layer>
          <layer>
              <option type="image" selector_label="CLIPART" comp_type="cliparts" init_value="1" cat_list="0-1-2-3-4-5-6-7" default="true" top="5" left="4" color="Grey" color_style="sigmodial" resize="0.65" mask="masks/S_lower.png"></option>
		  </layer>
	</step>
	<step name="Step 4: Front View" to_js="true" replace_preview="2">
		  <layer type="image" color="Black" value="rings/F.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" value="stones/square4/FRONT_STONE.png" stone_style="colorized"></layer>
          <layer type="text" font="Arial Black" text_style="top_text_upper" text_engraved="true" text_angle="130" color="#555555" talpha="1" scolor="black" salpha="1" sxoffset="0" syoffset="2" sdepth="3" width="146" height="58" fit="1" top="9" left="66" right="0" align="center" size="50" rotation="0" direction="CW" arc_top_rad="90" arc_bot_rad="50" yscale="1" rect="10"></layer>
          <layer type="text" font="Arial Black" text_style="top_text_lower" text_engraved="true" text_angle="130" color="#555555" talpha="1" scolor="black" salpha="1" sxoffset="0" syoffset="2" sdepth="3" width="135" height="42" fit="1" top="85" left="71" right="0" align="center" size="50" rotation="0" direction="CW" arc_top_rad="60" arc_bot_rad="40" yscale="1" rect="10"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="1" syoffset="-3" sdepth="2" width="75" height="16" fit="1" top="89" left="-3" right="0" align="center" size="16" rotation="-105" direction="CCW" arc_args="54" xscale="0.85" yscale="1.1" skewx="-28" skewy="8" mask="masks/F_left.png"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="-1" syoffset="-3" sdepth="2" width="75" height="16" fit="1" top="84" left="202" right="0" align="center" size="16" rotation="107" direction="CCW" arc_args="64" xscale="0.85" yscale="1.1" skewx="28" skewy="-8" mask="masks/F_right.png"></layer>
		  <layer type="text" font="Arial" text_style="arc2i" color="#555555" talpha="1" scolor="black" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" width="260" height="20" top="195" left="15" right="0" align="center" size="24" rotation="0" direction="CCW" arc_args="95-178" perspective="1.5" yscale="1" mask="masks/F_inside.png"></layer>
		  <layer type="text" font="Serifa BT" text_style="top_text_1" color="#dddddd" scolor="#555555" sdepth="8" width="264" height="162" top="1" left="-2" size="90" side_pad="5" star_size="2.5" star_y="16" arc_top_rad="200" arc_bot_rad="148" rect="15" perspective="80" rotation="-1.3" mask="masks/F_top.png"></layer>
          <layer>
              <option type="image" selector_label="CLIPART" comp_type="cliparts" init_value="1" resize="1" cat_list="all" default="true" top="20" left="-128" color="Grey" color_style="emboss" sxoffset="4" syoffset="0" rotation="75" xscale="0.6" yscale="0.3" skewx="0" skewy="-55" mask="masks/F_left.png"></option>
		  </layer>
          <layer>
              <option type="image" selector_label="CLIPART" comp_type="cliparts" init_value="1" resize="1" cat_list="all" default="true" top="20" left="125" color="Grey" color_style="emboss" sxoffset="-4" syoffset="0" rotation="-85" xscale="0.5" yscale="0.3" skewx="0" skewy="55" mask="masks/F_right.png"></option>
		  </layer>
	</step>
</steps>
EOT;
?>
