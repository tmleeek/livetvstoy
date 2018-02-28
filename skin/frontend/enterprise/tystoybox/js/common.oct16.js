function toggleSlider() {
    jQuery(document).on('click', '.slide-icon', function () {
        if (jQuery(this).next(".slide-toggle").is(':hidden')) {
            jQuery(".slide-toggle").slideUp();
            jQuery(".slide-icon").not(this).removeClass('t-expand');
            jQuery(this).next(".slide-toggle").slideDown();
            jQuery(this).addClass('t-expand');
            
            var isIPhone = function () {
                return (/iPhone/i).test(navigator.userAgent);
            };
            var deviceName = isIPhone() ? "iphone" : "desktop";
            if (deviceName == 'iphone') {
                var hasClass1 = jQuery(this).hasClass('user-img');
                if (hasClass1 == false) {
                    jQuery('.zoomContainer').hide();
                } else {
                    jQuery('.zoomContainer').show();
                }
            }
        } else {
            jQuery(this).next(".slide-toggle").slideUp();
            jQuery(this).removeClass('t-expand');
			var isIPhone = function () {
                return (/iPhone/i).test(navigator.userAgent);
            };
            var deviceName = isIPhone() ? "iphone" : "desktop";
            if (deviceName == 'iphone') {
                jQuery('.zoomContainer').show();
            }
        }
    });

}

//For closing all the open menu bar in mobile view
function closeTab() {
    jQuery('.top-cart .block-title').click(function () {

        if (jQuery(window).width() < 991) {

            var deviceName = isIPad() ? "ipad" : "desktop";
            if (deviceName == "ipad") {
                jQuery('.zoomContainer').hide();
            }

            jQuery('.slide-toggle').slideUp();
            jQuery('.slide-icon.t-expand').removeClass('t-expand');
        }
    });
    jQuery('.slide-icon').click(function () {
        if (jQuery(window).width() < 991) {
            var deviceName = isIPad() ? "ipad" : "desktop";
            if (deviceName == "ipad") {
                jQuery('.zoomContainer').show();
            }

            jQuery('.top-cart .block-content').slideUp();
            jQuery('.top-cart .block-title').removeClass('expanded');
        }
    });
}

//function for top nav
function callTopNavigationFunction() {    //inner navigation in mobile view

        jQuery('#nav li a').click(function () {
			if (jQuery(window).width() < 991) {
				if (jQuery(this).hasClass('t-expand') == true) {
					jQuery('#nav li a').removeClass('t-expand');
					jQuery('#nav li a').next('div.top-div').removeClass('nav-toggle');
					
				} else {
					jQuery('#nav li a').removeClass('t-expand');
					jQuery('#nav li a').next('div.top-div').removeClass('nav-toggle');
					jQuery('#nav span.sub-menu-title').removeClass('t-expand');
					jQuery('#nav span.sub-menu-title').next('ul').removeClass('nav-toggle');
					jQuery(this).addClass('t-expand');
					jQuery(this).next().addClass('nav-toggle');
					
				}
			}
        });
        jQuery('#nav .sub-menu span.sub-menu-title').click(function () {
			if (jQuery(window).width() < 991) {
				if (jQuery(this).hasClass('t-expand') == true) {
					jQuery('#nav span.sub-menu-title').removeClass('t-expand');
					jQuery('#nav span.sub-menu-title').next('ul').removeClass('nav-toggle');
					
	
				} else {
					jQuery('#nav span.sub-menu-title').removeClass('t-expand');
					jQuery('#nav span.sub-menu-title').next('ul').removeClass('nav-toggle');
					jQuery(this).addClass('t-expand');
					jQuery(this).next().addClass('nav-toggle');
					
				}
			}
        });
}

//for sliding up when click on search.
if (jQuery(window).width() < 991) {
    jQuery(document).on('click', '#search', function () {
        jQuery(".slide-toggle").slideUp();
        jQuery(".slide-icon").removeClass('t-expand')
    });
}

