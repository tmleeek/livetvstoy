<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front Side" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="/pendants/engraved/F.png" resize="1" img_type="jpg" jpg_quality="90" left="0" top="0" width="330" height="330" border="1"></layer>
		  <layer type="text" font="Arial" text_style="arcnew2" ucolor="#999999" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#bbbbbb" salpha="1" sxoffset="0" syoffset="0" sdepth="1" usecolor="true" stroke="true" strokecolor="#eeeeee" strokewidth="1" width="90" height="20" top="97" left="185" align="center" size="20" rotation="87" direction="CCW" arc_args="1" xscale="1" yscale="1.1" mask="masks/F_left.png" fit="1" kern="2"></layer>
		  <layer type="text" font="Arial" text_style="arcnew2" ucolor="#999999" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#bbbbbb" salpha="1" sxoffset="0" syoffset="0" sdepth="1" usecolor="true" stroke="true" strokecolor="#eeeeee" strokewidth="1" width="90" height="20" top="97" left="244" align="center" size="20" rotation="96" direction="CCW" arc_args="1" xscale="1" yscale="1.1" mask="masks/F_right.png" fit="1" kern="2"></layer>
	</step>
</steps>
EOT;
?>
