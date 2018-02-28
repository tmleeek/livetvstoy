<?php
/**
 * Baker Taylor Inventory Shell Script
 *
 * Processes inventory files from suppliers.
 * Process: Build list of products that match the selected supplier
 *          Find those products' supplier SKUs in the inventory file
 *          Set the update stock level and status for each
 *
 * @category    Mage
 * @package     Mage_Shell
 */

require_once '../abstract.php';

/**
 * Baker Taylor Inventory Shell Script
 *
 * @category    BakerTaylor
 * @package     Mage_Shell
 */
class BakerTaylor_Shell_Inventory extends Mage_Shell_Abstract
{

    /**
     * Log filename settings
     */
    const LOG_FILE_INVENTORY = 'dropship_inventory.log';

    // The new CSV is used to increase the speed of imports, but could possibly be removed, at is is only a very minor change
    public $newCsv = "/var/www/CPS/public_html/var/DropShipping/BakerTaylor/Incoming/Inventory/new.csv";

    /**
     * List of suppliers
     */
    protected $suppliers = array(
        'bs' => 'bs',
        'bt' => 'bt',
        'btbooks' => 'bt'
    );

    /**
     * Run script
     */
    public function run()
    {
        if ($this->getArg('supplier') && $this->getArg('file')) {

            Mage::log("Processing " . $this->getArg('file'), null, self::LOG_FILE_INVENTORY);
            echo "Processing " . $this->getArg('file') . "\n";
            $this->updateInventory($this->getArg('file'), $this->getArg('new'), $this->getArg('supplier'));

        } else {

            Mage::log("A file must be specified (\"file=path/filename\") and a supplier must be selected.(\"supplier=suppliercode\")", null, self::LOG_FILE_INVENTORY);
            Mage::log("Possible supplier codes:", null, self::LOG_FILE_INVENTORY);
            Mage::log("_Code_\t_Supplier_", null, self::LOG_FILE_INVENTORY);

            echo "A file must be specified (\"file=path/filename\") and a supplier must be selected.(\"supplier=suppliercode\")";
            echo "Possible supplier codes:\n";
            echo "_Code_\t_Supplier_";

            foreach ($this->suppliers as $code => $supplier) {
                echo $code . "\t= " . $supplier . "\n";
                Mage::log($code . "\t= " . $supplier . "\n", null, self::LOG_FILE_INVENTORY);
            }

            echo "Aborting\n\n";
        }
    }

    // Takes the inventory file and supplier code and determines the method
    // for finding supplier skus => qty.
    // Returns array of supplier sku to qty (key => value)
    private function _getSkusToQty($file, $supplierCode)
    {
        $skusToQty = array();

        if ($supplierCode == 'bt') {
            // handle csv for Baker & Taylor
            $fh = fopen($file, 'r');
            $skuRow = null;
            $qtyRow = null;
            while ($data = fgetcsv($fh, 0, '|')) {
                if ($data[0] != '') {
                    $skusToQty[$data[0]] = trim($data[2]);
                }
            }
            fclose($fh);
        } elseif ($supplierCode == 'btbooks') {

            $fh = fopen($file, 'r');
            $skuRow = null;
            $qtyRow = null;
            while ($data = fgetcsv($fh, 0, ',')) {
                if ($data[0] != '') {
                    // Check the value we already have -- add each row together
                    if (isset($skusToQty[$data[0]])) {
                        $skusToQty[$data[0]] += trim($data[2]);
                    } else {
                        $skusToQty[$data[0]] = trim($data[2]);
                    }
                }
            }
            fclose($fh);
        } else {

            // Create SimpleXMLElement Object for our XML File
            libxml_use_internal_errors(true);

            $tmpfile = file_get_contents($file);
            $tmpfile = str_replace('&', '&amp;', $tmpfile);
            $xml = simplexml_load_string($tmpfile);

            if (!$xml) {
                Mage::log("Unable to open XML File:", null, self::LOG_FILE_INVENTORY);
                echo "Unable to open XML File:\n";
                foreach (libxml_get_errors() as $error) {
                    Mage::log($error->message, null, self::LOG_FILE_INVENTORY);
                    echo "\t", $error->message . "\n";
                }
                die();
            }

            // Use the right XML object for this supplier
            switch ($supplierCode) {
                case 'bs':
                    $xmlObject = $xml->BsiMerchantInventoryListType->InventoryItem;
                    break;
                default:
                    $xmlObject = $xml->product;
                    break;
            }

            // Find each product and loop through them
            foreach ($xmlObject as $currentProduct) {
                switch ($supplierCode) {
                    case 'bs':
                        $sku = trim((string)$currentProduct->BuySeasonsSKU);
                        $qty = trim((string)$currentProduct->Quantity);
                        break;
                    default:
                        $sku = trim((string)$currentProduct->vendorSKU);
                        $qty = trim((string)$currentProduct->qtyonhand);
                        break;
                }
                $qty = preg_replace("/,/", "", $qty); // Remove commas
                $qty = preg_replace("/\..*/", "", $qty); // Strip decimals

                $skusToQty[$sku] = $qty;
            }
        }

        // Remove case sensitivity by making all fields uppercase
        $skusToQty = array_change_key_case($skusToQty, CASE_UPPER);
        return $skusToQty;
    }

