<?php
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

$installer = $this;

$installer->startSetup();

//initializing banners array
$banners = array(
    'after_body_start',
    'Top Banner - Limoges',
    '<div class="html-banner thb" style="background:#dfdee3;">
        <span><a href="#"><img src="{{skin url=images/limoges_banner.png}}"
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



//Add banner for free gift on home page

//initializing banners array
$banners = array(
    'breadcrumbs.container',
    'Free gift banner - Limoges',
    '<div>
        banner content comes here
    </div>'
);




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