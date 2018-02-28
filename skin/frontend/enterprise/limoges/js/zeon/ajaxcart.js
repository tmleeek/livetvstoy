/**
 * zeonsolutions inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA that is bundled with this package in
 * the file LICENSE.txt. It is also available through the world-wide-web at this
 * URL: http://shop.zeonsolutions.com/license-enterprise.txt
 *
 * ================================================================= MAGENTO
 * EDITION USAGE NOTICE This package designed for Magento ENTERPRISE edition
 * =================================================================
 * zeonsolutions does not guarantee correct work of this extension on any other
 * Magento edition except Magento ENTERPRISE edition. zeonsolutions does not
 * provide extension support in case of incorrect edition usage.
 * =================================================================
 *
 * @category design
 * @package enterprise_default
 * @version 0.0.1
 * @copyright
 * @copyright Copyright (c) 2013 zeonsolutions.Inc.
 *            (http://www.zeonsolutions.com)
 * @license http://shop.zeonsolutions.com/license-enterprise.txt
 */
var activepopup=0;
var AjaxCart = Class.create();
AjaxCart.prototype = {
    initialize : function() {
        this.container = $$('body')[0];
        this.onComplete = this.resetLoadWaiting.bindAsEventListener(this);
    },

    /*custom function added for checking current page is non-secure or not*/
    isNonSecurePage : function() {
        var pos = BASE_URL.indexOf('http://');
        if (pos !== -1) {
            return true;
        }
        return false;
    },

    resetLoadWaiting : function(transport) {
        this.setLoadWaiting(false);
    },

    setLoadWaiting : function(enable) {
        if (enable) {
            if(document.getElementById('qty')) {
                $('qty').blur();
            }
            if(activepopup==1){
                var evt = (evt) ? evt : ((window.event) ? window.event : null);
                if (evt) {
                    var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
                    if ((evt.keyCode == 13) && (node.type=="text"))  {return false;}
                }
            }
            activepopup=1;
            var container = this.container;
            //container.setStyle({
                //opacity : .5
            //});
            Element.show('loading-mask');
			Element.show('message-popup-window-mask');
           // $('additional-content').update('');
        } else {
            var container = this.container;
            //container.setStyle({
               // opacity : 1
           // });
            Element.hide('loading-mask');
        }
    },

    toggleSelectsUnderBlock : function(block, flag) {
        if (Prototype.Browser.IE) {
            var selects = document.getElementsByTagName("select");
            for ( var i = 0; i < selects.length; i++) {
                /**
                 * @todo: need check intersection
                 */
                if (flag) {
                    if (selects[i].needShowOnSuccess) {
                        selects[i].needShowOnSuccess = false;
                        // Element.show(selects[i])
                        selects[i].style.visibility = '';
                    }
                } else {
                    if (Element.visible(selects[i])) {
                        // Element.hide(selects[i]);
                        // selects[i].style.visibility = 'hidden';
                        selects[i].needShowOnSuccess = true;
                    }
                }
            }
        }
    },

    showButtons : function(loginRequired, isWishlist, isCompare) {
        if (loginRequired) {
            $('btn-login').show();
            $('btn-register').show();
        } else if (isWishlist) {
            $('btn-wishlist').show();
            $('btn-continue-shopping').show();
        } else if (isCompare) {
            $('btn-view-compare').show();
            $('btn-continue-shopping').show();
        } else {
            $('btn-cart-checkout').show();
            $('btn-continue-shopping').show();
        }
    },

    hideButtons : function() {
        $('btn-login').hide();
        $('btn-register').hide();
        //$('btn-view-compare').hide();
        $('btn-wishlist').hide();
        $('btn-cart-checkout').hide();
        $('btn-continue-shopping').hide();
    },

    center : function(elem) {
        var scrollPosition = document.viewport.getScrollOffsets();
        var width = elem.clientWidth;
        var height = elem.clientHeight;
        var H = (document.viewport.getHeight() - height) / 2
                + scrollPosition.top;
        var L = (document.viewport.getWidth() - width) / 2
                - scrollPosition.left;
        elem.setStyle({
            top : H + 'px',
            left : L + 'px'
        });
    },

    openMessagePopup : function(timePeriod, isWishlist) {
        if(typeof isWishlist != 'undefined') {
            Enterprise.splitButtonsLoaded = undefined;
            Enterprise.loadSplitButtons();
        } else {
            Enterprise.Wishlist.ShowDropdown();
        }
        truncateOptions();
        var height = this.container.getHeight();
        $('message-popup-window-mask').setStyle({
            'height' : height + 'px'
        });
        this.toggleSelectsUnderBlock($('message-popup-window-mask'), false);
        Element.show('message-popup-window-mask');
        var x = (window.innerWidth / 2)
                - ($('message-popup-window').offsetWidth / 2);
        var y = (window.innerHeight / 2)
                - ($('message-popup-window').offsetHeight / 2);
        Element.show('message-popup-window');
        this.center($('message-popup-window'));
        if (timePeriod) {
            setTimeout(this.closeMessagePopup.bind(this), timePeriod * 1000);
        }
        createCustomCheckboxesAndRadio();
    },

    closeMessagePopup : function() {
        activepopup=0;
        this.toggleSelectsUnderBlock($('message-popup-window-mask'), true);
        Element.hide('message-popup-window');
        Element.hide('message-popup-window-mask');
        $('message-content').update('');
        $('additional-content').update('');
        $("message-popup-window").style.width = "";
        this.hideButtons();
    },

    initializeClasses : function() {
        mainNav("nav", {
            "show_delay" : "100",
            "hide_delay" : "100"
        });
    },

    extractScripts : function(strings) {
        var scripts = strings.extractScripts();
        scripts.each(function(script) {
            try {
                eval(script.replace(/var /gi, ""));
            } catch (e) {
                if (window.console)
                    console.log(e.name);
            }
        });
    },

    addByUrl : function(postUrl, enabled) {
        if (enabled) {
            var isCompare = false;
            var isWishlist = false;
            if (postUrl.include('wishlist')) {
                isWishlist = true;
                postUrl = postUrl.gsub('wishlist/index/add',
                        'ajaxcart/wishlist/add');
            } else if (postUrl.include('product_compare')) {
                isCompare = true;
                postUrl = postUrl.gsub('catalog/product_compare/add',
                        'ajaxcart/compare/add');
            }
            if (this.isNonSecurePage()) {
                postUrl = postUrl.replace("https://", "http://");
                postUrl = postUrl.replace("secure.", "");
            }

            this.setLoadWaiting(true);
            var request = new Ajax.Request(
                    postUrl,
                    {
                        method : 'post',
                        onComplete : this.onComplete,
                        onFailure : function(response) {
                            alert('An error occurred while processing your request');
                            this.onComplete;
                        },
                        onSuccess : function(response) {
                            if (response && response.responseText) {
                                if (typeof (response.responseText) == 'string') {
                                    eval('result = ' + response.responseText);
                                }
                                if (result.message) {
                                    $('message-content').update(result.message);
                                }
                                // Update the header
                                if (typeof (result.header) != 'undefined') {
                                    var begin = result.header
                                            .indexOf('<header class="header-container">')
                                            + '<header class="header-container">'.length;
                                    var end = result.header.length
                                            - '</header>'.length;
                                    var header = result.header.substring(begin,
                                            end);
                                    $$('.header-container')[0].innerHTML = header;
                                    this.extractScripts(header);

                                    // Remove the extra container row.
                                    if ($$('.top-container-row')[1]) {
                                        $$('.top-container-row')[1].remove();
                                    }
                                    //this.initializeClasses();
                                }
                                // Update the sidebar block
                                if (typeof (result.sidebar) != 'undefined'
                                        && $(result.block_id) != null) {
                                    $(result.block_id).innerHTML = result.sidebar;
                                    this.extractScripts(result.sidebar);
                                    //this.initializeClasses();
                                }
                                var loginRequired = false;
                                if (typeof (result.loginRequired) != 'undefined'
                                        && result.loginRequired) {
                                    loginRequired = true;
                                }
                                this.showButtons(loginRequired, isWishlist,
                                        isCompare);
                                this.openMessagePopup(AJAX_CART.isAutoHidePopup);
                            }
                        }.bind(this)
                    });
        } else {
            setLocation(postUrl);
        }
    },

    moveByUrl : function(postUrl, enabled, wishlistId) {
        if (enabled) {
            var isCompare = false;
            var isWishlist = false;
            if (postUrl.include('wishlist')) {
                isWishlist = true;
                postUrl = postUrl.gsub('wishlist/index/add',
                    'ajaxcart/wishlist/add');
            } else if (postUrl.include('product_compare')) {
                isCompare = true;
                postUrl = postUrl.gsub('catalog/product_compare/add',
                    'ajaxcart/compare/add');
            }
            if (this.isNonSecurePage()) {
                postUrl = postUrl.replace("https://", "http://");
                postUrl = postUrl.replace("secure.", "");
            }
            if(jQuery('.popup-block') && jQuery('.popup-block').hasClass('active')) {
                jQuery('.popup-block').removeClass('active');
                jQuery('.popup-block').removeClass('loading');
                jQuery('.window-overlay').removeClass('active');
            }

            this.setLoadWaiting(true);
            var request = new Ajax.Request(
                postUrl,
                {
                    method : 'post',
                    parameters : {
                        wishlist_id : wishlistId
                    },
                    onComplete : this.onComplete,
                    onFailure : function(response) {
                        alert('An error occurred while processing your request');
                        this.onComplete;
                    },
                    onSuccess : function(response) {
                        if (response && response.responseText) {
                            if (typeof (response.responseText) == 'string') {
                                eval('result = ' + response.responseText);
                            }
                            if (result.message) {
                                $('message-content').update(result.message);
                            }
                            // Update the header
                            if (typeof (result.header) != 'undefined') {
                                var begin = result.header
                                    .indexOf('<header class="header-container">')
                                    + '<header class="header-container">'.length;
                                var end = result.header.length
                                    - '</header>'.length;
                                var header = result.header.substring(begin,
                                    end);
                                $$('.header-container')[0].innerHTML = header;
                                this.extractScripts(header);

                                // Remove the extra container row.
                                if ($$('.top-container-row')[1]) {
                                    $$('.top-container-row')[1].remove();
                                }
                                //this.initializeClasses();
                            }
                            // Update the sidebar block
                            if (typeof (result.sidebar) != 'undefined'
                                && $(result.block_id) != null) {
                                $(result.block_id).innerHTML = result.sidebar;
                                this.extractScripts(result.sidebar);
                                //this.initializeClasses();
                            }
                            var loginRequired = false;
                            if (typeof (result.loginRequired) != 'undefined'
                                && result.loginRequired) {
                                loginRequired = true;
                            }
                            this.showButtons(loginRequired, isWishlist,
                                isCompare);
                            this.openMessagePopup(AJAX_CART.isAutoHidePopup);
                        }
                    }.bind(this)
                });
        } else {
            setLocation(postUrl);
        }
    },

    remove : function(postUrl, enabled) {
        if (enabled) {
            postUrl = postUrl.gsub('checkout/cart/delete',
                    'ajaxcart/cart/delete');
            if (this.isNonSecurePage()) {
                postUrl = postUrl.replace("https://", "http://");
                postUrl = postUrl.replace("secure.", "");
            }
            this.setLoadWaiting(true);
            var request = new Ajax.Request(
                    postUrl,
                    {
                        method : 'post',
                        onComplete : this.onComplete,
                        onFailure : function(response) {
                            alert('An error occurred while processing your request');
                            this.onComplete;
                        },
                        onSuccess : function(response) {
                            if (response && response.responseText) {
                                if (typeof (response.responseText) == 'string') {
                                    eval('result = ' + response.responseText);
                                }
                                if (result.message) {
                                    $('message-content').update(result.message);
                                }
                                // Update the header
                                if (typeof (result.header) != 'undefined') {
                                    var begin = result.header
                                            .indexOf('<header class="header-container">')
                                            + '<header class="header-container">'.length;
                                    var end = result.header.length
                                            - '</header>'.length;
                                    var header = result.header.substring(begin,
                                            end);
                                    $$('.header-container')[0].innerHTML = header;
                                    this.extractScripts(header);

                                    // Remove the extra container row.
                                    if ($$('.top-container-row')[1]) {
                                        $$('.top-container-row')[1].remove();
                                    }

                                    // Call the function to call the top-menu.
                                    topmenuCall();
									stopTopnav();
                                    // this.initializeClasses();
                                }
                                // Update the content
                                if (typeof (result.content) != 'undefined') {
                                    if (result.content
                                            .indexOf('<div class="cart">') == -1) {
                                        var content = result.content;
                                    } else {
                                        var begin = result.content
                                                .indexOf('<div class="cart">')
                                                + '<div class="cart">'.length;
                                        var end = result.content.length
                                                - '</div>'.length;
                                        var content = result.content.substring(
                                                begin, end);
                                    }
                                    if (typeof $$('.cart')[0] != 'undefined') {
                                        $$('.cart')[0].innerHTML = content;
                                    }

                                    this.extractScripts(content);
                                }

                                this.hideButtons();
                                this.openMessagePopup(AJAX_CART.isAutoHidePopup);
                            }
                        }.bind(this)
                    });
        } else {
            setLocation(postUrl);
        }
    },

    viewProductOptions : function(postUrl, id) {
        this.closeMessagePopup();
        this.setLoadWaiting(true);
        var request = new Ajax.Request(BASE_URL + postUrl, {
            method : 'get',
            parameters : {
                id : id
            },
            evalJS : true,
            onComplete : this.onComplete,
            onFailure : function(response) {
                alert('An error occurred while processing your request');
                this.onComplete;
            },
            onSuccess : function(response) {
                try {
                    $("message-popup-window").style.width = "952px";
                    if (response && response.responseText) {
                        // response.responseText =
                        // response.responseText.replace(/(\r\n|\n|\r)/gm,"");
                        $('message-content').innerHTML = response.responseText;
                        this.extractScripts(response.responseText);
                        this.hideButtons();
                        this.openMessagePopup(0);
                    }
                } catch (error) {
                    alert(error);
                }
            }.bind(this)
        });
    },

    updateCart : function(postUrl, enabled) {
        if (enabled) {
            postUrl = postUrl.gsub('checkout/cart/updatePost',
                    'ajaxcart/cart/updatePost');
            this.setLoadWaiting(true);
            var request = new Ajax.Request(
                    postUrl,
                    {
                        method : 'post',
                        parameters : Form
                                .serialize($('product_updatecart_form')),
                        onComplete : this.onComplete,
                        onFailure : function(response) {
                            alert('An error occurred while processing your request');
                            this.onComplete;
                        },
                        onSuccess : function(response) {
                            if (response && response.responseText) {
                                if (typeof (response.responseText) == 'string') {
                                    eval('result = ' + response.responseText);
                                }

                                if (result.message) {
                                    $('message-content').update(result.message);
                                }
                                // Update the header
                                if (typeof (result.header) != 'undefined') {
                                    var begin = result.header
                                            .indexOf('<header class="header-container">')
                                            + '<header class="header-container">'.length;
                                    var end = result.header.length
                                            - '</header>'.length;
                                    var header = result.header.substring(begin,
                                            end);
                                    $$('.header-container')[0].innerHTML = header;
                                    this.extractScripts(header);

                                    // Remove the extra container row.
                                    if ($$('.top-container-row')[1]) {
                                        $$('.top-container-row')[1].remove();
                                    }

                                    // Call the function to call the top-menu.
                                    topmenuCall();
									stopTopnav();
                                    // this.initializeClasses();
                                }
	
                                // Update the content
                                if (typeof (result.content) != 'undefined') {
                                    if (result.content
                                            .indexOf('<div class="cart">') == -1) {
                                        var content = result.content;
                                    } else {
                                        var begin = result.content
                                                .indexOf('<div class="cart">')
                                                + '<div class="cart">'.length;
                                        var end = result.content.length
                                                - '</div>'.length;
                                        var content = result.content.substring(
                                                begin, end);
                                    }

                                    $$('.cart')[0].innerHTML = content;
                                    this.extractScripts(content);
                                }
                                this.hideButtons();
                                this.openMessagePopup(AJAX_CART.isAutoHidePopup);
                            }
                        }.bind(this)
                    });
        } else {
            $('product_updatecart_form').submit();
        }
    },

    createAddwishlist : function (postUrl, thisObj) {
        postUrlUpdate = postUrl.gsub('wishlist/add', 'wishlist/createList');
        var wishlistId = 0;
        this.setLoadWaiting(true);
        postUrlUpdate = BASE_URL + postUrlUpdate;
        if (this.isNonSecurePage()) {
            postUrlUpdate = postUrlUpdate.replace("https://", "http://");
            postUrlUpdate = postUrlUpdate.replace("secure.", "");
        }
        
        var request = new Ajax.Request(postUrlUpdate, {
            method : 'post',
            parameters : Form.serialize($('create-wishlistForm')),
            evalJS : true,
            async : false,
            onFailure : function(response) {
                alert('An error occurred while processing your request');
                this.onComplete;
            },
            onSuccess : function(response) {
                if (response && response.responseText) {
                    if (typeof (response.responseText) == 'string') {
                        eval('result = ' + response.responseText);
                    }
                    this.setLoadWaiting(true);

                    if (typeof (result.wishlist) != 'undefined') {
                        wishlistId = result.wishlist.id;
                        $('wishlist_id').value = result.wishlist.id;
                        $('wishlist-name').value = "";
                        jQuery("#wishlist-public").attr("checked", false);
                        jQuery('.add-wishlist').html('<span class="add-wish-icon"></span><a href="javascript:void(0);" '
                            + 'onclick="ajaxCart.addUpdate(\'ajaxcart/wishlist/add\', this); return false;" class="link-wishlist">'
                            + 'Wishlist</a>');
                        Enterprise.Wishlist.list.push(result.wishlist);
                        jQuery(thisObj).attr('href', 'javascript:void(0);?wishlist_id='+wishlistId);
                        
                        postUrl = BASE_URL + postUrl;
                        if (this.isNonSecurePage()) {
                            postUrl = postUrl.replace("https://", "http://");
                            postUrl = postUrl.replace("secure.", "");
                        }

                        var request = new Ajax.Request(postUrl, {
                             method : 'post',
                             parameters : Form.serialize($('product_addtocart_form')),
                             evalJS : true,
                             async: true,
                             onComplete : this.onComplete,
                             onFailure : function(response) {
                                 alert('An error occurred while processing your request');
                                 this.onComplete;
                             },
                             onSuccess : function(response) {
                                 $("message-popup-window").style.width = "";
                                 if (response && response.responseText) {
                                     if (typeof (response.responseText) == 'string') {
                                         eval('result = ' + response.responseText);
                                     }
                                     if (typeof (result.item_id) != 'undefined') {
                                         $('item_id').value = result.item_id;
                                     }
                                     if (typeof (result.wishlist_item_id) != 'undefined') {
                                         $('wishlist_item_id').value = result.wishlist_item_id;
                                     }
                                     if (result.message) {
                                         $('message-content').update(result.message);
                                     }

                                     var loginRequired = false;
                                     var isWishlist = true;
                                     if (typeof (result.loginRequired) != 'undefined'
                                             && result.loginRequired) {
                                         loginRequired = true;
                                     }

                                     if (typeof (result.wishlistlimit) != 'undefined') {
                                         Enterprise.Wishlist.canCreate = false;
                                     }

                                     this.showButtons(loginRequired, isWishlist, false);
                                     this.openMessagePopup(AJAX_CART.isAutoHidePopup);
                                 }
                             }.bind(this)
                         });
                    }

                    if (result.message) {
                        $('message-content').update(result.message);
                        var loginRequired = false;
                        var isWishlist = true;
                        if (typeof (result.loginRequired) != 'undefined'
                                && result.loginRequired) {
                            loginRequired = true;
                        }
                        this.showButtons(loginRequired, isWishlist, false);
                        this.setLoadWaiting(false);
                        this.openMessagePopup(AJAX_CART.isAutoHidePopup, false);
                    }
                }
            }.bind(this)

        });
        return wishlistId;
    },

    addUpdate : function(postUrl, thisObj) {
        if (this.isNonSecurePage()) {
            postUrl = postUrl.replace("https://", "http://");
            postUrl = postUrl.replace("secure.", "");
        }
        var isWishlist = false;
        var allsetType = true;
        if (postUrl.include('wishlist')) {
            isWishlist = true;
        }
        var productForm = new VarienForm('product_addtocart_form', false);
        if (!productForm.validator.validate() && !isWishlist) {
            return false;
        }
        if(isWishlist && jQuery('.popup-block').hasClass('active')) {
            jQuery('.popup-block').removeClass('active');
            jQuery('.popup-block').removeClass('loading');
            jQuery('.window-overlay').removeClass('active');

        }
        if (!productForm.validator.validate() && isWishlist) {
            alert('Please select product options.');
            return false;
        }

        this.setLoadWaiting(true);
        if (typeof thisObj != 'undefined') {
            var url = jQuery(thisObj).attr('href');
            var wishlistId = getallurl('wishlist_id', url);
            $('wishlist_id').value = wishlistId;
            if($('create-wishlistForm')) {
                if (isWishlist && wishlistId == 0 && jQuery('#create-wishlistForm #wishlist-name').val() != "") {
                    allsetType = false;
                    wishlistId = this.createAddwishlist(postUrl, thisObj);
                    return false;
                }
            }

        } else {
            jQuery('#create-wishlistForm #wishlist-name').val('');
        }
        if (allsetType) {
            var request = new Ajax.Request(BASE_URL + postUrl, {
                method : 'post',
                parameters : Form.serialize($('product_addtocart_form')),
                evalJS : true,
                async: true,
                onComplete : this.onComplete,
                onFailure : function(response) {
                    alert('An error occurred while processing your request');
                    this.onComplete;
                },
                onSuccess : function(response) {
                    $("message-popup-window").style.width = "";
                    if (response && response.responseText) {
                        if (typeof (response.responseText) == 'string') {
                            eval('result = ' + response.responseText);
                        }
                        if (typeof (result.item_id) != 'undefined') {
                            $('item_id').value = result.item_id;
                        }
                        if (typeof (result.wishlist_item_id) != 'undefined') {
                            $('wishlist_item_id').value = result.wishlist_item_id;
                        }
                        if (result.message) {
                            $('message-content').update(result.message);
                        }
                        // Update the header
                        if (typeof (result.header) != 'undefined') {
                            var begin = result.header
                                    .indexOf('<header class="header-container">')
                                    + '<header class="header-container">'.length;
                            var end = result.header.length - '</header>'.length;
                            var header = result.header.substring(begin, end);
                            $$('.header-container')[0].innerHTML = header;
                            this.extractScripts(header);

                            // Remove the extra container row.
                            if ($$('.top-container-row')[1]) {
                                $$('.top-container-row')[1].remove();
                            }

                            // Call the function to call the top-menu.
                            topmenuCall();
							stopTopnav();	
                            // this.initializeClasses();
                        }
                        // Update the sidebar block
                        // if (typeof(result.cart_sidebar) != 'undefined') {
                        // var begin = result.cart_sidebar.indexOf('<div
                        // class=".summary">') + '<div class=".summary">'.length;
                        // var end = result.cart_sidebar.length -
                        // '</header>'.length;
                        // var cart_sidebar =
                        // result.cart_sidebar.substring(begin,end);
                        // $$('.summary')[0].innerHTML = cart_sidebar;
                        // this.extractScripts(result.cart_sidebar);
                        // this.initializeClasses();
                        // }
                        // Display additional product block
                        if (typeof (result.additional) != 'undefined'
                                && result.additional) {
                            $('additional-content').innerHTML = result.additional;
                        }
                        var loginRequired = false;
                        if (typeof (result.loginRequired) != 'undefined'
                                && result.loginRequired) {
                            loginRequired = true;
                        }
                        this.showButtons(loginRequired, isWishlist, false);
                        this.openMessagePopup(AJAX_CART.isAutoHidePopup);
                    }
                }.bind(this)
            });
        }
    },

    addUpdateWishlistFromCart : function(postUrlget, wishlistId) {
        postUrl = postUrlget.gsub('wishlist/index/fromcart/',
                'ajaxcart/wishlist/fromcart/');
        if (this.isNonSecurePage()) {
            postUrl = postUrl.replace("https://", "http://");
            postUrl = postUrl.replace("secure.", "");
        }

        if(jQuery('.popup-block') && jQuery('.popup-block').hasClass('active')) {
            jQuery('.popup-block').removeClass('active');
            jQuery('.popup-block').removeClass('loading');
            jQuery('.window-overlay').removeClass('active');

        }
        this.setLoadWaiting(true);
        var request = new Ajax.Request(postUrl, {
            method : 'post',
            parameters : {
                wishlist_id : wishlistId
            },
            evalJS : true,
            onComplete : this.onComplete,
            onFailure : function(response) {
                alert('An error occurred while processing your request');
                this.onComplete;
            },
            onSuccess : function(response) {
                if (response && response.responseText) {
                    if (typeof (response.responseText) == 'string') {
                        eval('result = ' + response.responseText);
                    }
                    if (result.message) {
                        $('message-content').update(result.message);
                    }
                    // Update the content
                    if (typeof (result.content) != 'undefined') {
                        if (result.content
                                .indexOf('<div class="cart">') == -1) {
                            var content = result.content;
                        } else {
                            var begin = result.content
                                    .indexOf('<div class="cart">')
                                    + '<div class="cart">'.length;
                            var end = result.content.length
                                    - '</div>'.length;
                            var content = result.content.substring(
                                    begin, end);
                        }

                        $$('.cart')[0].innerHTML = content;
                        this.extractScripts(content);

                        // insert list in wishlist dropdown
                        if (typeof (result.listname) != 'undefined' && wishlistId != 0) {
                            var insertVal = true;
                            Enterprise.Wishlist.list.each(function(wishlistEnt) {
                                if(wishlistEnt.id == wishlistId) {
                                    insertVal = false;
                                }
                            });
                            if (insertVal) {
                                Enterprise.Wishlist.list.push({id:wishlistId,name:result.listname});
                                if ($('wishlist-name').value != "") {
                                    $('wishlist-name').value = "";
                                    jQuery("#wishlist-public").attr("checked", false);
                                }
                            }
                        }
                        if (typeof (result.wishlistlimit) != 'undefined') {
                            Enterprise.Wishlist.canCreate = false;
                        }

                    }

                    // Update the header
                    if (typeof (result.header) != 'undefined') {
                        var begin = result.header
                                .indexOf('<header class="header-container">')
                                + '<header class="header-container">'.length;
                        var end = result.header.length - '</header>'.length;
                        var header = result.header.substring(begin, end);
                        $$('.header-container')[0].innerHTML = header;
                        // Remove the extra container row.
                        if ($$('.top-container-row')[1]) {
                            $$('.top-container-row')[1].remove();
                        }
                        this.extractScripts(header);
                        // Call the function to call the top-menu.
                        topmenuCall();
						stopTopnav();
                    }
                    this.openMessagePopup(AJAX_CART.isAutoHidePopup);
                    this.hideButtons();
                }
            }.bind(this)
        });
    },

    removeWishListItem : function(postUrl, enabled, wishlistId) {
        var conf = confirm('Are you sure you want to remove this product from your wishlist?');
        if (enabled && conf) {
            postUrl = postUrl.gsub('wishlist/index/remove',
                    'ajaxcart/wishlist/remove');
            if (this.isNonSecurePage()) {
                postUrl = postUrl.replace("https://", "http://");
                postUrl = postUrl.replace("secure.", "");
            }
            this.setLoadWaiting(true);
            var request = new Ajax.Request(
                    postUrl,
                    {
                        method : 'post',
                        parameters : {
                            wishlist_id : wishlistId
                        },
                        onComplete : this.onComplete,
                        onFailure : function(response) {
                            alert('An error occurred while processing your request');
                            this.onComplete;
                        },
                        onSuccess : function(response) {
                            if (response && response.responseText) {
                                if (typeof (response.responseText) == 'string') {
                                    eval('result = ' + response.responseText);
                                }
                                if (result.message) {
                                    $('message-content').update(result.message);
                                }
                                // Update the header
                                if (typeof (result.header) != 'undefined') {
                                    var begin = result.header
                                            .indexOf('<header class="header-container">')
                                            + '<header class="header-container">'.length;
                                    var end = result.header.length
                                            - '</header>'.length;
                                    var header = result.header.substring(begin,
                                            end);
                                    $$('.header-container')[0].innerHTML = header;
                                    this.extractScripts(header);

                                    // Remove the extra container row.
                                    if ($$('.top-container-row')[1]) {
                                        $$('.top-container-row')[1].remove();
                                    }

                                    // Call the function to call the top-menu.
                                    topmenuCall();
									stopTopnav();
                                    // this.initializeClasses();
                                }
                                // Update the content
                                if (typeof (result.content) != 'undefined') {
                                    if (result.content
                                            .indexOf('<div class="my-wishlist">') == -1) {
                                        var content = result.content;
                                    } else {
                                        var begin = result.content
                                                .indexOf('<div class="my-wishlist">')
                                                + '<div class="my-wishlist">'.length;
                                        var end = result.content.length
                                                - '</div>'.length;
                                        var content = result.content.substring(
                                                begin, end);
                                    }
                                    $$('.my-wishlist')[0].innerHTML = content;
                                    this.extractScripts(content);
                                }
                                this.hideButtons();
                                this.openMessagePopup(AJAX_CART.isAutoHidePopup, true);
                            }
                        }.bind(this)
                    });
        } else if (conf) {
            setLocation(postUrl);
        } else {
            return false;
        }
    },

    ajaxCartWishlistMoveCopy : function(itemId, qty, wishlistId, actionText) {
        if (actionText == 'copy') {
            var postUrl = Enterprise.Wishlist.url.copyItem;
            postUrl = postUrl.gsub('wishlist/index/copyitem',
                    'ajaxcart/wishlist/copyitem');
        } else {
            var postUrl = Enterprise.Wishlist.url.moveItem;
            postUrl = postUrl.gsub('wishlist/index/moveitem',
                    'ajaxcart/wishlist/moveitem');
        }

        this.setLoadWaiting(true);
        var request = new Ajax.Request(postUrl, {
            method : 'post',
            parameters : {
                item_id : itemId,
                wishlist_id : wishlistId,
                qty : qty
            },
            onComplete : this.onComplete,
            onFailure : function(response) {
                alert('An error occurred while processing your request');
                this.onComplete;
            },
            onSuccess : function(response) {
                if (response && response.responseText) {
                    if (typeof (response.responseText) == 'string') {
                        eval('result = ' + response.responseText);
                    }
                    if (result.message) {
                        $('message-content').update(result.message);
                    }

                    // Update the content
                    if (typeof (result.content) != 'undefined'
                            && actionText == 'move') {
                        $('setItem' + itemId).innerHTML = '';
                        $('moveCopy' + itemId).innerHTML = '';
                        getContentText = $$('.wishlist-count')[0].innerHTML;
                        if (getContentText) {
                            subS = getContentText.replace('item(s)', '');
                            $$('.wishlist-count')[0].innerHTML = (subS - 1)
                                    + ' item(s)';
                            if ((subS - 1) <= 0 ) {
                                $('wishlist-table').innerHTML = '<p class="empty-history">You have no items in your wishlist.</p>';
                                $('save_and_share').hide();
                            }
                        }
                    }
                    this.hideButtons();
                    this.openMessagePopup(AJAX_CART.isAutoHidePopup);
                }
            }.bind(this)
        });
    },

    updateWishList : function(postUrl, enabled, itemId, wishlistId) {
        if (enabled) {
            var qty = $$('[name="qty[' + itemId + ']"]')[0].value;

            postUrl = postUrl.gsub('wishlist/index/update',
                    'ajaxcart/wishlist/update');
            this.setLoadWaiting(true);
            var request = new Ajax.Request(
                    postUrl,
                    {
                        method : 'post',
                        parameters : {
                            qty : qty,
                            item_id : itemId,
                            wishlist_id : wishlistId
                        },
                        onComplete : this.onComplete,
                        onFailure : function(response) {
                            alert('An error occurred while processing your request');
                            this.onComplete;
                        },
                        onSuccess : function(response) {
                            if (response && response.responseText) {
                                if (typeof (response.responseText) == 'string') {
                                    eval('result = ' + response.responseText);
                                }
                                if (result.message) {
                                    $('message-content').update(result.message);
                                }
                                updateBox = undefined;
                                // Update the header
                                if (typeof (result.header) != 'undefined') {
                                    var begin = result.header
                                            .indexOf('<header class="header-container">')
                                            + '<header class="header-container">'.length;
                                    var end = result.header.length
                                            - '</header>'.length;
                                    var header = result.header.substring(begin,
                                            end);
                                    $$('.header-container')[0].innerHTML = header;
                                    this.extractScripts(header);

                                    // Remove the extra container row.
                                    if ($$('.top-container-row')[1]) {
                                        $$('.top-container-row')[1].remove();
                                    }

                                    // Call the function to call the top-menu.
                                    topmenuCall();
									stopTopnav();
                                    // this.initializeClasses();
                                }
                                // Update the content
                                if (typeof (result.content) != 'undefined') {
                                    updateBox = true;
                                    if (result.content
                                            .indexOf('<div class="my-wishlist">') == -1) {
                                        var content = result.content;
                                    } else {
                                        var begin = result.content
                                                .indexOf('<div class="my-wishlist">')
                                                + '<div class="my-wishlist">'.length;
                                        var end = result.content.length
                                                - '</div>'.length;
                                        var content = result.content.substring(
                                                begin, end);
                                    }
                                    $$('.my-wishlist')[0].innerHTML = content;
                                    this.extractScripts(content);
                                }
                                this.hideButtons();
                                this.openMessagePopup(AJAX_CART.isAutoHidePopup, updateBox);
                            }
                        }.bind(this)
                    });
        } else {
            $('wishlist-view-form').submit();
        }
    },

    addWishListItemsToCart : function(postUrl, wishlistId) {
        this.setLoadWaiting(true);
        var request = new Ajax.Request(
                postUrl,
                {
                    method : 'post',
                    parameters : {
                        wishlist_id : wishlistId,
                        child_product: $('child_product').value
                    },
                    evalJS : true,
                    onComplete : this.onComplete,
                    onFailure : function(response) {
                        alert('An error occurred while processing your request');
                        this.onComplete;
                    },
                    onSuccess : function(response) {
                        if (response && response.responseText) {
                            if (typeof (response.responseText) == 'string') {
                                eval('result = ' + response.responseText);
                            }
                            if (result.message) {
                                $('message-content').update(result.message);
                            }
                            if (result.error) {
                                $('error-content').update(result.error);
                            }
                            if (result.additional) {
                                $('additional-content').update(
                                        result.additional);
                            }

                            // Update the content
                            if (typeof (result.content) != 'undefined'
                                    && AJAX_CART.isWishlistPage) {
                                var begin = result.content
                                        .indexOf('<div class="my-wishlist">')
                                        + '<div class="my-wishlist">'.length;
                                var end = result.content.length
                                        - '</div>'.length;
                                var content = result.content.substring(begin,
                                        end);
                                $$('.my-wishlist')[0].innerHTML = content;
                                this.extractScripts(content);
                            }

                            // Update the header
                            if (typeof (result.header) != 'undefined') {
                                var begin = result.header
                                        .indexOf('<header class="header-container">')
                                        + '<header class="header-container">'.length;
                                var end = result.header.length
                                        - '</header>'.length;
                                var header = result.header
                                        .substring(begin, end);
                                $$('.header-container')[0].innerHTML = header;
                                this.extractScripts(header);

                                // Remove the extra container row.
                                if ($$('.top-container-row')[1]) {
                                    $$('.top-container-row')[1].remove();
                                }
                                // Call the function to call the top-menu.
                                topmenuCall();
                                // this.initializeClasses();
								stopTopnav();
                            }

                            // Update the sidebar block
                            if (typeof (result.sidebar) != 'undefined'
                                    && $(result.block_id) != null) {
                                $(result.block_id).innerHTML = result.sidebar;
                                this.extractScripts(result.sidebar);
                                this.initializeClasses();
                            }
                            var loginRequired = false;
                            if (typeof (result.loginRequired) != 'undefined'
                                    && result.loginRequired) {
                                loginRequired = true;
                            }
                            this.showButtons(loginRequired, false, false);
                            this.openMessagePopup(AJAX_CART.isAutoHidePopup, true);
                        }
                    }.bind(this)
                });
    }
};

function validateDownloadableCallback(elmId, result) {
    var container = $('downloadable-links-list');
    if (result == 'failed') {
        container.removeClassName('validation-passed');
        container.addClassName('validation-failed');
    } else {
        container.removeClassName('validation-failed');
        container.addClassName('validation-passed');
    }
}