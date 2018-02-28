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
		<stones title="2 birthstones" value="2 birthstones" price="0" optvarid="2" rules="3|5" textrules="9|11" selected="1" ></stones>
		<stones title="3 birthstones" value="3 birthstones" price="0" optvarid="3" rules="2|4|6" textrules="8|10|12" ></stones>
		<stones title="4 birthstones" value="4 birthstones" price="0" optvarid="4" rules="2|3|4|5" textrules="8|9|10|11" ></stones>
		<stones title="5 birthstones" value="5 birthstones" price="0" optvarid="5" rules="1|2|3|4|5" textrules="7|8|9|10|11" ></stones>
		<stones title="6 birthstones" value="6 birthstones" price="0" optvarid="6" rules="1|2|3|4|5|6" textrules="7|8|9|10|11|12" ></stones>
</stones>
EOT;

/// ==========================
?>