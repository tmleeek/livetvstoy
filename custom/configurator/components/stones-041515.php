<?php
/// ==========================
// ======================================
if ( basename($_SERVER['PHP_SELF']) == basename( __FILE__ ) ) {
   $home = 'http://' . $_SERVER['SERVER_NAME'] . '/';
   header ("Location: $home");
   die();
}
// ==============================================

// To generate dynamic value for Wordings XML

$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<stones>
	  <stones name="None" color="none" brightness="100" saturation="100" file_name="none.png"></stones>
	  <stones name="JAN" color="#ff0000" brightness="100" saturation="200" file_name="january.png"></stones>
	  <stones name="FEB" color="#f0b4f5" brightness="140" saturation="180" file_name="february.png"></stones>
	  <stones name="MAR" color="#c1edfc" brightness="130" saturation="220" file_name="march.png"></stones>
	  <stones name="APR" color="#ffffff" brightness="130" saturation="100" file_name="april.png"></stones>
	  <stones name="MAY" color="#95d6b4" brightness="120" saturation="250" file_name="may.png"></stones>
	  <stones name="JUN" color="#fdc5d9" brightness="120" saturation="320" file_name="june.png"></stones>
	  <stones name="JUL" color="#fc74b4" brightness="100" saturation="180" file_name="july.png"></stones>
	  <stones name="AUG" color="#d5faad" brightness="120" saturation="200" file_name="august.png"></stones>
	  <stones name="SEP" color="#2d3ffb" brightness="100" saturation="200" file_name="september.png"></stones>
	  <stones name="OCT" color="#fe93d4" brightness="150" saturation="170" file_name="october.png"></stones>
	  <stones name="NOV" color="#ffca6d" brightness="130" saturation="220" file_name="november.png"></stones>
	  <stones name="DEC" color="#a5e6fe" brightness="150" saturation="230" file_name="december.png"></stones>
</stones>
EOT;

/// ==========================
?>