// For top navigation tablet ipad view.
jQuery(window).on('resize', function () {
    if (jQuery(window).width() > 1000) {
        jQuery(".slide-toggle").css('display', 'block')
    } else if (jQuery(window).width() <= 991 && jQuery(window).width() >= 768) {
        jQuery(".slide-toggle").css('display', 'none')
        jQuery(".slide-icon").removeClass('t-expand')
    }
    (jQuery(window).width() > 1000) ? jQuery(".filter-toggle").css('display', 'block') : jQuery(".filter-toggle").css('display', 'none'); // for filter sidebar
    //For filter layered navigation reset
    if (jQuery(window).width() > 991) {
        jQuery('.filter-toggle').css('top', 'auto');
    }

});
//For footer
jQuery(window).on('load', function () {
    //For top navigation js call
    topmenuCall();
    //tooltip for page
    showtooltip();
    closeTab();
	callTopNavigationFunction();
    if (jQuery(window).width() < 767) {
        jQuery(".slide-menu > span").addClass('slide-icon');
        jQuery(".slide-menu > ul").addClass('slide-toggle');
        toggleSlider();
    } else {
        jQuery(".slide-menu > span").removeClass('slide-icon');
        jQuery(".slide-menu > ul").removeClass('slide-toggle');
        jQuery(".slide-menu > ul").removeAttr('style');
        toggleSlider();
    }
});
//For footer menu resize
jQuery(window).on('resize', function () {
    jQuery('.slide-menu .slide-icon').removeClass('t-expand');
});
//FUNCTION FOR CHECKING DEVICE IS APPLE IPHONE OR IPAD
function isTouch() {
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
        return true;
    } else {
        return false;
    }
}
//document ready starts
jQuery(document).ready(function () {
    var pathname = window.location.pathname;
    var res = pathname.split("/");
    var item = res.pop();
    var htmlItem = item.split(".");
    var lastItem = htmlItem[0];
    var myPages = ["ordering", "payment", "shipping", "your-order", "returns", "privacy", "contactus", "customer-service", "our-products"];
    var pageLength = myPages.length;
    for (var i = 0; i < pageLength; i++) {
        if (myPages[i] == lastItem) {
            jQuery('ul.customer-links').children('li').removeClass('active');
            jQuery('a#' + lastItem).parent('li').addClass('active');
        } else if (lastItem == '') {
            jQuery('ul.customer-links').children('li').removeClass('active');
            jQuery('a#contactus').parent('li').addClass('active');
        }
    }
    if (isTouch()) {
        jQuery(document).on('touchstart', '.filter-col-block', function () {
            jQuery(".filter-toggle").slideToggle();
			jQuery(this).toggleClass('t-active');
            if (jQuery(window).width() < 991) {
                ttop = jQuery(this).offset().top + 30;
                jQuery('.filter-toggle').css('top', ttop);
            }
        });
    } else {
        jQuery(document).on('click', '.filter-col-block', function () {
            jQuery(".filter-toggle").slideToggle();
			jQuery(this).toggleClass('t-active');
            if (jQuery(window).width() < 991) {
                ttop = jQuery(this).offset().top + 30;
                jQuery('.filter-toggle').css('top', ttop);
            }
        });
    }
    jQuery(document).on('click', '.narrow-by-list dt', function () {
        jQuery(this).toggleClass("t-expand");
        jQuery(this).next(".narrow-by-list dd").slideToggle();
    });

    jQuery("wrapper").mouseup(function (e) {
        if (e.target.className != 'slide-icon') {
            if (jQuery('.slide-icon').next('.slide-toggle').css('display') == 'block') {
                jQuery('.slide-icon').next('.slide-toggle').slideUp('slide-toggle');
                e.stopPropagation();
            }
        }
    });
    jQuery('.number-of-records').dropkick();
    createCustomCheckboxesAndRadio();
    var winWidth, winHeight, popleft, poptop;
    winWidth = jQuery(window).width() / 2;
    winHeight = jQuery(window).height() / 2;
    popleft = winWidth - 133;
    poptop = winHeight - 123;
    jQuery(document).on('click', '.split-button li.new, li.create-new-wish, #wishlist-create-button', function () {
        jQuery('#wishlist-name').focus();
        createCustomCheckboxesAndRadio();
        jQuery('.popup-block').css('left', popleft);
        jQuery('.popup-block').css('top', poptop);
    });
    jQuery(document).on('click', '#product_description', function () {
        var isIPad = function () {
            return (/ipad/i).test(navigator.userAgent);
        };
        var deviceName = isIPad() ? "ipad" : "desktop";
        if (deviceName == 'ipad') {
            ddHeight = jQuery('#product_description').next('dd').css('height');
            jQuery('#product-review-form-seperator').css('margin-top', ddHeight);
        }
    });
    jQuery(document).on('click', 'li.avatar-li a', function () {
        var imageKey = jQuery(this).attr('rel');
        jQuery('li.avatar-li').removeClass('selected-avatar');
        jQuery(this).parent('li').addClass('selected-avatar');
        jQuery('#poptropica-avatar-id').val(imageKey);
        var imageName = jQuery(this).children("img").attr("title");
        jQuery("#avatar-name-div").text(imageName);
        jQuery("#avatar-name-id").val(imageName);
    });
    if(jQuery(window).width() <= 1024){
        jQuery("input.qty").removeAttr('type');
        jQuery("input.qty").attr('type','number');
        jQuery("input.qty").attr('min','1');
    }
    jQuery("#bundleProduct select").dropkick();
   if (jQuery(window).width() <=1024) {
	   jQuery(window).scroll(function () {
        if (jQuery(this).scrollTop() > 100) {
            jQuery('#scroll-to-top-id').fadeIn();
        } else {
            jQuery('#scroll-to-top-id').fadeOut();
        }

    });
   }
    jQuery(document).on('click', '#scroll-to-top-id', function () {
        jQuery("html, body").animate({ scrollTop: 0 }, "slow");
        return false;
    });
if(isTouch()){
	jQuery("#nav li.level-top > a").removeAttr("href");
}
});//document ready end

