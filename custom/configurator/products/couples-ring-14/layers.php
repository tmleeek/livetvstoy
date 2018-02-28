<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front Side" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="/rings/couples/F.png" resize="1" img_type="jpg" jpg_quality="90" left="0" top="0" width="330" height="330" border="1"></layer>
          <layer type="text" font="Alison" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dddddd" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="170" height="22" top="140" left="-23" align="center" size="22" rotation="0" direction="CW" arc_args="60-327" perspective="1" xscale="0.55" yscale="1" skewx="25" mask="masks/F_left.png"></layer>
          <layer type="text" font="Alison" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dddddd" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="170" height="22" top="175" left="228" align="center" size="22" rotation="0" direction="CW" arc_args="40-19" perspective="1" xscale="0.55" yscale="1" skewx="-10" mask="masks/F_right.png"></layer>
	</step>
</steps>
EOT;
?>
