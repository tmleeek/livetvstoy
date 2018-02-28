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
        <stones title="2 Birthstones" value="2 Birthstones" base="F2" price="0" optvarid="2" rules="1|2" ></stones>
		<stones title="3 Birthstones" value="3 Birthstones" base="F3" price="0" optvarid="3" rules="1|2|3" ></stones>
		<stones title="4 Birthstones" value="4 Birthstones" base="F4" price="0" optvarid="4" rules="1|2|3|4" ></stones>
        <stones title="5 Birthstones" value="5 Birthstones" base="F5" price="0" optvarid="5" rules="1|2|3|4|5" selected="1" ></stones>
</stones>
EOT;

/// ==========================
?>