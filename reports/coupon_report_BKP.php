<?php
/*require_once('/var/www/CPS/public_html/app/Mage.php'); //Path to Magento
umask(0);
Mage::app();

$resource = Mage::getSingleton('core/resource');
$readConnection = $resource->getConnection('core_read');
*/

mysql_connect('10.0.0.29', 'tystoybox', 'noez@2014');
mysql_select_db('tystoybox');


$ruleId                     =   238;//238;
$categoryId                 =   3042;//3104;//3042;

$fileName                   =   "order_report_".date('m-d-Y_h-i-s').".csv";
$file                       =   fopen('/var/www/CPS/public_html/reports/'.$fileName, 'w');

fputcsv($file, array('Category Link', 'Total Units Purchased', 'Additional Revenues, tagged onto groupon Order','Units Redeemed',' Total Sales $ of Groupon Orders, Before Split 
'));

$totalUnits                 =   0;
$unitPurchased              =   0;
$couponArr                  =   array();
$orderCount                 =   0;
$groupedOnAmount            =   0;
$amountExclude              =   0;
$orderIdIn                  =   0;
$totalAmount                =   0;

$getOrderSql                =   "SELECT coupon_code, entity_id, increment_id, created_at
                                    FROM sales_flat_order
                                    where coupon_code in (SELECT code FROM salesrule_coupon where rule_id='$ruleId')
                                        and created_at between '2015-11-01' and UTC_TIMESTAMP()
                                    order by entity_id desc";

/*$getOrderSql                =   "SELECT coupon_code, entity_id, increment_id, created_at
                                    FROM sales_flat_order
                                    where coupon_code in (SELECT code FROM salesrule_coupon where rule_id='$ruleId')
                                        and created_at between '2015-11-06' and '2015-11-08'
                                    order by entity_id desc";*/                                    
                                    
$getOrderExe                =   mysql_query($getOrderSql);
while($getOrderIdRs  =   mysql_fetch_array($getOrderExe)){
    $orderId                =   $getOrderIdRs[1];
    $orderIdIn              =   ($orderIdIn)?$orderIdIn.",'".$getOrderIdRs[1]."'":"'".$getOrderIdRs[1]."'";
    $orderCount             =   $orderCount+1;
    
    $productSql             =   "SELECT applied_rule_ids, sku, name, product_id, qty_ordered, price
                                    FROM sales_flat_order_item
                                    where order_id='$orderId'
                                        and product_id in ('34705','37301','41697')
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

$productSql1                =   "SELECT sum(price)
                                FROM sales_flat_order_item
                                where order_id in ($orderIdIn)
                                    and product_id not in ('34705','37301','41697')
                                order by applied_rule_ids desc";
$productExe1                =   mysql_query($productSql1);
$totalAmount              =   mysql_fetch_row($productExe1);

array_push($couponArr,'http://limogesjewelry.com/special-offers/groupon-mom-necklace',$unitPurchased,$totalAmount[0],$orderCount,$groupedOnAmount);
fputcsv($file, $couponArr);
?>