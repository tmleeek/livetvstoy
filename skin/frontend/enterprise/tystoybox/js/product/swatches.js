document.observe("dom:loaded", function() {
    function e(e, t) {
        var i = new Element("ul", {
            "class": "swatches-container " + e
        });
        if (t && (t.up().insertBefore(i, t), t.swatchLabel = e, t.swatchElement = i, t.setStyle({
            position: "absolute",
            top: "-9999px"
        })), "undefined" != typeof t.id) var s = t.id.replace("attribute", "");
        $A(t.options).each(function(e, o) {
            if (e.getAttribute("value")) {
                var u, f = r(e.innerHTML);
                e.getAttribute("price") && (f = r(f.replace(/\+([^+]+)$/, ""))), u = new Element("a", {
                    "class": "swatch-span"
                }), spConfig && "undefined" != typeof spConfig.config.defaultValues && e.value == spConfig.config.defaultValues[s] && u.addClassName("current"), u.update(e.innerHTML), elmli = new Element("li"), elmli.insert(u), u.observe("click", function() {
                    t.selectedIndex = o, n(t, "change");
                    var e = i.down(".current");
                    e && e.removeClassName("current"), u.addClassName("current")
                }), i.appendChild(elmli)
            }
        })
    }

    function t(n) {
        n.swatchElement && (n.up().removeChild(n.swatchElement), n.swatchElement = null), n.disabled || e(n.swatchLabel, n), n.nextSetting && t(n.nextSetting)
    }

    function n(e, t) {
        if (document.createEventObject) {
            var n = document.createEventObject();
            return e.fireEvent("on" + t, n)
        }
        var n = document.createEvent("HTMLEvents");
        return n.initEvent(t, !0, !0), !e.dispatchEvent(n)
    }

    function r(e) {
        return e.replace(/^\s\s*/, "").replace(/\s\s*$/, "")
    }

    function i(e, t) {
        elm = new Element("a", {
            "class": "detailslink fancybox.ajax " + t + " " + t.toLowerCase(),
            href: BASE_URL + "sizing/personalized",
            target: "_blank",
            title: t + " Details"
        }), elm.update(t + " Chart"), e.appendChild(elm)
    }
    try {
        $$("#product-options-wrapper dt").each(function(n) {
            var s = "";
            $A(n.down("label").childNodes).each(function(e) {
                3 == e.nodeType && (s += e.nodeValue)
            }), s = r(s);
            var o = n.next(),
                u = o.down("select");
            u && (i(n.down("label"), s), e(s, u), u.hasClassName("super-attribute-select") && u.observe("change", function() {
                setTimeout(function() {
                    t(u.nextSetting)
                }, 100)
            }))
        })
    } catch (s) {
        alert("Javascript error. Please report this error. Error:" + s.message)
    }
    
    
        //Really only needs to be the first element that has configureElement set on it,
        //rather than all.
       /* $('product_addtocart_form').getElements().each(function(el) {
            if(el.type == 'select-one') {
                if(el.options && (el.options.length > 1)) {
                    el.options[0].selected = true;
                    console.log(spConfig.config.basePrice);
                    optionsPrice.changePrice("config", {
                        price: spConfig.config.minPrice,
                        oldPrice: spConfig.config.oldPrice
                    });
                    optionsPrice.reload();
                }
            }
        });    */
});
Product.Config.prototype.formatPrice = Product.Config.prototype.formatPrice.wrap(function(e, t) {
    var n = "";
    return n
});
Product.Config.prototype.reloadPrice = Product.Config.prototype.reloadPrice.wrap(function() {
    if (this.config.disablePriceReload) {
        return
    }
    var e = 0;
    var t = 0;
    for (var n = this.settings.length - 1; n >= 0; n--) {
        var r = this.settings[n].options[this.settings[n].selectedIndex];
        if (r.config) {
            e += parseFloat(r.config.price);
            t += parseFloat(r.config.oldPrice);
            $("product_addtocart_form").child_product.value = r.config.allowedProducts[0]
        }
    }
    optionsPrice.changePrice("config", {
        price: e,
        oldPrice: t
    });
    optionsPrice.updateSpecialPriceDisplay(r.config.regPrice);
   
    optionsPrice.reload();
    return e;
    if ($("product-price-" + this.config.productId)) {
        $("product-price-" + this.config.productId).innerHTML = e
    }
    this.reloadOldPrice();
   
})

Product.OptionsPrice.prototype.updateSpecialPriceDisplay = function(setRegPrice) {

    var prodForm = $('product_addtocart_form');

    var specialPriceBox = prodForm.select('p.special-price');
    var oldPricePriceBox = prodForm.select('p.old-price, p.was-old-price');
    var magentopriceLabel = prodForm.select('span.price-label');

   if (setRegPrice) {
        specialPriceBox.each(function(x) {x.hide();});
        magentopriceLabel.each(function(x) {x.hide();});
        oldPricePriceBox.each(function(x) {
            x.removeClassName('old-price');
            x.addClassName('was-old-price');
        });
    }else{
        specialPriceBox.each(function(x) {x.show();});
        magentopriceLabel.each(function(x) {x.show();});
        oldPricePriceBox.each(function(x) {
            x.removeClassName('was-old-price');
            x.addClassName('old-price');
        });
    }
}; 
