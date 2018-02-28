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

$customerLinks = array(
    'title' => 'Customer Service Links - PP',
    'identifier' => 'customer-service-links-pp',
    'content' => '
        <div class="col-lg-2 col-md-2 col-sm-12 col-xm-12">
<div class="customer-head">Customer Service</div>
<ul class="customer-links">
<li><a id="customer-service" title="Overview"
href="{{store direct_url=customer-service}}"> Overview </a></li>
<li><a id="ordering" title="Ordering"
href="{{store direct_url=customer-service/ordering}}"> Ordering </a></li>
<li><a id="payment" title="Payment"
href="{{store direct_url=customer-service/payment}}"> Payment </a></li>
<li><a id="shipping" title="Shipping"
href="{{store direct_url=customer-service/shipping}}"> Shipping </a></li>
<li><a id="your-order" title="Your Order"
href="{{store direct_url=customer-service/your-order}}"> Your Order </a></li>
<li><a id="returns" title="Returns"
href="{{store direct_url=customer-service/returns}}"> Returns </a></li>
<li class="safety-privacy"><a id="privacy"
title="Safety, Security &amp; Privacy"
href="{{store direct_url=customer-service/privacy}}">
Safety, Security &amp; Privacy </a></li>
<li class="last"><a id="contactus" title="Contact Us"
href="{{store direct_url=contactus}}"> Contact Us </a></li>
</ul>
</div>
',
    'is_active' => 1,
    'stores' => array($storeId),
    'page_group' => 'all_pages',
    'sort_order' => 1
);

//creating block
$block = Mage::getModel('cms/block')
    ->setTitle($customerLinks['title'])
    ->setIdentifier($customerLinks['identifier'])
    ->setStores(array($storeId))
    ->setContent($customerLinks['content'])
    ->setIsActive($customerLinks['is_active'])
    ->save();

$contactUsBlock = array(
    'title' => 'PP contactus info',
    'identifier' => 'pp-contactus-info',
    'content' => '
        <div class="contact-info">
<p><strong>Need help? Have feedback? Let us know.</strong></p>
<p>Feel free to email or contact us. Our
friendly staff is ready to assist you.</p>
<div class="contact-head">Phone</div>
<p><strong>1-888-957-9696</strong></p>
<p>Hours of operation</p>
<p>8 AM - 5 PM CST | Monday-Friday</p>
</div>
',
    'is_active' => 1,
    'stores' => array($storeId),
    'page_group' => 'all_pages',
    'sort_order' => 1
);

//creating block
$block = Mage::getModel('cms/block')
    ->setTitle($contactUsBlock['title'])
    ->setIdentifier($contactUsBlock['identifier'])
    ->setStores(array($storeId))
    ->setContent($contactUsBlock['content'])
    ->setIsActive($contactUsBlock['is_active'])
    ->save();