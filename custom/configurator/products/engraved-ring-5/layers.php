<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front Side" default_view="true" to_js="true" replace_preview="0">
		<layer type="image" color="Black" value="/rings/engraved/F.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="330" height="330" border="1"></layer>
    	<layer type="text" font="Script 33" text_style="arcnew1" ucolor="#444444" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#777777" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" usecolor="true" stroke="true" strokecolor="#ffffff" strokewidth="2" width="360" height="36" top="202" left="3" align="center" size="36" rotation="0" direction="CCW" arc_args="72-178" perspective="1.5" xscale="0.85" yscale="0.95" mask="masks/F_mask.png"></layer>
    </step>
</steps>
EOT;
?>