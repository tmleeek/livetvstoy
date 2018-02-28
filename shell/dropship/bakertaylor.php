<?php
/**
 * Baker Taylor Drop Ship Shell Script
 *
 * @category    Mage
 * @package     Mage_Shell
 */

require_once '../abstract.php';

/**
 * Baker Taylor Drop Ship Shell Script
 *
 * @category    BakerTaylor
 * @package     Mage_Shell
 */
class BakerTaylor_Shell_DropShip extends Mage_Shell_Abstract
{
    /**
     * CPS incoming/outgoing local directories
     */
    const INCOMING_PO_DIR       = '/DropShipping/BakerTaylor/Incoming/POs';
    const OUTGOING_PO_DIR       = '/DropShipping/BakerTaylor/Outgoing/POs';
    const OUTGOING_SHIPPING_DIR = '/DropShipping/BakerTaylor/Outgoing/ShippingConf';
    const INCOMING_SHIPPING_DIR = '/DropShipping/BakerTaylor/Incoming/ShippingConf';

    /**
     * Baker Taylor customer numbers
     */
    const CUSTOMER_NUMBER_PBS = '70158630';
    const CUSTOMER_NUMBER_TTB = '70158631';

    /**
     * Baker Taylor shipping methods
     */
    const SHIP_METHOD_FREE     = '@33';
    const SHIP_METHOD_STANDARD = '@33';
    const SHIP_METHOD_2DAY     = 'ULB';
    const SHIP_METHOD_INT      = 'DHP';

    /**
     * CPS FTP settings
     */
    const FTP_HOST              = 'ftp.cpscompany.com';
    const FTP_USER              = 'cpsdrpshpbt';
    const FTP_PASS              = 'g@ru8ecR';
    const FTP_TIMEOUT           = '10';
    const FTP_OUTGOING_PO       = '/DropShipping/BakerandTaylor/Outgoing/POs';
    const FTP_INCOMING_SHIPPING = '/DropShipping/BakerandTaylor/Incoming/ShippingConf';

    /**
     * General Baker Taylor settings
     */
    const SUPPLIER_NAME = 'BakerTaylor';

    /**
     * General settings
     */
    const ADMIN_EMAIL = 'magentoalerts@cpscompany.com';

    /**
     * Log filename settings
     */
    const LOG_FILE_PO        = 'dropship.log';
    const LOG_FILE_SHIPMENTS = 'dropship_shipments.log';
    const LOG_FILE_FA        = 'dropship_fa.log';

    /**
     * ORDENT line number
     *
     * @var string
     */
    public $line;

    /** ORDENT string
     *
     * @var string
     */
    public $ordent;

    /**
     * PO file name
     *
     * @var string
     */
    public $fileName;

    /**
     * Incoming directory
     *
     * @var string
     */
    public $incomingPODir;

    /**
     * Outgoing directory
     *
     * @var string
     */
    public $outgoingPODir;

    /**
     * File to import remotely
     *
     * @var string
     */
    public $fileToImportRemote;

    /**
     * Array of shipments to convert to CSV
     *
     * @var array
     */
    public $shipmentFilesToConvert = array();

    /**
     * Array of emails to send notifications to
     *
     * @var array
     */
    public $emailNotificationsTo = array(
        'BXCustS@cpscompany.com',
        'magentoalerts@cpscompany.com'
    );

