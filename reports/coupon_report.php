<?php
error_reporting(1);
/*require_once('/var/www/CPS/public_html/app/Mage.php'); //Path to Magento
umask(0);
Mage::app();

$resource = Mage::getSingleton('core/resource');
$readConnection = $resource->getConnection('core_read');
*/

mysql_connect('10.0.0.29', 'tystoybox', 'noez@2014');
mysql_select_db('tystoybox');


$fileName                       =   "order_report_".date('m-d-Y_h-i-s').".csv";
$file                           =   fopen('/var/www/CPS/public_html/reports/'.$fileName, 'w');

fputcsv($file, array('Category Link', 'Total Units Purchased', 'Additional Revenues, tagged onto groupon Order','Units Redeemed',' Total Sales $ of Groupon Orders, Before Split 
'));


/*--------------------------------- Limoges Jewelry ----------------------------------*/

$ruleIdArr                      =   array('212','238','459','363','585');
$productIdArr                   =   array(
                                          array('37026','37590','37870'),
                                          array('34705','37301','41697'),
                                          array('19966','19967','19968','19969','19970','19971','19972','19973','19974','19975','19976','19977','19978','19979','20018','20019','20020','20021','20048','20049','20050','20063','20064','20065','21621','21622','21623','23159','23160','35394','35397','35399','35400','35528','35740','38154','38155','38156','38157','38158','38159','38160','38161','38162','38163','38164','38165','38166','38167','38168','38169','38300','38301','38302','38303','38304','38305','38306','38307','38308','38309','38310','38311','38312','38313','38314','38315','38316','38317','38318','38319','38320','38321','38322','38323','38324','38325','38326','38327','38328','38329','38330','38331','38332','38333','38334','38335','38336','38337','38338','38339','38340','38341','38342','38343','38344','38345','38346','39077','39078','39079','39080','39081','39082','39084','39085','39086','39087','39088','39089','39090','39092','39093','39094','39095'),
                                          array('37287','35239'),
                                          array('42017','42008','41999')
                                        );
$categoryIdArr                  =   array('3041','3042','3057','3059','3104');
/*
 3041 : Groupon Necklace
 3042 : Groupon Mom Necklace
 3057 : Groupon Class Rings
 3059 : Groupon I Love You Rings
 3104 : Groupon Ladies' Class Rings
 */
$categoryLinkArr                =   array(
                                          'http://limogesjewelry.com/special-offers/groupon-necklace',
                                          'http://limogesjewelry.com/special-offers/groupon-mom-necklace',
                                          'http://limogesjewelry.com/special-offers/groupon-class-rings',
                                          'http://limogesjewelry.com/special-offers/groupon-i-love-you-rings',
                                          'http://limogesjewelry.com/special-offers/groupon-ladies-class-rings'
                                        );

for($i=0;$i<count($ruleIdArr);$i++){
    
    $totalUnits                 =   0;
    $unitPurchased              =   0;
    $couponArr                  =   array();
    $orderCount                 =   0;
    $groupedOnAmount            =   0;
    $amountExclude              =   0;
    $orderIdIn                  =   0;
    $totalAmount                =   0;
    $productIdStr               =   "";
    
    for($j=0;$j<count($productIdArr[$i]);$j++){
        $productIdStr           =   ($productIdStr)?$productIdStr.",'".$productIdArr[$i][$j]."'":"'".$productIdArr[$i][$j]."'";
    }
 
    $getOrderSql                =   "SELECT coupon_code, entity_id, increment_id, created_at
                                        FROM sales_flat_order
                                        where coupon_code in (SELECT code FROM salesrule_coupon where rule_id='$ruleIdArr[$i]')
                                            and created_at between '2015-11-01' and UTC_TIMESTAMP()
                                        order by entity_id desc";
    
    /*$getOrderSql                =   "SELECT coupon_code, entity_id, increment_id, created_at
                                        FROM sales_flat_order
                                        where coupon_code in (SELECT code FROM salesrule_coupon where rule_id='$ruleIdArr[$i]')
                                            and created_at between '2015-11-06' and '2015-11-08'
                                        order by entity_id desc";*/                                 
                                        
    $getOrderExe                =   mysql_query($getOrderSql);
    while($getOrderIdRs  =   mysql_fetch_array($getOrderExe)){
        $orderId                =   $getOrderIdRs[1];
        $orderIdIn              =   ($orderIdIn)?$orderIdIn.",'".$getOrderIdRs[1]."'":"'".$getOrderIdRs[1]."'";
        $orderCount             =   $orderCount+1;
        
        /*$productSql             =   "SELECT applied_rule_ids, sku, name, product_id, qty_ordered, price
                                        FROM sales_flat_order_item
                                        where order_id='$orderId'
                                            and product_id in (SELECT product_id from catalog_category_product where category_id='$categoryId')
                                        order by applied_rule_ids desc";*/    
        
        $productSql             =   "SELECT applied_rule_ids, sku, name, product_id, qty_ordered, price
                                        FROM sales_flat_order_item
                                        where order_id='$orderId'
                                            and product_id in ($productIdStr)
                                        order by applied_rule_ids desc";
                                        
        $productExe             =   mysql_query($productSql);
        while($productRs  =   mysql_fetch_array($productExe)){
            $applied_rule_ids   =   $productRs[0];
            $qty_ordered        =   $productRs[4];
            $price              =   $productRs[5];
            
            if($applied_rule_ids){
                $unitPurchased  =   $unitPurchased+$qty_ordered;
                $totalUnits     =   $totalUnits+$qty_ordered;
                $groupedOnAmount=   $groupedOnAmount+$price;
            }else{
                $totalUnits     =   $totalUnits+$qty_ordered;
                //$amountExcluede =   $amountExcluede+$price;
            }
        }
    }
    
    /*$productSql1                =   "SELECT sum(price)
                                    FROM sales_flat_order_item
                                    where order_id in ($orderIdIn)
                                        and product_id not in (SELECT product_id from catalog_category_product where category_id='$categoryId')
                                    order by applied_rule_ids desc";*/
                                    
    $productSql1                =   "SELECT sum(price)
                                    FROM sales_flat_order_item
                                    where order_id in ($orderIdIn)
                                        and product_id not in ($productIdStr)
                                    order by applied_rule_ids desc";
    $productExe1                =   mysql_query($productSql1);
    $totalAmount              =   mysql_fetch_row($productExe1);
    
    array_push($couponArr,$categoryLinkArr[$i],$unitPurchased,$totalAmount[0],$orderCount,$groupedOnAmount);
    fputcsv($file, $couponArr);
}



?>