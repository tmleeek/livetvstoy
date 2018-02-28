<?php
//array for cms pages
$cmsPages = array(
    array(
        'title'         => 'Terms And Conditions',
        'root_template' => 'two_columns_left',
        'identifier'    => 'terms-and-conditions',
        'content'       => '
            <div class="std"><h1>Privacy Policy</h1>

<p>We are committed to making your online transaction safe, secure and
confidential to the highest standards available. Following are some relevant
facts about how we treat your information</p>

<p>We do not share or sell data. We collect billing and shipping information in
order to process your order and deliver it to its destination. This information
is used only by Ty’s Toy Box and the couriers we use for the purposes of
fulfilling your order. Personal information collected through this site is
used only for our internal purposes. We will not sell your personal information,
or authorize third parties to use such information for any commercial purpose,
without your prior consent.</p>

<p>
<b>Personal Information.</b>
We do not automatically collect personal information such as your name, address,
telephone number or email address. Such information will be collected only if
you provide it to us voluntarily in order to order products, enter a contest or
promotion, or sign up to receive our eUpdate newsletter. We dont require that
any personal information be provided in order to view our website.
</p>

<p><b>Information collected by TysToyBox.com is used to:</b></p>

    <ul>
        <li>allow you to order products, register for eUpdate and participate in
        special promotions;</li>
        <li>improve the site by observing products that are most popular;
        and</li>
        <li>personalize the delivery of information and recommend products to
        you.</li>
    </ul>

<p>Your personal information may also be used to create statistics about
TysToyBox.com that we use to measure our performance. These statistics do not
contain any information that could identify you personally. TysToyBox.com does
not sell your personal information to any organization for any purpose.</p>

<p><b>We do send email.</b> We will send you email at regular intervals to
ensure that you are always kept up to date on the status of your order.
From time to time we send additional eflyers out to inform our customers of
specials or promotions that they might be interested in. You can choose not to
receive the eflyers by selecting this option in your Ty’s Toy Box account.</p>

<p><b>We offer Optional Registration.</b> We recommend that you register as a
customer in order to take advantage of the many benefits of maintaining an
account. Unlike many other sites, registration is not necessary for placing
orders. We offer a "buy now" option, which only requires the basic information
required to process your order. Please note that this will limit access to your
order history and an address book.</p>

<p><b>Credit Card info protected by 128bit SSL Encryption.</b> The security of
your personal information is paramount. All Credit Card Transactions are
completed using a 128 Bit SSL Encrypted Secure Transaction. As we transmit the
information to the Banks Secure SSL Server, they require a 128-bit transaction
and will not process a transaction without one. Even though 40 or 56 Bit
transactions are very secure, our Banks insistence on 128 Bit SSL means that
there is never any chance of your information every being intercepted or
decoded.</p>

<p><b>Password Protected Accounts.</b> When you decide to register an account
in order to take advantage of the address book, quick pay and order restoration
features, your account information is secured by password protection assigned
and maintained by you. If you forget your password, this information is only
released to your specific email address recorded in your account profile.
It is not given out in any other situation without identity verification
provided by you. The account information contains the billing and shipping
addresses, phone numbers and email address provided and maintained by you as
well as your order history. Credit card information is not included in this
file.</p>

<p><b>We use Cookies.</b> Like most advanced sites these days, TysToyBox.com
uses cookies to make your shopping experience the best it can be. No personal
or credit card information is stored in these cookies and there is no risk to
your privacy or security caused by this action. It is simply an identifier
transferred to your system from ours that helps us manage your transaction.
It remembers items in your basket and currency selections. You can choose not
to use cookies if you like. We do not require cookies to process your order
however the lack of the memory feature may cause some frustration in certain
situations.</p>

<p><b>Getting Your Consent.</b>&nbsp;By using TysToyBox.com you are consenting
to the collection and use of personal and account information by Tys Toy Box,
for the purposes set out in this policy. </p>

<p><b>Changes to this Privacy Policy.</b>&nbsp;All changes will be posted on
TysToyBox.com promptly to inform visitors of what information is collected,
how it is used, and under what circumstances it may be disclosed.</p>

</div>
        ',
        'is_active'     => 1,
        'stores'        => array(0),
        'sort_order'    => 0
    ),
);

/**
 * Insert default and system pages
 */
foreach ($cmsPages as $data) {
    Mage::getModel('cms/page')->setData($data)->save();
}