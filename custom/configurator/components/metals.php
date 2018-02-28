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
	  <metal name="Yellow" key="Yellow" avail="true" color="#ead09f" scolor="#382d17" code="Y"></metal>
	  <metal name="White" key="White" avail="true" color="#dddddd" scolor="#222222" code="W"></metal>
	  <metal name="Silver" key="Silver" avail="true" color="#cccccc" scolor="#333333" code="S"></metal>
      <metal name="Titanium" key="Titanium" avail="true" color="#cccccc" scolor="#333333" code="T"></metal>
</metals>
EOT;

/// ==========================
?>