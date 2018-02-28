<?php
$installer = $this;

$installer->startSetup();

// Get store ids
$stores = Mage::getModel('core/store')->getCollection()
    ->addFieldToFilter('store_id', array('gt'=>0))->getAllIds();

//create store tables
foreach ($stores as $store) {
    $tableName = $installer->getTable(
        array('zeon_attributemapping/attributemapping', $store)
    );
    $tableCreate = "DROP TABLE IF EXISTS {$tableName};
        CREATE TABLE `{$tableName}` ( "
            . "`mapping_id` int(10) unsigned NOT NULL AUTO_INCREMENT "
                . "COMMENT 'Attribute Mapping Id',"
            . "`attribute_id` smallint(5) unsigned NOT NULL "
                . "COMMENT 'Attribute Id',"
            . "`option_id` smallint(5) unsigned NOT NULL "
                . "COMMENT 'Attribute Option Id',"
            . "`option_status` smallint(5) NOT NULL default '2' "
                . "COMMENT 'status of attribute option',"
            . "`url_key` varchar(255) DEFAULT NULL COMMENT 'Url key',"
            . "`display_in_slider` boolean DEFAULT FALSE,"
            . "`sort_order` int(11) DEFAULT NULL "
                . "COMMENT 'sort order on slider',"
            . "`slider_image` varchar(255) DEFAULT NULL,"
            . "`logo_image` varchar(255) DEFAULT NULL,"
            . "`page_background_image` varchar(255) NULL,"
            . "`description` text DEFAULT NULL COMMENT 'Description',"
            . "`meta_title` varchar(255) DEFAULT NULL COMMENT 'Meta Keywords',"
            . "`meta_keywords` text COMMENT 'Meta Keywords',"
            . "`meta_description` text COMMENT 'Meta Description',"
            . "PRIMARY KEY (`mapping_id`),"
            . "UNIQUE KEY `mapping_index` (`attribute_id`,`option_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Attribute Mapping table'";
    $installer->run($tableCreate);
}

// create character attribute
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$setup->addAttribute(
    'catalog_product',
    'character',
    array(
        'input'         => 'multiselect',
        'type'          => 'varchar',
        'attribute_set' => 'Default',
        'label'         => 'Character',
        'group'         => 'CPS Attributes',
        'backend'       => 'eav/entity_attribute_backend_array',
        'source'        => null,
        'entity_model'  => 'catalog/product',
        'global'        => true,
        'visible'       => true,
        'required'      => false,
        'default'       => '',
        'filterable'    => true,
        'is_filterable'    => '1',
        'option'        => array (
            'value' => array(
                'angelina-ballerina' => array('Angelina Ballerina'),
                'arthur' => array('Arthur'),
                'barney' => array('Barney'),
                'ben-10-omniverse' => array('Ben 10 Omniverse'),
                'bob-the-builder' => array('Bob the Builder'),
                'bubble-guppies' => array('Bubble Guppies'),
                'caillou' => array('Caillou'),
                'care-bears' => array('Care Bears'),
                'cat-in-the-hat' => array('Cat in the Hat'),
                'chuggington' => array('Chuggington'),
                'clifford' => array('Clifford'),
                'curious-george' => array('Curious George'),
                'daniel-tiger' => array('Daniel Tiger'),
                'dinosaur-train' => array('Dinosaur Train'),
                'disney-pixar-cars' => array('Disney Pixar Cars'),
                'dora-the-explorer' => array('Dora the Explorer'),
                'fireman-sam' => array('Fireman Sam'),
                'fizzys-lunch-lab' => array('Fizzys Lunch Lab'),
                'johnny-test' => array('Johnny Test'),
                'lego' => array('LEGO'),
                'mario' => array('Mario'),
                'martha-speaks' => array('Martha Speaks'),
                'maya-and-miguel' => array('Maya & Miguel'),
                'mickey-mouse' => array('Mickey Mouse'),
                'monster-jam' => array('Monster Jam'),
                'mr-men-little-miss' => array('Mr. Men Little Miss'),
                'olivia' => array('Olivia'),
                'peep-and-the-big-wide-world'
                    => array('Peep and the Big Wide World'),
                'pinkalicious' => array('Pinkalicious'),
                'poptropica' => array('Poptropica'),
                'raggs' => array('Raggs'),
                'rainbow-magic' => array('Rainbow Magic'),
                'richard-scarry' => array('Richard Scarry'),
                'sandra-magsamen' => array('Sandra Magsamen'),
                'scooby-doo' => array('Scooby-Doo'),
                'sesame-street' => array('Sesame Street'),
                'sid-the-science-kid' => array('Sid the Science Kid'),
                'spongebob-squarepants' => array('Spongebob Squarepants'),
                'strawberry-shortcake' => array('Strawberry Shortcake'),
                'strawberry-shortcake-classic'
                    => array('Strawberry Shortcake Classic'),
                'super-why' => array('Super Why!'),
                'teddy-ruxpin' => array('Teddy Ruxpin'),
                'thomas-and-friends' => array('Thomas & Friends'),
                'toy-story' => array('Toy Story'),
                'tys-toy-box' => array('Tys Toy Box'),
                'wiggles' => array('Wiggles'),
                'wild-kratts' => array('Wild Kratts'),
                'word-girl' => array('WordGirl'),
                'word-world' => array('WordWorld'),
                'wow-wow-wubbzy' => array('Wow! Wow! Wubbzy!'),
                'yo-gabba-gabba' => array('Yo Gabba Gabba!'),
            )
        ),
    )
);

$setup->updateAttribute('catalog_product', 'character', 'is_filterable', '1');

// create age attribute
$setup->addAttribute(
    'catalog_product',
    'shop_by_age',
    array(
        'input'         => 'multiselect',
        'type'          => 'varchar',
        'attribute_set' => 'Default',
        'label'         => 'Shop By Age',
        'group'         => 'CPS Attributes',
        'backend'       => 'eav/entity_attribute_backend_array',
        'source'        => null,
        'entity_model'  => 'catalog/product',
        'global'        => true,
        'visible'       => true,
        'required'      => false,
        'default'       => '',
        'filterable'    => true,
        'is_filterable'    => '1',
        'option'        => array (
            'value' => array(
                '0-18-months' => array('0-18 months'),
                '10-12-years' => array('10-12 years'),
                '2-3-years'   => array('2-3 years'),
                '4-6-years'   => array('4-6 years'),
                '7-9-years'   => array('7-9-years'),
                'teens'       => array('Teens'),
                'adult'       => array('Adult'),
            )
        ),
    )
);

$setup->updateAttribute('catalog_product', 'shop_by_age', 'is_filterable', '1');

// create brand attribute
$setup->addAttribute(
    'catalog_product',
    'brand',
    array(
        'input'         => 'multiselect',
        'type'          => 'varchar',
        'attribute_set' => 'Default',
        'label'         => 'Brand',
        'group'         => 'CPS Attributes',
        'backend'       => 'eav/entity_attribute_backend_array',
        'source'        => null,
        'entity_model'  => 'catalog/product',
        'global'        => true,
        'visible'       => true,
        'required'      => false,
        'default'       => '',
        'filterable'    => true,
        'is_filterable' => '1',
        'option'        => array (
            'value' => array(
                'briarpatch'             => array('Briarpatch'),
                'cardinal-games'         => array('Cardinal Games'),
                'fisher-price'           => array('Fisher Price'),
                'giddy-up'               => array('Giddy Up'),
                'gund'                   => array('GUND'),
                'Hasbro'                 => array('Hasbro'),
                'jay-franco-sons'        => array('Jay Franco & Sons'),
                'k-nex'                  => array('K\'Nex'),
                'learning-curve'         => array('Learning Curve'),
                'lego'                   => array('LEGO'),
                'license-2-play'         => array('License-2-Play'),
                'little-tikes'           => array('Little Tikes'),
                'madame-alexander-dolls' => array('Madame Alexander Dolls'),
                'mattel'                 => array('Mattel'),
                'mega-bloks'             => array('Mega Bloks'),
                'mega-brands'            => array('Mega Brands'),
                'neat-oh'                => array('Neat-Oh!'),
                'pbs'                    => array('PBS'),
                'pecoware'               => array('Pecoware'),
                'playhut'                => array('Playhut'),
                'pressman-toy'           => array('Pressman Toy'),
                'reeves-international'   => array('Reeves International'),
                'schylling-toys'         => array('Schylling Toys'),
                'spin-master'            => array('Spin Master'),
                'the-tin-box-company'    => array('The Tin Box Company'),
                'traxxas'                => array('Traxxas'),
                'uncle-milton'           => array('Uncle Milton'),
                'university-games'       => array('University Games'),
                'usaopoly'               => array('USAopoly'),
                'wild-republic'          => array('Wild Republic'),
                'wonder-forge'           => array('Wonder Forge'),
                'york-wallcoverings'     => array('York Wallcoverings'),
                'zak-designs'            => array('Zak Designs'),
                'zoobies'                => array('Zoobies'),
            )
        ),
    )
);

$setup->updateAttribute('catalog_product', 'brand', 'is_filterable', '1');

// block common content
$advContent = '<div class="left-col-ad"><a href="#">
    <img src="{{skin url=images/homepage_banner.jpg}}"
        alt="Menu Advertisement" />
    </a></div>';

$attributeHelper = Mage::helper('zeon_attributemapping');

//create cms blocks for menu
$topmenuBlock = $attributeHelper->getConfigDetails('menublock_identifier');
$blockTypes = $attributeHelper->getBlockNames();

foreach ($stores as $store) {
    $block = Mage::getModel('cms/block');
        $block->setTitle('Top Menu Block');
        $block->setIdentifier($topmenuBlock);
        $block->setStores(array($store));
        $block->setIsActive(1);
        $block->setContent(@implode(' | ', $blockTypes));
        $block->save();

    foreach ($blockTypes as $blocks) {
        if ($blocks == 'clearance') {
            continue;
        }
        $block = Mage::getModel('cms/block');
        $block->setTitle('Advertisement block for '.$blocks);
        $block->setIdentifier($attributeHelper->getBlockIdentifier($blocks));
        $block->setStores(array($store));
        $block->setIsActive(1);
        $block->setContent($advContent);
        $block->save();
    }
}

$installer->endSetup();