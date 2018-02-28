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
		<stones title="1 birthstone" value="1 birthstone" base="F1" price="0" optvarid="1" rules="1" ></stones>
        <stones title="2 birthstones" value="2 birthstones" base="F2" price="0" optvarid="2" rules="1|2" ></stones>
		<stones title="3 birthstones" value="3 birthstones" base="F3" price="0" optvarid="3" rules="1|2|3" ></stones>
		<stones title="4 birthstones" value="4 birthstones" base="F4" price="0" optvarid="4" rules="1|2|3|4" ></stones>
		<stones title="5 birthstones" value="5 birthstones" base="F5" price="0" optvarid="5" rules="1|2|3|4|5" ></stones>
        <stones title="6 birthstones" value="6 birthstones" base="F6" price="0" optvarid="6" rules="1|2|3|4|5|6" ></stones>
        <stones title="7 birthstones" value="7 birthstones" base="F7" price="0" optvarid="7" rules="1|2|3|4|5|6|7" ></stones>
        <stones title="8 birthstones" value="8 birthstones" base="F8" price="0" optvarid="8" rules="1|2|3|4|5|6|7|8" ></stones>
        <stones title="9 birthstones" value="9 birthstones" base="F9" price="0" optvarid="9" rules="1|2|3|4|5|6|7|8|9" ></stones>
        <stones title="10 birthstones" value="10 birthstones" base="F10" price="0" optvarid="10" rules="1|2|3|4|5|6|7|8|9|10" selected="1" ></stones>
</stones>
EOT;

/// ==========================
?>