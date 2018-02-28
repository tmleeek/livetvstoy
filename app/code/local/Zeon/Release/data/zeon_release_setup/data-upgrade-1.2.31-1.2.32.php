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

$footerBlock = array(
    'title' => 'Footer Links - Limoges',
    'identifier' => 'footer_links',
    'content' => '<div class="footer-links-set">
<div class="slide-menu col-lg-4 col-md-4 col-sm-4 col-xs-12"><span>Company Info<span>&nbsp;</span></span>
<ul>
<li class="first"><a href="{{store direct_url="about"}}">About Us</a></li>
<li><a href="{{store direct_url="contactus"}}">Contact Us</a></li>
</ul>
</div>
<div class="slide-menu col-lg-4 col-md-4 col-sm-4 col-xs-12"><span>Customer Service<span>&nbsp;</span></span>
<ul>
<li class="first"><a href="{{store direct_url="sales/order/history/"}}">Track My Order</a></li>
<li><a href="#">Shipping &amp; Delivery</a></li>
<li><a href="#">Returns</a></li>
<li class="last"><a href="#">Cancellations</a></li>
</ul>
</div>
<div class="slide-menu col-lg-4 col-md-4 col-sm-4 col-xs-12"><span>Top Categories<span>&nbsp;</span></span>
<ul>
<li class="first"><a href="#">Content Link</a></li>
<li><a href="#">Content Link</a></li>
<li><a href="#">Content Link</a></li>
<li class="last"><a href="#">Content Link</a></li>
</ul>
</div>
</div>',
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


$footerAboutBlock = array(
    'title' => 'Footer About Us - Limoges',
    'identifier' => 'footer_about',
    'content' => '<div class="footer-contactus">
    <h4>About Limog&eacute;s Jewelry</h4>
    <p>For more than 20 years, our team of experts has been supplying top name retailers with exquisite pieces. Most
    likely you\'ve admired one of our dazzling designs in a store or catalog without ever realizing their secret. We\'ve
    nurtured and grown our labour of love and have truly earned our reputation as a premier supplier of personalized
    jewelry products.There really is a world of sparkling, delightful, one-of-a-kind jewelry that can adorn you or
    someone you love for less than you ever thought possible... you only need to know exactly where to look. Welcome to
    Limoges Jewelry&hellip;. and enjoy!</p>
</div>',
    'is_active' => 1,
    'stores' => array($storeId),
    'page_group' => 'all_pages',
    'sort_order' => 1
);

//creating block
$block = Mage::getModel('cms/block')
    ->setTitle($footerAboutBlock['title'])
    ->setIdentifier($footerAboutBlock['identifier'])
    ->setStores(array($storeId))
    ->setContent($footerAboutBlock['content'])
    ->setIsActive($footerAboutBlock['is_active'])
    ->save();


$footerContactBlock = array(
    'title' => 'Footer Contact Us - Limoges',
    'identifier' => 'footer-contact',
    'content' => '<div class="footer-contact">
        <h4>CONTACT US</h4>
        <p>Got a question? We&rsquo;re here to help.</p>
        <p>Monday - Friday, 7am to 8pm CST, Saturday, 7am to 5pm CST</p>
        <p>1-847-375-1326</p>
        </div>',
    'is_active' => 1,
    'stores' => array($storeId),
    'page_group' => 'all_pages',
    'sort_order' => 1
);

//creating block
$block = Mage::getModel('cms/block')
    ->setTitle($footerContactBlock['title'])
    ->setIdentifier($footerContactBlock['identifier'])
    ->setStores(array($storeId))
    ->setContent($footerContactBlock['content'])
    ->setIsActive($footerContactBlock['is_active'])
    ->save();


$footerCopyrightBlock = array(
    'title' => 'Footer copyright - Limoges',
    'identifier' => 'footer-copyright',
    'content' => '<p>Use of this Web site constitutes acceptance of the <a title=""
href="{{store direct_url=customer-service/privacy}}">User Agreement</a> and <a title="Safety, Security &amp; Privacy"
href="{{store direct_url=customer-service/privacy#privacypolicy}}">Privacy Policy.</a></p>',
    'is_active' => 1,
    'stores' => array($storeId),
    'page_group' => 'all_pages',
    'sort_order' => 1
);

//creating block
$block = Mage::getModel('cms/block')
    ->setTitle($footerCopyrightBlock['title'])
    ->setIdentifier($footerCopyrightBlock['identifier'])
    ->setStores(array($storeId))
    ->setContent($footerCopyrightBlock['content'])
    ->setIsActive($footerCopyrightBlock['is_active'])
    ->save();

$socialLinksBlock = array(
    'title' => 'Footer Social Links - Limoges',
    'identifier' => 'social-links',
    'content' => '<div class="social-links">
<h4>Keep in touch</h4>
<a class="pinterest" title="Pin us on pinterest" href="https://www.pinterest.com/" target="_blank">
</a> <a class="fb" title="Like Us on Facebook"
href="http://www.facebook.com/" target="_blank"></a>
<a class="twitter" title="Follow us" href="http://www.twitter.com" rel="publisher" target="_blank">
</a></div>',
    'is_active' => 1,
    'stores' => array($storeId),
    'page_group' => 'all_pages',
    'sort_order' => 1
);

//creating block
$block = Mage::getModel('cms/block')
    ->setTitle($socialLinksBlock['title'])
    ->setIdentifier($socialLinksBlock['identifier'])
    ->setStores(array($storeId))
    ->setContent($socialLinksBlock['content'])
    ->setIsActive($socialLinksBlock['is_active'])
    ->save();