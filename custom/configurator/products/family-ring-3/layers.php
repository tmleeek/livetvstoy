<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front Side" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="/rings/family/F.png" resize="1" img_type="jpg" jpg_quality="90" left="0" top="0" width="330" height="330" border="1"></layer>
          <layer type="image" value="stones/family-ring-3/FRONT_STONE1.png" top="0" left="0" stone_style="colorized"></layer>
          <layer type="image" value="stones/family-ring-3/FRONT_STONE2.png" top="0" left="0" stone_style="colorized"></layer>
          <layer type="image" value="stones/family-ring-3/FRONT_STONE3.png" top="0" left="0" stone_style="colorized"></layer>
          <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dddddd" salpha="1" 
          sxoffset="-1" syoffset="-1" sdepth="1" usecolor="true" stroke="true" strokecolor="#ffffff" strokewidth="1" width="70" height="12" top="120" left="-10" align="right" size="12" rotation="0" direction="CW" arc_args="45-310" perspective="1" xscale="0.6" yscale="1" skewx="15" mask="masks/F_left.png"></layer>
          <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#000000" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#dddddd" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" usecolor="true" stroke="true" strokecolor="#ffffff" strokewidth="3" width="70" height="12" top="114" left="258" align="center" size="12" rotation="0" direction="CW" arc_args="50-65" perspective="1" xscale="0.7" yscale="1" skewx="-15" mask="masks/F_right.png"></layer>
	</step>
</steps>
EOT;
?>
