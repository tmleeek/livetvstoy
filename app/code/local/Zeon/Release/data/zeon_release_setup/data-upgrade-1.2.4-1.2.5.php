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
// initializing static blocks
$blocks = array(
    array(
        'title' => 'Shop By Age Block on Homepage',
        'identifier' => 'static-footer-block-homepage1',
        'content' => '
            <div class="banner-block col-lg-4 col-sm-4 col-xs-12">
                <a href="#">
                <img alt="Footer Banner"
                src="{{skin url=images/btm_banner_1.jpg}}" />
                </a>
            </div>
            ',
        'is_active' => 1,
        'stores' => array(0)
    ),
    array(
        'title' => 'Party Planner Block on Homepage',
        'identifier' => 'static-footer-block-homepage2',
        'content' => '
            <div class="banner-block col-lg-4 col-sm-4 col-xs-12">
                <a href="#">
                <img alt="Footer Banner"
                src="{{skin url=images/btm_banner_2.jpg}}" />
                </a>
            </div>
            ',
        'is_active' => 1,
        'stores' => array(0)
    ),
    array(
        'title' => 'Big Savings Block on Homepage',
        'identifier' => 'static-footer-block-homepage3',
        'content' => '
            <div class="banner-block col-lg-4 col-sm-4 col-xs-12">
                <a href="#">
                <img alt="Footer Banner"
                src="{{skin url=images/btm_banner_3.jpg}}" />
                </a>
            </div>
            ',
        'is_active' => 1,
        'stores' => array(0)
    ),
    array(
        'title' => 'Static Footer Block contents on Homepage',
        'identifier' => 'static-footer-block-contents-homepage',
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
        'stores' => array(0)
    )
);

// Loop on all the stores to create the block for each store.
foreach ($stores as $storeOrder => $store) {
    //creating static blocks & widgets for each static block created
    foreach ($blocks as $sortOrder => $blockData) {

        //creating block
        $block = Mage::getModel('cms/block');
        $block->setTitle($blockData['title'])
            ->setIdentifier($blockData['identifier'])
            ->setStores(array($store))
            ->setContent($blockData['content'])
            ->setIsActive($blockData['is_active'])
            ->save();


    }
}//end foreach


$existingBlock = array(
    'title' => 'Static 4 Footer Blocks on Homepage',
    'identifier' => 'static_block_group',
    'content' => '
        <div class="footer-banner">
            {{block type="cms/block" block_id="static-footer-block-homepage1"}}
            {{block type="cms/block" block_id="static-footer-block-homepage2"}}
            {{block type="cms/block" block_id="static-footer-block-homepage3"}}
        </div>
        {{block type="cms/block" block_id="static-footer-block-contents-homepage"}}
        ',
    'is_active' => 1,
    'stores' => array(0)
);
//Check For Existing Block
$cmsBlog = Mage::getModel('cms/block')->load($existingBlock['identifier']);
if ($cmsBlog) {
    //updating block
    $cmsBlog->setData('content', $existingBlock['content'])
        ->setData('title', $existingBlock['title'])
        ->save();
}