<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front Side" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="/rings/couples/F.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
          <layer type="image" value="stones/couples-ring-8/FRONT_STONE1.png" top="0" left="0" stone_style="colorized"></layer>
          <layer type="image" value="stones/couples-ring-8/FRONT_STONE2.png" top="0" left="0" stone_style="colorized"></layer>
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
          sdepth="1" usecolor="true" width="180" height="24" top="90" left="-30" align="center" size="24" rotation="0" direction="CW" arc_args="70-308" perspective="1" xscale="0.55" yscale="1.2" skewx="35" mask="masks/F_left.png"></layer>
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
          sdepth="1" usecolor="true" width="180" height="24" top="88" left="146" align="center" size="24" rotation="0" direction="CW" arc_args="90-54" perspective="1" xscale="0.6" yscale="1.2" skewx="-40"></layer>
	</step>
</steps>
EOT;
?>
