<?php
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
<metals>
	  <metal name="Yellow" key="Yellow" avail="true" color="#c6a668" code="Y"></metal>
	  <metal name="White" key="White" avail="true" color="#aaaaaa" code="W"></metal>
	  <metal name="Silver" key="Silver" avail="true" color="#999999" code="S"></metal>
</metals>
EOT;

/// ==========================
?>