function stickblock(){
	//party planner
if (jQuery(window).width() < 991) {
		 jQuery(window).scroll(function(e) {
           	 var window_top = jQuery('html,body').scrollTop();
			 var sticky_block = jQuery('#sticky-block').offset().top;
			if (window_top > sticky_block) {
				jQuery('.bundle-summary-wrapper').addClass('stick');
			 } else {
				jQuery('.bundle-summary-wrapper').removeClass('stick');
			}
        });

		jQuery('.overbundle-summary').click(function(){
			var overbundlesummary = jQuery('.bundle-summary-wrapper');
			if (overbundlesummary.hasClass('active')){
			 	overbundlesummary.animate({"left":"-205px"}, "slow").removeClass('active');
			} else {
				overbundlesummary.animate({"left":"0px"}, "slow").addClass('active');
			}

		});
 }
}
/**
 * show/hide top menu js
 */
function topmenuCall() {
    //menu js
    jQuery(document).on({
        mouseenter: function () {
            // show div and addclass
            showdiv = jQuery(this).find("a").attr("setshow");
            jQuery("." + showdiv).show();
            jQuery("." + showdiv).addClass("shown-sub");
            jQuery(this).addClass("over");
        },
        mouseleave: function () {
            jQuery(this).find(".top-div").hide();
            jQuery(this).find(".top-div").removeClass("shown-sub");
            jQuery(this).removeClass("over");
        }
    }, '.level0');
    closeTab();
}


/**
 * show/hide tooltip
 */


function showtooltip() {
    jQuery(document).on({
        mouseenter: function () {
            jQuery(this).find(".show-tooltip").show('fast');
        },
        mouseleave: function () {
            jQuery(this).find(".show-tooltip").hide('fast');
        }
    }, '.tooltip-active');
}

