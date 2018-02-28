<?php


require 'app/Mage.php';
Mage::app();

$sku = 40179;


//$product = Mage::getModel('catalog/product')->load($productId);
$product =Mage::getModel('catalog/product')->loadByAttribute('sku',$sku);

$mediaApi = Mage::getModel("catalog/product_attribute_media_api");
$items = $mediaApi->items(58019);
               if(!empty($items)){
					foreach($items as $item) {
						if($item['types'][0]=='thumbnail' || $item['types'][0]=='small_image'){

							$data = $item;
                            print_r($data);
							$data['exclude'] = 1;
							//unset($data['file']);
							$mediaApi->update(58019,$item['file'],$data);
						}
					}
					echo "Product ".$product->getId()." images excluded
";
				}

?>