    /**
     * Class constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->incomingPODir = Mage::getBaseDir('var') . self::INCOMING_PO_DIR . DS;
        $this->outgoingPODir = Mage::getBaseDir('var') . self::OUTGOING_PO_DIR . DS;
    }

    /**
     * Run script
     */
    public function run()
    {
        if ($this->getArg('fetch')) { // -- fetch

            Mage::log("Fetching PO CSV files...", null, self::LOG_FILE_PO);
            echo "Fetching PO CSV files...\n";
            $this->fetchPOs();

        } else if ($this->getArg('process')) { // --process

            Mage::log("Processing PO CSV file(s) to ORDENT format...", null, self::LOG_FILE_PO);
            echo "Processing PO CSV file(s) to ORDENT format...\n";
            $this->processPOs();

        } else if ($this->getArg('shipment')) { // --shipment <file>

            $file = $this->getArg('shipment');
            Mage::log("Converting shipment $file", null, self::LOG_FILE_SHIPMENTS);
            echo "Converting shipment $file\n";
            $this->convertShipmentToCSV($file);

        } else if ($this->getArg('fa')) { // --fa <file>

            $file = $this->getArg('fa');
            Mage::log("Processing order acknowledgment $file", null, self::LOG_FILE_FA);
            echo "Processing order acknowledgment $file\n";
            $this->processFa($file);

        } else {
            echo $this->usageHelp();
        }
    }

    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php bakertaylor_dropship.php -- [options]
    fetch                     Fetch PO file
    process                   Process the PO file and create ORDENT PO files
    --shipment <file>         Process a shipment file

USAGE;
    }

    /**
     * Fetch CSV file(s) from remote CPS FTP and save it to local server
     *
     * @return array
     */
    public function fetchPOs()
    {
        $io = new Varien_Io_File();

        // Check and create if incoming directory does not exist
        $io->checkAndCreateFolder($this->incomingPODir);

        try {
            $ftp = $this->_ftpConnect();
            $ftp->cd(self::FTP_OUTGOING_PO . DS);
            $files = $ftp->ls();
            if (isset($files) && count($files) > 1) {
                foreach ($files as $file) {
                    if ($file['text'] != './Archive') {
                        $fileName = substr($file['text'], 2);
                        $fileToImportRemoteTmp = $ftp->read(self::FTP_OUTGOING_PO . DS . $fileName);
                        if (!$fileToImportRemoteTmp) {
                            echo "$fileName import temp error.\n";
                        }
                        if (!$io->write($this->incomingPODir . $fileName, $fileToImportRemoteTmp)) {
                            echo "Could not write local $fileName.\n";
                        }
                        // Move to Archive folder
                        $ftp->mv(self::FTP_OUTGOING_PO . DS . $fileName, self::FTP_OUTGOING_PO . '/Archive/' . $fileName);

                        Mage::log("$fileName successfully transferred and moved to archive.", null, self::LOG_FILE_PO);
                        echo "$fileName successfully transferred and moved to archive.\n\n";
                    }
                }
            } else {
                Mage::log('No file(s) to fetch.', null, self::LOG_FILE_PO);
                echo "- No file(s) to fetch.\n\n";
            }
            $ftp->close();
        } catch (Mage_Core_Exception $e) {
            echo $e->getMessage() . "\n";
        } catch (Exception $e) {
            echo "Unknown error:\n\n";
            echo $e . "\n";
        }
    }

    /**
     * Converts a comma separated file(CSV) into ORDENT file(s)
     * Moves CSV file(s) to archive once ORDENT files are created
     *
     * @return void
     */
    public function processPOs()
    {
        try {
            $io = new Varien_Io_File();
            $io->cd($this->incomingPODir);
            $files = $io->ls();
            if (isset($files) && count($files) > 0) {
                foreach ($files as $file) {
                    if ($io->fileExists($file['text'])) {
                        $array = $this->csvToArray($this->incomingPODir . $file['text']);
                        $orders = $this->parseArray($array);
                        if (isset($orders) && count($orders) > 0) {
                            foreach ($orders as $order) {
                                $ordent = $this->generatePO($order);
                                $ordentFile = $this->outgoingPODir . self::SUPPLIER_NAME . $order['PONumber'] . '.done';
                                $io->checkAndCreateFolder($this->outgoingPODir);
                                if (!$io->fileExists($ordentFile)) {
                                    $io->write($ordentFile, $ordent);

                                    Mage::log("- " . $ordentFile . " file created.", null, self::LOG_FILE_PO);

                                } else {
                                    echo "\t- " . self::SUPPLIER_NAME . $order['PONumber'] . ".done already exists.\n";
                                }
                            }
                            // Check for Archive directory
                            if ($io->checkAndCreateFolder($this->incomingPODir . '/Archive')) {
                                // Move to Archive folder
                                $io->mv($this->incomingPODir . $file['text'], $this->incomingPODir . '/Archive/' . $file['text']);
                            }

                            Mage::log($file['text'] . " order(s) converted to ORDENT.", null, self::LOG_FILE_PO);
                            echo $file['text'] . " order(s) converted to ORDENT.\n\n";
                        }
                    }
                }
            } else {
                Mage::log('No file(s) to transfer.', null, self::LOG_FILE_PO);
                echo "- No file(s) to transfer.\n\n";

            }
        } catch (Mage_Core_Exception $e) {
            echo $e->getMessage() . "\n";
        } catch (Exception $e) {
            echo "Unknown error:\n\n";
            echo $e . "\n";
        }
    }

    /**
     * Convert a comma separated file(CSV) into an associated array.
     *
     * @param string $file Path to the CSV file
     * @return array
     */
    public function csvToArray($file)
    {
        $csv = new Varien_File_Csv();
        $data = $csv->getData($file);
        $keys = array_shift($data);
        foreach ($data as $i => $row) {
            $data[$i] = array_combine($keys, $row);
        }
        return $data;
    }

    /**
     * Parse a CSV array into an array we will use to convert into ORDENT format.
     * Also, checks for duplicate keys(PONumber) in the returned array,
     * if a duplicate key exists, we add the line item data to the already existing returned array
     *
     * @param array $data Associated array of data to parse
     * @return array
     */
    public function parseArray($data = array())
    {
        $result = array();
        foreach ($data as $row) {
            $key = $row['PONumber'];
            if (!array_key_exists($key, $result)) {
                $result[$key]['PONumber'] = $key;
                $result[$key]['MG Webstore'] = $row['MG Webstore'];
                $result[$key]['Ship Via'] = $row['Ship Via'];
                $result[$key]['ShipToFirstname'] = $row['ShipToFirstname'];
                $result[$key]['ShipToLastname'] = $row['ShipToLastname'];
                $result[$key]['Shipping Address 1'] = $row['Shipping Address 1'];
                $result[$key]['Shipping Address 2'] = $row['Shipping Address 2'];
                $result[$key]['Shipping City'] = $row['Shipping City'];
                $result[$key]['Shipping State/Province'] = $row['Shipping State/Province'];
                $result[$key]['Shipping Zip'] = $row['Shipping Zip'];
                $result[$key]['Shipping Country'] = $row['Shipping Country'];
                $result[$key]['Shipping Phone'] = $row['Shipping Phone'];
            }
            $result[$key]['Items'][] = array(
                'VendorCode' => $row['VendorCode'],
                'Quantity' => $row['Quantity'],
                'Price' => $row['Price']
            );
        }
        return $result;
    }

    /**
     * Generates a PO export of an ORDENT string
     *
     * @param array $data Associated array of data to parse
     * @return array
     */
    public function generatePO($data = array())
    {
        if (!empty($data)) {

            if (isset($data['PONumber']) && $data['PONumber']) {

                $btCustomerNumber = '';
                $method = '';

                // Customer number
                if (isset($data['MG Webstore']) && $store = $data['MG Webstore']) {
                    $btCustomerNumber = $this->_getCustomerNumber($store);
                }

                // Shipping method (default to Standard for now)
                if (isset($data['Ship Via']) && $method = $data['Ship Via']) {
                    $method = self::SHIP_METHOD_STANDARD;
                }

                $this->line = "000000";
                $this->ordent = str_pad($this->line++, 6, "0", STR_PAD_LEFT) . ";;ORDENT\r\n";
                $this->ordent .= str_pad($this->line++, 6, "0", STR_PAD_LEFT) . ";~EDIID="
                    . $btCustomerNumber . "/EDIORD=Y/BO=N/"
                    . "SHPVIA=$method/CHGVIA=$method/EDIADR=Y/PONUM="
                    . $data['PONumber'];

                $shippingCountry = '';
                if (isset($data['Shipping Country'])) {
                    $shippingCountry = $data['Shipping Country'];
                }
                // If this is an international order, we have to add some extra information
                // to the header
                if ($shippingCountry != 'United States') {
                    $this->ordent .= "/EDIINT=Y/SHPCPL=Y/EDICOU=" . $shippingCountry;
                }

                $this->ordent .= "\r\n";
                $customerName = '';
                if (isset($data['ShipToFirstname']) && $data['ShipToFirstname'] != '') {
                    $customerName = $data['ShipToFirstname'] . ' ' . $data['ShipToLastname'];
                }
                $this->ordent .= str_pad($this->line++, 6, "0", STR_PAD_LEFT) . ";EDINAM="
                    . $customerName . "\r\n";

                $street1 = '';
                if (isset($data['Shipping Address 1'])) {
                    $street1 = $data['Shipping Address 1'];
                }
                $this->ordent .= str_pad($this->line++, 6, "0", STR_PAD_LEFT) . ";EDIAD1="
                    . $street1 . "\r\n";

                $street2 = '';
                if (isset($data['Shipping Address 2'])) {
                    $street2 = $data['Shipping Address 2'];
                }
                $this->ordent .= str_pad($this->line++, 6, "0", STR_PAD_LEFT) . ";EDIAD2="
                    . $street2 . "\r\n";

                $city = '';
                if (isset($data['Shipping City'])) {
                    $city = $data['Shipping City'];
                }
                $this->ordent .= str_pad($this->line++, 6, "0", STR_PAD_LEFT) . ";EDICIT="
                    . $city . "\r\n";

                $this->ordent .= str_pad($this->line++, 6, "0", STR_PAD_LEFT) . ";EDISTA=";

                // If this is an international order, the state must be set to "ZZ"
                if ($shippingCountry != 'United States') {
                    $this->ordent .= "ZZ";
                } else {
                    $this->ordent .= $data['Shipping State/Province'];
                }
                $this->ordent .= "\r\n";

                $zipCode = '';
                if (isset($data['Shipping Zip'])) {
                    $zipCode = $data['Shipping Zip'];
                }
                $this->ordent .= str_pad($this->line++, 6, "0", STR_PAD_LEFT) . ";EDIZIP="
                    . $zipCode . "\r\n";

                $this->ordent .= str_pad($this->line++, 6, "0", STR_PAD_LEFT) . ";EDIFON=" . $data['Shipping Phone'];

                // Add telephone number if this is an international order
                if ($shippingCountry != 'United States') {
                    $this->ordent .= $data['Shipping Phone'];
                }
                $this->ordent .= "\r\n";

                if (isset($data['Items']) && is_array($data['Items'])) {
                    $this->_getItemsBuildForPO($data['Items']);
                }
            }
            $this->ordent .= "999999;" . str_pad($this->line - 1, 6, "0", STR_PAD_LEFT) . "\r\n";

            // Strip out any stray "!"
            $this->ordent = str_replace('!', '', $this->ordent);
        }
        return $this->ordent;
    }

    /**
     * Builds item collection for PO Export
     *
     * @param array Associated array of data to parse
     * @param string Qualifier for ORDENT file
     * @return void
     */
    private function _getItemsBuildForPO($itemCollection = array(), $qualifier = null)
    {
        if (!empty($itemCollection)) {
            foreach ($itemCollection as $item) {
                if (isset($item['VendorCode']) && $item['VendorCode']) {
                    if (strlen($item['VendorCode']) == '13') { // TODO: strlen equals value might need to change
                        $qualifier = 'IB';
                    } else {
                        $qualifier = 'UPC';
                    }
                    $this->ordent .= str_pad($this->line++, 6, "0", STR_PAD_LEFT) . ";$qualifier="
                        . $item['VendorCode'];
                    if (isset($item['Quantity']) && $item['Quantity']) {
                        $this->ordent .= "/QTY=" . round($item['Quantity']);
                    }
                    if (isset($item['Quantity']) && $item['Quantity']) {
                        $this->ordent .= "/EDIPRC=" . round($item['Price'], 2) . "/BO=N\r\n";
                    }
                }
            }
        }
    }

    /**
     * Returns the customer number based on store name
     *
     * @param string $store
     * @return string
     */
    private function _getCustomerNumber($store)
    {
        $customerNumber = '';

        switch ($store) {
            case "TV's Toy Box":
                $customerNumber = self::CUSTOMER_NUMBER_TTB;
                break;
            case 'PBS Kids Shop':
                $customerNumber = self::CUSTOMER_NUMBER_PBS;
                break;
        }
        return $customerNumber;
    }

    /**
     * Convert a tab separated file into an comma separated file(CSV)
     *
     * @param string $file
     * @return string
     */
    public function convertShipmentToCSV($file)
    {
        $incomingPath = Mage::getBaseDir('var') . self::INCOMING_SHIPPING_DIR . DS;
        $outgoingPath = Mage::getBaseDir('var') . self::OUTGOING_SHIPPING_DIR . DS;

        $io = new Varien_Io_File();
        $io->open(array('path' => $incomingPath));
        $io->streamOpen($file, 'r');

        $data = array();
        while (false !== ($line = $io->streamReadCsv("\t"))) {
            $data[] = $line;
        }

        // If data exists, create new CSV and FTP to CPS
        if (count($data) > 0) {

            $filename = $this->_fileName($file);
            $convertedFile = $outgoingPath . $filename;

            try {

                $csv = new Varien_File_Csv();
                $csv->saveData($convertedFile, $data);

                // TODO: Transfer converted file to CPS FTP
                $ftp = $this->_ftpConnect();

                // Check and create if outgoing directory does not exist
                $io->checkAndCreateFolder($outgoingPath);

                $remoteFile = self::FTP_INCOMING_SHIPPING . DS . $filename;

                $ftp->write($remoteFile, $convertedFile);
                $ftp->close();

                echo "'$file' successfully converted as '$filename' and transferred to CPS FTP\n";
                Mage::log("'$file' successfully converted as '$filename' and transferred to CPS FTP", null, self::LOG_FILE_SHIPMENTS);

            } catch (Mage_Core_Exception $e) {

                echo $e->getMessage() . "\n";
                Mage::log($e->getMessage(), null, self::LOG_FILE_SHIPMENTS);

            } catch (Exception $e) {

                echo "Unknown error:\n\n";
                echo $e . "\n";
                Mage::log($e, null, self::LOG_FILE_SHIPMENTS);
            }
        }
    }

    /**
     * Process FA file
     *
     * @param string $file
     * @return void
     */
    public function processFa($file)
    {
        $fh = fopen($file, 'r');
        if (!$fh) {
            echo "Unable to open $file\n\n";
            die();
        }

        $rejectedOrders = array();
        $acceptedOrders = array();

        while ($line = fgetcsv($fh, 0, "\t")) {
            // Skip the header
            if ($line[0] == "ORDER") {
                continue;
            }

            if ($line[3] == "IN" or $line[3] == "OC") {
                // if the order has been invoiced
                // add it to the acceptedOrders array
                $acceptedOrders[] = $line[0];
                echo "Order " . $line[0] . " accepted\n";
            } else {
                // Otherwise, it was rejected
                $rejectedOrders[] = array($line[0], $line[3], $line[9]);
                echo "Order " . $line[0] . " rejected\n";
            }

        }

        if ($rejectedOrders) {

            echo "Sending email notifications for rejected orders.\n";

            $emailBody = "Baker & Taylor has rejected the following order number(s):\n";

            foreach ($rejectedOrders as $orderData) {
                $emailBody .= "Order " . $orderData[0] . " was rejected due to error \"";
                switch ($orderData[1]) {
                    case ("LS"):
                        $emailBody .= "Low Stock";
                        break;
                    case ("ER"):
                        $emailBody .= "SKU Error";
                        break;
                    default:
                        $emailBody .= $orderData[1];
                        break;
                }
                $emailBody .= "\" for product " . $orderData[2] . "\n";
            }

            // Send email for rejected orders
            foreach ($this->emailNotificationsTo as $address) {
                $this->sendMail($address, "Order(s) Rejected by Baker & Taylor", $emailBody);
            }
        }

        if ($acceptedOrders) {
            echo "Adding comments to accepted orders.\n";

            foreach ($acceptedOrders as $id) {
                $order = Mage::getModel('sales/order_api');
                try {
                    $orderStatus = Mage::getModel('sales/order')->loadByIncrementId($id);
                    $orderStatus = $orderStatus->getStatus();
                    echo "Adding comment to Order increment ID: $id";
                    $order->addComment($id, $orderStatus, "Baker & Taylor Order Acknowledgment recieved in $file", false);
                } catch (Exception $e) {
                    echo "Unable to leave comment for Order increment ID: $id\n";
                }
            }
        }

    }

    /**
     *  Sends an email
     *
     * @param string $to
     * @param string $subject
     * @param string $message
     * @param bool $htmlMail email in html format, default false
     * @param mixed $cc send copy to, can be a string or an array of strings
     * @return bool
     */
    public function sendMail($to, $subject, $message, $htmlMail = false, $cc = null)
    {

        $from = 'admin@tvstoybox.com';

        $mail = new Zend_Mail();

        if ($htmlMail) {
            $mail->setBodyHtml($message);
        } else {
            $mail->setBodyText($message);
        }

        $mail->setFrom($from)
            ->addTo($to)
            ->setSubject($subject);

        if ($cc) {
            $mail->addCc($cc);
        }

        try {
            $mail->send();
        } catch (Exception $e) {
            echo "Sending email has failed:\n$e\n\n";
            return false;
        }

        return true;
    }

    /**
     * Create FTP connection
     *
     * @return object
     */
    private function _ftpConnect()
    {
        $ftp = new Varien_Io_Ftp();
        try {
            $ftp->open(
                array(
                    'host' => self::FTP_HOST,
                    'user' => self::FTP_USER,
                    'password' => self::FTP_PASS,
                    'timeout' => self::FTP_TIMEOUT
                )
            );
        } catch (Mage_Core_Exception $e) {
            echo $e->getMessage() . "\n";
        } catch (Exception $e) {
            echo "Unknown error:\n\n";
            echo $e . "\n";
        }
        return $ftp;
    }

    /**
     *  Set a filename based on the absolute path of the file
     *
     * @param string $file
     * @return string
     */
    function _fileName($file)
    {
        $filename = explode('/', $file);
        return $filename[10] . '.csv';
    }
}

$shell = new BakerTaylor_Shell_DropShip();
$shell->run();
