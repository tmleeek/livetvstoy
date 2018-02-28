<?php
class Kwanso_CategoryCsv_Model_Cron{
	public function csv(){
        $ids = Mage::getStoreConfig('cat_csv_sec/cat_csv_grp/cat_csv_fld');
        $ids = explode(',',$ids);
        foreach ($ids as $id ) {
            if ($id<1) {
                continue;
            }
            $_cat =  Mage::getModel('catalog/category')->setStoreId($store_ids[0])->load($id);
            $TvsMediaUrl = Mage::app()->getStore(1)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
            $thum = $TvsMediaUrl.'catalog/category/'.$_cat->getData('thumbnail');
            $categoryName = $_cat->getName();
            $data[] = array('category_name' => $categoryName, 'category_thumbnail' => $thum ,  );
            $bool = 1;
        }
        if(!$bool){
            // Mage::getSingleton('core/session')->addError('CSV not generated. Data not processed!');
            return;
        }
        $path = Mage::getBaseDir('base').'/var/export/';
        $date = new DateTime();
        $file_name=$path.'cb_cat_file.csv';
        $df = fopen($file_name, 'w');
        fputcsv($df, array_keys(reset($array)));
        $r  = array('category_name' => 'category_name'  , 'category_thumbnail' => 'category_thumbnail' ,  );
        fputcsv($df, $r);
        foreach ($data as $row) {
            fputcsv($df, $row);
        }
        fclose($df);
        // Mage::getSingleton('core/session')->addSuccess("CSV file successfully Created \n ");
        return;
    }
}
?>