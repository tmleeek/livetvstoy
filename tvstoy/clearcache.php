 <?php
require_once 'app/Mage.php';
$app = Mage::app();

//echo "<pre>";
if($app != null) {
        //echo "The app was initialized.\n";
        $cache = $app->getCache();
        if($cache != null) {
                //echo "The cache is not empty. Clean it.\n";
                $cache->clean();
        }
}
?>
