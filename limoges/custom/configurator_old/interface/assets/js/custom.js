var chain_cat_id = "N/A";
var chain_cat_sel = "N/A";
var chain_opt_sel = "N/A";
var chain_sopt_sel = "N/A";
var clip_cat_ID = "N/A";
if (!window.console) {
    console = {
        log: function () {}
    }
}

jQuery(document).ready( function($){


	var init = $(".f-T1").attr('maxlength'),
	txt1_length = 0,
	txt2_length = 0;
	var all_length =0;
	var txt1, txt2;


	// TOPTEXT
	$('.frontRField').on('keyup',function(){

	    txt1_length = $('.f-T1').val().length;
	    txt2_length = $('.f-T2').val().length;

	    all_length = txt1_length + txt2_length;

	    remain = parseInt(init - all_length);
	    txt1 = parseInt(init - txt2_length);
	    txt2 = parseInt(init - txt1_length);
	    
	    // remaining
	    $('span.limitT1').text('Limit '+txt1+' characters');
	    $('span.limitT2').text('Limit '+txt2+' characters');

	    $('p.1').text(init+"-"+all_length+"="+remain);
	    
	     $(".f-T1").attr('maxlength',txt1);
	     $(".f-T2").attr('maxlength',txt2);
	});
		
		var urlpost = site_url + "custom/configurator/img/?";
		urlpost = urlpost + "pid=" + productid+'&template='+prod_template;
		urlpost = urlpost + "&GM=" + gmode;
		urlpost = urlpost + "&SZ=P&MP=PF";

	// Add to Cart Button
	$(".WishListbtn").on('click', function(){
		
		if ($("#AJD_form").valid()){
			//Your ajax to post the form
			wishlist_post();
		}
		else{
			// scroll to invalid field
			goToByScroll();
		}
		
		return false;
		
	});

	// Add to Cart Button
	$("#designerFormSubmit").on('click', function(){
		
		if ($("#AJD_form").valid()){

			func_post();
		}
		else{
			// scroll to invalid field
			goToByScroll();
		}
		
		return false;
		
	});

});

function goToByScroll(){
      // Scroll
    JQ_AJD('html,body').animate({
        scrollTop: JQ_AJD("#AJD_form").find(":input.error:first").offset().top},
        'slow');

    JQ_AJD("#AJD_form").find(":input.error:first").focus();
}

function submit_form() {

    var lin = site_url + "custom/configurator/img/?";
    lin = lin + "pid=" + productid+'&template='+prod_template;
    lin = lin + "&GM=" + gmode;
    lin = lin + "&SZ=P&MP=PF";
    JQ_AJD.ajax({
        url: lin,
        type: "GET",
        success: function (data, textStatus, jqXHR) {
            if (data) {
                func_post(data)
            }
        },
        statusCode: {
            404: function () {
                alert("Server 404 - AJAX")
            }
        }
    })

}

function migrate_data(genimage){

	var $inputs = JQ_AJD("#" + AJD_from + " :input");
	
	$inputs.each(function () {

		//For Size
		if( JQ_AJD(this).is("select") && JQ_AJD(this).attr("ajd_type") == "size" && JQ_AJD("option:selected", this).attr("ajd_optid") && JQ_AJD("option:selected", this).attr("ajd_optvarid") ) {
			JQ_AJD('[name="super_attribute['+JQ_AJD("option:selected", this).attr("ajd_optid")+']"]').val(JQ_AJD("option:selected", this).attr("ajd_optvarid"));

		//For Birthstones
		}else if( JQ_AJD(this).is("select") && JQ_AJD(this).attr("ajd_type") == "stones" && JQ_AJD("option:selected", this).attr("ajd_optid") && JQ_AJD("option:selected", this).attr("ajd_optvarid") ) {
			
			JQ_AJD('[name="options['+JQ_AJD("option:selected", this).attr("ajd_optid")+']"]').val(JQ_AJD("option:selected", this).attr("ajd_optvarid"));

		//For Text Engraving
		} else if (JQ_AJD(this).attr("type") == "text2") {
		
			JQ_AJD('input[name="options[' +JQ_AJD(this).attr("ajd_optid")+ ']"]').val(JQ_AJD(this).val());

		
		// For ClipArt
		} else if (JQ_AJD(this).attr("type") == "hidden" && JQ_AJD(this).attr("ajd_type") == "comp_selector" ){
			
			if ( JQ_AJD(this).attr("clip_cat") ) {
				JQ_AJD('input[name="options[' +JQ_AJD(this).attr("ajd_optid")+ ']"]').val(JQ_AJD(this).attr("clip_cat")+"-"+JQ_AJD(this).val());
			}else{
				JQ_AJD('input[name="options[' +JQ_AJD(this).attr("ajd_optid")+ ']"]').val(JQ_AJD(this).val());
			}
			JQ_AJD('input[name="options[' +JQ_AJD(this).attr("ajd_optid")+ ']"]').removeClass('validate-special-data');
			
		}
	});
	
	JQ_AJD('#options_'+JQ_AJD('#AJD-data-holder').attr("ajd_optid")+ '_text').val(genimage);
	JQ_AJD('#options_'+JQ_AJD('#AJD-data-holder').attr("ajd_optid")+ '_text').removeClass('validate-special-data');
	
	// Automatically select the Clipart input radio buttons for Left and Right Images
	var $inputs2 = JQ_AJD("#product_addtocart_form :input");
	
	$inputs2.each(function () {
		
		if (JQ_AJD(this).attr("type") == "radio" && !JQ_AJD(this).is(":checked")) {
			JQ_AJD(this).attr('checked', 'checked');		
		}
		
	});

}

// submit data to cart
function func_post(genimage) {

	migrate_data(genimage);
	
	JQ_AJD('.remoteSubmit').trigger('click');
	
}

//submit data to wishlist
function wishlist_post(genimage) {

	migrate_data(genimage);
	
	JQ_AJD('.hiddenBtnWishlist').trigger('click');
	
}

function htmlEscape(str) {
    return String(str).replace(/&/g, "&amp;").replace(/"/g, "&quot;").replace(/'/g, "&#39;").replace(/</g, "&lt;").replace(/>/g, "&gt;")
};

function currencyFormat(num) {
    return "$" + num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}