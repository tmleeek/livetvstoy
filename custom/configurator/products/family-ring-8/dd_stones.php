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
		<stones title="1 birthstone" value="1 birthstone" price="0" optvarid="1" rules="4" ></stones>
        <stones title="2 birthstones" value="2 birthstones" price="0" optvarid="2" rules="3|5" ></stones>
		<stones title="3 birthstones" value="3 birthstones" price="0" optvarid="3" rules="3|4|5" ></stones>
		<stones title="4 birthstones" value="4 birthstones" price="0" optvarid="4" rules="2|3|5|6" ></stones>
		<stones title="5 birthstones" value="5 birthstones" price="0" optvarid="5" rules="2|3|4|5|6" ></stones>
        <stones title="6 birthstones" value="6 birthstones" price="0" optvarid="6" rules="1|2|3|5|6|7" ></stones>
        <stones title="7 birthstones" value="7 birthstones" price="0" optvarid="7" rules="1|2|3|4|5|6|7" selected="1" ></stones>
</stones>
EOT;

/// ==========================
?>