<?php
$storeId = 3;
$package = 'enterprise/personalizedplanet';
$stores = Mage::getModel('core/store')
    ->getCollection()
    ->addFieldToFilter('store_id', array('gt' => 2));
foreach ($stores as $value) {
    if ($value->getCode() == 'pp_storeview') {
        $storeId = $value->getStoreId();
    }
}

//creating flat table for attribute
$installer = $this;

$installer->startSetup();


$tableName = $installer->getTable(
    array('zeon_attributemapping/attributemapping', $storeId)
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

//initializing banners array
$banners = array(
    'after_body_start',
    'Top Banner - PP',
    '<div class="html-banner thb" style="background:#12b8ac;">
        <span><a href="#"><img src="{{skin url=images/TTB_Banner.png}}"
        alt="Header Banner" /></a></span>
    </div>'
);

//creating banners
$banner = Mage::getModel('enterprise_banner/banner')
    ->setName($banners[1])
    ->setIsEnabled(1)
    ->setStoreContents(array(0 => $banners[2]))
    ->save();
$bannerId = $banner->getId();

// creating widget for banner
$widgetInstance = Mage::getModel('widget/widget_instance')
    ->setData(
        'page_groups',
        array(
            array(
                'page_group' => 'all_pages',
                'all_pages' => array(
                    'page_id'       => 0,
                    'layout_handle' => 'default',
                    'for'           => 'all',
                    'block'         => $banners[0],
                    'template'      => 'banner/widget/block.phtml'
                ),
                'pages' => array(
                    'page_id'       => 0,
                    'for'           => 'all',
                    'layout_handle' => ''
                )
            )
        )
    )
    ->setData('store_ids', '0')
    ->setData(
        'widget_parameters',
        array(
            'display_mode' => 'fixed',
            'types'        => array(''),
            'rotate'       => '',
            'banner_ids'   => $bannerId,
            'unique_id'    => Mage::helper('core')->uniqHash()
        )
    )
    ->addData(
        array(
            'instance_type' => 'enterprise_banner/widget_banner',
            'package_theme' => $package,
            'title'         => $banners[1],
            'sort_order'    => 1
        )
    )
    ->save();


// initializing static blocks
$blocks = array(
    array(
        'title' => 'Footer Blocks on Homepage - PP',
        'identifier' => 'footer-block-on-homepage-pp',
        'content' => '
            <div class="footer-desc">
                <div class="footer-desc-top">
                    <span> </span>
                </div>
                <div class="desc-block">
                    <div class="desc-content">
                        <h3>What’s Inside Ty’s Ty Box?</h3>
                        <p>Here at Ty’s, we delight kids with complete
                        lines of hard-to-find character toys and party supplies,
                        and top off by personalizing hundreds of apparel and
                        decor items! When shopping for birthdays and special
                        occasions, parents, grandparents, aunts, uncles and
                        friends will find the perfect fun and friendly gifts
                        kids love – inside Ty’s Toy Box!
                        </p>
                    </div>
                </div>
                <div class="footer-desc-btm">
                    <span> </span>
                </div>
            </div>
            ',
        'is_active' => 1,
        'stores' => array(0),
        'page_group' => 'pages',
        'widget_title' => 'Footer Blocks on Homepage - PP',
        'sort_order' => 100
    ),
    array(
        'title' => 'Left Column Static Block group - PP',
        'identifier' => 'left_column_static_block_group',
        'content' => '
            <div class="left-col-ad-set">
                <div class="left-col-ad">
                    <a href="#">
                    <img src="{{skin url=images/homepage_banner.jpg}}"
                    alt="Sidebanner">
                    </a>
                </div>
            </div>',
        'is_active' => 1,
        'stores' => array(0),
        'page_group' => 'all_pages',
        'widget_title' => 'Left side static block widget - PP',
        'sort_order' => 1
    )
);

//creating static blocks & widgets for each static block created
foreach ($blocks as $sortOrder => $blockData) {
    //creating block
    $block = Mage::getModel('cms/block')
        ->setTitle($blockData['title'])
        ->setIdentifier($blockData['identifier'])
        ->setStores(array($storeId))
        ->setContent($blockData['content'])
        ->setIsActive($blockData['is_active'])
        ->save();
    $blockId = $block->getId();
    //checking for the page group
    //foreach ($packages as $package) {
    if ($blockData['page_group'] == 'pages') {
        $pageGroupArray = array(
            'pages' => array(
                'page_id'       => 0,
                'for'           => 'all',
                'layout_handle' => 'cms_index_index',
                'block'         => 'content',
                'template'      => 'cms/widget/static_block/default.phtml'
            )
        );
    } else if ($blockData['page_group'] == 'all_pages') {
        $pageGroupArray = array(
            'all_pages' => array(
                'page_id'       => 0,
                'layout_handle' => 'default',
                'for'           => 'all',
                'block'         => 'left',
                'template'      => 'cms/widget/static_block/default.phtml'
            )
        );
    }
    // creating widgets for respective blocks
    $widgetInstance = Mage::getModel('widget/widget_instance')
        ->setData(
            'page_groups',
            array(
                array(
                    'page_group'             => $blockData['page_group'],
                    $blockData['page_group'] =>
                        $pageGroupArray[$blockData['page_group']]
                )
            )
        )
        ->setData('store_ids', array(0))
        ->setData(
            'widget_parameters',
            array(
                'block_id'   => $blockId
            )
        )
        ->addData(
            array(
                'instance_type' => 'cms/widget_block',
                'package_theme' => $package,
                'title'         => $blockData['widget_title'],
                'sort_order'    => $blockData['sort_order']
            )
        )
        ->save();
}


$footerBlock = array(
    'title' => 'Footer Links - PP',
    'identifier' => 'footer_links',
    'content' => '
        <div class="footer-links-set col-lg-8  col-md-8 col-sm-12 col-xs-12">
<div class="slide-menu col-lg-3 col-md-3 col-sm-3 col-xs-12"><span style="font-size: small;">
<strong>Company Info</strong></span>
<ul>
<li class="first"><a title="About Us" href="{{store direct_url=\'about\'}}">About Us</a></li>
<li><a title="Contact Us" href="{{store direct_url=\'contactus\'}}">Contact Us</a></li>
<li><a title="Affiliate Program" href="http://www.shareasale.com/shareasale.cfm?merchantID=12990">
Affiliate Program</a></li>
<li class="last"><a title="Site Map" href="{{store direct_url=\'catalog/seo_sitemap/category\'}}">
Site Map</a></li>
</ul>
</div>
<div class="slide-menu col-lg-3 col-md-3 col-sm-3 col-xs-12"><span style="font-size: small;">
<strong>Customer Service</strong></span>
<ul>{{block type="page/html_footer" name="footer-ordering" as="footer-ordering"
template="page/html/footer-ordering.phtml" }}
<li><a href="{{store direct_url=\'customer-service/ordering\'}}">
Order Process</a></li>
<li><a href="{{store direct_url=\'customer-service/shipping\'}}">
Shipping</a></li>
<li class="last"><a href="{{store direct_url=\'customer-service/returns\'}}">
Returns</a></li>
</ul>
</div>
<div class="slide-menu col-lg-3 col-md-3 col-sm-3 col-xs-12">
<span style="font-size: small;"><strong>Top Categories</strong></span>
<ul>
<li class="first"><a title="Kids Beach Towel"
href="http://www.tystoybox.com/holiday-and-seasonal/summer" target="_self">Beach Towel</a></li>
<li><a title="Monster Jam Grave Digger " href="http://www.tystoybox.com/monster-jam"
target="_self">Grave Digger</a></li>
<li><a title="LEGO Playsets" href="http://www.tystoybox.com/lego" target="_self">
LEGO</a></li>
<li class="last"><a title="Curious Geroge School Supplies, Backpacks and Apparel"
href="http://www.tystoybox.com/curious-george" target="_self">Curious George</a></li>
</ul>
</div>
<div class="slide-menu col-lg-3 col-md-3 col-sm-3 col-xs-12"><span style="font-size: small;">
<strong>Top Searches</strong></span>
<ul>
<li class="first"><a title="Featured Products" href="{{store direct_url=\'catalog-manager/index/\'}}">
Featured Products</a></li>
<li><a title="Best Seller Products" href="{{store direct_url=\'catalog-manager/index/best\'}}">
Best Seller Products</a></li>
<li><a title="Most Popular Products" href="{{store direct_url=\'catalog-manager/index/popular\'}}">
Most Popular Products</a></li>
<li><a title="New Arrivals" href="{{store direct_url=\'catalog-manager/index/newarrivals\'}}">
New Arrivals</a></li>
</ul>
</div>
</div>
',
    'is_active' => 1,
    'stores' => array($storeId),
    'page_group' => 'all_pages',
    'sort_order' => 1
);

//creating block
$block = Mage::getModel('cms/block')
    ->setTitle($footerBlock['title'])
    ->setIdentifier($footerBlock['identifier'])
    ->setStores(array($storeId))
    ->setContent($footerBlock['content'])
    ->setIsActive($footerBlock['is_active'])
    ->save();