function showWishlistOptions() {
    jQuery(".change").on("click", function () {
        jQuery(".list-container").slideToggle('fast');
    });
}

//show review form in product detail page review tab
function showFormBlind(divName, timeInt) {
    if (typeof timeInt == 'undefined') {
        timeInt = 0;
        jQuery('#rating-form').hide();
    } else {
        jQuery('#rating-form').show();
    }
    if (jQuery(window).width() < 767) {
        if (timeInt) {
            Effect.BlindDown(divName, {
                duration: timeInt
            });
        } else {
            jQuery('#product_review').addClass('active');
            jQuery('#product_review').next().show('fast');
            //jQuery(window).scrollTop(jQuery('#review-rating-div').offset().top);
            jQuery('html, body').animate({
                scrollTop: jQuery("#review-rating-div").offset().top
            }, 1000);
        }
    } else {
        var isIPad = function () {
            return (/ipad/i).test(navigator.userAgent);
        };
        var deviceName = isIPad() ? "ipad" : "desktop";
        if (deviceName == 'ipad') {
            jQuery('#collateral-tabs').children('dt').removeClass('active');
            jQuery('#collateral-tabs').children('dd').hide();
            jQuery('#product_review').addClass('active');
            ddHeight = jQuery('#product_review').next('dd').css('height');
            jQuery('#product_review').next('dd').show();
            jQuery('#product-review-form-seperator').css('margin-top', ddHeight);
        } else {
            jQuery("#product_review").click();
        }
        Effect.BlindDown(divName, {
            duration: timeInt,
            afterFinish: function () {
                if (deviceName == 'ipad') {

                } else {
                    jQuery("#product_review").click();
                }
                //jQuery(window).scrollTop(jQuery('#review-rating-div').offset().top);
                jQuery('html, body').animate({
                    scrollTop: jQuery("#review-rating-div").offset().top
                }, 1000);
            }
        });
    }
}

// Override the update function (calls when selecting the country) to update regions dropdown
RegionUpdater.prototype.update = function () {
    //    if (countryCode) {
    //        this.countryEl.value = countryCode;
    //    }

    if (this.regions[this.countryEl.value]) {
        var i, option, region, def;

        def = this.regionSelectEl.getAttribute('defaultValue');
        if (this.regionTextEl) {
            if (!def) {
                def = this.regionTextEl.value.toLowerCase();
            }
            this.regionTextEl.value = '';
        }

        this.regionSelectEl.options.length = 1;
        for (regionId in this.regions[this.countryEl.value]) {
            region = this.regions[this.countryEl.value][regionId];

            option = document.createElement('OPTION');
            option.value = regionId;
            option.text = region.name.stripTags();
            option.title = region.name;

            if (this.regionSelectEl.options.add) {
                this.regionSelectEl.options.add(option);
            } else {
                this.regionSelectEl.appendChild(option);
            }

            if (regionId == def || (region.name && region.name.toLowerCase() == def) ||
                (region.name && region.code.toLowerCase() == def)
            ) {
                this.regionSelectEl.value = regionId;
            }
        }

        if (this.disableAction == 'hide') {
            if (this.regionTextEl) {
                this.regionTextEl.style.display = 'none';
            }

            this.regionSelectEl.style.display = '';
        } else if (this.disableAction == 'disable') {
            if (this.regionTextEl) {
                this.regionTextEl.disabled = true;
            }
            this.regionSelectEl.disabled = false;
        }
        this.setMarkDisplay(this.regionSelectEl, true);
    } else {
        this.regionSelectEl.options.length = 1;
        if (this.disableAction == 'hide') {
            if (this.regionTextEl) {
                this.regionTextEl.style.display = '';
            }
            this.regionSelectEl.style.display = 'none';
            Validation.reset(this.regionSelectEl);
        } else if (this.disableAction == 'disable') {
            if (this.regionTextEl) {
                this.regionTextEl.disabled = false;
            }
            this.regionSelectEl.disabled = true;
        } else if (this.disableAction == 'nullify') {
            this.regionSelectEl.options.length = 1;
            this.regionSelectEl.value = '';
            this.regionSelectEl.selectedIndex = 0;
            this.lastCountryId = '';
        }
        this.setMarkDisplay(this.regionSelectEl, false);
    }

    this._checkRegionRequired();
    // Make Zip and its label required/optional
    var zipUpdater = new ZipUpdater(this.countryEl.value, this.zipEl);
    zipUpdater.update();
};