    public function updateInventory($file, $onlyNew, $supplierCode)
    {
        // Make sure that the $supplierCode is valid
        if (!array_key_exists($supplierCode, $this->suppliers)) {
            Mage::log("Invalid supplier code '$supplierCode'. Aborting.", null, self::LOG_FILE_INVENTORY);
            echo "Invalid supplier code '$supplierCode'. Aborting.\n";
            die();
        }

        // Grab supplier from supplier code
        $supplier = $this->suppliers[$supplierCode];

        // If command was passed "--new" flag, pull in the new.csv, and add it's
        // SKUs to an array
        if ($onlyNew) {
            $newSkus = array();
            $fh = fopen($this->newCsv, 'r');
            $supplierSkuRow = null;
            while ($data = fgetcsv($fh)) {
                if (!$supplierSkuRow) {
                    foreach ($data as $header) {
                        if ($header == "supplier_sku") {
                            $supplierSkuRow = key($data) - 1;
                        }
                    }
                }
                if (!$supplierSkuRow) {
                    Mage::log("No supplier_sku row found in new.csv, aborting.", null, self::LOG_FILE_INVENTORY);
                    echo "No supplier_sku row found in new.csv, aborting.\n";
                    die();
                }

                $newSkus[] = trim($data[$supplierSkuRow]);
            }
        }

        $productCollection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToFilter('type_id', array('eq' => 'simple'))
            ->addAttributeToFilter('supplier', array('eq' => $supplier))
            ->addAttributeToSelect('supplier_sku');

        $productCollection = $productCollection->exportToArray();

        //Mage::log(print_r($productCollection, true), null, self::LOG_FILE_INVENTORY);

        $productTable = array();

        foreach ($productCollection as $key => $product) {
            $productTable[$key] = strtoupper($product['supplier_sku']);
        }

        //Mage::log(print_r($productTable, true), null, self::LOG_FILE_INVENTORY);

        $skusToQty = $this->_getSkusToQty($file, $supplierCode);

        foreach ($productTable as $productId => $supplierSku) {

            // Skip this product if it is not listed in the new products csv
            if ($onlyNew) {
                if (!in_array($supplierSku, $newSkus)) {
                    continue;
                }
            }

            if (!array_key_exists($supplierSku, $skusToQty)) {
                continue;
            }

            $qty = (int)$skusToQty[$supplierSku];

            // If a product matched our vendorSKU, update the quantity with
            // the current "qtyonhand" from the XML doc.
            $stock = Mage::getModel('cataloginventory/stock_item');
            $stock->loadByProduct($productId);

            // Find the minimum qty of the product
            $minQty = $stock->getMinQty();

            // If the product qty is below the minimum, mark it as out of stock
            if ($qty <= $minQty or $qty <= 0) {
                $stock->setData('is_in_stock', '0');
            } else {
                $stock->setData('is_in_stock', '1');
            }

            // Save changes to product stock status
            $product = Mage::getModel('catalog/product')->load($productId);
            $stock->assignProduct($product);

            $oldQty = $stock->getData('qty');

            if ($oldQty <> $qty) {
                $stock->setData('qty', $qty);
                $stock->save();
                Mage::log("Supplier SKU $supplierSku ProductID $productId updated from a quantity of " . round($oldQty) . " to a quantity of $qty.", null, self::LOG_FILE_INVENTORY);
                echo "Supplier SKU $supplierSku ProductID $productId updated from a quantity of " . round($oldQty) . " to a quantity of $qty.\n";
            }

            // Find and load parent ID
            $parentIdList = Mage::getModel('catalog/product_type_configurable')
                ->getParentIdsByChild($productId);

            foreach ($parentIdList as $parentId) {

                $parent = Mage::getModel('catalog/product')->load($parentId);

                // Skipped grouped items
                if ($parent->getTypeId() == 'simple') {
                    continue;
                }

                // Find all children IDs
                $children = Mage::getResourceSingleton('catalog/product_type_configurable')
                    ->getChildrenIds($parentId);
                $children = $children[0];

                // Array of out of stock products
                $oos = array();

                foreach ($children as $id) {
                    $stock->loadByProduct($id);
                    if ($stock->getData('is_in_stock') == '0') {
                        $oos[] = $id;
                    }
                }

                // All children are out of stock and the parent is in stock
                if (count($children) == count($oos) && $parent->getData('is_in_stock') == '1') {
                    Mage::log("Updating Parent Product ID " . $parentId . "'s stock status to \"Out of Stock\".", null, self::LOG_FILE_INVENTORY);
                    echo "Updating Parent Product ID " . $parentId . "'s stock status to \"Out of Stock\".\n";
                    $parentStock = Mage::getModel('cataloginventory/stock_item');
                    $parentStock->loadByProduct($parentId);
                    $parentStock->setData('is_in_stock', '0');
                    $parentStock->assignProduct($parent);
                    $parentStock->save();
                } // The some children have stock but the parent is listed as out of stock
                elseif (count($children) != count($oos) && $parent->getData('is_in_stock') == '0') {
                    Mage::log("Updating Parent Product ID " . $parentId . "'s stock status to \"In Stock\".", null, self::LOG_FILE_INVENTORY);
                    echo "Updating Parent Product ID " . $parentId . "'s stock status to \"In Stock\".\n";
                    $parentStock = Mage::getModel('cataloginventory/stock_item');
                    $parentStock->loadByProduct($parentId);
                    $parentStock->setData('is_in_stock', '1');
                    $parentStock->assignProduct($parent);
                    $parentStock->save();
                }
            }
        }
    }
}

$shell = new BakerTaylor_Shell_Inventory();
$shell->run();
