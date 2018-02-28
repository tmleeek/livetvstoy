<?php
// ======================================
if ( basename($_SERVER['PHP_SELF']) == basename( __FILE__ ) ) {
   $home = 'http://' . $_SERVER['SERVER_NAME'] . '/';
   header ("Location: $home");
   die();
}
// ==============================================

$test = false;


// ======== SELECT CLIPART SECTIONS ====== ///
$mainCat  = get_table_info2("clipart_cat_sections cc",
        "LEFT JOIN clipart_categories c ON cc.id = c.parid WHERE cc.online = 'y' GROUP BY cc.id ORDER BY cc.output_order", "COUNT(*) num, cc.*, c.title OpTitle");
// ================================================================

// ======== SELECT ENGRAVING SAMPLES SECTIONS ====== ///
$EngrCat  = get_table_info2("engraving_categories ec, engraving_samples es",
        "WHERE ec.id = es.cid AND ec.online = 'y' AND es.online = 'y' GROUP BY ec.id ORDER BY ec.output_order", "ec.*");
// ================================================================


$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<categories>
	<chains show_all_button="0" show_none_button="true">
			<cat name="Chains for Women"></cat>
			<cat name="Chains for Men"></cat>
	</chains>

EOT;


//// ======== CLIPART ====== ////
$xml_data .= "	<cliparts show_all_button=\"0\">\n";
if ( $mainCat ) {
        for ( $j=0; $j<count($mainCat); $j++ ) {
		$xml_data .= "<cat name=\"". str_replace(' & ', ' &amp; ', $mainCat[$j]["title"]) ."\">\n";
		//// ============
                $cat = get_table_info2("clipart_categories", "WHERE parid = '".$mainCat[$j]["id"]."' AND online = 'y' ORDER BY output_order", "*");
                if ( $cat ) {
                        for ( $i=0; $i<count($cat); $i++ ) {
                                $rec = get_table_info2("clipart", "WHERE online = 'y' AND imagePng != '' AND cid = '".$cat[$i]["id"]."' LIMIT 1", "*");
                                if ( $rec ) {
                                        $xml_data .= "      		<cat name=\"". str_replace(' & ', ' &amp; ', $cat[$i]["title"]) ."\"></cat>\n";
                                }
                        }
                        /// ================
                }
                $xml_data .= "		</cat>\n";
        }
}
$xml_data .= "	</cliparts>\n";




$xml_data .= <<<EOT
	<font show_all_button="true">
		<cat name="Serif"></cat>
		<cat name="Sans Serif"></cat>
		<cat name="Script"></cat>
		<cat name="Blackletter"></cat>
		<cat name="Monogram"></cat>
		<cat name="Basic Fonts"></cat>
		<cat name="Engraving Fonts 1"></cat>
	</font>

EOT;



//// ===== engraving samples ==============
$xml_data .= "	<wording show_all_button=\"true\">\n";
if ( $EngrCat ) {
	for ( $i=0; $i<count($EngrCat); $i++ ) {
		$xml_data .= "		<cat name=\"". str_replace(' & ', ' &amp; ', $EngrCat[$i]["title"]) ."\"></cat>\n";
	}
}
$xml_data .= "	</wording>\n";

$xml_data .= "</categories>";


/// ==========================
?>
