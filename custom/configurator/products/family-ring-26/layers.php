<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front Side" default_view="true" to_js="true" replace_preview="0">
		<layer type="image" color="Black" value="/rings/family/F.png" resize="1" img_type="jpg" jpg_quality="90" left="0" top="0" width="330" height="330" border="1"></layer>

          <layer type="image" value="stones/family-ring-26/BIRTHSTONE1.png" top="0" left="0" stone_style="colorized"></layer>
          <layer type="image" value="stones/family-ring-26/BIRTHSTONE2.png" top="0" left="0" stone_style="colorized"></layer>
          <layer type="image" value="stones/family-ring-26/BIRTHSTONE3.png" top="0" left="0" stone_style="colorized"></layer>
    	  <layer type="text" font="Myriad Web" text_style="arcnew1" ucolor="#444444" talpha="1" txoffset="1" tyoffset="1" tdepth="1" uscolor="#777777" salpha="1" sxoffset="-1" syoffset="-1" sdepth="1" usecolor="true" stroke="true" strokecolor="#ffffff" strokewidth="2" width="350" height="20" top="188,186,187" left="5,5,5" align="center" size="20" rotation="0" direction="CCW" arc_args="70-180,64-180,70-180" perspective="1.5" xscale="0.85" yscale="1" mask="masks/mask1.png"></layer>
                    
	</step>
</steps>
EOT;
?>
