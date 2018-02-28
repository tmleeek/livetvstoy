<?php
/**
 * Blank URL Key Shell Script
 *
 * @category    Mage
 * @package     Mage_Shell
 */

require_once 'abstract.php';

/**
 * Blank URL Key Shell Script
 *
 * @category    BakerTaylor
 * @package     Mage_Shell
 */
class Url_Notification_Shell extends Mage_Shell_Abstract
{
    /**
     * Run script
     */
    public function run()
    {
        // Core resource
        $resource = Mage::getSingleton('core/resource');

        // Read connection
        $readConnection = $resource->getConnection('core_read');

        // Get the table name
        $table = $resource->getTableName('catalog_product_entity_url_key');

        // Query to select null and blank url values
        $query = "SELECT * FROM {$table} WHERE {$table}.value IS NULL OR {$table}.value = ''";

        // Run the query
        $results = $readConnection->fetchAll($query);

        // Count the results
        $total = count($results);

        // If we have results
        if ($total > 0) {

            Mage::log("(" . $total . ") blank url key(s) found.", null, 'blank_urls.log');

            $message = "The following product(s) had blank url keys:\n\n";

            // Process the result(s)
            foreach ($results as $row) {

                $valueId  = (int)$row['value_id'];
                $entityId = (int)$row['entity_id'];
                $storeId  = (int)$row['store_id'];

                $message .= "Entity ID: " . $entityId . " | Store ID: " . $storeId . "\n";

                if ($storeId == 0) {

                    // Load the product with store id
                    $product = Mage::getModel('catalog/product')
                        ->setStoreId($storeId)
                        ->load($entityId);

                    // Get the url-key
                    $urlKey = $product->getUrlKey();

                    // If url-key is empty then use the product name as url-key
                    if (empty($urlKey) || '' == trim($urlKey)) {
                        $productName = $product->getName();
                        $urlKey      = Mage::getModel('catalog/product')->formatUrlKey($productName);
                    }

                    // Set the final url-key against the product.
                    try {
                        $product->setUrlKey($urlKey);
                        $product->save();
                        $message .= "URL KEY UPDATED | Entity ID: {$entityId} | Key: {$urlKey} | Store ID: {$storeId} was updated.\n";
                        $message .= "------------------------------------------------\n";
                    } catch (Exception $e) {
                        $message .= "EXCEPTION | Entity ID: {$entityId} | Store ID: {$storeId}\n";
                        $message .= "\t" . $e->getMessage() . "\n\n";
                        $message .= "------------------------------------------------\n";
                    }

                } else {

                    // Write connection
                    $writeConnection = $resource->getConnection('core_write');

                    // Build the query to delete the record
                    $query = "DELETE FROM {$table} WHERE {$table}.value_id = {$valueId}";

                    // Execute the query
                    try {
                        $writeConnection->query($query);
                        $message .= "REMOVED | Entity ID: {$entityId} | Store ID: {$storeId} was removed.\n";
                        $message .= "------------------------------------------------\n";
                    } catch (Exception $e) {
                        $message .= "EXCEPTION | Entity ID: {$entityId} | Store ID: {$storeId}\n";
                        $message .= "\t" . $e->getMessage() . "\n\n";
                        $message .= "------------------------------------------------\n";
                    }
                }
            }
            // Send the missing url records to team
            $this->sendMail($total, $message);

        } else {

            Mage::log("No blank url keys found.", null, 'blank_urls.log');
            echo "No blank url keys found.\n\n";
        }
    }

    /**
     * Send email
     */
    public function sendMail($total, $message)
    {
        // Send the missing url records to team
        $mail = new Zend_Mail();
        $mail->setFrom('cron@tystoybox.com', 'CPS | Missing Product URLs')
            ->addTo('blake.bauman@zeonsolutions.com')
            ->setSubject('IMPORTANT: ' . $total . ' blank urls found.')
            ->setBodyText($message)
            ->addCc('sami.atari@zeonsolutions.com')
            ->addCc('suhas.dhoke@zeonsolutions.com')
            ->addCc('vidisha.ganjre@zeonsolutions.com')
            ->addCc('aniket.nimje@zeonsolutions.com');

        try {
            $mail->send();
            Mage::log("Mail sent to team.", null, 'blank_urls.log');
        } catch (Exception $e) {
            echo "Sending email has failed:\n$e\n\n";
        }
    }
}

$shell = new Url_Notification_Shell();
$shell->run();