function showDrop(showId) {
    jQuery(showId).slideToggle('fast');
}

function getallurl(name, url) {
    if (!url) {
        url = window.location.href;
    }
    var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(url);
    if (!results) {
        return 0;
    }
    return results[1] || 0;
}


function createCustomCheckboxesAndRadio() {
    //here I style checkbox with iCheck
    jQuery(":checkbox").each(function () {
        //         var self = jQuery(this),
        //             label = self.next(),
        //             label_text = label.text();
        //
        //         label.remove();
        jQuery(this).iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue'
            //insert: '<div class="icheck_line-icon"></div>' + label_text
        });
    });
    //here I style radio with iCheck
    jQuery(":radio").each(function () {
        //         var self = jQuery(this),
        //             label = self.next(),
        //             label_text = label.text();
        //
        //         label.remove();
        jQuery(this).iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue'
            //insert: '<div class="icheck_line-icon"></div>' + label_text
        });
    });
}

function callSameAsBillingAddressFunction() {
    jQuery("input[name='shipping[same_as_billing]']").on('ifChecked', function (event) {

        shipping.setSameAsBilling(this.checked);

        //changing contents of shipping address dropdown
        //var selectedValue = jQuery('select#shipping-address-select').find(":selected").val();
        var selectedText = jQuery('select#shipping-address-select').find(":selected").text();
        jQuery('select#shipping-address-select').parent('div.dk_container').children('a.dk_label').text(selectedText);
        jQuery('select#shipping-address-select').prev('div.dk_options').children('ul').children('li').removeClass('dk_option_current');

        //getting values from billing address select box and updating shipping select
        var billingCountryVal = jQuery("select[name='billing[country_id]']").parent('div.dk_container').children('a.dk_label').text();
        var billingRegionVal = jQuery("select[name='billing[region_id]']").parent('div.dk_container').children('a.dk_label').text();

        jQuery("select[name='shipping[region_id]']").parent('div.dk_container').children('a.dk_label').text(billingRegionVal);
        jQuery("select[name='shipping[country_id]']").parent('div.dk_container').children('a.dk_label').text(billingCountryVal);
    });
}

//function to replace entities with special characters
function escapeHtml(unsafe) {
    return unsafe
        .replace("&amp;", "&")
        .replace("&lt;", "<")
        .replace("&gt;", ">")
        .replace("&#039;", "'");
}
jQuery(window).on('resize', function () {
    if (jQuery(window).width() < 767) {
        winWidth = jQuery(window).width() / 2;
        winHeight = jQuery(window).height() / 2;
        popleft = winWidth - 133;
        poptop = winHeight - 123;
        jQuery(document).on('click', '.split-button li.new, #wishlist-create-button', function () {
            jQuery('.popup-block').css('left', popleft);
            jQuery('.popup-block').css('top', poptop);
        });
        jQuery('.popup-block').css('left', popleft);
        jQuery('.popup-block').css('top', poptop);
    }
});

// call Offer popup
function getTopBannerDetails() {
    jQuery.fancybox({
        /*width': '90%',
'height': 'auto',*/
        'padding': '0px',
        'autoScale': true,
        'transitionIn': 'fade',
        'transitionOut': 'fade',
        'wrapCSS': 'toppopup',
        'showCloseButton': true,
        'type': 'inline',
        'href': '#bannerPopuplink'
    });
    jQuery("#bannerPopuplink").trigger('click');
}

