<?php
//upgrade script for creating static pages for limoges store

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

//array for cms pages

$cmsPages = array(
        array(
        'title' => 'About Limoges',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'about',
        'content_heading' => 'About Limoges',
        'stores' => array($storeId),
        'content' => '
<div class="about-section first">
     Coming Soon...
</div>
        ',
        'is_active'     => 1,
        'stores'        => array($storeId),
        'sort_order'    => 26
    ),//end about us
    array(
        'title' => 'Customer Service Shipping',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'customer-service/shipping',
        'content_heading' => 'Customer Service Shipping',
        'stores' => array($storeId),
    'content' => '<div class="customer-service row">{{block type="cms/block" block_id="customer-service-links-limoges"}}
<div class="cs-wrapper col-lg-10 col-md-10 col-sm-12 col-xm-12">
<ul id="cs-sections" class="cs">
<li id="cs-shipping" class="section">
<div class="green-title-sec"><span class="green-title">Shipping</span></div>
<div id="cs-section-shipping" class="step a-item static-content">
<h3 id="instockitems">Item Availability</h3>
<p>A product\'s availability is noted on the product\'s detail page as "In-Stock" or "Out of Stock." If the item is out
of stock, there will be a "Notify Me" option, which will allow you to enter your e-mail address to be notified as soon
as the item is back in stock. Most items on our site are in stock and ready to ship.</p>
<h3 id="customprinteditems">In-Stock Items</h3>
<p>Most orders for In-Stock items begin the order process as soon as your online purchase is completed. Your In-Stock
item will be shipped once the item is located in stock, your payment is approved, and the receiving address is verified.
 You will not be charged for any item until it is shipped to you.</p> <p>For example, if you order an In-Stock item on
 Monday that leaves the warehouse in 1-2 full business days, it will leave the warehouse by end-of-day Wednesday. During
  checkout, you will get to select a shipping method and see the total time it takes for the item to leave the warehouse
   and ship to its destination. Please note that business days are Monday-Friday, excluding federal holidays within the
   United States.</p><p>Please note expected shipment times appearing on the product\'s detail page specify when an item
is expected to leave our warehouse, not when the item will arrive at its final shipping destination.</p>
<p>After your order leaves our warehouse, delivery times vary according to the shipping method you select during
checkout and the location of your shipping address.</p>
<h3>Custom Printed Items</h3>
<p>Please allow an additional 2-4 business days for all custom printed items (with or without a name) to be created
prior to shipping. All custom printed items ship by US Mail. International and expedited shipping is not available.</p>
<h3 id="shoppingmethods">Shipping Methods and Costs</h3>
<p>Depending on the item(s) you purchase on Personalized Planet and the location to which the items will be delivered,
different shipping methods will be available. Each shipping method has its own restrictions and charges that will be
applied to your order.</p><p>At checkout, you will be prompted to choose a shipping method for your item(s). (Please
note, some items may offer only one shipping method.) Shipping costs are dependent on the items in your order and the
shipping method you select. Your total shipping charges will automatically compute during checkout prior to the
completion of your order.</p><p>Generally, you will have the option of upgrading your shipping method for faster
delivery (such as Second Day or Overnight service). If you choose to upgrade your shipping method, your order must be
received and clear credit authorization by 12:00 p.m. (noon) EST or your order may not be processed until the following
business day. Business days are Monday-Friday, excluding federal holidays within the United States.</p>
<h4>Domestic Shipping</h4>
<p>We pride ourselves on being one of the fastest shippers on the Internet. If you place your order Monday - Friday
before 3 pm EST, the order will usually ship the same day. Orders placed after the 3 pm EST cutoff will usually ship the
 next business day. Orders placed between Friday 3 pm EST and Monday 3 pm EST will usually ship that Monday.</p>
<ul>
<li><span>Economy Ground: </span>Delivered by your local Post Office, this is our most economical shipping method.
Delivery confirmation is provided and this method takes approximately 4-7 business days for delivery. P.O. Boxes are
accepted with this shipping method.</li>
<li><span>Standard Ground: </span>Designed for residential deliveries, orders using this method arrive within
approximately 3-5 business days. This method CANNOT ship to P.O. Boxes.</li>
<li><span>FedEx 2nd Day Air: </span>Guaranteed 2 business day (Monday - Friday, excluding holidays) delivery to most
locations in the U.S. mainland after the package leaves our warehouse. Generally all 2nd Day Air orders ship from our
warehouse on the same day if order is placed by 3:00 pm EST. This method CANNOT ship to P.O. Boxes.</li>
<li><span>FedEx Next Day Air: </span>Guaranteed next business day delivery to most locations in the U.S. mainland after
the package leaves our warehouse. Generally all Next Day Air orders ship from our warehouse on the same day if ordered
by 3:00 pm EST. Shipping to your business address could result in more timely delivery over shipping to your residential
 address. This method CANNOT ship to P.O. Boxes.</li>
</ul>
<h4>Third Party Ship</h4>
<p>Personalized Planet offers a large selection of items that may be sent by various shipping carriers depending on
where the item is shipping from. Orders that contain items shipping from various locations will arrive in separate
packages. Please allow 3 to 4 extra days for shipping these items. Expedited or express shipping may not be available on
 orders containing these items during checkout.</p>
<h4>Shipping to Alaska and Hawaii</h4>
<p>We ship all orders to Alaska and Hawaii via USPS Priority Mail. While most orders arrive in a timely manner,
occasionally shipments may take 2-5 weeks. If you haven\'t received your order in 14 business days, please e-mail
<a href="mailto:customerservice@personalizedplanet.com">customerservice@personalizedplanet.com</a>.</p>
<h3 id="shoppingrules">Shipping Rules and Restrictions</h3>
<ul>
<li>Orders are shipped on business days only. Business days are Monday-Friday, excluding federal holidays within the
United States.</li>
<li>Personalized Planet makes every effort to ship In-Stock items within 24 hours of order placement.</li>
<li>All expedited orders require a street address.</li>
<li>Please allow an additional 2-4 business days for all custom printed items (with or without a name) to be created
prior to shipping. All custom printed items ship by US Mail. International and expedited shipping is not available.</li>
<li>Please call a Customer Service Specialist at <strong>1-800-555-5555</strong> if you have any questions.</li>
</ul>
<h4>Oversized Items</h4>
<p>Oversized items will be charged the applicable oversized shipping charges. Oversized items are not eligible for free
shipping promotions and are not available to be shipped internationally or using expedited shipping methods.</p>
<h3 id="trackingandorder">Tracking Your Order</h3>
<p>At our warehouse, tracking numbers are assigned to packages and are generally available within 24 hours. However, it
may take up to 48 hours or longer before the package is checked into the carrier\'s tracking system. Therefore, even
though your package has already shipped from our warehouse and is on its way to you, the carrier may not be able to
provide any information about your package for up to 48 hours or more. Please allow an additional 2-4 business days for
custom printed items to be created prior to shipping.</p>
<p>When you click on "Track Order," you will be prompted to login with your e-mail address and order number. An order
summary page will provide you with detailed information about your current order or past orders. After your order is
shipped, your tracking number, if available, will be displayed. Depending on the shipping company, you can click on the
tracking number to view the delivery status of your order. A shipping company may not have the ability to track a number
 for up to 24 business hours.</p>
<p>Orders placed on Personalized Planet may be delivered by one of several different carriers and shipping methods.
Therefore, tracking availability may vary depending on the type of item you purchased, the shipping method you selected
during Checkout, and the carrier that is delivering your item(s).</p>
</div>
</li>
</ul>
</div>
</div>',
        'is_active'     => 1,
        'stores'        => array($storeId),
        'sort_order'    => 24
    ),//end shipping

    array(
        'title' => 'Customer Service Returns',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'customer-service/returns',
        'content_heading' => 'Customer Service Returns',
        'stores' => array($storeId),
        'content' => '<div class="customer-service row">
{{block type="cms/block" block_id="customer-service-links-limoges"}}
<div class="cs-wrapper col-lg-10 col-md-10 col-sm-12 col-xm-12">
<div class="green-title-sec"><span class="green-title">Returns</span></div>
<div id="cs-section-returns" class="step a-item static-content">
<h3 id="maneyback">Money Back Guarantee</h3>
<p>We want you to be completely satisfied with your purchase from Personalized Planet. If you\'re not 100% happy with a
qualifying item, simply return it within 30 days in the original packaging for a full refund or credit of the product
purchase price.</p>
<ul>
<li><span>Personalized items may not be returned unless damaged or defective.</span> Unfortunately, due to the nature of
our customization on demand business, personalized items cannot be returned because they cannot be resold. Therefore,
we are unable to refund or credit personalized clothing that was ordered in an incorrect size. Please be sure to use the
<a href="{{config path=">"sizing chart"</a> for all clothing orders.</li>
<li><span>Items should be returned in unopened or original condition, with a copy of the packing slip, to our Return
    Center.</span>A refund of the price of the item and any applicable taxes will be issued using the original form of
payment used. We will not issue refunds for items returned that have been used, or are classified as un-sellable.</li>
</ul>
<p>Please include a copy of your packing slip with the items you are returning as well as your name, address, and order
number. Returns should be sent to:</p>
<address>Personalized Planet<br /> Returns Department<br /> 755 Remington Blvd. Suite B<br /> Bolingbrook, IL 60440
</address><br />
<p>All Canadian returns must be addressed to the following to be prepared for entry into the United States and should
include all of the information requested above:</p>
<address>Personalized Planet Returns<br /> c/o DHL Global Mail, Returns Dept.<br /> 355 Admiral Blvd, Unit 4<br />
Mississauga, ON<br /> L5T 2N1</address>
<h4>Customer Inquiries should be directed to the following:</h4>
<ul>
<li>Customer Service Email: <a href="mailto: customerservice@personalizedplanet.com">
    customerservice@personalizedplanet.com</a>.</li>
<li>For additional information on the manufacturer\'s warranty for a specific product, please contact the manufacturer
directly.</li>
</ul>
</div>
</div>
</div>',
        'is_active'     => 1,
        'stores'        => array($storeId),
        'sort_order'    => 23
    ),//end returns
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
        'title' => 'Customer Service Privacy',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'customer-service/privacy',
        'content_heading' => 'Customer Service Privacy',
        'stores' => array($storeId),
        'content' => '<div class="customer-service row">
{{block type="cms/block" block_id="customer-service-links-limoges"}}
<div class="cs-wrapper col-lg-10 col-md-10 col-sm-12 col-xm-12">
<div class="green-title-sec"><span class="green-title">Safety, Security &amp; Privacy</span></div>
<div id="cs-section-safety" class="step a-item static-content">
<p id="privacyterms">PLEASE READ THESE TERMS AND CONDITIONS CAREFULLY BEFORE USING THIS WEB SITE. YOUR USE OF THIS WEB
SITE CONFIRMS YOUR UNCONDITIONAL ACCEPTANCE OF THE FOLLOWING TERMS AND CONDITIONS. IF YOU DO NOT ACCEPT THESE TERMS AND
CONDITIONS, DO NOT USE THIS WEB SITE.</p>
<p><strong>Products, Content and Specifications.</strong> All features, content, specifications, products and prices of
products and services described or depicted on this Web site are subject to change at any time without notice. Certain
weights, measures and similar descriptions are approximate and are provided for convenience purposes only. We make all
reasonable efforts to accurately display the attributes of our products, including the applicable colors; however, the
actual color you see will depend on your computer system and we cannot guarantee that your computer will accurately
display such colors. The inclusion of any products or services in this Web site at a particular time does not imply or
warrant that these products or services will be available at any time. It is your responsibility to ascertain and obey
all applicable local, state, federal and international laws (including minimum age requirements) in regard to the
possession, use and sale of any item purchased from this Web site. By placing an order, you represent that the products
ordered will be used only in a lawful manner.</p>
<p><strong>Shipping Limitations.</strong> When an order is placed, it will be shipped to an address designated by the
purchaser as long as that shipped address is compliant with the shipping restrictions contained on this Web site. All
purchases from this Web site are made pursuant to a shipment contract. As a result, risk of loss and title for items
purchased from this Web site pass to you upon delivery of the items to the carrier. You are responsible for filing any
claims with carriers for damaged and/or lost shipments.</p>
<p><strong>Accuracy of Information.</strong> Personalized Planet attempts to ensure that information on this Web site is
 complete, accurate and current. Despite our efforts, the information on this Web site may occasionally be inaccurate,
 incomplete or out of date. We make no representation as to the completeness, accuracy or currentness of any information
  on this Web site. For example, products included on the Web site may be unavailable, may have different attributes
  than those listed, or may actually carry a different price than that stated on the Web site. In addition, we may make
  changes in information about price and availability without notice. While it is our practice to confirm orders by
  e-mail, the receipt of an e-mail order confirmation does not constitute our acceptance of an order or our confirmation
   of an offer to sell a product or service. We reserve the right, without prior notice, to limit the order quantity on
   any product or service and/or to refuse service to any customer. We also may require verification of information
   prior to the acceptance and/or shipment of any order.</p>
<p><strong>Use of this Web Site.</strong> The Web site design and all text, graphics, information, content, and other
material displayed on or that can be downloaded from this Web site are either the property of, or used with permission
by Personalized Planet and are protected by copyright, trademark and other laws and may not be used except as permitted
in these Terms and Conditions or with the prior written permission of the owner of such material. You may not modify the
 information or materials located on this Web site in any way or reproduce or publicly display, perform, or distribute
 or otherwise use any such materials for any public or commercial purpose. Any unauthorized use of any such information
 or materials may violate copyright laws, trademark laws, laws of privacy and publicity, and other laws and regulations.
 </p>
<p><strong>Trademarks.</strong> Certain trademarks, trade names, service marks and logos used or displayed on this Web
site are registered and unregistered trademarks, trade names and service marks of Personalized Planet and its
affiliates. Other trademarks, trade names and service marks used or displayed on this Web site are the registered and
unregistered trademarks, trade names and service marks of their respective owners, including Personalized Planet and its
 affiliates. Nothing contained on this Web site grants or should be construed as granting, by implication, estoppel, or
 otherwise, any license or right to use any trademarks, trade names, service marks or logos displayed on this Web site
 without the written permission of Personalized Planet or such third party owner.</p>
<p><strong>Linking to this Web Site.</strong> Creating or maintaining any link from another Web site to any page on this
 Web site without Personalized Planet\'s prior written permission is prohibited. Running or displaying this Web site or
any material displayed on this Web site in frames or through similar means on another Web site without Personalized
Planet\'s prior written permission is prohibited. Any permitted links to this Web site must comply will all applicable
laws, rules and regulations.</p>
<p><strong>Third Party Links.</strong> From time to time, this Web site may contain links to Web sites that are not
owned, operated or controlled by Personalized Planet or their affiliates. All such links are provided solely as a
convenience to you. If you use these links, you will leave this Web site. Neither Personalized Planet, nor any of their
affiliates are responsible for any content, materials or other information located on or accessible from any other Web
site. Neither Personalized Planet, nor any of their affiliates endorse, guarantee, or make any representations or
warranties regarding any other Web site, or any content, materials or other information located or accessible from such
Web sites, or the results that you may obtain from using such Web sites. If you decide to access any other Web site
linked to or from this Web site, you do so entirely at your own risk.</p>
<p><strong>Inappropriate Material.</strong> You are prohibited from posting or transmitting any unlawful, threatening,
defamatory, libelous, obscene, pornographic or profane material or any material that could constitute or encourage
conduct that would be considered a criminal offense or give rise to civil liability, or otherwise violate any law. In
addition to any remedies that Personalized Planet may have at law or in equity, if Personalized Planet reasonably
determines that you have violated or are likely to violate the foregoing prohibitions, Personalized Planet may take any
action they reasonably deem necessary to cure or prevent the violation, including without limitation, the immediate
removal from this Web site of the related materials. Personalized Planet will fully cooperate with any law enforcement
authorities or court order or subpoena requesting or directing Personalized Planet to disclose the identity of anyone
posting such materials.</p>
<p><strong>User Information.</strong> Other than personally identifiable information, which is subject to this Web
site\'s Privacy Policy, any material, information, suggestions, ideas, concepts, know-how, techniques, questions,
comments or other communication you transmit or post to this Web site in any manner ("User Communications") is and will
be considered non-confidential and non-proprietary. Personalized Planet, each of its affiliates and/or their designees
may use any or all User Communications for any purpose whatsoever, including, without limitation, reproduction,
transmission, disclosure, publication, broadcast, development, manufacturing and/or marketing in any manner whatsoever
for any or all commercial or non-commercial purposes. Personalized Planet may, but is not obligated to, monitor or
review any User Communications. Personalized Planet shall have no obligations to use, return, review, or respond to any
User Communications. Personalized Planet will have no liability related to the content of any such User Communications,
whether or not arising under the laws of copyright, libel, privacy, obscenity, or otherwise. Personalized Planet retains
 the right to remove any or all User Communications that includes any material Personalized Planet deems inappropriate
 or unacceptable.</p>
<p><strong>DISCLAIMERS.</strong> YOUR USE OF THIS SITE IS AT YOUR RISK. THE MATERIALS AND SERVICES PROVIDED IN
CONNECTION WITH THIS WEB SITE ARE PROVIDED "AS IS" WITHOUT ANY WARRANTIES OF ANY KIND INCLUDING WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, OR NON-INFRINGEMENT OF INTELLECTUAL PROPERTY. NEITHER PERSONALIZED
PLANET NOR ANY OF THEIR AFFILIATES WARRANT THE ACCURACY OR COMPLETENESS OF THE MATERIALS OR SERVICES ON OR THROUGH THIS
WEB SITE. THE MATERIALS AND SERVICES ON OR THROUGH THIS WEB SITE MAY BE OUT OF DATE, AND NEITHER PERSONALIZED PLANET NOR
 ANY OF THEIR AFFILIATES MAKE ANY COMMITMENT OR ASSUMES ANY DUTY TO UPDATE SUCH MATERIALS OR SERVICES. THE FOREGOING
 EXCLUSIONS OF IMPLIED WARRANTIES DO NOT APPLY TO THE EXTENT PROHIBITED BY LAW. PLEASE REFER TO YOUR LOCAL LAWS FOR ANY
 SUCH PROHIBITIONS. <br /> ALL PRODUCTS AND SERVICES PURCHASED ON OR THROUGH THIS WEB SITE ARE SUBJECT ONLY TO ANY
 APPLICABLE WARRANTIES OF THEIR RESPECTIVE MANUFACTURES, DISTRIBUTORS AND SUPPLIERS, IF ANY. TO THE FULLEST EXTENT
 PERMISSIBLE BY APPLICABLE LAW, PERSONALIZED PLANET HEREBY DISCLAIM ALL WARRANTIES OF ANY KIND, EITHER EXPRESS OR
 IMPLIED, INCLUDING, ANY IMPLIED WARRANTIES WITH RESPECT TO THE PRODUCTS AND SERVICES LISTED OR PURCHASED ON OR THROUGH
 THIS WEB SITE. WITHOUT LIMITING THE GENERALITY OF THE FOREGOING, PERSONALIZED PLANET HEREBY EXPRESSLY DISCLAIM ALL
 LIABILITY FOR PRODUCT DEFECT OR FAILURE, CLAIMS THAT ARE DUE TO NORMAL WEAR, PRODUCT MISUSE, ABUSE, PRODUCT
 MODIFICATION, IMPROPER PRODUCT SELECTION, NON-COMPLIANCE WITH ANY CODES, OR MISAPPROPRIATION. WE MAKE NO WARRANTIES TO
 THOSE DEFINED AS "CONSUMERS" IN THE MAGNUSON-MOSS WARRANTY-FEDERAL TRADE COMMISSION IMPROVEMENTS ACT. THE FOREGOING
 EXCLUSIONS OF IMPLIED WARRANTIES DO NOT APPLY TO THE EXTENT PROHIBITED BY LAW. PLEASE REFER TO YOUR LOCAL LAWS FOR ANY
 SUCH PROHIBITIONS.</p>
<p><strong>LIMITATIONS OF LIABILITY.</strong> Personalized Planet does not assume any responsibility, and shall not be
liable for, any damages to, or viruses that may infect, your computer, telecommunication equipment, or other property
caused by or arising from your access to, use of, or browsing this Web site or your downloading of any materials, from
this Web site. IN NO EVENT WILL PERSONALIZED PLANET, THEIR RESPECTIVE OFFICERS, DIRECTORS, EMPLOYEES, SHAREHOLDERS,
AFFILIATES, AGENTS, SUCCESSORS, ASSIGNS, RETAIL PARTNERS NOR ANY PARTY INVOLVED IN THE CREATION, PRODUCTION OR
TRANSMISSION OF THIS WEB SITE BE LIABLE TO ANY PARTY FOR ANY INDIRECT, SPECIAL, PUNITIVE, INCIDENTAL OR CONSEQUENTIAL
DAMAGES (INCLUDING, WITHOUT LIMITATION, THOSE RESULTING FROM LOST PROFITS, LOST DATA OR BUSINESS INTERRUPTION) ARISING
OUT OF THE USE, INABILITY TO USE, OR THE RESULTS OF USE OF THIS WEB SITE, ANY WEB SITES LINKED TO THIS WEB SITE, OR THE
MATERIALS, INFORMATION OR SERVICES CONTAINED AT ANY OR ALL SUCH SITES, WHETHER BASED ON WARRANTY, CONTRACT, TORT OR ANY
OTHER LEGAL THEORY AND WHETHER OR NOT ADVISED OF THE POSSIBILITY OF SUCH DAMAGES. THE FOREGOING LIMITATIONS OF LIABILITY
 DO NOT APPLY TO THE EXTENT PROHIBITED BY LAW. PLEASE REFER TO YOUR LOCAL LAWS FOR ANY SUCH PROHIBITIONS. <br /> IN THE
 EVENT OF ANY PROBLEM WITH THIS WEB SITE OR ANY CONTENT, YOU AGREE THAT YOUR SOLE REMEDY IS TO CEASE USING THIS WEB
 SITE. IN THE EVENT OF ANY PROBLEM WITH THE PRODUCTS OR SERVICES THAT YOU HAVE PURCHASED ON OR THROUGH THIS WEB SITE,
 YOU AGREE THAT YOUR REMEDY, IF ANY, IS FROM THE MANUFACTURER OF SUCH PRODUCTS OR SUPPLIER OF SUCH SERVICES, IN
 ACCORDANCE WITH SUCH MANUFACTURER\'S OR SUPPLIER\'S WARRANTY, OR TO SEEK A RETURN AND REFUND FOR SUCH PRODUCT OR
 SERVICES
 IN ACCORDANCE WITH THE RETURNS AND REFUNDS POLICIES POSTED ON THIS WEB SITE.</p>
<p><strong>Revisions to these Terms and Conditions.</strong> Personalized Planet may revise these Terms and Conditions
at any time by updating this posting. You should visit this page from time to time to review the then current Terms and
Conditions because they are binding on you. Certain provisions of these Terms and Conditions may be superseded by
expressly designated legal notices or terms located on particular pages at this Web site.</p>
<p><strong>Choice of Law; Jurisdiction.</strong> These Terms and Conditions supersede any other agreement between you
and Tys Toy Box to the extent necessary to resolve any inconsistency or ambiguity between them. This Web Site is
administered by Tys Toy Box from its offices in New York. These Terms and Conditions will be governed by and construed
in accordance with the laws of the State of New York, without giving effect to any principles of conflicts of laws.
Any action seeking legal or equitable relief arising out of or relating to this Web Site shall be brought only in the
state or federal courts located in the State of New York. A printed version of these Terms and Conditions shall be
admissible in judicial and administrative proceedings based upon or relating to these Terms and Conditions to the same
extent and subject to the same conditions as other business documents and records originally generated and maintained in
 printed form.</p>
<p><strong>Termination.</strong> You or we may suspend or terminate your account or your use of this Web site at any
time, for any reason or for no reason. You are personally liable for any orders that you place or charges that you incur
 prior to termination. We reserve the right to change, suspend, or discontinue all or any aspect of this Web site at any
  time without notice.</p>
<p><strong>Additional Assistance.</strong> If you do not understand any of the foregoing Terms and Conditions or if you
have any questions or comments, we invite you to contact our customer service department at
<a href="mailto:customerservice@personalizedplanet.com">customerservice@personalizedplanet.com</a>.</p>
<hr />
<h3 id="privacypolicy">Privacy Policy</h3>
<p>We are committed to making your online transaction safe, secure, and confidential to the highest standards available.
 Following are some relevant facts about how we treat your information.</p>
<p>We do not share or sell data. We collect billing and shipping information in order to process your order and deliver
it to its destination. This information is used only by Personalized Planet and the couriers we use for the purposes of
fulfilling your order. Personal information collected through this site is used only for our internal purposes. We will
not sell your personal information, or authorize third parties to use such information for any commercial purpose,
without your prior consent.</p>
<h4>Personal Information</h4>
<p>We do not automatically collect personal information such as your name, address, telephone number or e-mail address.
Such information will be collected only if you provide it to us voluntarily in order to order products, enter a contest
or promotion, or sign up to receive our e-Update newsletter. We don\'t require that any personal information be provided
in order to view our website.</p>
<p><strong>Information collected by Personalized Planet is used to: </strong></p>
<ul>
<li>allow you to order products, register for e-Update and participate in special promotions;</li>
<li>improve the site by observing products that are most popular; and</li>
<li>personalize the delivery of information and recommend products to you.</li>
</ul>
<p>Your personal information may also be used to create statistics about Personalized Planet that we use to measure our
performance. These statistics do not contain any information that could identify you personally. Personalized Planet
does not sell your personal information to any organization for any purpose.</p>
<p><strong>We do send email.</strong> We will send you email at regular intervals to ensure that you are always kept up
to date on the status of your order. From time to time we send additional eflyers out to inform our customers of
specials or promotions that they might be interested in. You can choose not to receive the eflyers by selecting this
option in your Tys Toy Box account.</p>
<p><strong>We offer Optional Registration.</strong> We recommend that you register as a customer in order to take
advantage of the many benefits of maintaining an account. Unlike many other sites, registration is not necessary for
placing orders. We offer a "buy now" option, which only requires the basic information required to process your order.
Please note that this will limit access to your order history and an address book.</p>
<p><strong>Credit Card info protected by 128bit SSL Encryption.</strong> The security of your personal information is
paramount. All Credit Card Transactions are completed using a 128 Bit SSL Encrypted Secure Transaction. As we transmit
the information to the Bank\'s Secure SSL Server, they require a 128-bit transaction and will not process a transaction
without one. Even though 40 or 56 Bit transactions are very secure, our Bank\'s insistence on 128 Bit SSL means that
there is never any chance of your information every being intercepted or decoded.</p>
<p><strong>Password Protected Accounts.</strong> When you decide to register an account in order to take advantage of
the address book, quick pay and order restoration features, your account information is secured by password protection
assigned and maintained by you. If you forget your password, this information is only released to your specific e-mail
address recorded in your account profile. It is not given out in any other situation without identity verification
provided by you. The account information contains the billing and shipping addresses, phone numbers and e-mail address
provided and maintained by you as well as your order history. Credit card information is not included in this file.</p>
<p><strong>We use Cookies.</strong> Like most advanced sites these days, Personalized Planet uses cookies to make your
shopping experience the best it can be. No personal or credit card information is stored in these cookies and there is
no risk to your privacy or security caused by this action. It is simply an identifier transferred to your system from
ours that helps us manage your transaction. It remembers items in your basket and currency selections. You can choose
not to use cookies if you like. We do not require cookies to process your order however the lack of the memory feature
may cause some frustration in certain situations.</p>
<p><strong>Getting Your Consent</strong>. By using Personalized Planet you are consenting to the collection and use of
personal and account information by Personalized Planet, for the purposes set out in this policy.</p>
<hr />
<h3>Children&rsquo;s Privacy Policy</h3>
<p>Personalized Planet does not knowingly solicit or collect personally identifiable information online from children
under the age of 13 without prior verifiable parental consent. If Personalized Planet learns that a child under the age
of 13 has submitted personally identifiable information online without parental consent, it will take all reasonable
measures to delete such information from its databases and to not use such information for any purpose (except where
necessary to protect the safety of the child or others as required or allowed by law). If you become aware of any
personally identifiable information we have collected from children under 13, please contact us at
<a href="mailto:customerservice@personalizedplanet.com">customerservice@personalizedplanet.com</a> or by calling
<strong>1-888-555-5555</strong>.</p>
<p>For additional tips on how to help children stay safe on the Internet, we recommend that you visit the following
sites: <br />
<a href="http://www.google.com/url?q=http%3A%2F%2Fbusiness.ftc.gov%2Fprivacy-and-security%2Fchildrens-privacy&amp;sa=D&amp;sntz=1&amp;usg=AFQjCNFNsb3NVUryodJHhRqY1wDgDcjQ0w" target="_blank">
    business.ftc.gov/privacy-and-security/childrens-privacy</a> <br /> <a href="http://www.google.com/url?q=http%3A%2F%2Fwww.onguardonline.gov%2Farticles%2F0012-kids-and-socializing-online&amp;sa=D&amp;sntz=1&amp;usg=AFQjCNEHkXMtgE00Q0j9vYb2x6lz5r6Y4w" target="_blank">www.onguardonline.gov/articles/0012-kids-and-socializing-online</a> <br />
<a href="http://www.google.com/url?q=http%3A%2F%2Fwww.onguardonline.gov%2Ffeatures%2Ffeature-0002-featured-info-parents&amp;sa=D&amp;sntz=1&amp;usg=AFQjCNF90G8GPc7y32chLkNDRhUXviw8zw" target="_blank">http://www.onguardonline.gov/features/feature-0002-featured-info-parents</a></p>
</div>
</div>
</div>',
        'is_active'     => 1,
        'stores'        => array($storeId),
        'sort_order'    => 22
    ),//end privacy
    array(
        'title' => 'Overview',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'customer-service',
        'content_heading' => 'Customer Service Overview',
        'stores' => array($storeId),
        'content' => '<div class="customer-service row">{{block type="cms/block" block_id="customer-service-links-limoges"}}
<div class="cs-wrapper col-lg-10 col-md-10 col-sm-12 col-xm-12">
<div class="customer-header-container">
<div class="customer-header col-lg-5 col-md-5 col-sm-5"><img src="{{skin url=\'images/bkg_cust_service_bg.png\'}}" alt="Customer Service" /></div>
<div class="customer-service-content"><a class="contact-no">1-847-375-1326</a> <a class="email-us" href="mailto:info@tystoybox.com">Email us</a></div>
</div>
<div class="cutomer-links-set col-lg-12 col-md-12 col-sm-12 col-xs-12 first">
<div class="slide-menu col-lg-3 col-md-3 col-sm-3 col-xs-12"><span class="pink-head">Ordering<span>&nbsp;</span></span>
<ul>
<li class="first"><a title="Shopping Cart" href="customer-service/ordering#shoppingcart">Shopping Cart</a></li>
<li><a title="Checkout" href="customer-service/ordering#checkout">Checkout</a></li>
<li><a title="Gift Wrap/Gift Message" href="customer-service/ordering#giftwrap">Gift Wrap/Gift Message</a></li>
<li><a title="Google Checkout" href="customer-service/ordering#checkout">Google Checkout</a></li>
<li><a title="Google Checkout FAQ" href="https://checkout.google.com/support/">Google Checkout FAQ</a></li>
<li><a title="Checkout" href="href=&quot;customer-service/ordering#couponcodes&quot;">Coupon Codes</a></li>
<li class="last more-links"><a title="more" href="customer-service/ordering">More</a></li>
</ul>
</div>
<div class="slide-menu col-lg-3 col-md-3 col-sm-3 col-xs-12"><span class="pink-head">Payment<span>&nbsp;</span></span>
<ul>
<li class="first"><a title="Payment Options" href="customer-service/payment#paymentoptions">Payment Options</a></li>
<li><a title="Coupon Codes" href="customer-service/payment#couponcodes">Coupon Codes</a></li>
<li><a title="PayPal" href="customer-service/payment#paypal">PayPal</a></li>
<li><a title="PayPal FAQ" href="customer-service/payment#paypalfaq">PayPal FAQ</a></li>
<li class="last more-links"><a title="more" href="customer-service/payment">More</a></li>
</ul>
</div>
<div class="slide-menu col-lg-3 col-md-3 col-sm-3 col-xs-12"><span class="pink-head">Shipping<span>&nbsp;</span></span>
<ul>
<li class="first"><a title="Item Availability" href="customer-service/shipping#instockitems">Item Availability</a></li>
<li><a title="Shipping Methods and Costs" href="customer-service/shipping#shoppingmethods">Shipping Methods and Costs</a></li>
<li><a title="Shipping Rules and Restrictions" href="customer-service/shipping#shoppingrules">Shipping Rules and Restrictions</a></li>
<li><a title="Tracking Your Order" href="customer-service/shipping#trackingandorder">Tracking Your Order</a></li>
<li class="last more-links"><a title="more" href="customer-service/shipping">More</a></li>
</ul>
</div>
<div class="slide-menu col-lg-3 col-md-3 col-sm-3 col-xs-12 last"><span class="pink-head">Your Order<span>&nbsp;</span></span>
<ul>
<li class="first"><a title="E-mails About Your Order" href="customer-service/your-order#emailabout">E-mails About Your Order</a></li>
<li><a title="Tracking Your Order" href="customer-service/your-order#trackingyourorder">Tracking Your Order</a></li>
<li><a title="Changing or Canceling" href="customer-service/your-order#changingcancelorder">Changing or Canceling your order</a></li>
<li class="last more-links"><a title="more" href="customer-service/your-order">More</a></li>
</ul>
</div>
</div>
<div class="cutomer-links-set col-lg-12 col-md-12 col-sm-12 col-xs-12">
<div class="slide-menu col-lg-3 col-md-3 col-sm-3 col-xs-12"><span class="pink-head">Your Account<span>&nbsp;</span></span>
<ul>
<li class="first"><a title="Opening and Managing Your Account" href="customer-service">Opening and Managing</a></li>
<li class="last more-links"><a title="more" href="customer-service">More</a></li>
</ul>
</div>
<div class="slide-menu col-lg-3 col-md-3 col-sm-3 col-xs-12"><span class="pink-head">Returns<span>&nbsp;</span></span>
<ul>
<li class="first"><a title="Payment Options" href="customer-service/returns#maneyback">No Hassle Return Policy</a></li>
<li class="last more-links"><a title="more" href="customer-service/returns">More</a></li>
</ul>
</div>
<div class="slide-menu col-lg-3 col-md-3 col-sm-3 col-xs-12"><span class="pink-head">Safety, Security &amp; Privacy<span>&nbsp;</span></span>
<ul>
<li class="first"><a title="Terms and Conditions" href="customer-service/privacy#privacyterms">Terms and Conditions</a></li>
<li><a title="Privacy Policy" href="customer-service/privacy#privacypolicy">Privacy Policy</a></li>
<li class="last more-links"><a title="more" href="customer-service/privacy">More</a></li>
</ul>
</div>
<div class="slide-menu col-lg-3 col-md-3 col-sm-3 col-xs-12 last"><span class="pink-head">Still Need Help?<span>&nbsp;</span></span>
<ul>
<li class="first"><a title="Contact Us By Phone" href="contactus">Contact Us By Phone Or Email</a></li>
<li class="last more-links"><a title="more" href="contactus">More</a></li>
</ul>
</div>
</div>
</div>
</div>',
        'is_active'     => 1,
        'stores'        => array($storeId),
        'sort_order'    => 25
    ),//end Overview
    array(
        'title' => 'Customer Service Payment',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'customer-service/payment',
        'content_heading' => 'Customer Service Payment',
        'stores' => array($storeId),
    'content' => '<div class="customer-service row">{{block type="cms/block" block_id="customer-service-links-limoges"}}
<div class="cs-wrapper  col-lg-10 col-md-10 col-sm-12 col-xm-12">
<ul id="cs-sections" class="cs">
<li id="cs-payment" class="section">
<div class="green-title-sec"><span class="green-title">Payment</span></div>
<div class="step-title static-content">
<div class="cs-wrapper">
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
<li>Debit and Bank Check Cards may reflect deduction of funds immediately upon order.</li>
<li>For your security, your billing name and address must match that of the credit card used for payment. We reserve the
 right to cancel any order that does not match these criteria.</li>
</ul>
<h3 id="couponcodes">Coupon Codes</h3>
<h4>Steps for Redeeming a Coupon Code:</h4>
<ul>
<li>In the shopping cart, prior to checking out, enter the code exactly as it appears in the box next to "If you have a
special offer code, enter it now"</li>
<li>Click "Apply Discount."</li>
<li>If your discount qualifies, it will be displayed in the payment summary. Only one Coupon Code per order will be
accepted.</li>
</ul>
<h3 id="paypal">PayPal</h3>
<p>PayPal is an alternate method for purchasing your order on PersonalizePlanet.com. It enables any individual or
business with an email address to securely send payments online. With a PayPal account, you can choose to pay with your
credit card, debit card, bank account, or PayPal account balance for any purchase you make. Your credit card and bank
numbers are never seen by the seller or merchant. Plus, you\'re 100% protected against unauthorized payments sent from
your account.</p>
<h4>PayPal Conditions</h4>
<ul>
<li>If you select PayPal as your payment option, you will continue through the standard checkout process then
automatically proceed to paypal.com to complete your payment.</li>
<li>Once you have been redirected to paypal.com, you will have 25 minutes to complete the payment before your order
is dropped.</li>
<li>PayPal currently cannot be used by Customers with a Canadian shipping and/or billing address.</li>
</ul>
<p>For more information, visit the <a href="https://www.paypal.com/help" target="_blank">PayPal Help Center</a>.</p>
<h4 id="paypalfaq">PayPal FAQ</h4>
<ul>
<li><span>How does PayPal work?</span><br /> PayPal is used to securely send payments over the Internet. You can choose
to pay from your PayPal account balance, a credit card, debit card, or bank account. To make a PayPal purchase, select
PayPal during checkout and choose your method of payment. Your funds are transferred immediately and securely. PayPal
currently cannot be used by Customers with a Canadian shipping and/or billing address.</li>
<li><span>How do I create a PayPal account? </span><br />To get started, simply fill out the PayPal registration with
your desired account type, country of residence, home address, and login information.</li>
<li><span>How secure is PayPal?</span><br /> PayPal is highly secure and committed to protecting the privacy of its
users. Its industry-leading fraud prevention team is constantly developing state-of-the-art technology to keep your
money and information safe. When you use PayPal to send money, recipients never see your bank account or credit card
 numbers.</li>
<li><span>How do I contact PayPal Customer Service?</span><br />For the fastest response, you may access the
user-friendly <a href="https://www.paypal.com/help" target="_blank">Help Center</a>. Developed by the PayPal Customer
Service team, this Help Center contains a comprehensive information database. Simply type a question into the search
box to receive a complete answer. If you do not find the information you need in the Help Center, PayPal Customer
Service representatives are available to assist you. <a href="https://www.paypal.com/us/cgi-bin/
                                    helpscr?cmd=_help&amp;t=escalateTab" target="_blank">Send an email</a> for a prompt
                                    response or contact PayPal directly by phone:</li>
</ul>
<p>Customer Service: 888-957-9693 (a U.S. telephone number)<br /> 4:00 AM PDT to 10:00 PM PDT Monday through Friday
<br /> 6:00 AM PDT to 8:00 PM PDT Saturday and Sunday<br /> <a href="https://www.paypal.com/signup"
target="_blank">Sign up for PayPal now</a></p>
</div>
</div>
</div>
</li>
</ul>
</div>
</div>',
        'is_active'     => 1,
        'stores'        => array($storeId),
        'sort_order'    => 21
    ),//end of payment
    array(
        'title' => 'Customer Service Ordering',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'customer-service/ordering',
        'content_heading' => 'Customer Service Ordering',
        'stores' => array($storeId),
        'content' => '<div class="customer-service row">
{{block type="cms/block" block_id="customer-service-links-limoges"}}
<div class="cs-wrapper col-lg-10 col-md-10 col-sm-12 col-xm-12">
<ul id="cs-sections" class="cs">
<li id="cs-ordering" class="section">
<div class="green-title-sec"><span class="green-title">Ordering</span></div>
<div id="cs-section-ordering" class="step a-item static-content">
<h3 id="shoppingcart">Shopping Cart</h3>
<p>When you are shopping on PersonalizePlanet.com and add an item to your cart, it is saved in your Shopping Cart. The
Shopping Cart holds products you wish to purchase while you shop, the same way you use a shopping cart in a retail
store. Items you place in your Shopping Cart will remain there until they are purchased or removed.</p>
<h4>Options in the Shopping Cart</h4>
<br />
<ul>
<li><span>Review your order.</span> This ensures the accuracy of items in your Shopping Cart and the quantity selected.
</li>
<li><span>Update the quantity.</span> To do this, simply type in the quantity you wish to purchase and click "Update
Cart."</li>
<li><span>Remove an item from your Shopping Cart.</span> To remove an item, click on the trashcan icon or change the
quantity to zero and click "Update Cart."</li>
<li><span>Continue shopping.</span> If you wish to continue shopping on PersonalizePlanet.com, you can use the Shopping
Cart to store items you wish to purchase. Click on "Continue Shopping" to search the site for additional items. At any
time during your shopping experience, you can return to your Shopping Cart by clicking on "View Cart."</li>
<li><span>Proceed to Secure Checkout.</span> When you are ready to purchase your item(s), click on "Secure Checkout."
Our checkout process is fast, easy and secure. For more information on our secure shopping guarantee,
<a href="/customer-service/privacy">click here</a>.</li>
</ul>
<h3 id="checkout">Checkout</h3>
<p>You are now ready to complete your purchase.</p>
<h4>Returning Customers:</h4>
<ul>
<li><span>Sign In.</span> First, sign in using your email address and password. If you forgot your password,
<a href="/customer/account/forgotpassword/">click here</a>.
<ul>
<li>Your email address serves as a convenient way to receive important information about your order and serves as your
PersonalizePlanet.com account identification.</li>
<li>Your account stores information such as order history and your billing and shipping address. It also offers you the
ability to track your order(s). The password assures that only you can have access to your account information.</li>
</ul>
</li>
<li><span>Select or Add Shipping Address.</span>Next, select or add the shipping address where you would like your order
 sent.
<ul>
<li>To select an address, click the drop down box under &ldquo;Choose a Shipping Method.&rdquo; To add an address, click
 on &ldquo;Add a New Shipping Address.&rdquo;</li>
</ul>
</li>
<li><span>Select Shipping Options.</span> Select a shipping method for your item(s) and click "Continue Checkout."</li>
<li><span>Select and Enter Payment Options.</span> At this time, you must enter your payment method. For more
information on payment, <a href="/customer-service/payment"> click here</a>.</li>
<li><span>Order Review.</span> On the Order Review page, you can review your entire order, including your shipping
address, shipping method(s), and billing information, all of which can be edited at this point. You will also see your
applied discounts and promotions, total charges and gift options. To complete your purchase, click "Send My Order."</li>
<li><span>Receipt Page.</span> After you complete the checkout process, the Receipt page provides you with your order
number. You will need to save this order number for your records. You will need this information for all references to
your order. <em>Please note: We cannot change or cancel an order once it has been placed. For more information about
Changing or Canceling an Order, <a href="/customer-service/your-order">click here</a>.</em></li>
</ul>
<h4>New Customers:</h4>
<ul>
<li><span>Sign In.</span> First, sign in using your email address and password. If you forgot your password,
<a href="/customer/account/forgotpassword/">click here</a>.
<ul>
<li>Your email address serves as a convenient way to receive important information about your order and serves as your
PersonalizePlanet.com account identification.</li>
<li>Your account stores information such as order history and your billing and shipping address. It also offers you the
ability to track your order(s). The password assures that only you can have access to your account information.</li>
</ul>
</li>
<li><span>Enter Shipping/Billing Address.</span>Enter your shipping and billing address in the fields below and indicate
 where you would like your order sent. Click "Continue Checkout" when complete.
<ul>
<li>Your name and billing address must be entered exactly as they appear on your credit card statement to avoid any
delay in the authorization process.</li>
</ul>
</li>
<li><span>Select Shipping Options.</span> Select a shipping method for your item(s) and click "Continue Checkout."</li>
<li><span>Select and Enter Payment Options.</span> At this time, you must enter your payment method. For more
information on payment, <a href="/customer-service/payment"> click here</a>.</li>
<li><span>Order Review.</span> On the Order Review page, you can review your entire order, including your shipping
address, shipping method(s), and billing information, all of which can be edited at this point. You will also see your
applied discounts and promotions, total charges and gift options. To complete your purchase, click "Send My Order."</li>
<li><span>Receipt Page.</span> After you complete the checkout process, the Receipt page provides you with your order
number. You will need to save this order number for your records. You will need this information for all references to
your order. <em>Please note: We cannot change or cancel an order once it has been placed. For more information about
Changing or Canceling an Order, <a href="/customer-service/your-order">click here</a>.</em></li>
</ul>
<h3 id="giftwrap">Gift Wrap/Gift Message</h3>
<p>For your shopping convenience, some items can be gift-wrapped for a cost of $3.99 per item. If you would like to have
 your item(s) gift-wrapped, simply click on the Gift-Wrap option during Checkout.</p>
<h4>Steps to Selecting Gift-Wrap:</h4>
<ul>
<li>When you are finished, click "Continue Checkout."</li>
<li>The gift-wrap charge will be noted on the following screen.</li>
</ul>
<h4>Please note:</h4>
<ul>
<li>Gift-wrapping may not be available for some items due to their size. If you are purchasing an item that can be
gift-wrapped, the Gift-Wrap option will be noted on the Product page, in your Cart, and the Review Your Order page.</li>
<li>The charge for gift-wrap and a personalized card is $4.95 per item and is not refundable.</li>
<li>If you do not want your item wrapped, do not select the Gift-Wrap option.</li>
</ul>
<h3 id="couponcodes">Coupon Codes</h3>
<h4>Steps for Redeeming a Coupon Code:</h4>
<ul>
<li>In the Shopping Cart, prior to checking out, enter the code exactly as it appears, in the box next to "If you have a
 special offer code, enter it now." <span>Codes are case sensitive.</span></li>
<li>Click "Apply Discount."</li>
<li>If your discount qualifies, it will be displayed in the payment summary. <span>Only one Coupon Code per order will
be accepted.</span></li>
</ul>
<h3 id="changingcancel">Changing or Canceling Your Order</h3>
<p>After you have clicked "Send My Order", your order begins to process and <span>you cannot cancel or change your
order.</span> * Our system is designed to fill orders as quickly as possible. Once you receive your order in the mail,
simply return any items you do not want by following our <a href="/customer-service/returns">Return Instructions</a>.
Please note: personalized items may not be returned unless damaged or defective.</p>
</div>
</li>
</ul>
</div>
</div>',
        'is_active'     => 1,
        'stores'        => array($storeId),
        'sort_order'    => 20
    ),//end customer-service-order
    array(
        'title' => 'Customer Service Your Order',
        'root_template' => 'one_column',
        'meta_keywords' => '',
        'meta_description' => '',
        'identifier' => 'customer-service/your-order',
        'content_heading' => 'Customer Service Your Order',
        'stores' => array($storeId),
        'content' => '<div class="customer-service row">
{{block type="cms/block" block_id="customer-service-links-limoges"}}
<div class="cs-wrapper col-lg-10 col-md-10 col-sm-12 col-xm-12">
<ul id="cs-sections" class="cs">
<li id="cs-order" class="section">
<div class="green-title-sec"><span class="green-title">Your Order</span></div>
<div id="cs-section-order" class="step a-item static-content">
<h3 id="emailabout">E-mails About Your Order</h3>
<p>You will receive e-mails about your order after it has been placed. Below are examples of e-mails you might receive:
</p>
<ul>
<li>Order Confirmation. This e-mail confirms that we have received your order. It includes your order number. Keep this
e-mail for your records.</li>
<li>Shipment Confirmation. This e-mail confirms that your order or part of your order has shipped. You may receive
multiple e-mails depending on the items you selected, or if you ordered multiple items and they were shipped separately.
 The arrival time of your order depends on the shipping method selected, item selected, and your shipping location.</li>
</ul>
<h3 id="trackingyourorder">Tracking Your Order</h3>
<p>At our warehouse, tracking numbers are assigned to packages and are generally available within 24 hours. However, it
may take up to 48 hours or longer before the package is checked into the carrier\'s tracking system. Therefore, even
though your package has already shipped from our warehouse and is on its way to you, the carrier may not be able to
provide any information about your package for up to 48 hours or more. Please allow an additional 2-4 business days for
custom printed items to be created prior to shipping.</p>
<p>When you click on &ldquo;Track Order&rdquo;, you will be prompted to log in with your e-mail address and order
number. An order summary page will provide you with detailed information about your current order or past orders. After
your order is shipped, your tracking number, if available, will be displayed. Depending on the shipping company, you can
 click on the tracking number to view the delivery status of your order. A shipping company may not have the ability to
  track a number for up to 24 business hours.</p>
<p>Orders placed on PersonalizePlanet.com may be delivered by one of several different carriers and shipping methods.
Therefore, tracking availability may vary depending on the type of item you purchased, the shipping method you selected
during Checkout, and the carrier that is delivering your item(s).</p>
<h3 id="changingcancelorder">Changing or Canceling Your Order</h3>
<p>After you have clicked "Send My Order", your order begins to process and you cannot cancel or change your order. Our
system is designed to fill orders as quickly as possible. Once you receive your order in the mail, simply return any
items you do not want by following our <a href="{{store direct_url=customer-service/returns}}"> Return Instructions</a>.
 Please note:
personalized items may not be returned unless damaged or defective.</p>
</div>
</li>
</ul>
</div>
</div>',
        'is_active'     => 1,
        'stores'        => array($storeId),
        'sort_order'    => 25
    ),//end your order
        array(
            'title' => 'Customer Service Our Products',
            'root_template' => 'one_column',
            'meta_keywords' => '',
            'meta_description' => '',
            'identifier' => 'customer-service/our-products',
            'content_heading' => 'Customer Service Our Products',
            'stores' => array($storeId),
            'content' => '<div class="customer-service row">
{{block type="cms/block" block_id="customer-service-links-limoges"}}
<div class="cs-wrapper col-lg-10 col-md-10 col-sm-12 col-xm-12">
<ul id="cs-sections" class="cs">
<li id="cs-order" class="section">
<div class="green-title-sec"><span class="green-title">Our Products</span></div>
<div id="cs-section-order" class="step a-item static-content">
Coming Soon...
</div>
</div>',
            'is_active'     => 1,
            'stores'        => array($storeId),
            'sort_order'    => 25
        ),//end our products
);
foreach ($cmsPages as $data) {
    Mage::getModel('cms/page')->setData($data)->save();
}


