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
		<stones title="1 birthstone" value="1 birthstone" base="F1" price="0" optvarid="1" rules="1" textrules="4" textindex="1" ></stones>
        <stones title="2 birthstones" value="2 birthstones" base="F2" price="0" optvarid="2" rules="1|2" textrules="4" textindex="2" ></stones>
		<stones title="3 birthstones" value="3 birthstones" base="F3" price="0" optvarid="3" rules="1|2|3" selected="1" textrules="4" textindex="0" ></stones>
</stones>
EOT;

/// ==========================
?>