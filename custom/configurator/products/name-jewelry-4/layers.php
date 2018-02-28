<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Top View" group_fields="false" default_view="true">
	  <layer type="image" color="Black" value="/pendants/blank-wide.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="400" height="400" border="1"></layer>
      <layer type="text" font="Brush Script Std" text_style="name_text_new_2" usecolor="true" ucolor="#dcdcde" talpha="1" uscolor="#7d634e" salpha="1" sxoffset="1" syoffset="1" sdepth="1" width="330" height="100" fit="2" top="210" left="25" right="0" align="center" size="100" hook_img="pendants/name-jewelry/hook20.png" bridge_img="pendants/name-jewelry/bridge.png" chain_left_img="pendants/name-jewelry/left-chain-1.png" chain_right_img="pendants/name-jewelry/right-chain-1.png" bottom_img_center="pendants/name-jewelry/heart.png" bottom_img_left="pendants/name-jewelry/left-scroll.png" bottom_img_right="pendants/name-jewelry/right-scroll.png"></layer>
      <layer type="image" value="pendants/name-jewelry/stone1.png" top="328" left="193" stone_style="colorized" resize="1"></layer>
	</step>
</steps>
EOT;
?>