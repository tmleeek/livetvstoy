<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front Side" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="/rings/couples/F.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
          <layer type="image" value="stones/couples-pendant-1/FRONT_STONE1.png" top="0" left="0" stone_style="colorized"></layer>
          <layer type="image" value="stones/couples-pendant-1/FRONT_STONE2.png" top="0" left="0" stone_style="colorized"></layer>
          <layer type="text" 
          font="Alison" 
          text_style="arcnew1" 
          ucolor="#000000" 
          talpha="1"
          txoffset="1"
      	  tyoffset="1"
          tdepth="1"
          uscolor="#dfc796" 
          salpha="1" 
          sxoffset="1" 
          syoffset="1" 
          sdepth="1" usecolor="true" width="100" height="9" top="98" left="47" align="center" size="9" rotation="0" direction="CW" arc_args="20-290" perspective="1" xscale="0.7" yscale="1" skewx="0" mask="masks/F_left.png"></layer>
          <layer type="text" 
          font="Alison" 
          text_style="arcnew1" 
          ucolor="#000000" 
          talpha="1"
          txoffset="1"
      	  tyoffset="1"
          tdepth="1"
          uscolor="#dfc796" 
          salpha="1" 
          sxoffset="1" 
          syoffset="1" 
          sdepth="1" usecolor="true" width="110" height="10" top="109" left="125" align="center" size="10" rotation="0" direction="CW" arc_args="20-292" perspective="1" xscale="0.7" yscale="1" skewx="0" mask="masks/F_right.png"></layer>
	</step>
</steps>
EOT;
?>
