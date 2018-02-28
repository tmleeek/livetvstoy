<?php
error_reporting(0);
$products               =   array();

header('Content-Type: text/csv; charset=utf-8');

mysql_connect('localhost', 'root', 'test1234');
mysql_select_db('yosisamra_new');
/*require_once('/var/www/CPS/public_html/app/Mage.php'); //Path to Magento
umask(0);
Mage::app();

$resource = Mage::getSingleton('core/resource');
$readConnection = $resource->getConnection('core_read');

mysql_select_db('tystoybox');
*/
$output                     =   fopen('php://output', 'w');

if($_REQUEST['couponCount']){
    
    $fileName                   =   "coupon_usage_count_report_".date('m-d-Y');
    header('Content-Disposition: attachment; filename='.$fileName.'.csv');
    
    //$couponCode               =   array('296029','296015');
    $couponCode                 =   array();
    $couponIdExe                =   mysql_query("SELECT coupon_id from salesrule_coupon");
    while($couponCodeRs  =   mysql_fetch_array($couponIdExe, MYSQL_NUM)){
        array_push($couponCode,$couponCodeRs[0]);
    }
    
    fputcsv($output, array('Coupon Name', 'Coupon Used'));
            
    foreach($couponCode as $code){
        $value          =   mysql_query("SELECT c.code as code,sum(sc.times_used) as count FROM salesrule_coupon_usage as sc
                                left join salesrule_coupon as c on c.coupon_id = sc.coupon_id
                                where sc.coupon_id='$code'");
        while ($row = mysql_fetch_array($value, MYSQL_NUM)){
            if($row[1] && $row[0]){
                //echo $row[0]."---".$row[1]."<br/>";            
                fputcsv($output, $row);    
            }
            
        }
    }    
}else if($_REQUEST['couponProducts']){
    
    $fileName                   =   "order_report_".date('m-d-Y');
    header('Content-Disposition: attachment; filename='.$fileName.'.csv');    
    
    fputcsv($output, array('Coupon Name', 'Total Units', 'Units Purchased'));
    
    $couponCodeIdExe            =   mysql_query("SELECT coupon_code, entity_id, increment_id, created_at FROM sales_flat_order where coupon_code ='GRPS5RTAT'");
    while($couponCodeIdRs  =   mysql_fetch_array($couponCodeIdExe)){
        $couponCode             =   $couponCodeIdRs[0];
        $entityId               =   $couponCodeIdRs[1];
        $orderId                =   $couponCodeIdRs[2];
        $createdDate            =   date('m-d-Y H:i:s',Mage::getModel('core/date')->timestamp($couponCodeIdRs[3]));
        
        $productExe             =   mysql_query("SELECT applied_rule_ids, sku, name, product_id, qty_ordered  FROM sales_flat_order_item where order_id='$entityId' order by applied_rule_ids desc");
        
        $totalUnits             =   0   ;
        $unitPurchased          =   0;
        while($productRs  =   mysql_fetch_array($productExe)){
            $applied_rule_ids           =   $productRs[0];
            $sku                        =   $productRs[1];
            $name                       =   $productRs[2];
            $product_id                 =   $productRs[3];
            $qty_ordered                =   $productRs[4];
            
            $applied_rule_id            =   explode(",",$applied_rule_ids);
            $couponCodeVal              =   "";
            if($applied_rule_ids){
                $unitPurchased          =   $unitPurchased+$qty_ordered;
                $totalUnits             =   $totalUnits+$qty_ordered;
            }else{
                $totalUnits             =   $totalUnits+$qty_ordered;
            }
            $couponArr                  =   array();
            array_push($couponArr,$totalUnits,$unitPurchased);
            fputcsv($output, $couponArr);
            
        }   
    }    
}else if($_REQUEST['couponProducts1']){
    
    $fileName                   =   "order_report_".date('m-d-Y');
    //header('Content-Disposition: attachment; filename='.$fileName.'.csv');    
    
    fputcsv($output, array('Order ID','Coupon Name', 'SKU', 'Product Name', 'Rules Applied','Date and time'));
    
    $couponCodeIdExe            =   mysql_query("SELECT coupon_code, entity_id, increment_id, created_at FROM sales_flat_order where coupon_code !='' and  created_at between DATE_SUB(UTC_TIMESTAMP(),INTERVAL 1 DAY) and UTC_TIMESTAMP() order by entity_id desc") or die(mysql_error());
    while($couponCodeIdRs  =   mysql_fetch_array($couponCodeIdExe)){
        $couponCode             =   $couponCodeIdRs[0];
        $entityId               =   $couponCodeIdRs[1];
        $orderId                =   $couponCodeIdRs[2];
        $createdDate            =   date('m-d-Y H:i:s',Mage::getModel('core/date')->timestamp($couponCodeIdRs[3]));
        
        $productExe             =   mysql_query("SELECT applied_rule_ids, sku, name, product_id FROM sales_flat_order_item where order_id='$entityId' order by applied_rule_ids desc");
        //$i                      =   0;
        fputcsv($output, array(" "," "," "," "," "," "," "));
        while($productRs  =   mysql_fetch_array($productExe)){
            $applied_rule_ids           =   $productRs[0];
            $sku                        =   $productRs[1];
            $name                       =   $productRs[2];
            $product_id                 =   $productRs[3];
            
            $applied_rule_id            =   explode(",",$applied_rule_ids);
            $couponCodeVal              =   "";
            if($applied_rule_ids){
                foreach($applied_rule_id as $ruleId){
                    $getCouponValExe        =   mysql_query("SELECT code FROM salesrule_coupon where rule_id='$ruleId'");
                    $getCouponVal           =   mysql_fetch_row($getCouponValExe);
                    if($couponCodeVal){
                        $couponCodeVal      .=   ", ".($getCouponVal[0])?$getCouponVal[0]:"No Coupon Found";
                    }else{
                        $couponCodeVal      =   ($getCouponVal[0])?$getCouponVal[0]:"No Coupon Found";
                    }
                }                
            }
            
            $applied_rule_val           =   ($applied_rule_ids)?"Yes":"No";
            $products                   =   array();
            array_push($products,$orderId,$couponCodeVal,$sku,$name,$applied_rule_val,$createdDate);
            fputcsv($output, $products);
            /*if($applied_rule_ids){
                $products               =   array();
                if($i == 0){
                    array_push($products,$couponCode,$product_id,$name,$sku,$entityId);    
                }else{  
                    array_push($products,$couponCode,$product_id,$name,$sku,$entityId);
                }
                array_push($products,$couponCode,$product_id,$name,$sku,'Yes',$entityId);
                fputcsv($output, $products);
                //$i++;
            }else{
                //array_push($products,$couponCode,$product_id,$name,$sku,'No',$entityId);
                // fputcsv($output, $products);
                //$i++;
            }*/
        }   
    }    
}





?>