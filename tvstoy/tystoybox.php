<?php
/*curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
$content = curl_exec($ch);
curl_close($ch);

print_r($content);
*/

/**
 * Get a web file (HTML, XHTML, XML, image, etc.) from a URL.  Return an
 * array containing the HTTP server response header fields and content.
 */
function get_web_page( $url )
{
    $options = array(
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => false,    // don't return headers
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_USERAGENT      => "Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0", // who am i
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
        CURLOPT_TIMEOUT        => 120,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
    );

    $ch      = curl_init( $url );
    curl_setopt_array( $ch, $options );
    $content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );

    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['content'] = $content;
    return $header;
}

//$response = get_web_page('http://www.tystoybox.com');
$response = get_web_page('http://10.0.0.26/index.php');
echo $pagesrc = $response['content'];


$pattern = '/\<li class=\"item/';
$result = preg_match_all( $pattern, $pagesrc , $matches );
$prdct_cnt = count($matches[0]);
print_r($matches);
if ($prdct_cnt < 16) {
        $Name = "Support"; //senders name
        $email = "noreply@tystoybox.com"; //senders e-mail adress
        //$recipient = "AIsmail@cpscompany.com"; //recipient
        $recipient = "bpunati@cpscompany.com"; //recipient
        $mail_body = "\n PBSKids items may be missing from home page ! "; //"The text for the mail..."; //mail body
        $subject = "Urgent - PBSKids products missing"; //subject
        $header = "From: ". $Name . " <" . $email . ">\r\n" . //optional headerfields
                  "CC: abhilash@vtrio.com \r\n" . 
                  "CC: afsar@vtrio.com \r\n" .
                  "CC: renjith@vtrio.com \r\n" .
                  "CC: sysadmin@vtrio.com \r\n" ;

        mail($recipient, $subject, $mail_body, $header); //mail command :)
	
	shell_exec('/usr/bin/php /var/www/CPS/public_html/shell/indexer.php --reindexall');
}

?>

