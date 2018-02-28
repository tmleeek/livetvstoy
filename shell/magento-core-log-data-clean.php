<?php
/**
 * Short description for file
 *         This files is used to Clean the logs from database and folder(/var/log/ and /var/report/).
 *
 * PHP versions All
 *
 * @category   PHP Coding File
 * @package
 * @author     Manish Pawar
 * @copyright  Zeon Solutions Pvt Ltd.
 * @license    As described below
 * @version    1.1.0
 * @link
 * @see        -NA-
 * @since      17 Jan 2012
 * @modified   Manish Pawar [04-May-2012]
 * @deprecated -NA-
 */

/*********************************************************
* Licence:
* This file is sole property of the installer.
* Any type of copy or reproduction without the consent
* of owner is prohibited.
* If in any case used leave this part intact without
* any modification.
* All Rights Reserved
* Copyright 2012 Owner
*******************************************************/
/*******************************************
* FOLLOWING PARAMETER NEED TO BE CHANGED *
*******************************************/
@date_default_timezone_set('America/Chicago');

//only run through command prompt
if ($_SERVER['REMOTE_ADDR'] != $_SERVER['SERVER_ADDR']) {
    die('Invalid Request');
}

$cleanLogTablesFlag     = true;
$cleanVarDirectoryFlag     = true;
$path = dirname(dirname(__FILE__)) . '/var/';

/**
* BOF :: Database Log Clean Setting
*/
$tables = array(
              'core_cache_tag'
        );

/**
 * BOF :: Mail Setting
 */
$arrMail['projectName']  = 'CPS'; // No Blank Space
$arrMail['mailFrom']     = $arrMail['projectName'].' :: core_cache_tag';
$arrMail['mailFromEmail']= 'afsar@vtrio.com';
$arrMail['mailTo']       = 'afsar@vtrio.com';
$arrMail['subject']      = $arrMail['projectName'].' :: ';
$arrMail['logFile']      = $path.$arrMail['projectName'].'-log-clean-'.date('Ymd').'.txt';
$fp                      = openLogFile($arrMail);

/*******************************************
 * FOLLOWING PARAMETER NEED TO BE CHANGED *
 *******************************************/

/**
 * BOF :: Clean the Logs from database
 */
writeLog($fp, '--------------TRUNCATE Table Start Here--------------');
if ( $cleanLogTablesFlag ) {
    cleanLogTables($tables, $fp, $arrMail);
} else {
    writeLog($fp, 'cleanLogTablesFlag Flag is Disabled');
}
/**
 * EOF :: Clean the Logs from database
 */

/**
 * BOF :: Send Status Mail to Admin
 */
$logContents = getLog($arrMail['logFile']);
sendMail($arrMail['mailFrom'], $arrMail['mailFromEmail'], $arrMail['mailTo'], $arrMail['subject'].' Log Status', $logContents);

/*******************************************
 * FUNCTIONALITY CODE STARTS BELOW *
 *******************************************/

/**
 * Function is used to TRUNCATE the table data
 * @param    array    $table        List of tables
 * @param    object    $fp            File Object
 * @param    array    $arrMail    Mail Details
 *
 */
function cleanLogTables($tables, $fp, $arrMail) {
    /*
     * Get database details from local.xml file
     */
    $localXmlPath = dirname(dirname(__FILE__)).'/app/etc/local.xml';
    $xml = simplexml_load_file($localXmlPath, NULL, LIBXML_NOCDATA);

    $db['host'] = $xml->global->resources->default_setup->connection->host;
    $db['name'] = $xml->global->resources->default_setup->connection->dbname;
    $db['user'] = $xml->global->resources->default_setup->connection->username;
    $db['pass'] = $xml->global->resources->default_setup->connection->password;
    $db['pref'] = $xml->global->resources->db->table_prefix;

    if ( !mysql_connect($db['host'], $db['user'], $db['pass']) ) {
        writeLog($fp, 'Database Connection Error : '.mysql_error());
        sendMail($arrMail['mailFrom'], $arrMail['mailFromEmail'], $arrMail['mailTo'], $arrMail['subject'].' Database Connection Error', 'Database Connection Error');
        die();
    }
    if ( !mysql_select_db($db['name']) ) {
        writeLog($fp, 'Select Database Error: '.mysql_error());
        sendMail($arrMail['mailFrom'], $arrMail['mailFromEmail'], $arrMail['mailTo'], $arrMail['subject'].' Select Database Error', 'Select Database Error');
        die();
    }

    if ( is_array($tables) && count($tables) > 0 ) {
        foreach($tables as $key => $tableName) {
            if ( mysql_query('TRUNCATE `'.$db['pref'].$tableName.'`') ) {
                writeLog($fp, 'RUN SUCCESSFULLY :: TRUNCATE `'.$db['pref'].$tableName.'`');
            }
            else {
                writeLog($fp, 'ERROR :: TRUNCATE `'.$db['pref'].$tableName.'` : '.mysql_error());
            }
        }
    }
    else {
        writeLog($fp, 'Tables Array is Empty');
    }

}

/**
 * Open the Log File
 * @param    array    $arrMail    Mail Details
 */
function openLogFile($arrMail) {
    $logFile = $arrMail['logFile'];

    $fh = fopen($logFile, 'a+') or die("can't open file");
    if (!$fh ) {
        sendMail($arrMail['mailFrom'], $arrMail['mailFromEmail'], $arrMail['mailTo'], $arrMail['subject'].' Log file Not Created', $logFile.' = Log file Not Created');
        die('Log file Not Created');
    }
    else {
        exec('chmod 777 '.$logFile);
    }
    return $fh;
}

/**
 * Open the Log File
 *
 * @param  object    $fh            File Open Object
 * @param  string    $message    Log Message
 * @param  int        $action        File Name
 */
function writeLog($fh, $message) {
    echo ''.$msg = date("d-M-Y H:i:s")." :: ".$message."\n";
    fwrite($fh,$msg);
}

function getLog($logFile) {
    return file_get_contents($logFile);
}

function closeLogFile($fh) {
    //unlink($fh);
}

/**
 * Function Is used to send the mail To user
 *
 * @param  string    $mailFrom        Name
 * @param  string    $mailFromEmail    Email ID
 * @param  string    $mailTo            Mail To Email ID
 * @param  string    $mailSubject    Subject
 * @param  string    $mailBody        Message
 * @param  string    $mailType        Mail Type (TEXT / HTML)
 */
// Function Is used to send the mail To user
function sendMail($mailFrom='', $mailFromEmail='', $mailTo='', $mailSubject='', $mailBody='', $mailType='TEXT') {

    $headers  = "MIME-Version: 1.0" . "\r\n";

    if($mailType == "TEXT") {
        $headers .= "Content-type: text/plain; charset=iso-8859-1" . "\r\n";
    } else {
        $headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
    }

    $headers .= 'From: '.$mailFrom.' <'.$mailFromEmail.'>' . "\r\n".'X-Mailer: PHP/' . phpversion();

    if ($mailTo != '') {
        // echo $mailTo."<br>".$mailSubject."<br>".$mailBody."<br>".$headers ;
        //$result = mail($mailTo , $mailSubject , $mailBody , $headers );
        $result = true;
    }

    return $result ;
}
?>