// Extend the 'validate-email' validation rule to change the message.
Validation.addAllThese([
    //['validate-email', 'Please enter a valid email address. For example johndoe@domain.com.', function (v) {
    ['validate-email', 'Please enter a valid email-id. eg. johndoe@domain.com.',
        function (v) {
            //return Validation.get('IsEmpty').test(v) || /\w{1,}[@][\w\-]{1,}([.]([\w\-]{1,})){1,3}$/.test(v)
            //return Validation.get('IsEmpty').test(v) || /^[\!\#$%\*/?|\^\{\}`~&\'\+\-=_a-z0-9][\!\#$%\*/?|\^\{\}`~&\'\+\-=_a-z0-9\.]{1,30}[\!\#$%\*/?|\^\{\}`~&\'\+\-=_a-z0-9]@([a-z0-9_-]{1,30}\.){1,5}[a-z]{2,4}$/i.test(v)
            return Validation.get('IsEmpty').test(v) || /^([a-z0-9,!\#\$%&'\*\+\/=\?\^_`\{\|\}~-]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z0-9,!\#\$%&'\*\+\/=\?\^_`\{\|\}~-]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*@([a-z0-9-]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z0-9-]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*\.(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]){2,})$/i.test(v)
        }
    ],
    ['validate-firstname-lastname', 'Please enter name in Firstname Lastname format. Only alphabets allowed.',
        function (v) {
            v = v.trim();
            var res = v.split(" ");
            //alert(Validation.get('IsEmpty').test(res[0]));
            //alert(/^[a-zA-Z]+$/.test(res[0]));
            if (Validation.get('IsEmpty').test(res[0]) || Validation.get('IsEmpty').test(res[1]) ||
               (/^[a-zA-Z]+$/.test(res[0]) != true) ||  (/^[a-zA-Z]+$/.test(res[1]) != true)) {
                return false;
            } else {
                return true;
            }
        }
    ],
    ['validate-phone-number', 'Please enter a valid phone number. For example 123-456-7890 or 1234567890.',
        function (v) {
            return Validation.get('IsEmpty').test(v) || /^(-|\s)?\d{3}(-|\s)?\d{3}(-|\s)\d{4}$/.test(v) || /^[0-9]{10}$/.test(v);
        }
    ]
]);

var isIPad = function () {
    return (/iPad/i).test(navigator.userAgent);
};
<!-- Show PBS Kids Leaving Pop Up-->
function showLeavingPopUp(url, textToDisplay) {
    jQuery('#continue-to-apps').text(textToDisplay);
    jQuery('div.goback-cont').show();
	jQuery('.overlay-pop').show();
    jQuery('a#continue-to-apps').attr('rel', url);
}
function closeAlertBox() {
    jQuery('div.goback-cont').hide();
	jQuery('.overlay-pop').hide();
}
function openInNewTab() {
    var relUrl =  jQuery('a#continue-to-apps').attr('rel');
    closeAlertBox();
    window.open(relUrl);
}
function updatePartyTotal()
{
    var currentTotal = jQuery('div#productView span.full-product-price').text();
    jQuery('span.full-product-price').children('span.price').text(currentTotal);
    if (checkHiddenFields()) {
        jQuery('#bundle_inactive').removeClass('inactive');
    }
}
jQuery( window ).load(function() {
    updatePartyTotal();
    var childName  = jQuery('#childs_name').val();
    if (childName == '') {
        childName = 'No name entered';
    }
    jQuery('#child-name-div').text(childName);
});
jQuery(document).on('click', 'a.goBtn', function () {
    validateGuestForm();
});
function validateGuestForm()
{
    var error = 0;
    var guestCount = jQuery('#guest_count').val();
    var childName  = jQuery('#childs_name').val();
    var childAge   = jQuery('#childs_age').val();
    if (guestCount == '') {
        jQuery('#guest_count').addClass('validation-failed');
        error = 1;
    } else if (validation.isValidNumber(guestCount) == false) {
        jQuery('#guest_count').addClass('validation-failed');
        error = 1;
    }
    if (childName == '') {
        jQuery('#childs_name').addClass('validation-failed');
        error = 1;
    }
    if (childAge == '') {
        jQuery('#childs_age').addClass('validation-failed');
        error = 1;
    } else if (validation.isValidNumber(childAge) == false) {
        jQuery('#childs_age').addClass('validation-failed');
        error = 1;
    }
    if (error == 0) {
        jQuery("#cart-button-div-id").removeClass('hide');
        jQuery('#bundle_inactive').removeClass('inactive');
        jQuery('#guest_count_hidden').val(guestCount);
        jQuery('#childs_name_hidden').val(childName);
        jQuery('#childs_age_hidden').val(childAge);
        jQuery('#child-name-div').text(childName);
        //jQuery('#product-options-wrapper input[type=text].qty').val(guestCount);
        return true;
    } else {
        return false;
    }
}
var validation = {
    isValidNumber:function(num) {
       var pattern =/^\d+$/;;
       return pattern.test(num);// returns a boolean
    }
};
function checkHiddenFields()
{
    var guestCountHidden = jQuery('#guest_count_hidden').val();
    var childNameHidden  = jQuery('#childs_name_hidden').val();
    var childAgeHidden   = jQuery('#childs_age_hidden').val();
    if (guestCountHidden != '' && childNameHidden != '' && childAgeHidden != '') {
        return true;
    } else {
        return false;
    }
}


// New Validation Class for Special Char Validation
Validation.add('validate-special-data',
    'We cannot accommodate special characters such as É, Ö, Ñ, È.',
    function (v) {
        if(v != '' &&  v) {
//            if ((v.length > 0)&&(v != 'Optional')) {
//                jQuery("input:checkbox[name='required_check']").addClass('required_check required-entry');
//            } else {
//                jQuery("input:checkbox[name='required_check']").removeClass('required_check required-entry');
//            }
            return /^[A-Za-z0-9 '/]+$/.test(v);
        }

        return true;
});
// New Validation Class for more Special Char Validation in PP
Validation.add('validate-more-special-data',
    'We cannot accommodate special characters such as É, Ö, Ñ, È.',
    function (v) {
        if(v != '' &&  v) {
            return /^[A-Za-z0-9 '",&@+-/!().]+$/.test(v);
        }
        return true;
    }
);
//function used to validate personalized fields starts
function validatePersonalizedFields()
{
    var personalizeFieldId = new Array();
    var index = 0;
    var empty = jQuery('#product_addtocart_form').find(".input-box input[type=text]").filter(function() {
        personalizeFieldId[index] = '#'+this.id;
        jQuery(personalizeFieldId[index]).removeClass('validate-length');
        index++;
    });
    jQuery("input.product-custom-option").keyup(function(){
        jQuery("input:checkbox[name='required_check']").removeClass('required_check');
        jQuery("input:checkbox[name='required_check']").removeClass('required-entry');
        jQuery('#advice-required-entry-required_check').hide();
        jQuery(personalizeFieldId).each(function(  index, value  ) {
            jQuery(personalizeFieldId[index]).removeClass('validate-length');
            jQuery('.validation-advice').hide();
            var personalizeFieldValue = jQuery(value).val();
            if (personalizeFieldValue != '' && personalizeFieldValue != 'Optional') {
                var noteMessage = '';
                noteMessage = jQuery(personalizeFieldId[index]).parent('div').parent('dd').prev('dt').children('span.limit-text').text();
                if (noteMessage != '' || typeof noteMessage != 'undefined') {
                    jQuery(personalizeFieldId[index]).addClass('validate-length');
                }
            }
            if (personalizeFieldValue != '' && personalizeFieldValue != 'Optional') {
                jQuery("input:checkbox[name='required_check']").addClass('required_check required-entry');
                jQuery('#advice-required-entry-required_check').show();
            }
        });
    });
}//function used to validate personalized fields end