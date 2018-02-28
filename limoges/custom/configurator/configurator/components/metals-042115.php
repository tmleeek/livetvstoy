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
	  <metal name="Yellow" key="Yellow" avail="true" color="#c6a668" scolor="#55421c" code="Y"></metal>
	  <metal name="White" key="White" avail="true" color="#cccccc" scolor="#666666" code="W"></metal>
	  <metal name="Silver" key="Silver" avail="true" color="#bbbbbb" scolor="#555555" code="S"></metal>
</metals>
EOT;

/// ==========================
?>