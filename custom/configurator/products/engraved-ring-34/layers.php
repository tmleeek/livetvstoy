<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front Side" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="/rings/engraved/F.png" resize="1" img_type="jpg" jpg_quality="90" left="0" top="0" width="330" height="330" border="1"></layer>
	  <layer type="text" font="Monotype Corsiva" text_style="arcnew1" ucolor="#574d51" talpha="1" txoffset="1" tyoffset="1" tdepth="2" uscolor="#150e12" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" usecolor="true" stroke="true" strokecolor="#cac3c6" strokewidth="2" width="430" height="32" top="111" left="-60" align="center" size="16" rotation="0" direction="CW" arc_args="86" perspective="1.5" xscale="1" yscale="1" mask="masks/F_top.png"></layer>
    <layer type="text" font="Monotype Corsiva" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#333333" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" usecolor="true" stroke="true" strokecolor="#eeeeee" strokewidth="1" width="350" height="42" top="144" left="-29" align="center" size="38" rotation="0" direction="CCW" arc_args="76-180" perspective="1.5" xscale="1" yscale="1" mask="masks/F_bottom.png"></layer>
	</step>
</steps>
EOT;
?>
