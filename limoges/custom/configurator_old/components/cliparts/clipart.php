<?php
// ==========================
// ======================================
if ( basename($_SERVER['PHP_SELF']) == basename( __FILE__ ) ) {
   $home = 'http://' . $_SERVER['SERVER_NAME'] . '/';
   header ("Location: $home");
   die();
}
// ==============================================
/*
$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<cliparts>

EOT;


// ======== SELECT CLIPART SECTIONS ====== ///
$mainCat  = get_table_info2("clipart_cat_sections cc",
        "LEFT JOIN clipart_categories c ON cc.id = c.parid WHERE cc.online = 'y' GROUP BY cc.id ORDER BY cc.output_order", "COUNT(*) num, cc.*, c.title OpTitle");



        
        
// ================================================================
if ( $mainCat ) {

        /// ========= make one static main category till we have subcategories in dab =======
        for ( $j=0; $j<count($mainCat); $j++ ) {
                $cat = get_table_info2("clipart_categories", "WHERE parid = '".$mainCat[$j]["id"]."' AND online = 'y' ORDER BY output_order", "*");
                if ( $cat ) {
                        for ( $i=0; $i<count($cat); $i++ ) {
                                $rec = get_table_info2("clipart", "WHERE online = 'y' AND imagePng != '' AND cid = '".$cat[$i]["id"]."' ORDER BY output_order", "*");
                                if ( $rec ) {
                                        for ( $ii=0; $ii<count($rec); $ii++ ) {
						$assCat[$ii] = $j . "_" . $i;
						$xml_data .= "<clipart id=\"". $rec[$ii]["id"] ."\" name=\"". str_replace(' & ', ' &amp; ', $rec[$ii]["title"]) ."\" file_name=\"". $rec[$ii]["imagePng"] ."\" ";
						$xml_data .= "assoc_cat=\"".$assCat[$ii]."\" avail=\"true\"/>\n";
                                        }
                                }
                        }
                        /// ================
                }
        }

}
$xml_data .= "</cliparts>\n";
/// ==========================*/



