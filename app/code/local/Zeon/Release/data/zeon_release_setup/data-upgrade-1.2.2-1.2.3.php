<?php
// initializing static blocks
$blockData = array(
    0 => array(
        'title' => 'Tystoybox contactus info',
        'identifier' => 'tystoybox-contactus-info',
        'content' => '
            <div class="contact-info">
                <p><strong>Need help? Have feedback? Let us know.</strong></p>
                <p>Feel free to email or contact us.
                Our friendly staff is ready to assist you.</p>

                <div class="contact-head">Phone</div>
                <p><strong>1-888-957-9696</strong></p>
                <p>Hours of operation</p>
                <p>8 AM - 5 PM CST | Monday-Friday</p>
            </div>
            ',
        'is_active' => 1,
        'stores' => array(0)
    ),
    1 => array(
        'title' => 'Customer Service Links',
        'identifier' => 'customer-service-links',
        'content' => '
            <div class="col-lg-2 col-md-2 col-sm-12 col-xm-12">
            <div class="customer-head">Customer Service</div>
            <ul class="customer-links">
                <li>
                    <a title="Overview" id="customer-service"
                    href="{{store direct_url=customer-service}}">
                    Overview
                    </a>
                </li>
                <li>
                    <a title="Ordering" id="ordering"
                    href="{{store direct_url=customer-service/ordering}}">
                    Ordering
                    </a>
                </li>
                <li>
                    <a title="Payment" id="payment"
                    href="{{store direct_url=customer-service/payment}}">
                    Payment
                    </a>
                </li>
                <li>
                    <a title="Shipping" id="shipping"
                    href="{{store direct_url=customer-service/shipping}}">
                    Shipping
                    </a>
                </li>
                <li>
                    <a title="Your Order" id="your-order"
                    href="{{store direct_url=customer-service/your-order}}">
                    Your Order
                    </a>
                </li>
                <li>
                    <a title="Returns" id="returns"
                    href="{{store direct_url=customer-service/returns}}">
                    Returns
                    </a>
                </li>
                <li class="safety-privacy">
                    <a title="Safety, Security &amp; Privacy"
                    id="privacy"
                    href="{{store direct_url=customer-service/privacy}}">
                    Safety, Security &amp; Privacy
                    </a>
                </li>
                <li class="last">
                    <a title="Contact Us" id="contactus"
                    href="{{store direct_url=contactus}}">
                    Contact Us
                    </a>
                </li>
            </ul>
            </div>
            ',
        'is_active' => 1,
        'stores' => array(0)
    ),
    2 => array(
        'title' => 'Top Contact Links',
        'identifier' => 'top-contact-links',
        'content' => '
            <ul class="contact-links">
            <li>
                <a title="Contact Us" href="{{store direct_url="contactus"}}">
                Contact Us
                </a>
            </li>
            <li>888-123-4567</li>
            <li>Chat</li>
            <li class="last">
                <a title="Track Order"
                href="{{store direct_url="sales/order/history/"}}">
                Track Order
                </a>
            </li>
            </ul>
            ',
        'is_active' => 1,
        'stores' => array(0)
    )

);
//creating block

foreach ($blockData as $key => $block) {

    Mage::getModel('cms/block')
        ->setTitle($block['title'])
        ->setIdentifier($block['identifier'])
        ->setStores($block['stores'])
        ->setContent($block['content'])
        ->setIsActive($block['is_active'])
        ->save();
}