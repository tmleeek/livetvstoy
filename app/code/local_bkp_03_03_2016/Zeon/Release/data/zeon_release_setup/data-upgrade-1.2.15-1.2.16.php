<?php
//array for cms pages
$cmsPages = array(
    array(
        'title' => 'Customer Service Ordering - PBS KIDS Shop',
        'root_template' => 'one_column',
        'meta_keywords' => 'PBS kids shop, customer service, ordering',
        'meta_description' => 'Welcome to Customer Service Ordering Form for PBS KIDS Shop',
        'identifier' => 'customer-service/ordering',
        'content_heading' => '',
        'stores' => array(2),
        'content' => '
<div class="cs-wrapper">
    <ol class="cs" id="cs-sections">
                    <li id="cs-ordering" class="section">
                    <div class="step-title">
                    <h2>Ordering</h2>

                    </div>
                    <div id="cs-section-ordering" class="step a-item">
                    <!-- section content -->
                    <h3 id="shoppingcart">Shopping Cart</h3>
                    <p>When you are shopping on Shop.PBSKids.org and add an item to your cart, it is saved in your Shopping Cart. The Shopping Cart holds products you wish to purchase while you shop, the same way you use a shopping cart in a retail store. Items you place in your Shopping Cart will remain there until they are purchased or removed.</p>
                    <h4>Options in the Shopping Cart:</h4><br />
                    <ul>
                    <li><span>Review your order</span>. This ensures the accuracy of items in your Shopping Cart and the quantity selected.</li>
                    <li><span>Update the quantity</span>. To do this, simply type in the quantity you wish to purchase and click "Update Cart."</li>
                    <li><span>Remove an item from your Shopping Cart</span>. To remove an item, click on the trashcan icon or change the quantity to zero and click "Update Cart."</li>
                    <li><span>Continue shopping</span>. If you wish to continue shopping on Shop.PBSKids.org, you can use the Shopping Cart to store items you wish to purchase. Click on "Continue Shopping" to search the site for additional items. At any time during your shopping experience, you can return to your Shopping Cart by clicking on "View Cart."</li>
                    <li><span>Proceed to Secure Checkout</span>. When you are ready to purchase your item(s), click on "Secure Checkout." Our checkout process is fast, easy and secure. For more information on our secure shopping guarantee, <a href="/customer-service/privacy#privacy">click here</a>.</li>
                    </ul>
                    <h3 id="checkout">Checkout</h3>
                    <p>You are now ready to complete your purchase.</p>
                    <h4>Returning Customers:</h4>
                    <ol>
                    <li><span>Sign in</span>. First, sign in using your email address and password. If you have forgotten your password, <a href="/customer/account/forgotpassword/">click here</a>.</li>
                    <ul>
                    <li>Your email address serves as a convenient way to receive important information about your order and serves as your Shop.PBSKids.org account identification.</li>
                    <li>Your account stores information such as order history and your billing and shipping address. It also offers you the ability to track your order(s). The password assures that only you can have access to your account information.</li>
                    </ul>
                    <li><span>Select or add shipping address</span>. Next, select or add the shipping address where you would like your order sent.</li>
                    <ul>
                    <li>To select an address, click the drop down box under "Choose a Shipping Method." To add an address, click on "Add a New Shipping Address."</li>
                    </ul>
                    <li><span>Select shipping method</span>. Select a shipping method for your item(s) and click "Continue Checkout."</li>
                    <li><span>Select and enter payment options</span>. At this time, you must enter your payment method. For more information on payment, <a href="/customer-service/payment">click here</a>.</li>
                    <li><span>Order review</span>. On the Order Review page, you can review your entire order, including your shipping address, shipping method(s), and billing information, all of which can be edited at this point. You will also see your applied discounts and promotions, total charges and gift options. To complete your purchase, click "Send My Order."</li>
                    <li><span>Receipt page</span>. After you complete the checkout process, the Receipt page provides you with your order number.  You will need to save this order number for your records. You will need this information for all references to your order. <i>Please note: We cannot change or cancel an order once it has been placed. For more information about Changing or Canceling an Order, <a href="/customer-service/your-order">click here</a>.</i></li>
                    </ol>
                    <h4>New Customers:</h4>
                    <ol>
                    <li><span>Sign in</span>. First, sign in using your email address and password. If you forgot your password, <a href="/customer/account/forgotpassword/">click here</a>.</li>
                    <ul>
                    <li>Your email address serves as a convenient way to receive important information about your order and serves as your Shop.PBSKids.org account identification.</li>
                    <li>Your account stores information such as order history and your billing and shipping address. It also offers you the ability to track your order(s). The password assures that only you can have access to your account information.</li>
                    </ul>
                    <li><span>Enter shipping/billing address</span>. Enter your shipping and billing addresses in the appropriate fields. Indicate where you would like your order sent. Click "Continue Checkout" when complete.</li>
                    <ul>
                    <li>Your name and billing address must be entered exactly as they appear on your credit card statement to avoid any delay in the authorization process.</li>
                    </ul>
                    <li><span>Select shipping method</span>. Select a shipping method for your item(s) and click "Continue Checkout."</li>
                    <li><span>Select and enter payment options</span>. At this time, you must enter your payment method. For more information on payment, <a href="/customer-service/payment">click here</a>. </li>
                    <li><span>Order review</span>. On the Order Review page, you can review your entire order, including your shipping address, shipping method(s), and billing information, all of which can be edited at this point. You will also see your applied discounts and promotions, total charges and gift options. To complete your purchase, click "Send My Order."</li>
                    <li><span>Receipt page</span>. After you complete the checkout process, the Receipt page provides you with your order number.  You will need to save this order number for your records. You will need this information for all references to your order. <i>Please note: We cannot change or cancel an order once it has been placed. For more information about Changing or Canceling an Order, <a href="/customer-service/your-order">click here</a>.</i></li>
                    </ol>
                    <h3 id="couponcodes">Coupon Codes</h3>
                    <h4>Steps for redeeming a Coupon Code:</h4>
                    <ol>
                    <li>In the Shopping Cart, prior to checking out, enter the code exactly as it appears, in the box next to "If you have a special offer code, enter it now." <span>Codes are case sensitive.</span></li>
                    <li>Click "Apply Discount."</li>
                    <li>If your discount qualifies, it will be displayed in the payment summary. <span>Only one Coupon Code per order will be accepted.</span></li>
                    </ol>
                    <h3>Changing or Canceling Your Order</h3>
                    <p>After you have clicked "Send My Order", your order begins to process and <span>you cannot cancel or change your order</span>. Our system is designed to fill orders as quickly as possible. Once you receive your order in the mail, simply return any items you do not want by following our Return Instructions. Please note: personalized items may not be returned unless damaged or defective.</p>
                    </div>
                    </li></ol></div>
        ',
        'is_active'     => 1,
        'sort_order'    => 10
    ),
    array(
        'title' => 'Customer Service Payment - PBS KIDS Shop',
        'root_template' => 'one_column',
        'meta_keywords' => 'payment center, PBS KIDS Shop',
        'meta_description' => 'Welcome to the PBS KIDS Shop Payment Center.',
        'identifier' => 'customer-service/payment',
        'content_heading' => '',
        'stores' => array(2),
        'content' => '
            <div class="cs-wrapper">

    <ol class="cs" id="cs-sections">
                    <li id="cs-payment" class="section">
                    <div class="step-title">
                    <h2>Payment Information</h2>

                    </div>
                    <div id="cs-section-payment" class="step a-item">
                    <!-- section content -->
                    <div id="payment-left-col">
                    <h3 id="options">Payment Options</h3>
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
                    <li>CODâ€™s</li>
                    <li>Layaway plan</li>
                    <li>Canadian checks or money orders</li>
                    </ul>
                    </div>
                    <h4>Important information about payment:</h4>
                    <ul>
                    <li>Debit and Bank Check Cards may reflect deduction of funds immediately upon order.</li>
                    <li>For your security, your billing name and address must match that of the credit card used for payment. We reserve the right to cancel any order that does not match these criteria.</li>
                    </ul>
                    <h3 id="couponcodes">Coupon Codes</h3>
                    <h4>Steps for Redeeming a Coupon Code:</h4>
                    <p>In the Shopping Cart, prior to checking out, enter the code exactly as it appears, in the box next to "If you have a special offer code, enter it now." <span>Codes are case sensitive.</span></p>
                    <ol>
                    <li>In the shopping cart, prior to checking out, enter the code exactly as it appears in the box next to "If you have a special offer code, enter it now."</li>
                    <li>Click "Apply Discount."</li>
                    <li>If your discount qualifies, it will be displayed in the payment summary. Only one Coupon Code per order will be accepted.</li>
                    </ol>

                    <h3 id="paypal">PayPal</h3>
                    <p>PayPal is an alternate method for purchasing your order on Shop.PBSKids.org. It enables any individual or business with an email address to securely send payments online. With a PayPal account, you can choose to pay with your credit card, debit card, bank account, or PayPal account balance for any purchase you make. Your credit card and bank numbers are never seen by the seller or merchant. Plus, you\'re 100% protected against unauthorized payments sent from your account.</p>
                    <h4>PayPal Conditions</h4>
                    <ul>
                    <li>If you select PayPal as your payment option, you will continue through the standard checkout process then automatically proceed to paypal.com to complete your payment.</li>
                    <li>Once you have been redirected to paypal.com, you will have 25 minutes to complete the payment before your order is dropped.</li>
                    <li>PayPal currently cannot be used by customers with a Canadian shipping and/or billing address.</li>
                    </ul>
                    <p>For more information, visit the <a href="https://www.paypal.com/cgi-bin/helpweb?cmd=_help" target="_blank">PayPal Help Center</a>.</p>
                    <h4 id="paypalfaq">PayPal FAQ</h4>
                    <ul>
                    <li><span>How does PayPal work?</span><br />PayPal is used to securely send payments over the Internet. You can choose to pay from your PayPal account balance, a credit card, debit card, or bank account. To make a PayPal purchase, select PayPal during checkout and choose your method of payment. Your funds are transferred immediately and securely. PayPal currently cannot be used by customers with a Canadian shipping and/or billing address.</li>
                    <li><span>How do I create a PayPal account?</span><br />To get started, simply fill out the PayPal registration with your desired account type, country of residence, home address, and login information.</li>
                    <li><span>How secure is PayPal?</span><br />PayPal is highly secure and committed to protecting the privacy of its users. Its industry-leading fraud prevention team is constantly developing state-of-the-art technology to keep your money and information safe. When you use PayPal to send money, recipients never see your bank account or credit card numbers.</li>
                    <li><span>How do I contact PayPal Customer Service?</span><br />For the fastest response, you may access the user-friendly <a href="https://www.paypal.com/cgi-bin/helpweb?cmd=_help" target="_blank">Help Center</a>. Developed by the PayPal Customer Service team, this Help Center contains a comprehensive information database. Simply type a question into the search box to receive a complete answer.  If you do not find the information you need in the Help Center, PayPal Customer Service representatives are available to assist you. <a href="https://www.paypal.com/cgi-bin/helpscr?cmd=_help&t=escalateTab" target="_blank">Contact PayPal</a> for a prompt response or contact PayPal directly by phone:</li>
                    </ul>
                    <p>Customer Service: 1-402-935-2050 (a U.S. telephone number)<br />
                    4:00 AM PDT to 10:00 PM PDT Monday through Friday<br />
                    6:00 AM PDT to 8:00 PM PDT Saturday and Sunday<br />
                    <a href="http://paypal.com/" target="_blank">Sign up for PayPal now</a></p>
                    </div>
                    </li></ol></div>
        ',
        'is_active'     => 1,
        'sort_order'    => 20
    ),
    array(
        'title' => 'Customer Service Shipping - PBS KIDS Shop',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'customer-service/shipping',
        'content_heading' => '',
        'stores' => array(2),
        'content' => '
<div class="cs-wrapper">

    <ol class="cs" id="cs-sections">
                    <li id="cs-shipping" class="section">
                    <div class="step-title">
                    <h2>Shipping Information</h2>

                    </div>
                    <div id="cs-section-shipping" class="step a-item">
                    <h3 id="availability">Item Availability</h3>
                    <p>A product\'s availability is noted on the product\'s detail page as "In-Stock" or "Out of Stock." If the item is out of stock, there will be a  "Notify Me" option, which will allow you to enter your e-mail address to be notified as soon as the item is back in stock. Most items on our site are in stock and ready to ship.</p>
                    <h3>In-Stock Items</h3>
                    <p>Most orders for In-Stock items begin the order process as soon as your online purchase is completed. Your In-Stock item will be shipped once the item is located in stock, your payment is approved, and the receiving address is verified. You will not be charged for any item until it is shipped to you.</p>
                    <p>For example, if you order an In-Stock item on Monday that leaves the warehouse in 1-2 full business days, it will leave the warehouse by end-of-day Wednesday. During checkout, you will get to select a shipping method and see the total time it takes for the item to leave the warehouse and ship to its destination. Please note that business days are Monday-Friday, excluding federal holidays within the United States.</p>
                    <p>Please note expected shipment times appearing on the product\'s detail page specify when an item is expected to leave our warehouse, not when the item will arrive at its final shipping destination.</p>
                    <p>After your order leaves our warehouse, delivery times vary according to the shipping method you select during checkout and the location of your shipping address.</p>
                    <h3>Custom Printed Items</h3>
                    <p>Please allow an additional 2-4 business days for all custom printed items (with or without a name) to be created prior to shipping.  All custom printed items ship by US Mail.  International and expedited shipping is not available.</p>
                    <h3 id="methods">Shipping Methods and Costs</h3>
                    <p>Depending on the item(s) you purchase on Shop.PBSKids.org and the location to which the items will be delivered, different shipping methods will be available. Each shipping method has its own restrictions and charges that will be applied to your order.</p>
                    <p>At checkout, you will be prompted to choose a shipping method for your item(s). (Please note, some items may offer only one shipping method.) Shipping costs are dependent on the items in your order and the shipping method you select. Your total shipping charges will automatically compute during checkout prior to the completion of your order.</p>
                    <p>Generally, you will have the option of upgrading your shipping method for faster delivery (such as Second Day or Overnight service). If you choose to upgrade your shipping method, your order must be received and clear credit authorization by 12:00 p.m. (noon) EST or your order may not be processed until the following business day. Business days are Monday-Friday, excluding federal holidays within the United States.</p>
                    <h4>Domestic Shipping</h4>
                    <p>We pride ourselves on being one of the fastest shippers on the Internet. If you place your order Monday - Friday before 3 pm EST, the order will usually ship the same day. Orders placed after the 3 pm EST cutoff will usually ship the next business day. Orders placed between Friday 3 pm EST and Monday 3 pm EST will usually ship that Monday.</p>
                    <ul>
                    <li><span>Economy Ground</span>: Delivered by your local Post Office, this is our most economical shipping method. Delivery confirmation is provided and this method takes approximately 4-7 business days for delivery.  P.O. Boxes are accepted with this shipping method.</li>
                    <li><span>Standard Ground</span>: Designed for residential deliveries, orders using this method arrive within approximately 3-5 business days. This method CANNOT ship to P.O. Boxes.</li>
                    <li><span>FedEx 2nd Day Air</span>: Guaranteed 2 business day (Monday - Friday, excluding holidays) delivery to most locations in the U.S. mainland after the package leaves our warehouse. Generally all 2nd Day Air orders ship from our warehouse on the same day if order is placed by 3:00 pm EST.  This method CANNOT ship to P.O. Boxes.</li>
                    <li><span>FedEx Next Day Air</span>: Guaranteed next business day delivery to most locations in the U.S. mainland after the package leaves our warehouse. Generally all Next Day Air orders ship from our warehouse on the same day if ordered by 3:00 pm EST.  Shipping to your business address could result in more timely delivery over shipping to your residential address.   This method CANNOT ship to P.O. Boxes.</li>
                    </ul>
                    <h4>Third Party Ship</h4>
                    <p>The PBS KIDS Shop offers a large selection of items that may be sent by various shipping carriers depending on where the item is shipping from. Orders that contain items shipping from various locations will arrive in separate packages. Please allow 3 to 4 extra days for shipping these items. Expedited or express shipping may not be available on orders containing these items during checkout.</p>
                    <h4>Shipping to Canada</h4>
                    <p>We do not currently offer shipping to Canada.</p>
                    <h4>Shipping to Alaska and Hawaii</h4>
                    <ul>
                    <li>We ship all orders to Alaska and Hawaii via USPS Priority Mail. While most orders arrive in a timely manner, occasionally shipments may take 2-5 weeks.</li>
                    <li>If you haven\'t received your order in 14 business days, please email <a href="mailto:customerservice@shop.pbskids.org">customerservice@shop.pbskids.org</a>.</li>
                    </ul>
                    <h3 id="rules">Shipping Rules and Restrictions</h3>
                    <ul>
                    <li>Orders are shipped on business days only. Business days are Monday-Friday, excluding federal holidays within the United States.</li>
                    <li>Shop.PBSKids.org makes every effort to ship In-Stock items within 24 hours of order placement.</li>
                    <li>All expedited orders require a street address.</li>
                    <li>Please call a Customer Service Specialist at 1-888-957-9696 if you have any questions.</li>
                    </ul>
                    <h4>Oversized Items</h4>
                    <p>Oversized items include train tables, play boards, furniture items, large train sets, and other items denoted on the site. Each item will be charged the applicable oversized shipping charges. Oversized items are not eligible for free shipping promotions and are not available to be shipped internationally or using expedited shipping methods.</p>
                    <h3 id="tracking">Tracking Your Order</h3>
                    <p>At our warehouse, tracking numbers are assigned to packages and are generally available within 24 hours.  However, it may take up to 48 hours or longer before the package is checked into the carrier\'s tracking system. Therefore, even though your package has already shipped from our warehouse and is on its way to you, the carrier may not be able to provide any information about your package for up to 48 hours or more.  Please allow an additional 2-4 business days for custom printed items to be created prior to shipping.</p>
                    <p>When you click on "Track Order," you will be prompted to login with your e-mail address and order number. An order summary page will provide you with detailed information about your current order or past orders. After your order is shipped, your tracking number, if available, will be displayed. Depending on the shipping company, you can click on the tracking number to view the delivery status of your order. A shipping company may not have the ability to track a number for up to 24 business hours.</p>
                    <p>Orders placed on Shop.PBSKids.org may be delivered by one of several different carriers and shipping methods. Therefore, tracking availability may vary depending on the type of item you purchased, the shipping method you selected during Checkout, and the carrier that is delivering your item(s).</p>
                    </div>
                    </div>
    ',
        'is_active'     => 1,
        'sort_order'    => 30
    ),
    array(
        'title' => 'Customer Service Your Order - PBS KIDS Shop',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'customer-service/your-order',
        'content_heading' => '',
        'stores' => array(2),
        'content' => '
<div class="cs-wrapper">
    <ol class="cs" id="cs-sections">
                    <li id="cs-order" class="section">
                    <div class="step-title">
                    <h2>Your Order</h2>

                    </div>
                    <div id="cs-section-order" class="step a-item">
                    <!-- section content -->
                    <h3 id="emails">E-mails About Your Order</h3>
                    <p>You will receive e-mails about your order after it has been placed. Below are examples of e-mails you might receive:</p>
                    <ul>
                    <li><span>Order Confirmation</span>: This e-mail confirms that we have received your order. It includes your order number. Keep this e-mail for your records.</li>
                    <li><span>Shipment Confirmation</span>: This e-mail confirms that your order or part of your order has shipped. You may receive multiple e-mails depending on the items you selected, or if you ordered multiple items and they were shipped separately. The arrival time of your order depends on the shipping method selected, item selected, and your shipping location.</li>
                    </ul>
                    <h3 id="tracking">Tracking Your Order</h3>
                    <p>After your order is shipped, your tracking number, if available, will be displayed. Depending on the shipping company, you can click on the tracking number to view the delivery status of your order. A shipping company may not have the ability to track a number for up to 24 business hours.</p>
                    <p>When you click on "Track Order," you will be prompted to log in with your e-mail address and order number. An order summary page will provide you with detailed information about your current order or past orders. After your order is shipped, your tracking number, if available, will be displayed. Depending on the shipping company, you can click on the tracking number to view the delivery status of your order. A shipping company may not have the ability to track a number for up to 24 business hours.</p>
                    <p>Orders placed on Shop.PBSKids.org may be delivered by one of several different carriers and shipping methods. Therefore, tracking availability may vary depending on the type of item you purchased, the shipping method you selected during Checkout, and the carrier that is delivering your item(s).</p>
                    <h3 id="updating">Changing or Canceling Your Order</h3>
                    <p>After you have clicked "Send My Order," your order begins to process and you cannot cancel or change your order. Our system is designed to fill orders as quickly as possible. Once you receive your order in the mail, simply return any items you do not want by following our <a href="/customer-service/returns">Return Instructions</a>. Please note: personalized items may not be returned unless damaged or defective.</p>
                    </div>
                    </li></ol></div>
    ',
        'is_active'     => 1,
        'sort_order'    => 40
    ),
    array(
        'title' => 'Customer Service Returns - PBS KIDS Shop',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'customer-service/returns',
        'content_heading' => '',
        'stores' => array(2),
        'content' => '
<div class="cs-wrapper">
     <ol class="cs" id="cs-sections">
                    <li id="cs-returns" class="section">
                    <div class="step-title">
                    <h2>Returns</h2>

                    </div>
                    <div id="cs-section-returns" class="step a-item">
                    <!-- section content -->
                    <h3>Money Back Guarantee</h3>
                    <p>We want you to be completely satisfied with your purchase from Shop.PBSKids.org. If you\'re not 100% happy with a qualifying item, simply return it within 30 days in the original packaging for a full refund or credit of the product purchase price.</p>
                    <ul>
                    <li><span>Personalized items may not be returned unless damaged or defective.</span> Unfortunately, due to the nature of our customization on demand business, personalized items cannot be returned because they cannot be resold. Therefore, we are unable to refund or credit personalized clothing that was ordered in an incorrect size. Please be sure to use the \'<a href="{{config path="web/unsecure/base_url"}}sizing/personalized">sizing chart</a>\' for all clothing orders.</li>
                    <li><span>Items should be returned in unopened or original condition, with a copy of the packing slip, to our Return Center.</span> A refund of the price of the item and any applicable taxes will be issued using the original form of payment used. We will not issue refunds for items returned that have been used, or are classified as un-sellable.</li>
                    </ul>
                    <p>Please include a copy of your packing slip with the items you are returning as well as your name, address, and order number. Returns should be sent to:</p>
                    <address>PBS KIDS Shop<br />Returns Department<br />755 Remington Blvd. Suite B<br />Bolingbrook, IL  60440</address><br /><br />
                    <p>All Canadian returns must be addressed to the following to be prepared for entry into the United States and should include all of the information requested above:</p><br />
                    <address>PBS KIDS Shop Returns<br />c/o DHL Global Mail, Returns Dept.<br />355 Admiral Blvd, Unit 4<br />Mississauga, ON<br />L5T 2N1</address>
                    <h4>Customer Inquiries should be directed to the following:</h4>
                    <ul>
                    <li>Customer Service E-mail: <a href="mailto:customerservice@shop.pbskids.org">customerservice@shop.pbskids.org</a>.</li>
                    <li>For additional information on the manufacturerâ€™s warranty for a specific product, please contact the manufacturer directly.</li>
                    </ul>
                    </div>
                    </li></ol></div>
    ',
        'is_active'     => 1,
        'sort_order'    => 50
    ),
    array(
        'title' => 'Customer Service Privacy - PBS KIDS Shop',
        'root_template' => 'one_column',
        'meta_keywords' => 'privacy policy, customer service, PBS KIDS Shop',
        'meta_description' => 'Welcome to the PBS KIDS Shop Customer Service Privacy Policy.',
        'identifier' => 'customer-service/privacy',
        'content_heading' => '',
        'stores' => array(2),
        'content' => '
<div class="cs-wrapper">
     <ol class="cs" id="cs-sections">
                    <li id="cs-safety" class="section">
                    <div class="step-title">
                    <h2>Safety, Security &amp; Privacy</h2>
                    </div>
                    <div id="cs-section-safety" class="step a-item">
                    <!-- section content -->
                    <p id="terms open">PLEASE READ THESE TERMS AND CONDITIONS CAREFULLY BEFORE USING THIS WEB SITE. YOUR USE OF THIS WEB SITE CONFIRMS YOUR UNCONDITIONAL ACCEPTANCE OF THE FOLLOWING TERMS AND CONDITIONS. IF YOU DO NOT ACCEPT THESE TERMS AND CONDITIONS, DO NOT USE THIS WEB SITE.</p>
                    <ol>
                    <li><span>Products, Content and Specifications</span>. All features, content, specifications, products and prices of products and services described or depicted on this Web Site are subject to change at any time without notice. Certain weights, measures and similar descriptions are approximate and are provided for convenience purposes only. We make all reasonable efforts to accurately display the attributes of our products, including the applicable colors; however, the actual color you see will depend on your computer system and we cannot guarantee that your computer will accurately display such colors. The inclusion of any products or services in this Web Site at a particular time does not imply or warrant that these products or services will be available at any time. It is your responsibility to ascertain and obey all applicable local, state, federal and international laws (including minimum age requirements) in regard to the possession, use and sale of any item purchased from this Web Site. By placing an order, you represent that the products ordered will be used only in a lawful manner. All videocassettes, DVDs and similar products sold are for private, home use (where no admission fee is charged), non-public performance and may not be duplicated.</li>
                    <li><span>Shipping Limitations</span>. When an order is placed, it will be shipped to an address designated by the purchaser as long as that shipping address is compliant with the shipping restrictions contained on this Web Site. All purchases from this Web Site are made pursuant to a shipment contract. As a result, risk of loss and title for items purchased from this Web Site pass to you upon delivery of the items to the carrier. You are responsible for filing any claims with carriers for damaged and/or lost shipments.</li>
                    <li><span>Accuracy of Information</span>. PBS KIDS Shop attempts to ensure that information on this Web Site is complete, accurate and current. Despite our efforts, the information on this Web Site may occasionally be inaccurate, incomplete or out of date. We make no representation as to the completeness, accuracy or currentness of any information on this Web Site. For example, products included on the Web Site may be unavailable, may have different attributes than those listed, or may actually carry a different price than that stated on the Web Site. In addition, we may make changes in information about price and availability without notice. While it is our practice to confirm orders by email, the receipt of an email order confirmation does not constitute our acceptance of an order or our confirmation of an offer to sell a product or service. We reserve the right, without prior notice, to limit the order quantity on any product or service and/or to refuse service to any customer. We also may require verification of information prior to the acceptance and/or shipment of any order.</li>
                    <li><span>Use of this Web Site</span>. The Web Site design and all text, graphics, information, content, and other material displayed on or that can be downloaded from this Web Site are either the property of, or used with permission by PBS KIDS Shop and are protected by copyright, trademark and other laws and may not be used except as permitted in these Terms and Conditions or with the prior written permission of the owner of such material. You may not modify the information or materials located on this Web Site in any way or reproduce or publicly display, perform, or distribute or otherwise use any such materials for any public or commercial purpose. Any unauthorized use of any such information or materials may violate copyright laws, trademark laws, laws of privacy and publicity, and other laws and regulations.</li>
                    <li><span>Trademarks</span>. Certain trademarks, trade names, service marks and logos used or displayed on this Web Site are registered and unregistered trademarks, trade names and service marks of PBS KIDS Shop and its affiliates. Other trademarks, trade names and service marks used or displayed on this Web Site are the registered and unregistered trademarks, trade names and service marks of their respective owners, including PBS and its affiliates. Nothing contained on this Web Site grants or should be construed as granting, by implication, estoppel, or otherwise, any license or right to use any trademarks, trade names, service marks or logos displayed on this Web Site without the written permission of PBS KIDS Shop or such third party owner.</li>
                    <li><span>Linking to this Web Site</span>. Creating or maintaining any link from another Web site to any page on this Web Site without PBS KIDS Shop\'s prior written permission is prohibited. Running or displaying this Web Site or any material displayed on this Web Site in frames or through similar means on another Web site without PBS KIDS Shop\'s prior written permission is prohibited. Any permitted links to this Web Site must comply with all applicable laws, rules and regulations.</li>
                    <li><span>Third Party Links</span>. From time to time, this Web Site may contain links to Web sites that are not owned, operated or controlled by PBS KIDS Shop or their affiliates. All such links are provided solely as a convenience to you. If you use these links, you will leave this Web Site. Neither PBS KIDS Shop, nor any of their affiliates are responsible for any content, materials or other information located on or accessible from any other Web site. Neither PBS KIDS Shop, nor any of their affiliates endorse, guarantee, or make any representations or warranties regarding any other Web site, or any content, materials or other information located or accessible from such Web sites, or the results that you may obtain from using such Web sites. If you decide to access any other Web site linked to or from this Web Site, you do so entirely at your own risk.</li>
                    <li><span>Inappropriate Material</span>. You are prohibited from posting or transmitting any unlawful, threatening, defamatory, libelous, obscene, pornographic or profane material or any material that could constitute or encourage conduct that would be considered a criminal offense or give rise to civil liability, or otherwise violate any law. In addition to any remedies that PBS KIDS Shop may have at law or in equity, if PBS KIDS Shop reasonably determines that you have violated or are likely to violate the foregoing prohibitions, PBS KIDS Shop may take any action they reasonably deem necessary to cure or prevent the violation, including without limitation, the immediate removal from this Web Site of the related materials. PBS KIDS Shop will fully cooperate with any law enforcement authorities or court order or subpoena requesting or directing PBS KIDS Shop to disclose the identity of anyone posting such materials.</li>
                    <li><span>User Information</span>. Other than personally identifiable information, which is subject to this Web Site\'s Privacy Policy, any material, information, suggestions, ideas, concepts, know-how, techniques, questions, comments or other communication you transmit or post to this Web Site in any manner ("User Communications") is and will be considered non-confidential and non-proprietary. PBS KIDS Shop, each of its affiliates and/or their designees may use any or all User Communications for any purpose whatsoever, including, without limitation, reproduction, transmission, disclosure, publication, broadcast, development, manufacturing and/or marketing in any manner whatsoever for any or all commercial or non-commercial purposes. PBS KIDS Shop may, but is not obligated to, monitor or review any User Communications. PBS KIDS Shop shall have no obligations to use, return, review, or respond to any User Communications. PBS KIDS Shop will have no liability related to the content of any such User Communications, whether or not arising under the laws of copyright, libel, privacy, obscenity, or otherwise. PBS KIDS Shop retains the right to remove any or all User Communications that includes any material PBS KIDS Shop deems inappropriate or unacceptable.</li>
                    <li><span>DISCLAIMERS</span>. YOUR USE OF THIS SITE IS AT YOUR RISK. THE MATERIALS AND SERVICES PROVIDED IN CONNECTION WITH THIS WEB SITE ARE PROVIDED "AS IS" WITHOUT ANY WARRANTIES OF ANY KIND INCLUDING WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, OR NON-INFRINGEMENT OF INTELLECTUAL PROPERTY. NEITHER PBS KIDS SHOP NOR ANY OF THEIR AFFILIATES WARRANT THE ACCURACY OR COMPLETENESS OF THE MATERIALS OR SERVICES ON OR THROUGH THIS WEB SITE. THE MATERIALS AND SERVICES ON OR THROUGH THIS WEB SITE MAY BE OUT OF DATE, AND NEITHER PBS KIDS Shop NOR ANY OF THEIR AFFILIATES MAKE ANY COMMITMENT OR ASSUMES ANY DUTY TO UPDATE SUCH MATERIALS OR SERVICES. THE FOREGOING EXCLUSIONS OF IMPLIED WARRANTIES DO NOT APPLY TO THE EXTENT PROHIBITED BY LAW. PLEASE REFER TO YOUR LOCAL LAWS FOR ANY SUCH PROHIBITIONS.<br /><br />ALL PRODUCTS AND SERVICES PURCHASED ON OR THROUGH THIS WEB SITE ARE SUBJECT ONLY TO ANY APPLICABLE WARRANTIES OF THEIR RESPECTIVE MANUFACTURES, DISTRIBUTORS AND SUPPLIERS, IF ANY. TO THE FULLEST EXTENT PERMISSIBLE BY APPLICABLE LAW, PBS KIDS Shop HEREBY DISCLAIM ALL WARRANTIES OF ANY KIND, EITHER EXPRESS OR IMPLIED, INCLUDING, ANY IMPLIED WARRANTIES WITH RESPECT TO THE PRODUCTS AND SERVICES LISTED OR PURCHASED ON OR THROUGH THIS WEB SITE. WITHOUT LIMITING THE GENERALITY OF THE FOREGOING, PBS KIDS SHOP HEREBY EXPRESSLY DISCLAIM ALL LIABILITY FOR PRODUCT DEFECT OR FAILURE, CLAIMS THAT ARE DUE TO NORMAL WEAR, PRODUCT MISUSE, ABUSE, PRODUCT MODIFICATION, IMPROPER PRODUCT SELECTION, NON-COMPLIANCE WITH ANY CODES, OR MISAPPROPRIATION. WE MAKE NO WARRANTIES TO THOSE DEFINED AS "CONSUMERS" IN THE MAGNUSON-MOSS WARRANTY-FEDERAL TRADE COMMISSION IMPROVEMENTS ACT. THE FOREGOING EXCLUSIONS OF IMPLIED WARRANTIES DO NOT APPLY TO THE EXTENT PROHIBITED BY LAW. PLEASE REFER TO YOUR LOCAL LAWS FOR ANY SUCH PROHIBITIONS.</li>
                    <li><span>LIMITATIONS OF LIABILITY</span>. PBS KIDS Shop does not assume any responsibility, and shall not be liable for, any damages to, or viruses that may infect, your computer, telecommunication equipment, or other property caused by or arising from your access to, use of, or browsing this Web Site or your downloading of any materials, from this Web Site. IN NO EVENT WILL PBS KIDS Shop, THEIR RESPECTIVE OFFICERS, DIRECTORS, EMPLOYEES, SHAREHOLDERS, AFFILIATES, AGENTS, SUCCESSORS, ASSIGNS, RETAIL PARTNERS NOR ANY PARTY INVOLVED IN THE CREATION, PRODUCTION OR TRANSMISSION OF THIS WEB SITE BE LIABLE TO ANY PARTY FOR ANY INDIRECT, SPECIAL, PUNITIVE, INCIDENTAL OR CONSEQUENTIAL DAMAGES (INCLUDING, WITHOUT LIMITATION, THOSE RESULTING FROM LOST PROFITS, LOST DATA OR BUSINESS INTERRUPTION) ARISING OUT OF THE USE, INABILITY TO USE, OR THE RESULTS OF USE OF THIS WEB SITE, ANY WEB SITES LINKED TO THIS WEB SITE, OR THE MATERIALS, INFORMATION OR SERVICES CONTAINED AT ANY OR ALL SUCH SITES, WHETHER BASED ON WARRANTY, CONTRACT, TORT OR ANY OTHER LEGAL THEORY AND WHETHER OR NOT ADVISED OF THE POSSIBILITY OF SUCH DAMAGES. THE FOREGOING LIMITATIONS OF LIABILITY DO NOT APPLY TO THE EXTENT PROHIBITED BY LAW. PLEASE REFER TO YOUR LOCAL LAWS FOR ANY SUCH PROHIBITIONS.<br /><br />IN THE EVENT OF ANY PROBLEM WITH THIS WEB SITE OR ANY CONTENT, YOU AGREE THAT YOUR SOLE REMEDY IS TO CEASE USING THIS WEB SITE. IN THE EVENT OF ANY PROBLEM WITH THE PRODUCTS OR SERVICES THAT YOU HAVE PURCHASED ON OR THROUGH THIS WEB SITE, YOU AGREE THAT YOUR REMEDY, IF ANY, IS FROM THE MANUFACTURER OF SUCH PRODUCTS OR SUPPLIER OF SUCH SERVICES, IN ACCORDANCE WITH SUCH MANUFACTURER\'S OR SUPPLIER\'S WARRANTY, OR TO SEEK A RETURN AND REFUND FOR SUCH PRODUCT OR SERVICES IN ACCORDANCE WITH THE RETURNS AND REFUNDS POLICIES POSTED ON THIS WEB SITE.</li>
                    <li><span>Revisions to these Terms and Conditions</span>. PBS KIDS Shop may revise these Terms and Conditions at any time by updating this posting. You should visit this page from time to time to review the then current Terms and Conditions because they are binding on you. Certain provisions of these Terms and Conditions may be superseded by expressly designated legal notices or terms located on particular pages at this Web Site.</li>
                    <li><span>Choice of Law; Jurisdiction</span>. These Terms and Conditions supersede any other agreement between you and PBS KIDS Shop to the extent necessary to resolve any inconsistency or ambiguity between them. This Web Site is administered by PBS KIDS Shop from its offices in Illinois. These Terms and Conditions will be governed by and construed in accordance with the laws of the State of Illinois, without giving effect to any principles of conflicts of laws. Any action seeking legal or equitable relief arising out of or relating to this Web Site shall be brought only in the state or federal courts located in the State of Illinois. A printed version of these Terms and Conditions shall be admissible in judicial and administrative proceedings based upon or relating to these Terms and Conditions to the same extent and subject to the same conditions as other business documents and records originally generated and maintained in printed form.</li>
                    <li><span>Termination</span>. You or we may suspend or terminate your account or your use of this Web Site at any time, for any reason or for no reason. You are personally liable for any orders that you place or charges that you incur prior to termination. We reserve the right to change, suspend, or discontinue all or any aspect of this Web Site at any time without notice.</li>
                    <li><span>Additional Assistance</span>. If you do not understand any of the foregoing Terms and Conditions or if you have any questions or comments, we invite you to call our customer service department at <a href="mailto:customerservice@shop.pbskids.org">customerservice@shop.pbskids.org</a>.</li>
                    </ol>
                    <h3 id="privacy">Privacy Policy</h3>
                    <p>We are committed to making your online transaction safe, secure and confidential to the highest standards available. Following are some relevant facts about how we treat your information</p>
                    <p>We do not share or sell data. We collect billing and shipping information in order to process your order and deliver it to its destination. This information is used only by PBS KIDS Shop and the couriers we use for the purposes of fulfilling your order. Personal information collected through this site is used only for our internal purposes. We will not sell your personal information, or authorize third parties to use such information for any commercial purpose, without your prior consent.</p>
                    <p><span>Personal Information.</span> We do not automatically collect personal information such as your name, address, telephone number or email address. Such information will be collected only if you provide it to us voluntarily in order to order products, enter a contest or promotion, or sign up to receive our eUpdate newsletter. We don\'t require that any personal information be provided in order to view our website.</p>
                    <p><span>Information collected by Shop.PBSKids.org is used to:</span></p>
                    <ul>
                    <li>allow you to order products, register for eUpdate and participate in special promotions;</li>
                    <li>improve the site by observing products that are most popular; and</li>
                    <li>personalize the delivery of information and recommend products to you.</li>
                    </ul>
                    <p>Your personal information may also be used to create statistics about Shop.PBSKids.org that we use to measure our performance. These statistics do not contain any information that could identify you personally. Shop.PBSKids.org does not sell your personal information to any organization for any purpose.</p>
                    <p><span>We Do Send E-mail</span>. We will send you email at regular intervals to ensure that you are always kept up to date on the status of your order. From time to time we send additional eflyers out to inform our customers of specials or promotions that they might be interested in. You can choose not to receive the eflyers by selecting this option in your PBS KIDS Shop account.</p>
                    <p><span>We Offer Optional Registration</span>. We recommend that you register as a customer in order to take advantage of the many benefits of maintaining an account. Unlike many other sites, registration is not necessary for placing orders. We offer a "buy now" option, which only requires the basic information required to process your order. Please note that this will limit access to your order history and an address book.</p>
                    <p><span>Credit Card Info Protected by 128bit SSL Encryption</span>. The security of your personal information is paramount. All Credit Card Transactions are completed using a 128 Bit SSL Encrypted Secure Transaction. As we transmit the information to the Bank\'s Secure SSL Server, they require a 128-bit transaction and will not process a transaction without one. Even though 40 or 56 Bit transactions are very secure, our Bank\'s insistence on 128 Bit SSL means that there is never any chance of your information every being intercepted or decoded.</p>
                    <p><span>Password Protected Accounts</span>. When you decide to register an account in order to take advantage of the address book, quick pay and order restoration features, your account information is secured by password protection assigned and maintained by you. If you forget your password, this information is only released to your specific email address recorded in your account profile. It is not given out in any other situation without identity verification provided by you. The account information contains the billing and shipping addresses, phone numbers and email address provided and maintained by you as well as your order history. Credit card information is not included in this file.</p>
                    <p><span>We Use Cookies</span>. Like most advanced sites these days, Shop.PBSKids.org uses cookies to make your shopping experience the best it can be. No personal or credit card information is stored in these cookies and there is no risk to your privacy or security caused by this action. It is simply an identifier transferred to your system from ours that helps us manage your transaction. It remembers items in your basket and currency selections. You can choose not to use cookies if you like. We do not require cookies to process your order however the lack of the memory feature may cause some frustration in certain situations.</p>
                    <p><span>Getting Your Consent.</span> By using Shop.PBSKids.org you are consenting to the collection and use of personal and account information by PBS KIDS Shop, for the purposes set out in this policy.
                    <p><span>Changes to this Privacy Policy.</span> All changes will be posted on Shop.PBSKids.org promptly to inform visitors of what information is collected, how it is used, and under what circumstances it may be disclosed.</p>
                    </div>
                    </li>
                    </ol>
                    </div>
    ',
        'is_active'     => 1,
        'sort_order'    => 60
    ),
    array(
        'title' => 'About PBS KIDS Shop',
        'root_template' => 'one_column',
        'meta_keywords' => 'pbs kids shop, barney, sesame street, favorite tv characters, pbs.org',
        'meta_description' => 'Welcome to the PBS KIDS Shop\'s About Us page.',
        'identifier' => 'about',
        'content_heading' => '',
        'stores' => array(2),
        'content' => '
<h3>About PBS KIDS Shop</h3>
<div class="about-section first">
     <h4>Bring Home the Fun</h4>
    <p>Parents, family members and friends can discover a single destination to shop for items featuring all their favorite PBS KIDS characters. From books and DVDs to toys and party supplies, playtime has never been more educational and adventurous.</p>
</div>
<div class="about-section">
     <h4>A Household Favorite</h4>
    <p>As the #1 trusted educational brand on television, PBS KIDS brings you programs that nurture the minds and spirits of children, while encouraging them to have fun exploring the world around them.</p>
</div>
<div class="about-section">
     <h4>You Make a Difference</h4>
    <p>With every purchase on the PBS KIDS Shop, a portion will go to support educational programing that continues to shape the minds of children.</p>
</div>
    ',
        'is_active'     => 1,
        'sort_order'    => 70
    ),
    array(
        'title' => 'Personalized Apparel Sizing Information - PBS KIDS Shop',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'sizing/personalized',
        'content_heading' => 'Personalized Apparel Sizing Information',
        'stores' => array(2),
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
                <td colspan="6" class="white align-left"><span>Toddler</span> Note: sizes run small</td>
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
                <td colspan="6" class="white align-left"><span>Toddler</span> Note: sizes run small</td>
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
                <td colspan="6" class="white align-left"><span>Toddler</span> Note: sizes run small</td>
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
                <td colspan="6" class="white align-left"><span>Toddler</span> Note: sizes run small</td>
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
                <img src="{{media url="shirt-sizing-women.jpg"}}" alt="" />
                <p>To start, get a measuring tape. Remember to keep it snug and straight while you\'re using it to get a more accurate fit.</p>
                <p>To find your size, measure around the bust line, waist, hips and inseam. Write down your results as you go.</p>
                <p><span>A. Bust/Chest</span> - Put the measuring tape under your arms to measure around the fullest part of your bust line.</p>
                <p class="last"><span>B. Waist</span> - Measure around your natural waistline. This is the narrowest part of your torso, usually near your belly button.</p>
                </div>
                <div>
                <h4>Measuring Mens & Boys Clothing</h4>
                <img src="{{media url="shirt-sizing-men.jpg"}}" alt="" />
                <p>To start, get a measuring tape. Remember to keep it snug and straight while you\'re using it to get a more accurate fit.</p>
                <p>To find your size, measure around the bust line, waist, hips and inseam. Write down your results as you go.</p>
                <p><span>A. Neck</span> - Measure around the base of your neck, which is its widest part.</p>
                <p><span>B. Chest</span> - Put the tape under your arms and measure around the widest part of your chest.</p>
                <p class="last"><span>C. Waist</span> - Measure around your natural waistline. This is the narrowest part of your torso, usually near your belly button.</p>

                </div>
                </div>
                </div>
                </div>
    ',
        'is_active'     => 1,
        'sort_order'    => 80
    ),
    array(
        'title' => 'Support out Mission - PBS KIDS Shop',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'about/support-our-mission',
        'content_heading' => '',
        'stores' => array(2),
        'content' => '
<img src="{{media url="pbs-support-banner.png"}}" alt="" />
     <div class="landing-wrapper">
     <div class="supporting-content">
     <h3>When you shop at PBS KIDS you make a difference. That\'s because with each purchase, you\'re helping to support public television and the educational programs your kids and grandkids enjoy so much. </h3>
            <p>If you\'d like to make an even bigger difference, PBS KIDS Shop offers you another opportunity to help the growth of public television. At checkout, you\'ll be able to round up the amount of your purchase to the nearest dollar. Your extra donation will go towards funding the future development of PBS educational programming and services. It\'s a great example of how just a little bit can go a long way.</p>
            <h4>Learning and Exploring Across the Nation </h4>
            <p>As America\'s largest classroom, PBS is available to all of America\'s children including those who can\'t attend preschool and offers educational media that help prepare children for success in school. PBS is the <a href="http://www.pbsteachers.org/" target="_blank">No. 1 source of media content for preschool teachers</a> and the <a href="http://www.pbskids.org/video/" target="_blank">No. 1 place parents turn to for preschool video online</a>, with <a href="http://www.pbskids.org/read/" target="_blank">content proven to improve critical literacy skills in young children</a>.</p>
            <p id="learnMore"><a href="http://www.pbs.org/about/support-our-mission/" target="_blank">Learn more about how you can support public television</a> <img src="{{media url="PBS-Customer-Service-arrow.png"}}" alt="" /></p>

            <!-- FACEBOOK BANNER -->

            <div style="margin:50px 0 80px; clear:both;padding:0;position:relative;width:627px;height:91px;background-image:url({{media url="angelina-facebook-v4.png"}});">
            <a href="http://www.facebook.com/PBSKIDS" style="display:block; position:absolute; top:0px; left:0px; width:420px; height:132px; border:none; text-indent:-9999px;">
            Like toys? Like us! Check us out on facebook!
            </a>

            <div style="top:14px; left:374px; position:absolute;">
            <iframe src="http://www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2FPBSKIDS&amp;width=260&amp;colorscheme=light&amp;show_faces=false&amp;stream=false&amp;header=false&amp;height=62" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:260px; height:62px;" allowTransparency="true"></iframe>
            </div>
            </div>

            <!-- END FACEBOOK BANNER -->

            </div>
            <div class="supporting-right-col">
            <a href="http://www.pbs.org/about/support-our-mission/"><img src="{{media url="PBS-supporting-col-banner.png"}}" alt="Support PBS" /></a>
            </div>
            </div>
    ',
        'is_active'     => 1,
        'sort_order'    => 90
    ),
    array(
        'title' => 'PBS Browser Upgrade Message',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'browser-upgrade',
        'content_heading' => '',
        'stores' => array(2),
        'content' => '
<div class="upgrade-wrapper">
     <img src="{{media url="/PBS-shop-logo-beta.jpg"}}" alt="" />
     <div class="content">
     <h2>Did you know that your Internet Explorer is out of date?</h2>
            <p>To get the best possible experience using the PBS Kids Shop website we recommend that you upgrade to a newer version or other web browser. A list of the most popular web browsers can be found below.</p>
            <p>Just click on the icons to get to the download page</p>
            <ul>
            <li><div class="icon"><a href="http://www.microsoft.com/windows/Internet-explorer/default.aspx"><img src="{{media url="/browser_ie.gif"}}" alt="" /></a></div><div class="title">Interent Explorer</div></li>
            <li><div class="icon"><a href="http://www.mozilla.com/firefox/"><img src="{{media url="/browser_firefox.gif"}}" alt="" /></div></a><div class="title">Firefox</div></li>
            <li><div class="icon"><a href="http://www.apple.com/safari/download/"><img src="{{media url="/browser_safari.gif"}}" alt="" /></div></a><div class="title">Safari</div></li>
            <li><div class="icon"><a href="http://www.opera.com/download/"><img src="{{media url="/browser_opera.gif"}}" alt="" /></div></a><div class="title">Opera</div></li>
            <li><div class="icon"><a href="http://www.google.com/chrome"><img src="{{media url="/browser_chrome.gif"}}" alt="" /></div></a><div class="title">Chrome</div></li>
            </ul>
            </div></div>
    ',
        'is_active'     => 1,
        'sort_order'    => 100
    ),
    array(
        'title' => 'Rabbit Skins Sizing Information - PBS KIDS Shop',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'sizing/rabbit-skins',
        'content_heading' => 'Rabbit Skins Sizing Information',
        'stores' => array(2),
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
                <td colspan="6" class="white align-left"><span>Toddler</span> Note: sizes run small</td>
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
                <td colspan="6" class="white align-left"><span>Toddler</span> Note: sizes run small</td>
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
                <td colspan="6" class="white align-left"><span>Toddler</span> Note: sizes run small</td>
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
                <td colspan="6" class="white align-left"><span>Toddler</span> Note: sizes run small</td>
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
                </tbody>
                </table>
                </div>
                <div class="sizing-content-right-col">
                <div>
                <h4>Measuring Womens, Juniors & Girls Clothing</h4>
                <img src="{{media url="shirt-sizing-women.jpg"}}" alt="" />
                <p>To start, get a measuring tape. Remember to keep it snug and straight while you\'re using it to get a more accurate fit.</p>
                <p>To find your size, measure around the bust line, waist, hips and inseam. Write down your results as you go.</p>
                <p><span>A. Bust/Chest</span> - Put the measuring tape under your arms to measure around the fullest part of your bust line.</p>
                <p class="last"><span>B. Waist</span> - Measure around your natural waistline. This is the narrowest part of your torso, usually near your belly button.</p>
                </div>
                <div>
                <h4>Measuring Mens & Boys Clothing</h4>
                <img src="{{media url="shirt-sizing-men.jpg"}}" alt="" />
                <p>To start, get a measuring tape. Remember to keep it snug and straight while you\'re using it to get a more accurate fit.</p>
                <p>To find your size, measure around the bust line, waist, hips and inseam. Write down your results as you go.</p>
                <p><span>A. Neck</span> - Measure around the base of your neck, which is its widest part.</p>
                <p><span>B. Chest</span> - Put the tape under your arms and measure around the widest part of your chest.</p>
                <p class="last"><span>C. Waist</span> - Measure around your natural waistline. This is the narrowest part of your torso, usually near your belly button.</p>

                </div>
                </div>
                </div>
                </div>
    ',
        'is_active'     => 1,
        'sort_order'    => 110
    ),
    array(
        'title' => 'Gildan Sizing Information - PBS KIDS Shop',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'sizing/gildan',
        'content_heading' => 'Gildan Sizing Informaion',
        'stores' => array(2),
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
                <td colspan="6" class="white align-left"><span>Youth</span></td>
                </tr>
                <tr>
                <td width="50" class="align-left">Small</td>
                <td width="50" class="white">6-8</td>
                <td width="50" >&nbsp;</td>
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
                <td colspan="6" class="white align-left"><span>Youth</span></td>
                </tr>
                <tr>
                <td width="50" class="align-left">Small</td>
                <td width="50" class="white">6-8</td>
                <td width="50" >&nbsp;</td>
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
                <td colspan="6" class="white align-left"><span>Youth</span></td>
                </tr>
                <tr>
                <td width="50" class="align-left">Small</td>
                <td width="50" class="white">6-8</td>
                <td width="50" >&nbsp;</td>
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
                <td colspan="6" class="white align-left"><span>Youth</span></td>
                </tr>
                <tr>
                <td width="50" class="align-left">Small</td>
                <td width="50" class="white">6-8</td>
                <td width="50" >&nbsp;</td>
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
                </div>
                <div class="sizing-content-right-col">
                <div>
                <h4>Measuring Womens, Juniors & Girls Clothing</h4>
                <img src="{{media url="shirt-sizing-women.jpg"}}" alt="" />
                <p>To start, get a measuring tape. Remember to keep it snug and straight while you\'re using it to get a more accurate fit.</p>
                <p>To find your size, measure around the bust line, waist, hips and inseam. Write down your results as you go.</p>
                <p><span>A. Bust/Chest</span> - Put the measuring tape under your arms to measure around the fullest part of your bust line.</p>
                <p class="last"><span>B. Waist</span> - Measure around your natural waistline. This is the narrowest part of your torso, usually near your belly button.</p>
                </div>
                <div>
                <h4>Measuring Mens & Boys Clothing</h4>
                <img src="{{media url="shirt-sizing-men.jpg"}}" alt="" />
                <p>To start, get a measuring tape. Remember to keep it snug and straight while you\'re using it to get a more accurate fit.</p>
                <p>To find your size, measure around the bust line, waist, hips and inseam. Write down your results as you go.</p>
                <p><span>A. Neck</span> - Measure around the base of your neck, which is its widest part.</p>
                <p><span>B. Chest</span> - Put the tape under your arms and measure around the widest part of your chest.</p>
                <p class="last"><span>C. Waist</span> - Measure around your natural waistline. This is the narrowest part of your torso, usually near your belly button.</p>

                </div>
                </div>
                </div>
                </div>
    ',
        'is_active'     => 1,
        'sort_order'    => 120
    ),
    array(
        'title' => 'Badger Sizing Information - PBS KIDS Shop',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'sizing/badger',
        'content_heading' => 'Badger Sizing Information',
        'stores' => array(2),
        'content' => '
<div class="sizing-wrapper">
     <div class="sizing-content">
     <div class="sizing-content-left-col">
         <h3 id="first">Sports Jerseys</h3>
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
                <img src="{{media url="shirt-sizing-women.jpg"}}" alt="" />
                <p>To start, get a measuring tape. Remember to keep it snug and straight while you\'re using it to get a more accurate fit.</p>
                <p>To find your size, measure around the bust line, waist, hips and inseam. Write down your results as you go.</p>
                <p><span>A. Bust/Chest</span> - Put the measuring tape under your arms to measure around the fullest part of your bust line.</p>
                <p class="last"><span>B. Waist</span> - Measure around your natural waistline. This is the narrowest part of your torso, usually near your belly button.</p>
                </div>
                <div>
                <h4>Measuring Mens & Boys Clothing</h4>
                <img src="{{media url="shirt-sizing-men.jpg"}}" alt="" />
                <p>To start, get a measuring tape. Remember to keep it snug and straight while you\'re using it to get a more accurate fit.</p>
                <p>To find your size, measure around the bust line, waist, hips and inseam. Write down your results as you go.</p>
                <p><span>A. Neck</span> - Measure around the base of your neck, which is its widest part.</p>
                <p><span>B. Chest</span> - Put the tape under your arms and measure around the widest part of your chest.</p>
                <p class="last"><span>C. Waist</span> - Measure around your natural waistline. This is the narrowest part of your torso, usually near your belly button.</p>

                </div>
                </div>
                </div>
                </div>
    ',
        'is_active'     => 1,
        'sort_order'    => 130
    ),
    array(
        'title' => 'Aeropost Delivery - PBS Kids',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'aeropost-delivery',
        'content_heading' => '',
        'stores' => array(2),
        'content' => '
<div id="aeropostInfo-wrapper">
     <h2 class="aeropostInfo-SectionTitle">Aeropost</h2>
    <div id="aeropost" class="aeropostInfo-infoSection">
         <p>Your shopping cart will reflect actual costs. Please contact us with
            any questions prior to placing your order.</p>
            <p>We are available <b>MON-FRI 9:00am-9:00pm EST</b></p>
            <ul>
            <li>Phone: 1-888-957-9696</li>
            <li>E-mail: <a href="mailto:customerservice@shop.pbskids.org">customerservice@shop.pbskids.org</a></li>
            </ul>
            </div>
            <h2 class="aeropostInfo-SectionTitile">Aeropost Delivery</h2>
            <div id="aeropostDelivery" class="aeropostInfo-infoSection">
            <ul>
            <li>All Aeropost orders will be processed and shipped to Aeropost\'s
            distribution center in Miami, Florida. From there, they will be
            shipped by Aeropost to the appropriate recipients. If you would
            like more information about delivery times or opening an account,
            please contact <a href="http://www.aeropost.com/main.htm" target="_blank">Aeropost</a>.</li>
            <li>Transit times do not include order processing time; please add
            1-2 business days for processing. Estimates may vary as a result of
            weather conditions, national holidays, etc.</li>
            <li>Once processed, most orders arrive at Aeropost\'s Florida
            distribution center in approximately 3 business days. Estimates may
            vary as a result of weather conditions, national holidays, etc.</li>
            <li>In most Central and South American countries, orders are usually
            ready for delivery within 48 hours after receipt in Miami. Delivery
            times in the Caribbean vary, but generally packages are shipped from
            Miami every Monday and Thursday and are delivered or available for
            pickup on the same day they are received in the Islands. While every
            effort is made to deliver orders as rapidly as possible, delays may
            occur during customs clearance. Estimates may vary as a result of
            weather conditions, national holidays, etc.</li>
            </ul>
            </div>
            </div>
    ',
        'is_active'     => 1,
        'sort_order'    => 140
    ),
    array(
        'title' => 'PBS Email Terms and Conditions',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'pbs-terms-and-conditions',
        'content_heading' => '',
        'stores' => array(2),
        'content' => '
<h1>Privacy Policy</h1>

<p>We are committed to making your online transaction safe, secure and confidential to the highest standards available. Following are some relevant facts about how we treat your information</p>

<p>We do not share or sell data. We collect billing and shipping information in order to process your order and deliver it to its destination. This information is used only by PBS KIDS Shop and the couriers we use for the purposes of fulfilling your order. Personal information collected through this site is used only for our internal purposes. We will not sell your personal information, or authorize third parties to use such information for any commercial purpose, without your prior consent.</p>

<p><b>Personal Information.</b>Â We do not automatically collect personal information such as your name, address, telephone number or email address. Such information will be collected only if you provide it to us voluntarily in order to order products, enter a contest or promotion, or sign up to receive our eUpdate newsletter. We don\'t require that any personal information be provided in order to view our website.</p>

<p><b>Information collected by Shop.PBSKids.org is used to:</b></p>

<ul>
    <li>allow you to order products, register for eUpdate and participate in special promotions;</li>
    <li>improve the site by observing products that are most popular; and</li>
    <li>personalize the delivery of information and recommend products to you.</li>
</ul>

<p>Your personal information may also be used to create statistics about Shop.PBSKids.org that we use to measure our performance. These statistics do not contain any information that could identify you personally. Shop.PBSKids.org does not sell your personal information to any organization for any purpose.</p>

<p><b>We Do Send E-mail.</b> We will send you email at regular intervals to ensure that you are always kept up to date on the status of your order. From time to time we send additional eflyers out to inform our customers of specials or promotions that they might be interested in. You can choose not to receive the eflyers by selecting this option in your PBS KIDS Shop account.</p>

<p><b>We Offer Optional Registration.</b> We recommend that you register as a customer in order to take advantage of the many benefits of maintaining an account. Unlike many other sites, registration is not necessary for placing orders. We offer a "buy now" option, which only requires the basic information required to process your order. Please note that this will limit access to your order history and an address book.</p>

<p><b>Credit Card Info Protected by 128bit SSL Encryption.</b> The security of your personal information is paramount. All Credit Card Transactions are completed using a 128 Bit SSL Encrypted Secure Transaction. As we transmit the information to the Bank\'s Secure SSL Server, they require a 128-bit transaction and will not process a transaction without one. Even though 40 or 56 Bit transactions are very secure, our Bank\'s insistence on 128 Bit SSL means that there is never any chance of your information every being intercepted or decoded.</p>

<p><b>Password Protected Accounts.</b> When you decide to register an account in order to take advantage of the address book, quick pay and order restoration features, your account information is secured by password protection assigned and maintained by you. If you forget your password, this information is only released to your specific email address recorded in your account profile. It is not given out in any other situation without identity verification provided by you. The account information contains the billing and shipping addresses, phone numbers and email address provided and maintained by you as well as your order history. Credit card information is not included in this file.</p>

<p><b>We Use Cookies.</b> Like most advanced sites these days, Shop.PBSKids.org uses cookies to make your shopping experience the best it can be. No personal or credit card information is stored in these cookies and there is no risk to your privacy or security caused by this action. It is simply an identifier transferred to your system from ours that helps us manage your transaction. It remembers items in your basket and currency selections. You can choose not to use cookies if you like. We do not require cookies to process your order however the lack of the memory feature may cause some frustration in certain situations.</p>

<p><b>Getting Your Consent.</b>Â By using Shop.PBSKids.org you are consenting to the collection and use of personal and account information by PBS KIDS Shop, for the purposes set out in this policy.</p>

<p><b>Changes to this Privacy Policy.</b>Â All changes will be posted on Shop.PBSKids.org promptly to inform visitors of what information is collected, how it is used, and under what circumstances it may be disclosed.</p>

    ',
        'is_active'     => 1,
        'sort_order'    => 150
    ),
    array(
        'title' => 'PBS Affiliate',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'affiliate',
        'content_heading' => '',
        'stores' => array(2),
        'content' => '
<div id="pbs-affiliate-wrap">
     <div id="pbs-affiliate">
     <img src="{{media url="pbs-affiliate_v3.png"}}" alt="PBS Affiliate" />
         <a href="https://signup.cj.com/member/brandedPublisherSignUp.do?air_refmerchantid=3684443" title="Click Here to Sign Up"></a>
    </div>
</div>
    ',
        'is_active'     => 1,
        'sort_order'    => 160
    ),
    array(
        'title' => 'PBS Email Discount',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'email',
        'content_heading' => '',
        'stores' => array(2),
        'content' => '
<div id="pbs-email-landing-wrap">
     <div id="pbs-email-landing">
     <a href="http://shop.pbskids.org/?utm_source=Email&utm_medium=PBS_newsletter&utm_content=CTA_landingpage&utm_campaign=12_01_12_thankyouextended"><img src="{{media url="120112_PBSKIDS_Landing.png"}}" alt="Happy Holidays! 20% Off Your Entire Purchase" /></a>
    </div>
    <p>*20% off product total only. Gift cards and shipping are not included. Valid through 12/2/12 at 11:59 PM CST.</p>
        </div>
    ',
        'is_active'     => 1,
        'sort_order'    => 170
    ),
    array(
        'title' => 'Shop PBS Default Birthday Party Supplies - PBS KIDS Shop Party Planner',
        'root_template' => 'one_column',
        'meta_keywords' => 'PBS KIDS Shop, PBS Default party supplies, invitations, party favors, birthday party supplies, kids party planner',
        'meta_description' => 'Find PBS Default party supplies with PBS KIDS Shop Party Planner. Enter age, name, number of guests for PBS Default party decorations, favors and invitations.',
        'identifier' => 'pbspartyplanner',
        'content_heading' => '',
        'stores' => array(2),
        'content' => '
<div id="partyplanner-wrap">
     <div id="partyplanner">
     <img usemap="#partyplanner_Map" src="{{media url="bpartyplan-2.png"}}" border="0" alt="pbs party planner"  height="860"   />
         <map name="partyplanner_Map">
         <area shape="rect" alt="" coords="46,271,173,415" href="http://wildkratts.shop.pbskids.org/partyplanner" title="Wild Krats">
              <area shape="rect" alt="" coords="193,271,320,414" href="http://caillou.shop.pbskids.org/partyplanner" title="Caillou">

              <area shape="rect" alt="" coords="338,271,462,415" href="http://dinosaurtrain.shop.pbskids.org/partyplanner" title="Dinosaur Train">
              <area shape="rect" alt="" coords="477,271,610,415" href="http://superwhy.shop.pbskids.org/partyplanner" title="Super Why">

              <area shape="rect" alt="" coords="620,271,744,413" href="http://curiousgeorge.shop.pbskids.org/partyplanner" title="Curious George">
              <area shape="rect" alt="" coords="760,271,888,412" href="http://sesamestreet.shop.pbskids.org/partyplanner" title="Sesame Street">

              <area shape="rect" coords="46,448,173,574" href="http://clifford.shop.pbskids.org/partyplanner" title="Clifford"/>
              <area shape="rect" coords="193,448,320,573" href="http://catinthehat.shop.pbskids.org/partyplanner" title="Cat In The Hat" />

              <area shape="rect" coords="338,448,462,572" href="http://sidscience.shop.pbskids.org/partyplanner"  title="Sid The Science Kid" />
              <area shape="rect" coords="477,448,610,573" href="http://arthur.shop.pbskids.org/partyplanner"  title="Aurthur" />

              <area shape="rect" coords="620,448,744,573" href="http://barney.shop.pbskids.org/partyplanner"  title="Barney" />
              <area shape="rect" coords="760,448,888,573" href="http://marthaspeaks.shop.pbskids.org/partyplanner"  title="Martha Speaks" />

              <area shape="rect" alt="" coords="201,632,310,756" href="http://angelinaballerina.shop.pbskids.org/partyplanner"  title="Angelina Ballerina" />
              <area shape="rect" alt="" coords="332,632,453,755" href="http://wordgirl.shop.pbskids.org/partyplanner"  title="Word Girl" />

              <area shape="rect" alt="" coords="474,632,609,755" href="http://mayamiguel.shop.pbskids.org/partyplanner " title="Maya and Miguel" />

              <area shape="rect" alt="" coords="620,632,752,755" href="http://wordworld.shop.pbskids.org/partyplanner"  title="World World" />
              <area shape="rect" alt="" coords="0,0,926,180" href="http://shop.pbskids.org/partyplanner" title="Party Planner" >

              <area shape="rect" alt="" coords="48,792,887,859" href="http://www.pbs.org/parents/birthday-parties" title="PBS Birthday Party Planner">


        </map>
    </div>
</div>
    ',
        'is_active'     => 1,
        'sort_order'    => 180
    ),

);

/**
 * Insert default and system pages
 */
foreach ($cmsPages as $data) {
    Mage::getModel('cms/page')->setData($data)->save();
}