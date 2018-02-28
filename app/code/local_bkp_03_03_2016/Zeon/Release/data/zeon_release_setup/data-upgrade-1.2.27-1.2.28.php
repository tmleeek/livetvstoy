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
//array for cms pages

$cmsPages = array(
    array(
        'title' => 'About Personalize Planet',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'about',
        'content_heading' => 'About Personalize Planet',
        'stores' => array($storeId),
        'content' => '
<div class="about-section first">
     Coming Soon...
</div>
        ',
        'is_active'     => 1,
        'stores'        => array($storeId),
        'sort_order'    => 26
    ),//end about
    array(
        'title' => 'Customer Service Ordering',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'customer-service/ordering',
        'content_heading' => 'Customer Service Ordering',
        'stores' => array($storeId),
        'content' => '

<div class="customer-service row">
{{block type="cms/block" block_id="customer-service-links-pp"}}

<div class="cs-wrapper col-lg-10 col-md-10 col-sm-12 col-xm-12">
    <ul id="cs-sections" class="cs">

        <li id="cs-ordering" class="section">

            <div class="green-title-sec">
                <span class="green-title">Ordering</span>
            </div>

            <div id="cs-section-ordering" class="step a-item">
                <h3 id="shoppingcart">Shopping Cart</h3>

                <p>When you are shopping on PersonalizePlanet.com and add an item to
                your cart, it is saved in your Shopping Cart. The Shopping Cart
                holds products you wish to purchase while you shop, the same
                way you use a shopping cart in a retail store. Items you place
                in your Shopping Cart will remain there until they are purchased
                or removed.</p>

                <h4>Options in the Shopping Cart</h4>

                <br />

                <ul>

                    <li><span>Review your order.</span> This ensures the
                    accuracy of items in your Shopping Cart and the quantity
                    selected.</li>

                    <li><span>Update the quantity.</span> To do this, simply
                    type in the quantity you wish to purchase and click
                    "Update Cart."</li>

                    <li><span>Remove an item from your Shopping Cart.</span>
                    To remove an item, click on the trashcan icon or change the
                    quantity to zero and click "Update Cart."</li>

                    <li><span>Continue shopping.</span> If you wish to continue
                    shopping on PersonalizePlanet.com, you can use the Shopping Cart to
                    store items you wish to purchase. Click on "Continue
                    Shopping" to search the site for additional items. At any
                    time during your shopping experience, you can return to your
                    Shopping Cart by clicking on "View Cart."</li>

                    <li><span>Proceed to Secure Checkout.</span> When you are
                    ready to purchase your item(s), click on "Secure Checkout."
                    Our checkout process is fast, easy and secure. For more
                    information on our secure shopping guarantee,
                    <a href="/customer-service/privacy">click here</a>.</li>

                </ul>

                <h3 id="checkout">Checkout</h3>

                <p>You are now ready to complete your purchase.</p>

                <h4>Returning Customers:</h4>

                <ul>

                    <li> <span>Sign In.</span> First, sign in using your email
                    address and password. If you forgot your password,
                    <a href="/customer/account/forgotpassword/">click here</a>.

                        <ul>

                            <li>Your email address serves as a convenient way to
                            receive important information about your order and
                            serves as your PersonalizePlanet.com account identification.
                            </li>

                            <li>Your account stores information such as order
                            history and your billing and shipping address. It
                            also offers you the ability to track your order(s).
                            The password assures that only you can have access
                            to your account information.</li>

                        </ul>

                    </li>

                    <li> <span>Select or Add Shipping Address.</span> Next,
                    select or add the shipping address where you would like your
                    order sent.

                        <ul>

                            <li>To select an address, click the drop down box
                            under &ldquo;Choose a Shipping Method.&rdquo;
                            To add an address, click on &ldquo;Add a New
                            Shipping Address.&rdquo;</li>

                        </ul>

                    </li>

                    <li> <span>Select Shipping Options.</span> Select a shipping
                    method for your item(s) and click "Continue Checkout." </li>

                    <li> <span>Select and Enter Payment Options.</span> At this
                    time, you must enter your payment method. For more
                    information on payment, <a href="/customer-service/payment">
                    click here</a>. </li>

                    <li> <span>Order Review.</span> On the Order Review page,
                    you can review your entire order, including your shipping
                    address, shipping method(s), and billing information, all
                    of which can be edited at this point. You will also see
                    your applied discounts and promotions, total charges and
                    gift options. To complete your purchase, click
                    "Send My Order." </li>

                    <li> <span>Receipt Page.</span> After you complete the
                    checkout process, the Receipt page provides you with your
                    order number.  You will need to save this order number for
                    your records. You will need this information for all
                    references to your order. <em>Please note: We cannot change
                    or cancel an order once it has been placed.
                    For more information about Changing or Canceling an Order,
                    <a href="/customer-service/your-order">click here</a>.</em>
                    </li>

                </ul>

                <h4>New Customers:</h4>

                <ul>

                    <li> <span>Sign In.</span> First, sign in using your email
                    address and password. If you forgot your password,
                    <a href="/customer/account/forgotpassword/">click here</a>.

                        <ul>

                            <li>Your email address serves as a convenient way to
                            receive important information about your order and
                            serves as your PersonalizePlanet.com account identification.
                            </li>

                            <li>Your account stores information such as order
                            history and your billing and shipping address. It
                            also offers you the ability to track your order(s).
                            The password assures that only you can have access
                            to your account information.</li>

                        </ul>

                    </li>

                    <li> <span>Enter Shipping/Billing Address.</span> Enter your
                    shipping and billing address in the fields below and
                    indicate where you would like your order sent. Click
                    "Continue Checkout" when complete.

                        <ul>

                            <li>Your name and billing address must be entered
                            exactly as they appear on your credit card statement
                            to avoid any delay in the authorization process.
                            </li>

                        </ul>

                    </li>

                    <li> <span>Select Shipping Options.</span> Select a shipping
                    method for your item(s) and click "Continue Checkout." </li>

                    <li> <span>Select and Enter Payment Options.</span> At this
                    time, you must enter your payment method. For more
                    information on payment, <a href="/customer-service/payment">
                    click here</a>. </li>

                    <li> <span>Order Review.</span> On the Order Review page,
                    you can review your entire order, including your shipping
                    address, shipping method(s), and billing information, all
                    of which can be edited at this point. You will also see your
                    applied discounts and promotions, total charges and gift
                    options. To complete your purchase, click "Send My Order."
                    </li>

                    <li> <span>Receipt Page.</span> After you complete the
                    checkout process, the Receipt page provides you with your
                    order number.  You will need to save this order number for
                    your records. You will need this information for all
                    references to your order. <em>Please note: We cannot change
                    or cancel an order once it has been placed. For more
                    information about Changing or Canceling an Order,
                    <a href="/customer-service/your-order">click here</a>.</em>
                    </li>

                </ul>

                <h3 id="giftwrap">Gift Wrap/Gift Message</h3>

                <p>For your shopping convenience, some items can be gift-wrapped
                for a cost of $3.99 per item.  If you would like to have your
                item(s) gift-wrapped, simply click on the Gift-Wrap option
                during Checkout.</p>

                <h4>Steps to Selecting Gift-Wrap:</h4>

                <ul>

                    <li>When you are finished, click "Continue Checkout."</li>

                    <li>The gift-wrap charge will be noted on the following
                    screen.</li>

                </ul>

                <h4>Please note:</h4>

                <ul>

                    <li>Gift-wrapping may not be available for some items due to
                    their size. If you are purchasing an item that can be
                    gift-wrapped, the Gift-Wrap option will be noted on the
                    Product page, in your Cart, and the Review Your Order page.
                    </li>

                    <li>The charge for gift-wrap and a personalized card is
                    $4.95 per item and is not refundable.</li>

                    <li>If you do not want your item wrapped, do not select the
                    Gift-Wrap option.</li>

                </ul>

                <h3 id="couponcodes">Coupon Codes</h3>

                <h4>Steps for Redeeming a Coupon Code:</h4>

                <ul>

                    <li>In the Shopping Cart, prior to checking out, enter the
                    code exactly as it appears, in the box next to "If you have
                    a special offer code, enter it now." <span>Codes are case
                    sensitive.</span></li>

                    <li>Click "Apply Discount."</li>

                    <li>If your discount qualifies, it will be displayed in the
                    payment summary. <span>Only one Coupon Code per order will
                    be accepted.</span></li>

                </ul>

                <h3 id="changingcancel">Changing or Canceling Your Order</h3>

                <p>After you have clicked "Send My Order", your order begins to
                process and <span>you cannot cancel or change your order.</span>
                * Our system is designed to fill orders as quickly as possible.
                Once you receive your order in the mail, simply return any items
                you do not want by following our
                <a href="/customer-service/returns">Return Instructions</a>.
                Please note: personalized items may not be returned unless
                damaged or defective.</p>

            </div>
        </li>

    </ul></div></div>
        ',
        'is_active'     => 1,
        'stores'        => array($storeId),
        'sort_order'    => 20
    ),//end customer-service-order
    array(
        'title' => 'Customer Service Returns',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'customer-service/returns',
        'content_heading' => 'Customer Service Returns',
        'stores' => array($storeId),
        'content' => '
<div class="customer-service row">
{{block type="cms/block" block_id="customer-service-links-pp"}}
<div class="cs-wrapper col-lg-10 col-md-10 col-sm-12 col-xm-12">
           </div></div>
        ',
        'is_active'     => 1,
        'stores'        => array($storeId),
        'sort_order'    => 23
    ),//end returns
    array(
        'title' => 'Customer Service Shipping',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'customer-service/shipping',
        'content_heading' => 'Customer Service Shipping',
        'stores' => array($storeId),
        'content' => '
<div class="customer-service row">
{{block type="cms/block" block_id="customer-service-links-pp"}}
<div class="cs-wrapper col-lg-10 col-md-10 col-sm-12 col-xm-12">

    <ul id="cs-sections" class="cs">
        <li id="cs-shipping" class="section">
                <div class="green-title-sec">
                    <span class="green-title">Shipping</span>
                </div>
            <div id="cs-section-shipping" class="step a-item">

                 <h3 id="instockitems">In-Stock Items</h3>
                <p>Most orders for In-Stock items begin the order process as
                soon as your online purchase is completed. Your In-Stock item
                will be shipped once the item is located in stock, your payment
                is approved, and the receiving address is verified. You will not
                be charged for any item until it is shipped to you.</p>
                <p>For example, if you order an In-Stock item on Monday that
                leaves the warehouse in 1-2 full business days, it will leave
                the warehouse by end-of-day Wednesday. During checkout, you will
                get to select a shipping method and see the total time it takes
                for the item to leave the warehouse and ship to its destination.
                Please note that business days are Monday-Friday, excluding
                federal holidays within the United States.</p>
                <p>Please note expected shipment times appearing on the
                product&rsquo;s detail page specify when an item is expected to
                leave our warehouse not when the item will arrive at its final
                shipping destination. After your order leaves our warehouse,
                delivery times vary according to the shipping method you select
                during checkout and the location of your shipping address.</p>
                <h3 id="customprinteditems">Custom Printed Items</h3>
                <p>Please allow an additional 2-4 business days for all custom
                printed items (with or without a name) to be created prior to
                shipping.  All custom printed items ship by US Mail.
                International and expedited shipping is not available.</p>
                <h3 id="shoppingmethods">Shipping Methods and Costs</h3>
                <p>Depending on the item(s) you purchase on PersonalizePlanet.com and
                the location to which the items will be delivered, different
                shipping methods will be available. Each shipping method has its
                own restrictions and charges that will be applied to your order.
                </p>
                <p>At checkout, you will be prompted to choose a shipping method
                for your item(s). (Please note, some items may offer only one
                shipping method.) Shipping costs are dependent on the items in
                your order and the shipping method you select. Your total
                shipping charges will automatically compute during checkout
                prior to the completion of your order.</p>
                <p>Generally, you will have the option of upgrading your
                shipping method for faster delivery (such as Second Day, or
                Overnight service). If you choose to upgrade your shipping
                method, your order must be received and clear credit
                authorization by 12:00 p.m. (noon) EST or your order may not be
                processed until the following business day. Business days are
                Monday-Friday, excluding federal holidays within the United
                States.</p>
                <h4>Domestic Shipping</h4>
                <p>We pride ourselves on being one of the fastest shippers on
                the Internet. If you place your order Monday - Friday before
                3 pm EST, the order will usually ship the same day. Orders
                placed after the 3 pm EST cutoff will usually ship the next
                business day. Orders placed between Friday 3 pm EST and Monday
                3 pm EST will usually ship that Monday.</p>
                <ul>
                    <li><span>Economy Ground</span> - Delivered by your local
                    Post Office. This is our most economical shipping method.
                    Delivery confirmation is provided and this method takes
                    approximately 4-7 business days for delivery.  P.O.
                    Boxes are accepted with this shipping method.</li>
                    <li><span>Standard Ground</span> - Designed for residential
                    deliveries, and arrives within approximately 3-5 business
                    days.  This method CANNOT ship to P.O. Boxes.</li>
                    <li><span>FedEx 2nd Day Air</span> - Guaranteed 2 business
                    day (Monday - Friday, excluding holidays) delivery to most
                    locations in the U.S. mainland after the package leaves our
                    warehouse. Generally all 2nd Day Air orders ship from our
                    warehouse on the same day if order is placed by 3:00 pm
                    EST. This method CANNOT ship to P.O. Boxes.</li>
                    <li><span>FedEx Next Day Air</span> - Guaranteed next
                    business day delivery to most locations in the U.S. mainland
                    after the package leaves our warehouse. Generally all Next
                    Day Air orders ship from our warehouse on the same day if
                    ordered by 3:00 pm EST.  Shipping to your business address
                    could result in more timely delivery over shipping to your
                    residential address. This method CANNOT ship to P.O. Boxes.
                    </li>
                </ul>
                <h4>Drop Ship</h4>
                <p>PersonalizePlanet.com offers a large selection of items that may be
                sent by various shipping carriers depending on where the item is
                shipping from. Orders that contain items shipping from various
                locations will arrive in separate packages. Please allow 3 to 4
                extra days for shipping these items. Expedited or express
                shipping may not be available on orders containing these items
                during checkout.</p>
                <h4>Shipping to Canada</h4>
                <p>We do not currently offer shipping to Canada.</p>

                <h4>Shipping to Alaska and Hawaii</h4>
                <ul>
                    <li>We ship all orders to Alaska and Hawaii via USPS
                    Priority Mail. While most orders arrive in a timely manner,
                    occasionally shipments may take 2-5 weeks.</li>
                    <li>If you haven\'t received your order in 14 business days,
                    please email <a href="mailto:customerservice@PersonalizePlanet.com">
                    customerservice@PersonalizePlanet.com</a>.</li>
                </ul>
                <h3 id="shoppingrules">Shipping Rules and Restrictions</h3>
                <ul>
                    <li>Orders are shipped on business days only. Business days
                    are Monday-Friday, excluding federal holidays within the
                    United States.</li>
                    <li>PersonalizePlanet.com makes every effort to ship In-Stock
                    items within 24 hours of order placement.</li>
                    <li>All expedited orders require a street address.</li>
                    <li>Please allow an additional 2-4 business days for all
                    custom printed items (with or without a name) to be created
                    prior to shipping.  All custom printed items ship by US
                    Mail. International and expedited shipping is not available.
                    </li>
                    <li>Please call a Customer Service Specialist at
                    1-888-957-9693 if you have any questions.</li>
                </ul>
                <h4>Oversized Items</h4>
                <p>Oversized items include train tables, play boards, furniture
                items, large train sets, and other items denoted on the site.
                Each item will be charged the applicable oversized shipping
                charges. Oversized items are not eligible for free shipping
                promotions and are not available to be shipped internationally
                or using expedited shipping methods.</p>
                <h3 id="trackingandorder">Tracking Your Order</h3>
                <p>At our warehouse, tracking numbers are assigned to packages
                and are generally available within 24 hours.  However, it may
                take up to 48 hours or longer before the package is checked into
                the carrier\'s tracking system. Therefore, even though your
                package has already shipped from our warehouse and is on its way
                to you, the carrier may not be able to provide any information
                about your package for up to 48 hours or more.  Please allow an
                additional 2-4 business days for custom printed items to be
                created prior to shipping.</p>
                <p>When you click on &ldquo;Track Order&rdquo;, you will be
                prompted to login with your e-mail address and order number. An
                order summary page will provide you with detailed information
                about your current order or past orders. After your order is
                shipped, your tracking number, if available, will be displayed.
                Depending on the shipping company, you can click on the tracking
                number to view the delivery status of your order. A shipping
                company may not have the ability to track a number for up to 24
                business hours.</p>
                <p>Orders placed on PersonalizePlanet.com may be delivered by one of
                several different carriers and shipping methods. Therefore,
                tracking availability may vary depending on the type of item you
                purchased, the shipping method you selected during Checkout, and
                the carrier that is delivering your item(s).</p>
            </div>
            </div></div>
        ',
        'is_active'     => 1,
        'stores'        => array($storeId),
        'sort_order'    => 24
    ),//end shipping
    array(
        'title' => 'Shipping & Delivery',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'shipping_and_delivery',
        'content_heading' => 'Shipping & Delivery',
        'stores' => array($storeId),
        'content' => '',
        'is_active'     => 1,
        'stores'        => array($storeId),
        'sort_order'    => 25
    ),//end shipping & delivery
    array(
        'title' => 'Cancellation',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'cancellation',
        'content_heading' => 'cancellation',
        'stores' => array($storeId),
        'content' => '',
        'is_active'     => 1,
        'stores'        => array($storeId),
        'sort_order'    => 25
    ),//end cancellation
    array(
        'title' => 'Customer Service Privacy',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'customer-service/privacy',
        'content_heading' => 'Customer Service Privacy',
        'stores' => array($storeId),
        'content' => '
<div class="customer-service row">
{{block type="cms/block" block_id="customer-service-links-pp"}}
<div class="cs-wrapper col-lg-10 col-md-10 col-sm-12 col-xm-12">
    Coming Soon...
    </div></div>
        ',
        'is_active'     => 1,
        'stores'        => array($storeId),
        'sort_order'    => 22
    ),//end privacy

    array(
        'title' => 'User Agreement',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'user_agreement',
        'content_heading' => 'User Agreement',
        'stores' => array($storeId),
        'content' => '',
        'is_active'     => 1,
        'stores'        => array($storeId),
        'sort_order'    => 25
    ),//end User Agreement
    array(
        'title' => 'Customer Service Payment',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'customer-service/payment',
        'content_heading' => 'Customer Service Payment',
        'stores' => array($storeId),
        'content' => '

<div class="customer-service row">
{{block type="cms/block" block_id="customer-service-links-pp"}}
<div class="cs-wrapper  col-lg-10 col-md-10 col-sm-12 col-xm-12">

    <ul id="cs-sections" class="cs">
        <li id="cs-payment" class="section">

            <div class="green-title-sec">
                <span class="green-title">Payment</span>
            </div>

            <div class="step-title">
                <div class="cs-wrapper">

                    <ul id="cs-sections" class="cs">
                        <li id="cs-payment" class="section">

                            <div id="cs-section-payment" class="step a-item">
                                <div id="payment-left-col">
                                    <h3 id="paymentoptions">Payment Options</h3>
                                    <h4>Credit cards accepted:</h4>
                                    <ul>
                                        <li>Visa</li>
                                        <li>MasterCard</li>
                                        <li>American Express</li>
                                        <li>Discover Card</li>
                                    </ul>
                                </div>
                                <div id="payment-right-col">
                                    <h4>We do not accept:</h4>
                                    <ul>
                                        <li>Personal checks or money orders</li>
                                        <li>COD&rsquo;s</li>
                                        <li>Layaway plan</li>
                                        <li>Canadian checks or money orders</li>
                                    </ul>
                                </div>
                                <h4>Important information about payment:</h4>
                                <ul>
                                    <li>Debit and Bank Check Cards may reflect
                                    deduction of funds immediately upon order.
                                    </li>
                                    <li>For your security, your billing name and
                                    address must match that of the credit card
                                    used for payment. We reserve the right to
                                    cancel any order that does not match these
                                    criteria.</li>
                                </ul>
                                <h3 id="couponcodes">Coupon Codes</h3>
                                <h4>Steps for Redeeming a Coupon Code:</h4>
                                <ul>
                                    <li>In the shopping cart, prior to checking
                                    out, enter the code exactly as it appears in
                                    the box next to "If you have a special offer
                                    code, enter it now"</li>
                                    <li>Click "Apply Discount."</li>
                                    <li>If your discount qualifies, it will be
                                    displayed in the payment summary. Only one
                                    Coupon Code per order will be accepted.</li>
                                </ul>
                                <h3 id="paypal">PayPal</h3>
                                <p>PayPal is an alternate method for purchasing
                                your order on PersonalizePlanet.com. It enables any
                                individual or business with an email address to
                                securely send payments online. With a PayPal
                                account, you can choose to pay with your credit
                                card, debit card, bank account, or PayPal
                                account balance for any purchase you make. Your
                                credit card and bank numbers are never seen by
                                the seller or merchant. Plus, you\'re 100%
        protected against unauthorized payments sent from your account.</p>
                                <h4>PayPal Conditions</h4>
                                <ul>
                                    <li>If you select PayPal as your payment
                                    option, you will continue through the
                                    standard checkout process then automatically
                                    proceed to paypal.com to complete your
                                    payment.</li>
                                    <li>Once you have been redirected to
                                    paypal.com, you will have 25 minutes to
                                    complete the payment before your order is
                                    dropped.</li>
                                    <li>PayPal currently cannot be used by
                                    Customers with a Canadian shipping and/or
                                    billing address.</li>
                                </ul>
                                <p>For more information, visit the
                                <a href="https://www.paypal.com/help"
                                target="_blank">PayPal Help Center</a>.</p>
                                <h4 id="paypalfaq">PayPal FAQ</h4>
                                <ul>
                                    <li><span>How does PayPal work?</span><br />
                                    PayPal is used to securely send payments
                                    over the Internet. You can choose to pay
                                    from your PayPal account balance, a credit
                                    card, debit card, or bank account. To make a
                                    PayPal purchase, select PayPal during
                                    checkout and choose your method of payment.
                                    Your funds are transferred immediately and
                                    securely. PayPal currently cannot be used by
                                    Customers with a Canadian shipping and/or
                                    billing address.</li>
                                    <li><span>How do I create a PayPal account?
                                    </span><br />To get started, simply fill out
                                    the PayPal registration with your desired
                                    account type, country of residence, home
                                    address, and login information.</li>
                                    <li><span>How secure is PayPal?</span><br />
                                    PayPal is highly secure and committed to
                                    protecting the privacy of its users. Its
                                    industry-leading fraud prevention team is
                                    constantly developing state-of-the-art
                                    technology to keep your money and
                                    information safe. When you use PayPal to
                                    send money, recipients never see your bank
                                    account or credit card numbers.</li>
                                    <li><span>How do I contact PayPal Customer
                                    Service?</span><br />For the fastest
                                    response, you may access the user-friendly
                                    <a href="https://www.paypal.com/help"
                                    target="_blank">Help Center</a>. Developed
                                    by the PayPal Customer Service team, this
                                    Help Center contains a comprehensive
                                    information database. Simply type a question
                                    into the search box to receive a complete
                                    answer.  If you do not find the information
                                    you need in the Help Center, PayPal Customer
                                    Service representatives are available to
                                    assist you.
                                    <a href="https://www.paypal.com/us/cgi-bin/
                                    helpscr?cmd=_help&t=escalateTab"
                                    target="_blank">Send an email</a> for a
                                    prompt response or contact PayPal directly
                                    by phone:</li>
                                </ul>
                                <p>Customer Service: 888-957-9693 (a U.S.
                                telephone number)<br /> 4:00 AM PDT to 10:00 PM
                                PDT Monday through Friday<br /> 6:00 AM PDT to
                                8:00 PM PDT Saturday and Sunday<br />
                                <a href="https://www.paypal.com/signup"
                                target="_blank">Sign up for PayPal now</a></p>
                            </div>
                        </li>
                    </ul>
                </div>

            </div></div>
        ',
        'is_active'     => 1,
        'stores'        => array($storeId),
        'sort_order'    => 21
    ),//end of payment
    array(
        'title' => 'Overview',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'customer-service',
        'content_heading' => 'Customer Service Overview',
        'stores' => array($storeId),
        'content' => '<div class="customer-service row">
{{block type="cms/block" block_id="customer-service-links-pp"}}
<div class="cs-wrapper  col-lg-10 col-md-10 col-sm-12 col-xm-12">
Coming Soon...
</div></div>',
        'is_active'     => 1,
        'stores'        => array($storeId),
        'sort_order'    => 25
    ),//end Overview
    array(
        'title' => 'Personalized Apparel Sizing Information - Personalized Planet',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'sizing/personalized',
        'content_heading' => 'Personalized Apparel Sizing Information',
        'stores' => array($storeId),
        'content' => '

<div class="sizing-wrapper">
     <div class="sizing-content">
     <h3 id="first">T-Shirt Measurements</h3>
        <div class="sizing-content-left-col">
             <p>All measurements are in inches.</p>
                <table width="450" cellspacing="0">
                <thead>
                <tr>
                <th width="150" colspan="3" class="align-left">Size</th>
                <th width="50">Length</th>
                <th width="50">Chest</th>
                <th width="50">Sleeve</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                <td colspan="6" class="white align-left"><span>Toddler</span>
                Note: sizes run small</td>
                </tr>
                <tr>
                <td width="50" colspan="1" class="align-left">2T</td>
                <td width="50"class="white">&nbsp;</td>
                <td width="50">&nbsp;</td>
                <td>14</td>
                <td>12</td>
                <td>4</td>
                </tr>
                <tr>
                <td class="align-left">3T</td>
                <td class="white">&nbsp;</td>
                <td>&nbsp;</td>
                <td>15</td>
                <td>13</td>
                <td>4 1/4</td>
                </tr>
                <tr>
                <td class="align-left">4T</td>
                <td class="white">&nbsp;</td>
                <td>&nbsp;</td>
                <td>16</td>
                <td>14</td>
                <td>4 1/2</td>
                </tr>
                <tr>
                <td class="align-left">5/6</td>
                <td class="white">&nbsp;</td>
                <td>&nbsp;</td>
                <td>18.5</td>
                <td>15</td>
                <td>5</td>
                </tr>
                <tr>
                <td colspan="6" class="white">&nbsp;</td>
                </tr>
                <tr>
                <td colspan="6" class="white align-left"><span>Youth</span></td>
                </tr>
                <tr>
                <td class="align-left">Small</td>
                <td class="white">6-8</td>
                <td>&nbsp;</td>
                <td>21 1/2</td>
                <td>17</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td class="align-left">Medium</td>
                <td class="white">10-12</td>
                <td>&nbsp;</td>
                <td>23</td>
                <td>18</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td class="align-left">Large</td>
                <td class="white">14-16</td>
                <td>&nbsp;</td>
                <td>25</td>
                <td>19</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td class="align-left">X Large</td>
                <td class="white">18-20</td>
                <td>&nbsp;</td>
                <td>26 1/2</td>
                <td>20</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td colspan="6" class="white">&nbsp;</td>
                </tr>
                <tr>
                <td colspan="6" class="white align-left"><span>Adult</span></td>
                </tr>
                <tr>
                <td class="align-left">Small</td>
                <td class="white">&nbsp;</td>
                <td>&nbsp;</td>
                <td>28</td>
                <td>18</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td class="align-left">Medium</td>
                <td class="white">&nbsp;</td>
                <td>&nbsp;</td>
                <td>29</td>
                <td>20</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td class="align-left">Large</td>
                <td class="white">&nbsp;</td>
                <td>&nbsp;</td>
                <td>30</td>
                <td>22</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td class="align-left">X Large</td>
                <td class="white">&nbsp;</td>
                <td>&nbsp;</td>
                <td>31</td>
                <td>24</td>
                <td>&nbsp;</td>
                </tr>
                </tbody>
                </table>

                <h3>Crewneck Sweatshirts</h3>
                <table width="450" cellspacing="0">
                <thead>
                <tr>
                <th width="150" colspan="3" class="align-left">Size</th>
                <th width="50">Length</th>
                <th width="50">Chest</th>
                <th width="50">Sleeve</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                <td colspan="6" class="white align-left"><span>Toddler</span>
                Note: sizes run small</td>
                </tr>
                <tr>
                <td width="50" colspan="1" class="align-left">2T</td>
                <td width="50"class="white">&nbsp;</td>
                <td width="50">&nbsp;</td>
                <td>15</td>
                <td>13</td>
                <td>11 3/4</td>
                </tr>
                <tr>
                <td colspan="1" class="align-left">3T</td>
                <td class="white">&nbsp;</td>
                <td>&nbsp;</td>
                <td>16</td>
                <td>13 1/2</td>
                <td>12 1/4</td>
                </tr>
                <tr>
                <td class="align-left">4T</td>
                <td class="white">&nbsp;</td>
                <td>&nbsp;</td>
                <td>17</td>
                <td>14</td>
                <td>13 3/4</td>
                </tr>
                <tr>
                <td class="align-left">5/6</td>
                <td class="white">&nbsp;</td>
                <td>&nbsp;</td>
                <td>18</td>
                <td>15</td>
                <td>16</td>
                </tr>
                <tr>
                <td colspan="6" class="white">&nbsp;</td>
                </tr>
                <tr>
                <td colspan="6" class="white align-left"><span>Youth</span></td>
                </tr>
                <tr>
                <td class="align-left">Small</td>
                <td class="white">6-8</td>
                <td>&nbsp;</td>
                <td>18</td>
                <td>15</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td class="align-left">Medium</td>
                <td class="white">10-12</td>
                <td>&nbsp;</td>
                <td>21</td>
                <td>17</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td class="align-left">Large</td>
                <td class="white">14-16</td>
                <td>&nbsp;</td>
                <td>24</td>
                <td>19</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td class="align-left">X Large</td>
                <td class="white">18-20</td>
                <td>&nbsp;</td>
                <td>25 1/2</td>
                <td>20</td>
                <td>&nbsp;</td>
                </tr>
                </tbody>
                </table>


                <h3>Hooded Sweatshirts</h3>
                <table width="450" cellspacing="0">
                <thead>
                <tr>
                <th width="150" colspan="3" class="align-left">Size</th>
                <th width="50">Length</th>
                <th width="50">Chest</th>
                <th width="50">Sleeve</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                <td colspan="6" class="white align-left"><span>Toddler</span>
                Note: sizes run small</td>
                </tr>
                <tr>
                <td width="50" colspan="1" class="align-left">2T</td>
                <td width="50"class="white">&nbsp;</td>
                <td width="50">&nbsp;</td>
                <td>15 5/8</td>
                <td>14 1/2</td>
                <td>12</td>
                </tr>
                <tr>
                <td class="align-left">4T</td>
                <td class="white">&nbsp;</td>
                <td>&nbsp;</td>
                <td>16 5/8</td>
                <td>15 1/2</td>
                <td>12 3/4</td>
                </tr>
                <tr>
                <td class="align-left">5/6</td>
                <td class="white">&nbsp;</td>
                <td>&nbsp;</td>
                <td>17 5/8</td>
                <td>16 1/2</td>
                <td>13 1/2</td>
                </tr>
                <tr>
                <td colspan="6" class="white">&nbsp;</td>
                </tr>
                <tr>
                <td colspan="6" class="white align-left"><span>Youth</span></td>
                </tr>
                <tr>
                <td class="align-left">Small</td>
                <td class="white">6-8</td>
                <td>&nbsp;</td>
                <td>18</td>
                <td>15</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td class="align-left">Medium</td>
                <td class="white">10-12</td>
                <td>&nbsp;</td>
                <td>21</td>
                <td>17</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td class="align-left">Large</td>
                <td class="white">14-16</td>
                <td>&nbsp;</td>
                <td>24</td>
                <td>19</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td class="align-left">X Large</td>
                <td class="white">18-20</td>
                <td>&nbsp;</td>
                <td>25 1/2</td>
                <td>20</td>
                <td>&nbsp;</td>
                </tr>
                </tbody>
                </table>


                <h3>Long Sleeve T-Shirts</h3>
                <table width="450" cellspacing="0">
                <thead>
                <tr>
                <th width="150" colspan="3" class="align-left">Size</th>
                <th width="50">Length</th>
                <th width="50">Chest</th>
                <th width="50">Sleeve</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                <td colspan="6" class="white align-left"><span>Toddler</span>
                Note: sizes run small</td>
                </tr>
                <tr>
                <td width="50" colspan="1" class="align-left">2T</td>
                <td width="50"class="white">&nbsp;</td>
                <td width="50">&nbsp;</td>
                <td>14</td>
                <td>12</td>
                <td>11</td>
                </tr>
                <tr>
                <td class="align-left">3T</td>
                <td class="white">&nbsp;</td>
                <td>&nbsp;</td>
                <td>15</td>
                <td>13</td>
                <td>11 3/4</td>
                </tr>
                <tr>
                <td class="align-left">4T</td>
                <td class="white">&nbsp;</td>
                <td>&nbsp;</td>
                <td>16</td>
                <td>14</td>
                <td>12 1/2</td>
                </tr>
                <tr>
                <td class="align-left">5/6</td>
                <td class="white">&nbsp;</td>
                <td>&nbsp;</td>
                <td>18 1/2</td>
                <td>15</td>
                <td>14 3/4</td>
                </tr>
                <tr>
                <td colspan="6" class="white">&nbsp;</td>
                </tr>
                <tr>
                <td colspan="6" class="white align-left"><span>Youth</span></td>
                </tr>
                <tr>
                <td class="align-left">Small</td>
                <td class="white">6-8</td>
                <td>&nbsp;</td>
                <td>21 1/2</td>
                <td>17</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td class="align-left">Medium</td>
                <td class="white">10-12</td>
                <td>&nbsp;</td>
                <td>23</td>
                <td>18</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td class="align-left">Large</td>
                <td class="white">14-16</td>
                <td>&nbsp;</td>
                <td>25</td>
                <td>19</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td class="align-left">X Large</td>
                <td class="white">18-20</td>
                <td>&nbsp;</td>
                <td>26 1/2</td>
                <td>20</td>
                <td>&nbsp;</td>
                </tr>
                </tbody>
                </table>


                <h3>Sports Jerseys</h3>
                <table width="450" cellspacing="0">
                <thead>
                <tr>
                <th width="150" colspan="3" class="align-left">Size</th>
                <th width="50">Length</th>
                <th width="50">Chest</th>
                <th width="50">Sleeve</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                <td colspan="6" class="white align-left"><span>Youth</span></td>
                </tr>
                <tr>
                <td width="50" class="align-left">Small</td>
                <td width="50" class="white">6-8</td>
                <td width="50" >&nbsp;</td>
                <td>22</td>
                <td>16</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td class="align-left">Medium</td>
                <td class="white">10-12</td>
                <td>&nbsp;</td>
                <td>24</td>
                <td>17</td>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td class="align-left">Large</td>
                <td class="white">14-16</td>
                <td>&nbsp;</td>
                <td>25</td>
                <td>18</td>
                <td>&nbsp;</td>
                </tr>
                </tbody>
                </table>
                </div>
                <div class="sizing-content-right-col">
                <div>
                <h4>Measuring Womens, Juniors & Girls Clothing</h4>
                <img src="{{media url=images/shirt-sizing-women.jpg}}" alt="" />
                <p>To start, get a measuring tape. Remember to keep it snug and
                straight while you\'re using it to get a more accurate fit.</p>
                <p>To find your size, measure around the bust line, waist, hips
                and inseam. Write down your results as you go.</p>
                <p><span>A. Bust/Chest</span> - Put the measuring tape under
                your arms to measure around the fullest part of your bust line.
                </p>
                <p class="last"><span>B. Waist</span> - Measure around your
                natural waistline. This is the narrowest part of your torso,
                usually near your belly button.</p>
                </div>
                <div>
                <h4>Measuring Mens & Boys Clothing</h4>
                <img src="{{media url=images/shirt-sizing-men.jpg}}" alt="" />
                <p>To start, get a measuring tape. Remember to keep it snug and
                straight while you\'re using it to get a more accurate fit.</p>
                <p>To find your size, measure around the bust line, waist,
                hips and inseam. Write down your results as you go.</p>
                <p><span>A. Neck</span> - Measure around the base of your neck,
                which is its widest part.</p>
                <p><span>B. Chest</span> - Put the tape under your arms and
                measure around the widest part of your chest.</p>
                <p class="last"><span>C. Waist</span> - Measure around your
                natural waistline. This is the narrowest part of your torso,
                usually near your belly button.</p>

                </div>
                </div>
                </div>
                </div>
        ',
        'is_active'     => 1,
        'stores'        => array($storeId),
        'sort_order'    => 27
    ),//end sizing and personalized
    array(
        'title' => 'Customer Service Your Order',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'customer-service/your-order',
        'content_heading' => 'Customer Service Your Order',
        'stores' => array($storeId),
        'content' => '
<div class="customer-service row">
{{block type="cms/block" block_id="customer-service-links-pp"}}
<div class="cs-wrapper col-lg-10 col-md-10 col-sm-12 col-xm-12">

    <ul id="cs-sections" class="cs">
        <li id="cs-order" class="section">
            <div class="green-title-sec">
                    <span class="green-title">Your Order</span>
                </div>
            <div id="cs-section-order" class="step a-item">
                 <h3 id="emailabout">E-mails About Your Order</h3>
                <p>You will receive e-mails about your order after it has been
                placed. Below are examples of e-mails you might receive:</p>
                <ul>
                    <li>Order Confirmation. This e-mail confirms that we have
                    received your order. It includes your order number. Keep
                    this e-mail for your records.</li>
                    <li>Shipment Confirmation. This e-mail confirms that your
                    order or part of your order has shipped. You may receive
                    multiple e-mails depending on the items you selected, or
                    if you ordered multiple items and they were shipped
                    separately. The arrival time of your order depends on the
                    shipping method selected, item selected, and your shipping
                    location.</li>
                </ul>
                <h3 id="trackingyourorder">Tracking Your Order</h3>
                <p>At our warehouse, tracking numbers are assigned to packages
                and are generally available within 24 hours.  However, it may
                take up to 48 hours or longer before the package is checked
                into the carrier\'s tracking system. Therefore, even though your
                package has already shipped from our warehouse and is on its way
                to you, the carrier may not be able to provide any information
                about your package for up to 48 hours or more.  Please allow an
                additional 2-4 business days for custom printed items to be
                created prior to shipping.</p>
                <p>When you click on &ldquo;Track Order&rdquo;, you will be
                prompted to log in with your e-mail address and order number.
                An order summary page will provide you with detailed information
                about your current order or past orders. After your order is
                shipped, your tracking number, if available, will be displayed.
                Depending on the shipping company, you can click on the tracking
                number to view the delivery status of your order. A shipping
                company may not have the ability to track a number for up to 24
                business hours.</p>
                <p>Orders placed on PersonalizePlanet.com may be delivered by one of
                several different carriers and shipping methods. Therefore,
                tracking availability may vary depending on the type of item you
                purchased, the shipping method you selected during Checkout, and
                the carrier that is delivering your item(s).</p>
                <h3 id="changingcancelorder">
                Changing or Canceling Your Order</h3>
                <p>After you have clicked "Send My Order", your order begins to
                process and you cannot cancel or change your order. Our system
                is designed to fill orders as quickly as possible. Once you
                receive your order in the mail, simply return any items you do
                not want by following our <a href="/customer-service/returns">
                Return Instructions</a>. Please note: personalized items may not
                be returned unless damaged or defective.</p>
            </div>
        </li>
    </ul></div></div>
        ',
        'is_active'     => 1,
        'stores'        => array($storeId),
        'sort_order'    => 25
    ),//end your order
);

/**
 * Insert default and system pages
 */


foreach ($cmsPages as $data) {
    Mage::getModel('cms/page')->setData($data)->save();
}