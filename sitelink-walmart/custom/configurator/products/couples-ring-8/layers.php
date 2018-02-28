<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front Side" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="/rings/couples/F.png" resize="1" img_type="jpg" jpg_quality="90" left="0" top="0" width="330" height="330" border="1"></layer>
          <layer type="image" value="stones/couples-ring-8/FRONT_STONE1.png" top="0" left="0" stone_style="colorized"></layer>
          <layer type="image" value="stones/couples-ring-8/FRONT_STONE2.png" top="0" left="0" stone_style="colorized"></layer>
          <layer type="text" font="Alison" text_style="arcnew1" ucolor="#170f04" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#967145" salpha="-1" sxoffset="-1" syoffset="1" sdepth="1" usecolor="true" stroke="true" strokecolor="#fff4d2" strokewidth="1" width="210" height="26" top="88" left="-44" align="center" size="26" rotation="0" direction="CW" arc_args="100-294" perspective="1.3" xscale="0.5" yscale="1.5" skewx="45" mask="masks/F_left.png"></layer>
          <layer type="text" font="Alison" text_style="arcnew1" ucolor="#170f04" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#967145" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" usecolor="true" stroke="true" strokecolor="#fff4d2" strokewidth="1" width="190" height="26" top="100" left="156" align="center" size="26" rotation="0" direction="CW" arc_args="110-56" perspective="1.3" xscale="0.55" yscale="1.5" skewx="-45" mask="masks/F_right.png"></layer>
	</step>
</steps>
EOT;
?>
