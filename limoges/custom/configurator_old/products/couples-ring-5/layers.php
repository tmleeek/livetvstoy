<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front Side" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="/rings/couples/F.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="330" height="330" border="1"></layer>
          <layer type="image" value="stones/couples-ring-5/FRONT_STONE1.png" top="0" left="0" stone_style="colorized"></layer>
          <layer type="image" value="stones/couples-ring-5/FRONT_STONE2.png" top="0" left="0" stone_style="colorized"></layer>
          <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#170f04" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#967145" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" usecolor="true" stroke="true" strokecolor="#fff4d2" strokewidth="2" width="160" height="22" top="144" left="-27" align="center" size="24" rotation="0" direction="CW" arc_args="110-315" perspective="1.2" xscale="0.55" yscale="1.2" skewx="30" mask="masks/F_left.png"></layer>
          <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#170f04" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#967145" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" usecolor="true" stroke="true" strokecolor="#fff4d2" strokewidth="2" width="170" height="24" top="135" left="210" align="center" size="24" rotation="0" direction="CW" arc_args="50-46" perspective="1" xscale="0.46" yscale="1.1" skewx="-28" mask="masks/F_right.png"></layer>
	</step>
</steps>
EOT;
?>
