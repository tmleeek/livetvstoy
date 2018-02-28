<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front Side" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="/rings/couples/F.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="330" height="330" border="1"></layer>
          <layer type="image" value="stones/couples-ring-3/FRONT_STONE1.png" top="0" left="0" stone_style="colorized"></layer>
          <layer type="image" value="stones/couples-ring-3/FRONT_STONE2.png" top="0" left="0" stone_style="colorized"></layer>
          <layer type="text" font="Alison" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dddddd" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" usecolor="true" stroke="true" strokecolor="#ffffff" strokewidth="2" width="190" height="24" top="143" left="-30" align="center" size="24" rotation="0" direction="CW" arc_args="80-330" perspective="1" xscale="0.65" yscale="1" skewx="20" mask="masks/F_left.png"></layer>
          <layer type="text" font="Alison" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dddddd" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" usecolor="true" stroke="true" strokecolor="#ffffff" strokewidth="2" width="160" height="26" top="133" left="198" align="center" size="26" rotation="0" direction="CW" arc_args="85-40" perspective="1.2" xscale="0.55" yscale="1.3" skewx="-30" mask="masks/F_right.png"></layer>
	</step>
</steps>
EOT;
?>
