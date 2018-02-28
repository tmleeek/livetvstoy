<?php
/* Upgrade script for creating varius blocks of footer section for Limoges */

$storeId = 4;
$package = 'enterprise/limoges';
$stores = Mage::getModel('core/store')
->getCollection()
->addFieldToFilter('store_id', array('gt' => 3));
foreach ($stores as $value) {
if ($value->getCode() == 'limoges_store_view') {
$storeId = $value->getStoreId();
}
}
$spdDescBlock = array(
    'title' => 'Stone Placement Diagram - Description',
    'identifier' => 'spd_desc',
    'content' => '<table border="0">
			<tbody><tr><td width="30"></td><td width="610"></td></tr>
			<tr><td valign="top">1.</td><td valign="top">Stones/Names will be set on the item in the exact order shown
			above.<br>Please refer to the diagrams above to determine where each stone and <br>name will be placed.</td>
			</tr>
			<tr><td valign="top">2.</td><td valign="top">Each stone must have a corresponding name. There can be no<br>
			blank names with stones at this time.</td></tr>
			<tr><td valign="top">3.</td><td valign="top">The font style is the style shown in the product image.</td>
			</tr>
		</tbody></table>',
    'is_active' => 1,
    'stores' => array($storeId),
    'page_group' => 'all_pages',
    'sort_order' => 1
);

//creating block
$block = Mage::getModel('cms/block')
    ->setTitle($spdDescBlock['title'])
    ->setIdentifier($spdDescBlock['identifier'])
    ->setStores(array($storeId))
    ->setContent($spdDescBlock['content'])
    ->setIsActive($spdDescBlock['is_active'])
    ->save();