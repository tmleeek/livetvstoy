<?php
$installer = $this;

$installer->startSetup();

// Get store ids
$stores = Mage::getModel('core/store')->getCollection()
    ->addFieldToFilter('store_id', array('gt'=>0))->getAllIds();

// Var to store the package/theme names.
$packageName = array(
    0 => 'enterprise/tystoybox',
    1 => 'enterprise/pbskids',
);

$popularBlock = 'homepage_popular_character';
$content = '{{block
            type="zeon_attributemapping/view"
            name="popularcharacter"
            as="popularcharacter"
            template="zeon/attributemapping/block_popular.phtml"}}';

//create store tables
foreach ($stores as $sortOrder => $store) {
    $tableName = $installer->getTable(
        array('zeon_attributemapping/attributemapping', $store)
    );
    $tableAlter = "ALTER TABLE `{$tableName}`
		ADD COLUMN `populer_character` boolean DEFAULT FALSE,
		ADD COLUMN `populer_position` int(11) DEFAULT NULL;";
    $installer->run($tableAlter);

    $block = Mage::getModel('cms/block');
        $block->setTitle('Homepage - Most Popular Characters');
        $block->setIdentifier($popularBlock);
        $block->setStores(array($store));
        $block->setIsActive(1);
        $block->setContent($content);
        $block->save();

       $pageGroupArray = array(
            'page_id'       => 0,
            'for'           => 'all',
            'layout_handle' => 'cms_index_index',
            'block'         => 'content',
            'template'      => 'cms/widget/static_block/default.phtml'
        );

        // Creating widgets for respective blocks
        Mage::getModel('widget/widget_instance')
            ->setData(
                'page_groups',
                array(
                    array(
                        'page_group' => 'pages',
                        'pages'      => $pageGroupArray
                    )
                )
            )
            ->setData('store_ids', array($store))
            ->setData(
                'widget_parameters',
                array('block_id' => $block->getId())
            )
            ->addData(
                array(
                    'instance_type' => 'cms/widget_block',
                    'package_theme' => $packageName[$sortOrder],
                    'title'         => 'Homepage - Most Popular Characters',
                    'sort_order'    => '2'
                )
            )
            ->save();

}

$installer->endSetup();