$customerLinks = array(
    'title' => 'Customer Service Links - Limoges',
    'identifier' => 'customer-service-links-limoges',
    'content' => '<div class="customer-head">Customer Service</div>
<div class="col-lg-2 col-md-2 col-sm-12 col-xm-12 ">
<div class="customer-header-container mobile">
<div class="customer-header"><img src="{{skin url=\'images/bkg_cust_service_bg.png\'}}" alt="Customer Service" /></div>
<div class="customer-service-content"><a class="email-us" href="mailto:info@tystoybox.com">Email
us</a></div>
</div>
<div class="block-content">
<ul class="customer-links">
<li><a id="customer-service" title="Overview" href="{{store direct_url=customer-service}}"> Overview </a></li>
<li><a id="ordering" title="Ordering" href="{{store direct_url=customer-service/ordering}}"> Ordering </a></li>
<li><a id="payment" title="Payment" href="{{store direct_url=customer-service/payment}}"> Payment </a></li>
<li><a id="shipping" title="Shipping" href="{{store direct_url=customer-service/shipping}}"> Shipping </a></li>
<li><a id="your-order" title="Your Order" href="{{store direct_url=customer-service/your-order}}"> Your Order </a></li>
<li><a id="returns" title="Returns" href="{{store direct_url=customer-service/returns}}"> Returns </a></li>
<li><a id="our-products" title="Our Products" href="{{store direct_url=customer-service/our-products}}"> Our Products
</a>
</li>
<li class="safety-privacy"><a id="privacy" title="Safety, Security &amp; Privacy"
href="{{store direct_url=customer-service/privacy}}"> Safety, Security &amp; Privacy </a></li>
<li class="last"><a id="contactus" title="Contact Us" href="{{store direct_url=contactus}}"> Contact Us </a></li>
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
    ->setTitle($customerLinks['title'])
    ->setIdentifier($customerLinks['identifier'])
    ->setStores(array($storeId))
    ->setContent($customerLinks['content'])
    ->setIsActive($customerLinks['is_active'])
    ->save();