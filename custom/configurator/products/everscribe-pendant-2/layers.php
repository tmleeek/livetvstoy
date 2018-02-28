<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front Side" default_view="true" to_js="true" replace_preview="0">
		<layer type="image" color="Black" value="/pendants/everscribe/F.png" resize="1" img_type="jpg" jpg_quality="90" left="0" top="0" width="330" height="330" border="1"></layer>
		<layer type="text" text_style="textfit" font="Monotype Corsiva" color="Black" top="164" align="center" left="-5" size="72" mask="masks/front_mask.png" fit="1" width="214" default="Mother"></layer>
		<layer type="text" text_style="textjoin" font="Monotype Corsiva" color="Black" top="101" align="left" left="-125" size="28" join="true" tile="true" tile_width="50" tile_height="75" tile_type="0" width="430" height="330" mask="masks/front_mask2.png"></layer>
		<layer type="text" text_style="textjoin" font="Monotype Corsiva" color="Black" top="126" align="left" left="-20" size="28" join="true" tile="true" tile_width="50" tile_height="75" tile_type="0" width="430" height="330" mask="masks/front_mask2.png"></layer>
		<layer type="text" text_style="textjoin" font="Monotype Corsiva" color="Black" top="151" align="left" left="60" size="28" join="true" tile="true" tile_width="50" tile_height="75" tile_type="0" width="430" height="330" mask="masks/front_mask2.png"></layer>
		<layer type="text" text_style="textjoin" font="Monotype Corsiva" color="Black" top="330" align="left" left="0" size="28" join="true"></layer>
		<layer type="text" text_style="textjoin" font="Monotype Corsiva" color="Black" top="330" align="left" left="0" size="28" join="true"></layer>
	</step>
</steps>
EOT;
?>
