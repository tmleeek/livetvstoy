<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front Side" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="/rings/couples/F.png" resize="1" img_type="jpg" jpg_quality="70" left="0" top="0" width="300" height="300" border="1"></layer>
          <layer type="text" 
          font="Monotype Corsiva" 
          text_style="arc2i" 
          color="#555555" 
          talpha="1" 
          scolor="black" 
          salpha="1" 
          sxoffset="-1" 
          syoffset="-1" 
          sdepth="1" width="100" height="30" top="154" left="-38" align="center" size="20" rotation="0" direction="CW" arc_args="60-330" perspective="1" xscale="0.55" yscale="1" skewx="25" mask="masks/F_left.png"></layer>
          <layer type="text" 
          font="Monotype Corsiva" 
          text_style="arc2i" 
          color="#555555" 
          talpha="1" 
          scolor="black" 
          salpha="1" 
          sxoffset="-1" 
          syoffset="-1" 
          sdepth="1" width="100" height="30" top="163" left="227" align="center" size="20" rotation="0" direction="CW" arc_args="40-15" perspective="1" xscale="0.5" yscale="1" skewx="-20" mask="masks/F_right.png"></layer>
	</step>
</steps>
EOT;
?>
