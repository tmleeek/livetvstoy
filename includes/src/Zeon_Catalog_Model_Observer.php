<?php
/**
 * Observar class to save the product url key in DB against the product, if url-key is blank.
 *
 * @category   Zeon
 * @package    Zeon_Catalog
 */
class Zeon_Catalog_Model_Observer
{
    /**
    * Method to set the product-url-key against the product.
    */
    public function setProductUrlKey($observer)
    {
        // Get the product from the observer.
        $product = $observer->getEvent()->getProduct();

        // Get the url-key
        $urlKey  = $product->getUrlKey();
        if ($product->getExistsStoreValueFlag('url_key')) {
            return;
        }
        // If url-key is empty then use the product name as url-key
        if (empty($urlKey) || '' == trim($urlKey)) {
            $productName = $product->getName();
            $urlKey      = Mage::getModel('catalog/product')->formatUrlKey($productName);
        }

        // Flag var to check the existing url-key in db and for counter increment.
        $checkExistingUrlKey = true;
        $counter             = 1;

        // Get the attribute of url-key
        $entity    = $product->getResource();
        $attribute = Mage::getSingleton('eav/config')->getCollectionAttribute($entity->getType(), 'url_key');

        // Loop to check, whether the url-key is already exists or not.
        while ($checkExistingUrlKey) {
            $connection = $product->getResource()->getReadConnection();
            $select = $connection->select()
                ->from(
                    $attribute->getBackendTable(),
                    array('count' => new Zend_Db_Expr('COUNT(\'value_id\')'))
                )
                ->where($connection->quoteInto('entity_id <> ?', $product->getId()))
                ->where($connection->quoteInto('value = ?', $urlKey));
            $result = $connection->fetchOne($select);

            // If there is any result then change the url-key
            if ($result) {
                $urlKey = $urlKey . '-' . $counter++;
            } else {
                // Otherwise break the loop.
                $checkExistingUrlKey  = false;
            }
        }

        // Set the final url-key against the product.
        $product->setUrlKey($urlKey);
    }

}
