<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front Side" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="/rings/couples/F.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
          <layer type="image" value="stones/couples-ring-4/FRONT_STONE1.png" top="0" left="0" stone_style="colorized"></layer>
          <layer type="image" value="stones/couples-ring-4/FRONT_STONE2.png" top="0" left="0" stone_style="colorized"></layer>
          <layer type="text" 
          font="Myriad Web" 
          text_style="arcnew1" 
          ucolor="#000000" 
          talpha="1"
          txoffset="1"
      	  tyoffset="1"
          tdepth="1"
          uscolor="#dddddd" 
          salpha="1" 
          sxoffset="1" 
          syoffset="1" 
          sdepth="1" usecolor="true" width="150" height="30" top="166" left="-23" align="center" size="20" rotation="0" direction="CW" arc_args="50-350" perspective="1" xscale="0.55" yscale="1" skewx="10" mask="masks/F_left.png"></layer>
          <layer type="text" 
          font="Myriad Web" 
          text_style="arcnew1" 
          ucolor="#000000" 
          talpha="1"
          txoffset="1"
      	  tyoffset="1"
          tdepth="1"
          uscolor="#dddddd" 
          salpha="1" 
          sxoffset="1" 
          syoffset="1" 
          sdepth="1" usecolor="true" width="150" height="30" top="156" left="198" align="center" size="20" rotation="0" direction="CW" arc_args="45-22" perspective="1" xscale="0.55" yscale="1.1" skewx="-15" mask="masks/F_right.png"></layer>
	</step>
</steps>
EOT;
?>
