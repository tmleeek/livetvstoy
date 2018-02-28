<?php
error_reporting(1);
require_once('/var/www/CPS/public_html/app/Mage.php'); //Path to Magento
//umask(0);
Mage::app();

/*$resource = Mage::getSingleton('core/resource');
$readConnection = $resource->getConnection('core_read');
*/

mysql_connect('10.0.0.29', 'tystoybox', 'noez@2014');
mysql_select_db('tystoybox') or die(mysql_error());

$fileName                       =   "groupOn_report_".date('m-d-Y_h-i-s').".csv";
$file                           =   fopen('/var/www/CPS/public_html/reports/'.$fileName, 'w+');

fputcsv($file, array('Category Link', 'Total Units Purchased', 'Additional Revenues, tagged onto groupon Order','Units Redeemed',' Total Sales $ of Groupon Orders, Before Split 
'));


/*--------------------------------- Limoges Jewelry ----------------------------------*/

//$ruleIdArr                      =   array('212','238','459','363','585');
$ruleIdArr                      =   array(
                                          array('212'),
                                          array('238'),
                                          array('459'),
                                          array('363'),
                                          array('585')                                          
                                        );
$categoryIdArr                  =   array('3041','3042','3057','3059','3104');                                        


$categoryLinkArr                =   array(
                                          'http://limogesjewelry.com/special-offers/groupon-necklace',
                                          'http://limogesjewelry.com/special-offers/groupon-mom-necklace',
                                          'http://limogesjewelry.com/special-offers/groupon-class-rings',
                                          'http://limogesjewelry.com/special-offers/groupon-i-love-you-rings',
                                          'http://limogesjewelry.com/special-offers/groupon-ladies-class-rings'
                                        );

generateReport($categoryIdArr,$ruleIdArr,$file,$categoryLinkArr);

/*---------------------------------------------End------------------------------------------*/

fputcsv($file, array(" "," "," "," "," "));
fputcsv($file, array(" "," "," "," "," "));

/*--------------------------------- personalizedplanet ----------------------------------*/


$categoryIdArr                  =   array('3088','3089','3106');
$ruleIdArr                      =   array(
                                          array('520','556','557','558'),
                                          array('519','550','555'),
                                          array('610','611','612')
                                        );

$categoryLinkArr                =   array(
                                          'http://limogesjewelry.com/special-offers/groupon-necklace',
                                          'http://limogesjewelry.com/special-offers/groupon-mom-necklace',
                                          'http://limogesjewelry.com/special-offers/groupon-class-rings'
                                        );

generateReport($categoryIdArr,$ruleIdArr,$file,$categoryLinkArr);

/*---------------------------------------------End------------------------------------------*/


                                        
function generateReport($categoryIdArr,$ruleIdArr,$file,$categoryLinkArr){
    for($i=0;$i<count($categoryIdArr);$i++){
        $totalUnits                 =   0;
        $unitPurchased              =   0;
        $couponArr                  =   array();
        $orderCount                 =   0;
        $groupedOnAmount            =   0;
        $amountExclude              =   0;
        $orderIdIn                  =   0;
        $totalAmount                =   0;
        $productIdStr               =   "";    
        for($r=0;$r<count($ruleIdArr[$i]);$r++){
              
            $ruleIdValue                =   $ruleIdArr[$i][$r];
            $getRuleConditionSql        =   "SELECT actions_serialized FROM salesrule where rule_id='$ruleIdValue'";
            $getRuleConditionExe        =   mysql_query($getRuleConditionSql) or die(mysql_error());
            $getRuleConditionRs         =   mysql_fetch_row($getRuleConditionExe);
            $getRuleCondition           =   unserialize($getRuleConditionRs[0]);
            
            if($getRuleCondition['conditions']){
                $productIdStr           =   getProductIds($getRuleCondition['conditions']);
            }
        
            $getOrderSql                =   "SELECT coupon_code, entity_id, increment_id, created_at
                                                FROM sales_flat_order
                                                where coupon_code in (SELECT code FROM salesrule_coupon where rule_id='$ruleIdValue')
                                                    and created_at between '2015-11-01' and UTC_TIMESTAMP()
                                                order by entity_id desc";
                                           
                                                
            $getOrderExe                =   mysql_query($getOrderSql);
            while($getOrderIdRs  =   mysql_fetch_array($getOrderExe)){
                $orderId                =   $getOrderIdRs[1];
                $orderIdIn              =   ($orderIdIn)?$orderIdIn.",'".$getOrderIdRs[1]."'":"'".$getOrderIdRs[1]."'";
                $orderCount             =   $orderCount+1;   
                
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
                                            
            $productSql1                =   "SELECT sum(price)
                                            FROM sales_flat_order_item
                                            where order_id in ($orderIdIn)
                                                and product_id not in ($productIdStr)
                                            order by applied_rule_ids desc";
            $productExe1                =   mysql_query($productSql1);
            $totalAmount                =   mysql_fetch_row($productExe1);
            
        }
          
        $categoryUrlArr                 =   Mage::getModel('catalog/category')->setStoreId(Mage::app()->getStore()->getId())->load($categoryIdArr[$i]);
        print_r($categoryIdArr[$i]);
            
        array_push($couponArr,$categoryUrlArr->getUrl(),$unitPurchased,$totalAmount[0],$orderCount,$groupedOnAmount);
        fputcsv($file, $couponArr);     
    }
}
                                        
function getProductIds($ruleCondition){
       
    $productIds                 =   ""; 
        
    for($i=0;$i<count($ruleCondition);$i++){
        if($ruleCondition[$i]['attribute']=='sku'){
            if($ruleCondition[$i]['operator'] == '()'){
                $SKUStr   =   $ruleCondition[$i]['value'];
                $SKUValArr  =   explode(",",$SKUStr);
                for($j=0;$j<count($SKUValArr);$j++){
                    $skuId              =   trim($SKUValArr[$j]);
                    $getProductIdSql    =  "SELECT entity_id as product_id FROM catalog_product_entity where sku='$skuId'";
                    $getProductIdExe    =   mysql_query($getProductIdSql);
                    $getProductIdRs     =   mysql_fetch_row($getProductIdExe);
                    $productIds         =   ($productIds)?$productIds.",'".$getProductIdRs[0]."'":"'".$getProductIdRs[0]."'";
                }
            }
        }else if($ruleCondition[$i]['attribute']=='category_ids'){
            if($ruleCondition[$i]['operator'] == '=='){
                $productArr     =   array();
                $productIdStr   =   trim($ruleCondition[$i]['value']);
                $getProductsSQl =   "SELECT product_id from catalog_category_product where category_id='$productIdStr'";
                $getProductsExe =   mysql_query($getProductsSQl);
                if(mysql_num_rows($getProductsExe)>0){
                    while($getProductsRs = mysql_fetch_array($getProductsExe)){
                        array_push($productArr,trim($getProductsRs[0]));
                    }
                    for($j=0;$j<count($productArr);$j++){
                        $productIds           =   ($productIds)?$productIds.",'".$productArr[$j]."'":"'".$productArr[$j]."'";
                    }                    
                }
            }
        }
    }
    return $productIds;
}

?>