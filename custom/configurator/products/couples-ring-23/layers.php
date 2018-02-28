<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front Side" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="/rings/couples/F.png" resize="1" img_type="jpg" jpg_quality="90" left="0" top="0" width="330" height="330" border="1"></layer>
          <layer type="text" font="Alison" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dddddd" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="130" height="30" top="122" left="-10" align="right" size="20" rotation="0" direction="CW" arc_args="50-342" perspective="1" xscale="0.65" yscale="1" skewx="15" skewy="-5" mask="masks/F_left.png"></layer>
          <layer type="text" font="Alison" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dddddd" salpha="1" sxoffset="1" syoffset="1" sdepth="1" usecolor="true" width="150" height="28" top="124" left="215" align="left" size="20" rotation="0" direction="CW" arc_args="50-25" perspective="1" xscale="0.65" yscale="1" skewx="-20" skewy="5" mask="masks/F_right.png"></layer>
	</step>
</steps>
EOT;
?>
