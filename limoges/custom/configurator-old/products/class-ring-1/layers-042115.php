<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Top View" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="rings/T.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" value="stones/square1/TOP_STONE.png" top="0" left="0" stone_style="colorized"></layer>
          <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="0" syoffset="2" sdepth="1" width="162" height="71" fit="1" top="170" left="58" right="0" align="center" size="35" rotation="0.1" direction="CW" arc_args="2" yscale="1"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="0" syoffset="-4" sdepth="4" width="165" height="16" fit="1" top="71" left="21" right="0" align="center" size="20" rotation="-89.5" direction="CCW" arc_args="50" perspective="3" yscale="0.54"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="0" syoffset="-4" sdepth="4" width="165" height="16" fit="1" top="72" left="238" right="0" align="center" size="16" rotation="90" direction="CCW" arc_args="50" perspective="3" yscale="0.5"></layer>
		  <layer type="text" font="Serifa BT" text_style="top_text_1" color="#dddddd" scolor="#555555" sdepth="8" width="194" height="218" top="24" left="37" size="60" side_pad="5" star_size="2.5" star_y="16" arc_top_rad="80" arc_bot_rad="62" rect="16"></layer>
          <layer>
              <option type="image" selector_label="CLIPART" comp_type="cliparts" init_value="1" resize="1" cat_list="all" default="true" top="10" left="-128" color="Grey" rotation="90" color_style="emboss" sxoffset="2" syoffset="0" xscale="1.3" yscale="0.1"></option>
		  </layer>
          <layer>
              <option type="image" selector_label="CLIPART" comp_type="cliparts" init_value="1" resize="1" cat_list="all" default="true" top="0" left="128" color="Grey" rotation="-90" color_style="emboss" sxoffset="-4" syoffset="0" xscale="1.3" yscale="0.1"></option>
		  </layer>
	</step>
	<step name="Step 2: Left View" to_js="true" replace_preview="3" merge_adjust="80">
		  <layer type="image" color="Black" value="rings/S.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" color="Black" value="stones/square1/SIDE_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="-2" syoffset="0" sdepth="2" width="40" height="9" fit="1" top="-25" left="105" right="0" align="center" size="9" rotation="-90" direction="CW" arc_args="1" yscale="3" skewy="8" mask="masks/S_topmask.png"></layer>
          <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="0" syoffset="1" sdepth="1" width="132" height="42" fit="1" top="86" left="70.5" right="0" align="center" size="23" rotation="0" direction="CCW" arc_args="6-180" perspective="6" yscale="1.1" mask="masks/S_sidemask.png"></layer>
		  <layer type="text" font="Serifa BT" text_style="top_side_text" color="#dddddd" scolor="#555555" sdepth="5" width="450" height="300" top="5" left="-75" size="32" side_pad="3" star_size="1.4" view_side="L" mask="masks/S_upper.png" perspective="60" yscale="0.95" arc_args="20-180"></layer>
          <layer>
              <option type="image" selector_label="CLIPART" comp_type="cliparts" init_value="1" cat_list="all" default="true" top="5" left="4" color="Grey" color_style="sigmodial" resize="0.65" mask="masks/S_sidemask.png"></option>
		  </layer>
	</step>
	<step name="Step 3: Right View" to_js="true" replace_preview="1" merge_adjust="90">
		  <layer type="image" color="Black" value="rings/S.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" color="Black" value="stones/square1/SIDE_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="2" syoffset="0" sdepth="2" width="40" height="9" fit="1" top="-25" left="105" right="0" align="center" size="9" rotation="90" direction="CW" arc_args="1" yscale="3" skewy="8" mask="masks/S_topmask.png"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="0" syoffset="1" sdepth="1" width="132" height="42" fit="1" top="86" left="70.5" right="0" align="center" size="23" rotation="0" direction="CCW" arc_args="6-180" perspective="6" yscale="1.1" mask="masks/S_sidemask.png"></layer>
		  <layer type="text" font="Serifa BT" text_style="top_side_text" color="#dddddd" scolor="#555555" sdepth="5" width="450" height="300" top="5" left="-75" size="32" side_pad="3" star_size="1.4" view_side="R" mask="masks/S_upper.png" perspective="60" yscale="0.95" arc_args="20-180"></layer>
          <layer>
              <option type="image" selector_label="CLIPART" comp_type="cliparts" init_value="1" cat_list="all" default="true" top="5" left="4" color="Grey" color_style="sigmodial" resize="0.65" mask="masks/S_sidemask.png"></option>
		  </layer>
	</step>
	<step name="Step 4: Front View" to_js="true" replace_preview="2">
		  <layer type="image" color="Black" value="rings/F.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
		  <layer type="image" value="stones/square1/FRONT_STONE.png" stone_style="colorized"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="0" syoffset="3" sdepth="3" width="135" height="16" fit="1" top="49" left="68" right="0" align="center" size="16" rotation="-0.5" direction="CW" arc_args="20" perspective="3" yscale="0.9"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="1" syoffset="-3" sdepth="2" width="75" height="16" fit="1" top="71" left="5" right="0" align="center" size="16" rotation="-105" direction="CCW" arc_args="54" xscale="0.9" yscale="1.1" skewx="-28" skewy="8" mask="masks/F_leftmask.png"></layer>
		  <layer type="text" font="Arial Black" text_style="arc2" color="#dddddd" talpha="1" scolor="#555555" salpha="1" sxoffset="-1" syoffset="-3" sdepth="2" width="75" height="16" fit="1" top="70" left="202" right="0" align="center" size="16" rotation="100" direction="CCW" arc_args="64" xscale="0.9" yscale="1.1" skewx="28" skewy="-8" mask="masks/F_rightmask.png"></layer>
		  <layer type="text" font="Arial" text_style="arc2i" color="#555555" talpha="1" scolor="black" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" width="260" height="20" top="190" left="10" right="-10" align="center" size="16" rotation="0" direction="CCW" arc_args="105-178" perspective="1.5" yscale="1" mask="masks/F_insidemask.png"></layer>
		  <layer type="text" font="Serifa BT" text_style="top_text_1" color="#dddddd" scolor="#555555" sdepth="8" width="243" height="143" top="0" left="11" size="90" side_pad="5" star_size="2.5" star_y="16" arc_top_rad="205" arc_bot_rad="141" rect="15" perspective="70" rotation="-1.3" mask="masks/F_top_text_mask.png"></layer>
          <layer>
              <option type="image" selector_label="CLIPART" comp_type="cliparts" init_value="1" resize="1" cat_list="all" default="true" top="14" left="-123" color="Grey" color_style="emboss" sxoffset="4" syoffset="0" rotation="75" xscale="0.6" yscale="0.4" skewx="0" skewy="-55" mask="masks/F_leftmask.png"></option>
		  </layer>
          <layer>
              <option type="image" selector_label="CLIPART" comp_type="cliparts" init_value="1" resize="1" cat_list="all" default="true" top="10" left="125" color="Grey" color_style="emboss" sxoffset="-4" syoffset="0" rotation="-85" xscale="0.5" yscale="0.3" skewx="0" skewy="55" mask="masks/F_rightmask.png"></option>
		  </layer>
	</step>
</steps>
EOT;
?>
