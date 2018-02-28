<?php
// Get all the stores.
$stores = Mage::getModel('core/store')
    ->getCollection()
    ->addFieldToFilter('store_id', array('gt' => 0))
    ->getAllIds();
$packages = array(
    0 => 'enterprise/tystoybox',
    1 => 'enterprise/pbskids',
);
//initializing banners array
$banners = array(
    array(
        'after_body_start',
        'Top Banner',
        '<div class="html-banner thb" style="background:#12b8ac;">
            <span><a href="#"><img src="{{skin url=images/TTB_Banner.png}}"
            alt="Header Banner" /></a></span>
        </div>'
    ),
    array(
        'top.container',
        'Free Shipping Banner',
        '<div class="html-banner-inner col-lg-12">
            <span>
                <img alt="Inner HTML Banner"
                src="{{skin url=images/free-shiping-desktop.jpg}}" />
            </span>
        </div>'
    )
);
// Loop on all the stores to create the block for each store.
foreach ($stores as $storeOrder => $store) {
    //creating banner and its respective widgets
    foreach ($banners as $sortOrder => $bannerData) {
        //creating banners
        $banner = Mage::getModel('enterprise_banner/banner')
            ->setName($bannerData[1])
            ->setIsEnabled(1)
            ->setStoreContents(array(0 => $bannerData[2]))
            ->save();
        $bannerId = $banner->getId();

    // creating widget for banner
    //foreach ($packages as $package) {
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
                            'block'         => $bannerData[0],
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
                    'package_theme' => $packages[$storeOrder],
                    'title'         => $bannerData[1],
                    'sort_order'    => $sortOrder
                )
            )
            ->save();
    }
}// end foreach

// initializing static blocks
$blocks = array(
    array(
        'title' => 'Static Footer Blocks on Homepage',
        'identifier' => 'static_block_group',
        'content' => '
            <div class="footer-banner">
                <div class="banner-block col-lg-4 col-sm-4 col-xs-12">
                    <a href="#">
                    <img alt="Footer Banner"
                    src="{{skin url=images/btm_banner_1.jpg}}" />
                    </a>
                </div>
                <div class="banner-block col-lg-4 col-sm-4 col-xs-12">
                    <a href="#">
                    <img alt="Footer Banner"
                    src="{{skin url=images/btm_banner_2.jpg}}" />
                    </a>
                </div>
                <div class="banner-block col-lg-4 col-sm-4 col-xs-12">
                    <a href="#">
                    <img alt="Footer Banner"
                    src="{{skin url=images/btm_banner_3.jpg}}" />
                    </a>
                </div>
            </div>
            <div class="footer-desc">
                <div class="footer-desc-top">
                    <span> </span>
                </div>
                <div class="desc-block">
                    <div class="desc-content">
                        <h3>What’s Inside Ty’s Ty Box?</h3>
                        <p>Here at Ty’s, we delight kids with complete
                        lines of hard-to-find character toys and party supplies,
                        and top ‘em off by personalizing hundreds of apparel and
                        décor items! When shopping for birthdays and special
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
        'widget_title' => 'HTML static block widget',
        'sort_order' => 100
    ),
    array(
        'title' => 'Left Column Static Block group',
        'identifier' => 'left_column_static_block_group',
        'content' => '
            <div class="left-col-ad-set">
                <div class="left-col-ad">
                    <a href="#">
                    <img src="{{skin url=images/homepage_banner.jpg}}"
                    alt="Sidebanner">
                    </a>
                </div>
                <div class="left-col-ad">
                    <a href="#">
                    <img src="{{skin url=images/blank_ad.png}}"
                    alt="Sidebanner">
                    </a>
                </div>
            </div>',
        'is_active' => 1,
        'stores' => array(0),
        'page_group' => 'all_pages',
        'widget_title' => 'Left side static block widget',
        'sort_order' => 1
    ),
);
// Loop on all the stores to create the block for each store.
foreach ($stores as $storeOrder => $store) {
    //creating static blocks & widgets for each static block created
    foreach ($blocks as $sortOrder => $blockData) {
        //creating block
        $block = Mage::getModel('cms/block')
            ->setTitle($blockData['title'])
            ->setIdentifier($blockData['identifier'])
            ->setStores(array($store))
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
                    'package_theme' => $packages[$storeOrder],
                    'title'         => $blockData['widget_title'],
                    'sort_order'    => $blockData['sort_order']
                )
            )
            ->save();
    }
}// end foreach
