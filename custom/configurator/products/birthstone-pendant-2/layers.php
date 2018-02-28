<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front Side" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="/pendants/birthstone/F.png" resize="1" img_type="jpg" jpg_quality="90" left="0" top="0" width="330" height="330" border="1"></layer>
          <layer type="image" value="stones/birthstone-pendant-2/FRONT_STONE.png" top="0" left="0" stone_style="colorized"></layer>
          <layer type="text" font="Apple Chancery" text_style="vertical_text_1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#999999" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" usecolor="true" stroke="true" strokecolor="#ffffe8" strokewidth="1" width="35" height="186" top="134" bottom="248" left="155" right="0" align="center" size="28" rotation="0" direction="CW" yscale="1" line_height="1.3"></layer>
	</step>
</steps>
EOT;
?>
