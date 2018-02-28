<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front Side" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="/rings/engraved/F.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
    <layer type="text" 
	  font="Serifa BT" 
	  text_style="arcnew1" 
	  ucolor="#c7c7c7" 
	  talpha="1"
      txoffset="1"
      tyoffset="1"
      tdepth="1"
	  uscolor="#201f1d" 
	  salpha="1" 
	  sxoffset="1" 
	  syoffset="1" 
	  sdepth="1" usecolor="true" width="340" height="30" top="154" left="-3" align="center" size="28" rotation="0" direction="CCW" arc_args="75-181" perspective="1.5" xscale="0.8" yscale="1.1" mask="masks/F_mask.png"></layer></step>
</steps>
EOT;
?>