$xml_data = '<?xml version="1.0" encoding="UTF-8"?>
<cliparts>
	<clipart name="Academics" file_name="academics.png" assoc_cat="0_0" avail="true"/>
	<clipart name="Accounting" file_name="accounting.png" assoc_cat="0_0" avail="true"/>
	<clipart name="Agriculture" file_name="agriculture.png" assoc_cat="0_0" avail="true"/>
	<clipart name="Art" file_name="art.png" assoc_cat="0" avail="true"/>
	<clipart name="BasketBall Mark" file_name="basketball-mark.png" assoc_cat="0" avail="true"/>
	<clipart name="Business" file_name="business.png" assoc_cat="0" avail="true"/>
	<clipart name="Broadcasting" file_name="broadcasting.png" assoc_cat="0" avail="true"/>
	<clipart name="Class of 2010" file_name="class-of-2010.png" assoc_cat="0" avail="true"/>
	<clipart name="Class of 2011" file_name="class-of-2011.png" assoc_cat="0" avail="true"/>
	<clipart name="Class of 2011-2" file_name="class-of-2011-2.png" assoc_cat="0" avail="true"/>
	<clipart name="Debate" file_name="debate.png" assoc_cat="0" avail="true"/>
	<clipart name="Diploma" file_name="diploma.png" assoc_cat="0" avail="true"/>
	<clipart name="Drama" file_name="drama.png" assoc_cat="0" avail="true"/>
	<clipart name="Grad 1" file_name="grad-1.png" assoc_cat="0" avail="true"/>
	<clipart name="Grad 2" file_name="grad-2.png" assoc_cat="0" avail="true"/>
	<clipart name="Honor" file_name="honor.png" assoc_cat="0" avail="true"/>
	<clipart name="Journalism" file_name="journalism.png" assoc_cat="0" avail="true"/>
	<clipart name="JROTC" file_name="jrotc.png" assoc_cat="0" avail="true"/>
	<clipart name="Justice" file_name="justice.png" assoc_cat="0" avail="true"/>
	<clipart name="Law enforcement" file_name="law-enforcement.png" assoc_cat="0" avail="true"/>
	<clipart name="Medicine" file_name="medicine.png" assoc_cat="0" avail="true"/>
	<clipart name="Music" file_name="music.png" assoc_cat="0" avail="true"/>
	<clipart name="NHS" file_name="nhs.png" assoc_cat="0" avail="true"/>
	<clipart name="Science" file_name="science.png" assoc_cat="0" avail="true"/>
	<clipart name="Sign Language" file_name="sign-language.png" assoc_cat="0" avail="true"/>
	<clipart name="Eagle USA" file_name="eagle-usa.png" assoc_cat="1" avail="true"/>
	<clipart name="Friends" file_name="friends.png" assoc_cat="1" avail="true"/>
	<clipart name="Future" file_name="future.png" assoc_cat="1" avail="true"/>
	<clipart name="Hearts" file_name="hearts.png" assoc_cat="1" avail="true"/>
	<clipart name="Honor Ribbon" file_name="honor-ribbon.png" assoc_cat="1" avail="true"/>
	<clipart name="Peace" file_name="peace.png" assoc_cat="1" avail="true"/>
	<clipart name="Shamrock" file_name="shamrock.png" assoc_cat="1" avail="true"/>
	<clipart name="Yin-Yang" file_name="yin-yang.png" assoc_cat="1" avail="true"/>
	<clipart name="Cross bible" file_name="cross-bible.png" assoc_cat="2" avail="true"/>
	<clipart name="Cross wreath" file_name="cross-wreath.png" assoc_cat="2" avail="true"/>
	<clipart name="Cross-fish" file_name="cross-fish.png" assoc_cat="2" avail="true"/>
	<clipart name="Jesus" file_name="jesus.png" assoc_cat="2" avail="true"/>
	<clipart name="Praying Hands" file_name="praying-hands.png" assoc_cat="2" avail="true"/>
	<clipart name="Star of David" file_name="star-of-david.png" assoc_cat="2" avail="true"/>
	<clipart name="4H Club" file_name="4h-club.png" assoc_cat="3" avail="true"/>
	<clipart name="Boating" file_name="boating.png" assoc_cat="3" avail="true"/>
	<clipart name="Camping" file_name="camping.png" assoc_cat="3" avail="true"/>
	<clipart name="Chess" file_name="chess.png" assoc_cat="3" avail="true"/>
	<clipart name="Clarinet" file_name="clarinet.png" assoc_cat="3" avail="true"/>
	<clipart name="Climbing" file_name="climbing.png" assoc_cat="3" avail="true"/>
	<clipart name="Guitars" file_name="guitars.png" assoc_cat="3" avail="true"/>
	<clipart name="Horseback riding" file_name="horseback-riding.png" assoc_cat="3" avail="true"/>
	<clipart name="Scouting" file_name="scouting.png" assoc_cat="3" avail="true"/>
	<clipart name="Bears" file_name="bears.png" assoc_cat="4" avail="true"/>
	<clipart name="BlueJays" file_name="bluejays.png" assoc_cat="4" avail="true"/>
	<clipart name="Bulldogs" file_name="bulldogs.png" assoc_cat="4" avail="true"/>
	<clipart name="Bulls" file_name="bulls.png" assoc_cat="4" avail="true"/>
	<clipart name="Cardinals" file_name="cardinals.png" assoc_cat="4" avail="true"/>
	<clipart name="Chiefs" file_name="chiefs.png" assoc_cat="4" avail="true"/>
	<clipart name="Cougars-2" file_name="cougars-2.png" assoc_cat="4" avail="true"/>
	<clipart name="Dolphins" file_name="dolphins.png" assoc_cat="4" avail="true"/>
	<clipart name="Dragons" file_name="dragons.png" assoc_cat="4" avail="true"/>
	<clipart name="Eagles" file_name="eagles.png" assoc_cat="4" avail="true"/>
	<clipart name="Falcons" file_name="falcons.png" assoc_cat="4" avail="true"/>
	<clipart name="Hawks" file_name="hawks.png" assoc_cat="4" avail="true"/>
	<clipart name="Hornets" file_name="hornets.png" assoc_cat="4" avail="true"/>
	<clipart name="Huskies" file_name="huskies.png" assoc_cat="4" avail="true"/>
	<clipart name="Lions" file_name="lions.png" assoc_cat="4" avail="true"/>
	<clipart name="Mustangs" file_name="mustangs.png" assoc_cat="4" avail="true"/>
	<clipart name="Panthers" file_name="panthers.png" assoc_cat="4" avail="true"/>
	<clipart name="Patriot" file_name="patriot.png" assoc_cat="4" avail="true"/>
	<clipart name="Pirate" file_name="pirate.png" assoc_cat="4" avail="true"/>
	<clipart name="Rams" file_name="rams.png" assoc_cat="4" avail="true"/>
	<clipart name="Tigers" file_name="tigers.png" assoc_cat="4" avail="true"/>
	<clipart name="Tornado" file_name="tornado.png" assoc_cat="4" avail="true"/>
	<clipart name="Trojans" file_name="trojans.png" assoc_cat="4" avail="true"/>
	<clipart name="Vikings" file_name="vikings.png" assoc_cat="4" avail="true"/>
	<clipart name="Warriors" file_name="warriors.png" assoc_cat="4" avail="true"/>
	<clipart name="Wolves" file_name="wolves.png" assoc_cat="4" avail="true"/>
	<clipart name="Baseball 1" file_name="baseball-1.png" assoc_cat="5" avail="true"/>
	<clipart name="Baseball 2" file_name="baseball-2.png" assoc_cat="5" avail="true"/>
	<clipart name="Baseball 3" file_name="baseball-3.png" assoc_cat="5" avail="true"/>
	<clipart name="Basketball" file_name="basketball.png" assoc_cat="5" avail="true"/>
	<clipart name="Bowling" file_name="bowling.png" assoc_cat="5" avail="true"/>
	<clipart name="Cross-country running" file_name="cross-country-running.png" assoc_cat="5" avail="true"/>
	<clipart name="Diving" file_name="diving.png" assoc_cat="5" avail="true"/>
	<clipart name="Field Hockey" file_name="field-hockey.png" assoc_cat="5" avail="true"/>
	<clipart name="Football 1" file_name="football-1.png" assoc_cat="5" avail="true"/>
	<clipart name="Football 2" file_name="football-2.png" assoc_cat="5" avail="true"/>
	<clipart name="Golf" file_name="golf.png" assoc_cat="5" avail="true"/>
	<clipart name="Hockey" file_name="hockey.png" assoc_cat="5" avail="true"/>
	<clipart name="Soccer" file_name="soccer.png" assoc_cat="5" avail="true"/>
	<clipart name="Swimming" file_name="swimming.png" assoc_cat="5" avail="true"/>
	<clipart name="Tennis" file_name="tennis.png" assoc_cat="5" avail="true"/>
	<clipart name="Track" file_name="track.png" assoc_cat="5" avail="true"/>
	<clipart name="Volleyball" file_name="volleyball.png" assoc_cat="5" avail="true"/>
	<clipart name="Wrestling" file_name="wrestling.png" assoc_cat="5" avail="true"/>
	<clipart name="Aquarius" file_name="aquarius.png" assoc_cat="6" avail="true"/>
	<clipart name="Aries" file_name="aries.png" assoc_cat="6" avail="true"/>
	<clipart name="Cancer" file_name="cancer.png" assoc_cat="6" avail="true"/>
	<clipart name="Capricorn" file_name="capricorn.png" assoc_cat="6" avail="true"/>
	<clipart name="Gemini" file_name="gemini.png" assoc_cat="6" avail="true"/>
	<clipart name="Leo" file_name="leo.png" assoc_cat="6" avail="true"/>
	<clipart name="Libra" file_name="libra.png" assoc_cat="6" avail="true"/>
	<clipart name="Paw" file_name="paw.png" assoc_cat="0" avail="true"/>
	<clipart name="Pisces" file_name="pisces.png" assoc_cat="6" avail="true"/>
	<clipart name="Sagittarius" file_name="sagittarius.png" assoc_cat="6" avail="true"/>
	<clipart name="Scorpio" file_name="scorpio.png" assoc_cat="6" avail="true"/>
	<clipart name="Taurus" file_name="taurus.png" assoc_cat="6" avail="true"/>
	<clipart name="Virgo" file_name="virgo.png" assoc_cat="6" avail="true"/>
	<clipart name="Artpallete" file_name="artpallete.png" assoc_cat="7" avail="true"/>
	<clipart name="Award" file_name="award.png" assoc_cat="7" avail="true"/>
	<clipart name="Cat" file_name="cat.png" assoc_cat="7" avail="true"/>
	<clipart name="Cross" file_name="cross.png" assoc_cat="7" avail="true"/>
	<clipart name="Heart1" file_name="heart1.png" assoc_cat="7" avail="true"/>
	<clipart name="Lightning" file_name="lightning.png" assoc_cat="7" avail="true"/>
	<clipart name="Octopus" file_name="octopus.png" assoc_cat="7" avail="true"/>
	<clipart name="Rose" file_name="rose.png" assoc_cat="7" avail="true"/>
	<clipart name="Yinyang" file_name="yinyang.png" assoc_cat="7" avail="true"/>
	<clipart name="Air Force Civil Engineer" file_name="air-force-civil-engineer.png" assoc_cat="8" avail="true"/>
	<clipart name="Air Force Combat Control" file_name="air-force-combat-control.png" assoc_cat="8" avail="true"/>
	<clipart name="Air Force Command and Control" file_name="air-force-command-and-control.png" assoc_cat="8" avail="true"/>
	<clipart name="Air Force Commander" file_name="air-force-commander.png" assoc_cat="8" avail="true"/>
	<clipart name="Air Force Communications" file_name="air-force-communications.png" assoc_cat="8" avail="true"/>
	<clipart name="Air Force Emblem" file_name="air-force-emblem.png" assoc_cat="8" avail="true"/>
	<clipart name="1st Armor Division" file_name="1st-armor-division.png" assoc_cat="9" avail="true"/>
	<clipart name="1st Cavalry Division" file_name="1st-cavalry-division.png" assoc_cat="9" avail="true"/>
	<clipart name="1st Infantry Division" file_name="1st-infantry-division.png" assoc_cat="9" avail="true"/>
	<clipart name="2nd Armor Division" file_name="2nd-armor-division.png" assoc_cat="9" avail="true"/>
	<clipart name="2nd Armored Cavalry Regiment" file_name="2nd-armored-cavalry-regiment.png" assoc_cat="9" avail="true"/>
	<clipart name="2nd Infantry Division Regiment" file_name="2nd-infantry-division-regiment.png" assoc_cat="9" avail="true"/>
	<clipart name="Aces 3" file_name="aces-3.png" assoc_cat="10" avail="true"/>
	<clipart name="American Patriotism 3" file_name="american-patriotism-3.png" assoc_cat="10" avail="true"/>
	<clipart name="Bulldog 2" file_name="bulldog-2.png" assoc_cat="10" avail="true"/>
	<clipart name="Coxswain" file_name="coxswain.png" assoc_cat="10" avail="true"/>
	<clipart name="CPO Badge" file_name="cpo-badge.png" assoc_cat="10" avail="true"/>
	<clipart name="Cross 2" file_name="cross-2.png" assoc_cat="10" avail="true"/>
	<clipart name="Cutterman Insignia" file_name="cutterman-insignia.png" assoc_cat="11" avail="true"/>
	<clipart name="1st Marine Division" file_name="1st-marine-division.png" assoc_cat="11" avail="true"/>
	<clipart name="2nd Marine Division" file_name="2nd-marine-division.png" assoc_cat="11" avail="true"/>
	<clipart name="3rd Marine Division" file_name="3rd-marine-division.png" assoc_cat="11" avail="true"/>
	<clipart name="Abrams Tank" file_name="abrams-tank.png" assoc_cat="11" avail="true"/>
	<clipart name="Aces 2" file_name="aces-2.png" assoc_cat="11" avail="true"/>
	<clipart name="American Patriotism 2" file_name="american-patriotism-2.png" assoc_cat="11" avail="true"/>
	<clipart name="Aces" file_name="aces.png" assoc_cat="12" avail="true"/>
	<clipart name="Air Warfare Enlisted" file_name="air-warfare-enlisted.png" assoc_cat="12" avail="true"/>
	<clipart name="American Patriotism" file_name="american-patriotism.png" assoc_cat="12" avail="true"/>
	<clipart name="Blue Angels" file_name="blue-angels.png" assoc_cat="12" avail="true"/>
	<clipart name="Chief Petty Officer" file_name="chief-petty-officer.png" assoc_cat="12" avail="true"/>
	<clipart name="Dept Of Navy Seal" file_name="dept-of-navy-seal.png" assoc_cat="12" avail="true"/>
	<clipart name="Baseball 4" file_name="baseball-4.png" assoc_cat="13" avail="true"/>
	<clipart name="Basketball 2" file_name="basketball-2.png" assoc_cat="13" avail="true"/>
	<clipart name="Bowling 2" file_name="bowling-2.png" assoc_cat="13" avail="true"/>
	<clipart name="Football" file_name="football.png" assoc_cat="13" avail="true"/>
	<clipart name="Golf 2" file_name="golf-2.png" assoc_cat="13" avail="true"/>
	<clipart name="Soccer 2" file_name="soccer-2.png" assoc_cat="13" avail="true"/>
	<clipart name="Swimming 2" file_name="swimming-2.png" assoc_cat="13" avail="true"/>
	<clipart name="Tennis 2" file_name="tennis-2.png" assoc_cat="13" avail="true"/>
	<clipart name="Track 2" file_name="track-2.png" assoc_cat="13" avail="true"/>
	<clipart name="Volleyball 2" file_name="volleyball-2.png" assoc_cat="13" avail="true"/>
</cliparts>';

?>