/**
 * Ajax Configurator
 * 06/26/15
 * eTrader
*/

var JQ_AJD;			// Jquery that will be used by designer
var timer;
var timer2;
var step_def_view = 0;
var prev_val = '';
var step_view = 0;
var step_with_group = false;
var combo_list = new Object();
var loaded_views = new Object();
var arr_prod_step = new Array(0);
var productid;
var gmode;
var init_var = 0; // 1st load
var site_url;
var VAR_cur_opt_sel = 'N';
var VAR_cur_sopt_sel = 'N';
var w_field_width = 240;
var AJD_from = 'AJD_form';
var sel_metal = '';
var cbo_cats = new Object();
var sopt_switch = true;

var num_x = 0, num_y = 0, upl_x, upl_y, rot, zoom_num;
var rotate = 0;
var rot_inc = 15;
var incr = 5;
var zoom = 0;
var zoom_inc = 10;
var up_move_inc = 2;
var up_zoom_inc = 2;
var up_rot_inc = 15;
var step_button_switch = false;
var finished_steps = new Array();
var prev_click = false;

var fin_step_view = 0;

var lockscroll = true;
var hide_step = false;
var keynote_click = false;
var offset;
var keynote_offset;
var main_image_offset;
var main_image_top;
var main_image_height;
var main_image_scrolltop_orig;
var main_image_view = false;
var insert_note_view = false;
var mobile_device = false;

if ( ! window.console ) console = { log: function(){} }; // IE fix for console.log

//Start Configurator initial codes
function start_designer(){
	//start document ready
	JQ_AJD(document).ready(function() {
		
		if (JQ_AJD(window).width() < 381) {
			mobile_device = true;
		}
		
		//offset variables
		offset = JQ_AJD(".product-view").offset();
		offset_thumb = JQ_AJD("#designer_thumbs_holder").offset();
		offset_interface = JQ_AJD("#interface-holder").offset();
		main_image_height = JQ_AJD("#main-image-holder").height();
		
		delayed_update(1);
		
		timer = setTimeout('func_set_cats()',500);
		timer = setTimeout('clipart_tool()',1000);
		//timer = setTimeout('clipart_button()',1250);
		
	});
	//end document ready
}
//End Configurator initial codes


function func_set_cats() {
	JQ_AJD.each(cbo_cats, function(k, v) {
		sub_cat(JQ_AJD('#cat_'+k));
	});
}

function main_pic_view(cur_view) {
	JQ_AJD(".main-img-holder").addClass("hide");
	JQ_AJD(".main-img-holder").removeClass("show");
	JQ_AJD("#dview"+cur_view).addClass("show");
}

function load_img() {
	
	loaded_views = new Object();
				
	JQ_AJD('#loading_holder').show();
				
	var lin = 'http://imagick.cps-images.com/custom/configurator/img/?';
	//var lin = site_url+'custom/configurator/img/?';

	lin = lin + 'pid='+productid+'&template='+prod_template;
	lin = lin + '&GM='+gmode;
	lin = lin + '&'+(new Date()).getTime();
	lin = lin + '&sessid='+ md5(getCookie('frontend'));
	//lin = lin + '&sessid='+session_id;
	
	if(arr_prod_step.length > 0) {
		var t_lin = lin;
		lin = lin + '&stid='+step_view;	
	}
	
	//console.log(lin);
	
	loaded_views[step_view] = 'go';
	//alert(lin);
	var img = JQ_AJD('<img rel="#img_def" />')
		.attr('src', lin)
		.attr('id', 'dview'+step_view)
		.attr('class', 'main-img-holder')
        .load(function() {
		
			JQ_AJD('#designer_display').html('');
			JQ_AJD("#designer_display").append(img);
			JQ_AJD('#loading_holder').hide();	
	
			JQ_AJD(this).css( "marginRight", 'auto');	
			JQ_AJD(this).css( "marginLeft", 'auto');
			main_pic_view(step_view);
			
			//if(JQ_AJD('#img_def').length == 0)
				//JQ_AJD("body").append('<div class="designer_img_overlay" id="img_def"></div>');	
				
			if(arr_prod_step.length > 0) {
				update_thumbs(t_lin);
			}
		});
}


function update_thumbs(lin) {
	
	var t_size = 80;
	var f_size = 9;
	if (JQ_AJD('#designer_thumbs').is(':empty')) {
		
		for (i = 0; i < arr_prod_step.length; i++){
			if(arr_prod_step[i].place_holder != 'true') {
				
				str = "<div id='tmb_holder_"+i+"' align='center' style='display: inline-block; width: "+ (t_size+2) +"px; margin: 0;'><div class='thumb-holder' id='tmb_"+i+"' style='cursor: pointer; width: "+ t_size +"px; height: "+ t_size +"px;'></div><div style='width: "+ (t_size+6) +"px; color: #666666; font-size: " + f_size +"px; text-align: center; padding: 1px 0;'>"+arr_prod_step[i].name.substr(8)+"</div></div>";
				
				JQ_AJD("#designer_thumbs").append(str);
				
				if(i == step_view) {
					JQ_AJD("#tmb_"+i).css({borderColor: '#ff0000'});
				}
				
				//if(!(i==0 && hide_step)) {
					JQ_AJD('#tmb_'+i).click({value: i},function(e) {
						
							JQ_AJD(".thumb-holder").css({borderColor: '#FFF'});	
							step_view = e.data.value;
							JQ_AJD('#tmb_'+step_view).css({borderColor: '#ff0000'});
							if(step_with_group == true) {
									JQ_AJD("#designer-accordion").accordion({ active: parseInt(step_view)});
							}
							load_img_nxt(step_view);
						
					});
				//}
			}
		}
	}
	
	var img = JQ_AJD('<img />')
		.attr('src', lin+"&SZ=S&TS="+ t_size)
		.load(function() {
			for (i = 0; i < arr_prod_step.length; i++){
				JQ_AJD('#tmb_'+i).html("<div style='width: "+ t_size +"px; height: "+ t_size +"px; background-image: url("+lin+"&SZ=S&TS="+ t_size+");'></div>");
				JQ_AJD('#tmb_'+i+" div").css("background-position", "-"+((t_size+5) * i)+"px 0");
			}
		});

}

function load_img_nxt(cur_view) {
	
	var go_view = false;
	
	JQ_AJD.each(loaded_views, function(k, v) {
		if(k == cur_view) {
			go_view = true;
		}
	});

	if (go_view) {
		if(JQ_AJD("#dview"+cur_view).length > 0)
			main_pic_view(cur_view);
	}else{
		
		loaded_views[cur_view] = 'go';
		
		var lin = 'http://imagick.cps-images.com/custom/configurator/img/?';
		//var lin = site_url+'custom/configurator/img/?';
	
		lin = lin + 'pid='+productid+'&template='+prod_template;
		lin = lin + '&GM='+gmode;
		lin = lin + '&'+(new Date()).getTime();
		lin = lin + '&sessid='+ md5(getCookie('frontend'));
		//lin = lin + '&sessid='+session_id;
		
		if(arr_prod_step.length > 0) {
			var t_lin = lin;
			lin = lin + '&stid='+step_view;	
		}
		
		//console.log(lin + ' : next');
	
		var img = JQ_AJD('<img rel="#img_def" />')
			.attr('src', lin)
			.attr('id', 'dview'+cur_view)
			.attr('class', 'main-img-holder')
			.load(function() {
				JQ_AJD("#designer_display").append(img);	
				JQ_AJD(this).css( "marginRight", 'auto');	
				JQ_AJD(this).css( "marginLeft", 'auto');	
				main_pic_view(cur_view);
			});
	}
	
}


function tmb_focus(grp){

	if(JQ_AJD("#main-image-holder").is(":hidden")) {
		JQ_AJD("#main-image-holder").show();
		JQ_AJD("#media-holder").hide();
	}

	if(grp != 'N') {
		JQ_AJD('#tmb_'+grp).click();
	}
}

function delayed_update(del){
	
	if(del == 1) {
		update_vars();
	}else{
		clearTimeout(timer);
		timer = setTimeout('update_vars()',1500);
	}
}


function update_vars() {
	
	if(document.getElementById(AJD_from)){
		var dat = JQ_AJD('#'+AJD_from).serialize();
		var url_path = site_url+'custom/configurator/post.php?pid='+productid+'&template='+prod_template+'&metal='+material;
		
		var repl = new RegExp("'", 'g');
		dat = dat.replace(repl, '#39');
		repl = new RegExp("%5C", 'g');
		dat = dat.replace(repl, '#92');

		JQ_AJD.ajax({
		   url: url_path,
		   type: 'POST',
		   data: dat,
		   success: function(data, textStatus, jqXHR){
			    /*
			   	if(data) {
					W_ARR_list = JQ_AJD.parseJSON(data);
				}
				*/
				load_img();
		   },
		   statusCode: {
			404: function() {
			  alert('Server 404 - AJAX');
			}
		  }
		});
	}
}


function clipart_tool() {

	var $inputs = JQ_AJD("#"+AJD_from+' :input');

	$inputs.each(function() {
	
		if(JQ_AJD(this).attr('type') == 'radio' && JQ_AJD(this).is(':checked')) {
		
			var split_cosel = JQ_AJD(this).attr('id').split('_');
			var split_id = split_cosel[1].split('-');
			
			if(JQ_AJD(this).attr('comp_type') == 'cliparts') {
					load_clipart(0);
			}
		}
		
	});	
}


function comp_selector(cosel, type, sel_val) {
	
	
	var split_cosel = JQ_AJD(cosel).attr('id').split('_');
	var split_id = split_cosel[1].split('-');
	
	JQ_AJD('#upload_click').hide();

	if(JQ_AJD("#sopt_"+split_cosel[1]).length > 0) {
		var str = split_id[2]+'~'+sel_val+'~'+JQ_AJD("#sopt_"+split_cosel[1]).val();
	}else{
		var str = split_id[2]+'~'+sel_val;
	}
	
	JQ_AJD("#coselhi-" + split_id[0]+'-'+split_id[1]).val(str);
		
	if(typeof(layer) != 'undefined') {	
		VAR_cur_opt_sel = split_id[1];
	}else{
		VAR_cur_opt_sel = 'N';
	}
	
	JQ_AJD(cosel).parent().attr("class","comp-selector active");
	JQ_AJD(cosel).parent().siblings().attr("class","comp-selector");	
	JQ_AJD('input:radio[name=comp_opt_'+split_id[0]+']')[split_id[2]].checked = true;
	
	JQ_AJD('#coselhol-'+split_id[0]+' .cfields').addClass("hide");
	JQ_AJD('#coselhol-'+split_id[0]+' .cfields').removeClass("show");
	JQ_AJD("#cfield_"+split_cosel[1]).addClass("show");
	
	if(type != 'cliparts')
		sopt_update(split_cosel[1]);
	
	delayed_update(1);
	
	//photo_thumbnail_hide(step_view);
	
	if(type == 'cliparts') {
		
		JQ_AJD("#mover_" + split_cosel[1]).find('li .selector-item').css({borderColor: '#DDD'});
		var cur_item = JQ_AJD("#mover_" + split_cosel[1]).find("[ajd_id='" + sel_val + "']");
		cur_item.css({borderColor: '#3b94b6'});
		clipart_tool();
		
	}
	clipart_tool();
}

function alpha(e) {
	var k;
	document.all ? k = e.keyCode : k = e.which;
	return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 8);
}


function func_selector_js(hold, com_type, com_id, def_val, grp, opt_id) {
	
	var split_id = com_id.split('-');

	/**********************************************
	** Update Clipart Category List Selector upon click
	***********************************************/
	
	hold.find('.categorylist').click(function(){
		var id = JQ_AJD(this).attr("listid");
		hold.find('.categorylist').removeClass("active");
		JQ_AJD(this).addClass("active");
		
		hold.find(".selector-item").addClass("hide");
		hold.find(".selector-item").removeClass("show");
		
		if(id == 'all') {
			hold.find(".selector-item").addClass("show");
			hold.find(".selector-item").removeClass("hide");
		}else{
			JQ_AJD(".selector-item").each(function(){
				var cat_l = JQ_AJD(this).attr("cat_list").split('-');
				var go_show = false;
				for (i = 0; i < cat_l.length; i++){
					if(cat_l[i] == id) {
						go_show = true;
					}
				}
				if(go_show == true) {
					JQ_AJD(this).addClass("show");
					JQ_AJD(this).removeClass("hide");
				}
			});
			
			if( com_type == 'cliparts' ){
				JQ_AJD("#clip-"+opt_id).attr('clip_cat',JQ_AJD(this).attr("val"));
			}
		}
		
		update_vars();
		
		return false;
	});
	
	/**********************************************
	** Update Selected Clip Art Name upon click
	***********************************************/
	
	hold.find('li .selector-item').click(function(){
		hold.find('li .selector-item').css({borderColor: '#DDD'});
		hold.find('li .selector-item').attr('sel', '');
		hold.find('li .selector-item-chains').attr('style', '');
		
		var cur_item = JQ_AJD(this);
		
		cur_item.css({borderColor: '#3b94b6'});	
		cur_item.attr('sel', 'true');
		
		 if( com_type == 'cliparts' ){
				//console.log('selected clipart name: '+JQ_AJD(this).attr("title"));
				JQ_AJD("#clip-"+opt_id).attr('clip_name',JQ_AJD(this).attr("title"));
				JQ_AJD("#clip-"+opt_id).val(JQ_AJD(this).attr("title"));
				var n = cur_item.attr("value");
				var lab = cur_item.attr("title");
		}else if(com_type != 'chains') {
			var n = cur_item.attr("value");
			var lab = cur_item.attr("title");
		}

		var nn = n;
		
		
		if(typeof(split_id[2]) != 'undefined') {
			nn = split_id[2] + '~' + n;
		}
		
		
		if(n != prev_val) {
			
			JQ_AJD("#coselhi-" + split_id[0]+'-'+split_id[1]).val(nn);
			JQ_AJD('.label-title_'+com_id).html(lab);
			JQ_AJD('#selector_'+com_id).attr("onclick", "javascript: func_selector('"+com_type+"','"+com_id+"','"+n+"','"+opt_id+"');");

			if(com_type != 'chains') {
				sopt_update(com_id);
				
				prev_val = n;
				update_vars(1);
			}
		}
		return false;
	});
	
	hold.find('li .selector-item').dblclick(function(){
		hold.find('li .selector-item').css({borderColor: '#DDD'});
		hold.find('li .selector-item').attr('sel', '');
		hold.find('li .selector-item-chains').attr('style', '');
		
		var cur_item = JQ_AJD(this);
		
		cur_item.css({borderColor: '#3b94b6'});	
		cur_item.attr('sel', 'true');

		if(com_type != 'chains') {
			var n = cur_item.attr("value");
			var lab = cur_item.attr("title");
		}else{
			var n = chain_cat_sel+'-'+chain_opt_sel+'-'+chain_sopt_sel;
			var lab = JQ_AJD(this).find('.chain_opts :selected').text();
		}

		var nn = n;
		
		//var split_id = com_id.split('-');
		
		
		if(typeof(split_id[2]) != 'undefined') {
			nn = split_id[2] + '~' + n;
		}
		
		
		if(n != prev_val) {
			
			JQ_AJD("#coselhi-" + split_id[0]+'-'+split_id[1]).val(nn);
			JQ_AJD('.label-title_'+com_id).html(lab);
			JQ_AJD('#selector_'+com_id).attr("onclick", "javascript: func_selector('"+com_type+"','"+com_id+"','"+n+"');");

			if(com_type != 'chains') {
				sopt_update(com_id);
				
				prev_val = n;
				update_vars();
			}
		}
	});
			
}

function func_selector(com_type, id, def_val, opt_id) {
	
	var hold = JQ_AJD('#selector-mover');
	
	if(com_type == 'close') {
		hold.fadeOut('fast',function() {
			JQ_AJD("#interface-holder").fadeIn('fast');
		});
	}else{
			var split_id = id.split('-');
			var str = 'com_type='+com_type;
			if(typeof(def_val) != 'undefined') {
				str = str + '&def_val=' + def_val;
			}
			
			if(typeof(JQ_AJD('#selector_'+id).attr("cat_list")) != 'undefined') {
				str = str + '&cat_list=' + JQ_AJD('#selector_'+id).attr("cat_list");
			}
			
			str = str + '&sid='+split_id[0];

			hold.html('');
			hold.load(site_url+'custom/configurator/selector.php',str, function() {

				JQ_AJD("#interface-holder").fadeOut('fast',function() {
					hold.fadeIn('fast');
					window.scrollTo(0, offset_interface.top);
				});
				
				func_selector_js(hold, com_type, id, def_val, '', opt_id);
			});
	}
}

function sopt_update(id, update) {

	var split_id = id.split('-');
	var po_arr = JQ_AJD("#coselhi-" + split_id[0]+'-'+split_id[1]).val().split('~');
	var str = po_arr[0] + '~' + po_arr[1] + '~' + JQ_AJD('input:radio[name=sopt_'+id+']:checked').val() + '~' + po_arr[3];
	
	JQ_AJD("#coselhi-" + split_id[0]+'-'+split_id[1]).val(str);
	
	JQ_AJD('.comp-send-holder input:radio[name=sopt_'+id+']:checked').parent().parent().attr("class","send-selector active");
	JQ_AJD('.comp-send-holder input:radio[name=sopt_'+id+']:checked').parent().parent().siblings().attr("class","send-selector");
	
	sopt_switch = true;
	if(update && sopt_switch) {
		delayed_update(1);
	}
}

function sopt2_update(id, update) {

	var split_id = id.split('-');
	var po_arr = JQ_AJD("#coselhi-" + split_id[0]+'-'+split_id[1]).val().split('~');
	var str = po_arr[0] + '~' + po_arr[1] + '~' + po_arr[2] + '~' + JQ_AJD('input:radio[name=sopt2_'+id+']:checked').val();
	JQ_AJD("#coselhi-" + split_id[0]+'-'+split_id[1]).val(str);
	
	if(update)
		delayed_update(1);
}


function categories_act(id) {
	
	var split_soptsel = JQ_AJD(id).attr('id').split('_');
	var split_id = split_soptsel[1].split('-');
	
	var sely =  JQ_AJD('option:selected', JQ_AJD(id));
	
	var listid = sely.attr("listid");
	
	var hold = JQ_AJD('#mover_'+split_soptsel[1]);
	
	hold.find(".selector-item").addClass("hide");
	hold.find(".selector-item").removeClass("show");
	
	if(listid == 'all') {
		hold.find(".selector-item").addClass("show");
		hold.find(".selector-item").removeClass("hide");
	}else{
		hold.find(".selector-item").each(function(){
			var cat_l = JQ_AJD(this).attr("cat_list").split('-');

			
			var go_show = false;
			for (i = 0; i < cat_l.length; i++){
				if(cat_l[i] == listid) {
					go_show = true;
				}
			}
			if(go_show == true) {
				JQ_AJD(this).addClass("show");
				JQ_AJD(this).removeClass("hide");
			}
		});
	}
}

function sub_cat(id) {
	var split_soptsel = JQ_AJD(id).attr('id').split('_');
	var split_id = split_soptsel[1].split('-');
	
	var sely =  JQ_AJD('option:selected', JQ_AJD('#cat_'+split_soptsel[1]));
	if(sely.attr('listid') == 'all') {
		var ss = '';
	}else{
		var ss = cbo_cats[split_soptsel[1]][JQ_AJD(id).val()]['sub_cats'];
	}
	
	var str = '';
	
	str = '<select style="width: 170px; margin: 5px 0;" ';
	if(ss.length <= 0) {
		str = str + 'disabled="disabled" ';
	}
	
	str = str + 'id="subcat_'+split_soptsel[1]+'" onchange="javascript: categories_act(this);">';
		
	if(ss.length > 0) {
		for (i = 0; i < ss.length; i++){
			str = str + '<option style="padding: 2px;" ajd_id="'+sely.attr('ajd_id')+'" ajd_type="'+sely.attr('ajd_type')+'" listid="'+sely.attr('listid')+'_'+i+'" value="'+i+'">'+ss[i]['title']+'</option>' 
		}
		str = str + '<option style="padding: 2px;" ajd_id="'+sely.attr('ajd_id')+'" ajd_type="'+sely.attr('ajd_type')+'" listid="'+sely.attr('listid')+'" value="all" selected="selected">Show All</option>' 	
	}else{
		str = str + '<option style="padding: 2px;" value="none">No Subcategories</option>' 
	}
	
	str = str + "</select>";
	JQ_AJD('#subcat_'+split_soptsel[1]+'_holder').html('');
	JQ_AJD('#subcat_'+split_soptsel[1]+'_holder').append(str);
	
	categories_act(id);
	
	if(sely.attr('listid') != '0' && sely.attr('listid') != 'all') {
		load_clipart(sely.attr('listid'));
	}
}

function load_clipart(id) {
	JQ_AJD('img.clip_load').each(function(){
		var image = JQ_AJD(this);
		if((image.attr("src") != image.attr("data-original") && (image.attr("main-category") == id))) {
			image.attr("src", image.attr("data-original"));
		}
	} );
}


function check_char(obj,t,grp,e){
	
	if(window.event){
		var e = window.event;
	}
	
	var keypressed = e.which ? e.which : e.keyCode;
	
	if(keypressed === 8 // backspace
    || keypressed === 27 // escape
    || keypressed === 46 // delete
    || (keypressed >= 35 && keypressed <= 40) // end, home, arrows
    // TODO: shift, ctrl, alt, caps-lock, etc
    ) {
	
	}else{
		
		if(t == 'A-Z ' || t == 'A-Z0-9 ' || t == '0-9A-Z ') {
			obj.value = obj.value.toUpperCase();
		}
		
		var nReg = new RegExp("[~]", "g");	//enable all characters except tilde
		//var nReg = new RegExp('[^'+t+']', "g");	//enables only specified allowed characters
	
		obj.value = obj.value.replace(nReg,'');
		
	}
	
		
	if(grp != 'N') {
		JQ_AJD('#tmb_'+grp).click();
	}
	
	if(e.type == "keyup"){
		delayed_update();
	}

}


function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}


/*
 * JavaScript MD5 1.0.1
 * https://github.com/blueimp/JavaScript-MD5
 *
 * Copyright 2011, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 * 
 * Based on
 * A JavaScript implementation of the RSA Data Security, Inc. MD5 Message
 * Digest Algorithm, as defined in RFC 1321.
 * Version 2.2 Copyright (C) Paul Johnston 1999 - 2009
 * Other contributors: Greg Holt, Andrew Kepert, Ydnar, Lostinet
 * Distributed under the BSD License
 * See http://pajhome.org.uk/crypt/md5 for more info.
 */

/*jslint bitwise: true */
/*global unescape, define */

(function ($) {
    'use strict';

    /*
    * Add integers, wrapping at 2^32. This uses 16-bit operations internally
    * to work around bugs in some JS interpreters.
    */
    function safe_add(x, y) {
        var lsw = (x & 0xFFFF) + (y & 0xFFFF),
            msw = (x >> 16) + (y >> 16) + (lsw >> 16);
        return (msw << 16) | (lsw & 0xFFFF);
    }

    /*
    * Bitwise rotate a 32-bit number to the left.
    */
    function bit_rol(num, cnt) {
        return (num << cnt) | (num >>> (32 - cnt));
    }

    /*
    * These functions implement the four basic operations the algorithm uses.
    */
    function md5_cmn(q, a, b, x, s, t) {
        return safe_add(bit_rol(safe_add(safe_add(a, q), safe_add(x, t)), s), b);
    }
    function md5_ff(a, b, c, d, x, s, t) {
        return md5_cmn((b & c) | ((~b) & d), a, b, x, s, t);
    }
    function md5_gg(a, b, c, d, x, s, t) {
        return md5_cmn((b & d) | (c & (~d)), a, b, x, s, t);
    }
    function md5_hh(a, b, c, d, x, s, t) {
        return md5_cmn(b ^ c ^ d, a, b, x, s, t);
    }
    function md5_ii(a, b, c, d, x, s, t) {
        return md5_cmn(c ^ (b | (~d)), a, b, x, s, t);
    }

    /*
    * Calculate the MD5 of an array of little-endian words, and a bit length.
    */
    function binl_md5(x, len) {
        /* append padding */
        x[len >> 5] |= 0x80 << (len % 32);
        x[(((len + 64) >>> 9) << 4) + 14] = len;

        var i, olda, oldb, oldc, oldd,
            a =  1732584193,
            b = -271733879,
            c = -1732584194,
            d =  271733878;

        for (i = 0; i < x.length; i += 16) {
            olda = a;
            oldb = b;
            oldc = c;
            oldd = d;

            a = md5_ff(a, b, c, d, x[i],       7, -680876936);
            d = md5_ff(d, a, b, c, x[i +  1], 12, -389564586);
            c = md5_ff(c, d, a, b, x[i +  2], 17,  606105819);
            b = md5_ff(b, c, d, a, x[i +  3], 22, -1044525330);
            a = md5_ff(a, b, c, d, x[i +  4],  7, -176418897);
            d = md5_ff(d, a, b, c, x[i +  5], 12,  1200080426);
            c = md5_ff(c, d, a, b, x[i +  6], 17, -1473231341);
            b = md5_ff(b, c, d, a, x[i +  7], 22, -45705983);
            a = md5_ff(a, b, c, d, x[i +  8],  7,  1770035416);
            d = md5_ff(d, a, b, c, x[i +  9], 12, -1958414417);
            c = md5_ff(c, d, a, b, x[i + 10], 17, -42063);
            b = md5_ff(b, c, d, a, x[i + 11], 22, -1990404162);
            a = md5_ff(a, b, c, d, x[i + 12],  7,  1804603682);
            d = md5_ff(d, a, b, c, x[i + 13], 12, -40341101);
            c = md5_ff(c, d, a, b, x[i + 14], 17, -1502002290);
            b = md5_ff(b, c, d, a, x[i + 15], 22,  1236535329);

            a = md5_gg(a, b, c, d, x[i +  1],  5, -165796510);
            d = md5_gg(d, a, b, c, x[i +  6],  9, -1069501632);
            c = md5_gg(c, d, a, b, x[i + 11], 14,  643717713);
            b = md5_gg(b, c, d, a, x[i],      20, -373897302);
            a = md5_gg(a, b, c, d, x[i +  5],  5, -701558691);
            d = md5_gg(d, a, b, c, x[i + 10],  9,  38016083);
            c = md5_gg(c, d, a, b, x[i + 15], 14, -660478335);
            b = md5_gg(b, c, d, a, x[i +  4], 20, -405537848);
            a = md5_gg(a, b, c, d, x[i +  9],  5,  568446438);
            d = md5_gg(d, a, b, c, x[i + 14],  9, -1019803690);
            c = md5_gg(c, d, a, b, x[i +  3], 14, -187363961);
            b = md5_gg(b, c, d, a, x[i +  8], 20,  1163531501);
            a = md5_gg(a, b, c, d, x[i + 13],  5, -1444681467);
            d = md5_gg(d, a, b, c, x[i +  2],  9, -51403784);
            c = md5_gg(c, d, a, b, x[i +  7], 14,  1735328473);
            b = md5_gg(b, c, d, a, x[i + 12], 20, -1926607734);

            a = md5_hh(a, b, c, d, x[i +  5],  4, -378558);
            d = md5_hh(d, a, b, c, x[i +  8], 11, -2022574463);
            c = md5_hh(c, d, a, b, x[i + 11], 16,  1839030562);
            b = md5_hh(b, c, d, a, x[i + 14], 23, -35309556);
            a = md5_hh(a, b, c, d, x[i +  1],  4, -1530992060);
            d = md5_hh(d, a, b, c, x[i +  4], 11,  1272893353);
            c = md5_hh(c, d, a, b, x[i +  7], 16, -155497632);
            b = md5_hh(b, c, d, a, x[i + 10], 23, -1094730640);
            a = md5_hh(a, b, c, d, x[i + 13],  4,  681279174);
            d = md5_hh(d, a, b, c, x[i],      11, -358537222);
            c = md5_hh(c, d, a, b, x[i +  3], 16, -722521979);
            b = md5_hh(b, c, d, a, x[i +  6], 23,  76029189);
            a = md5_hh(a, b, c, d, x[i +  9],  4, -640364487);
            d = md5_hh(d, a, b, c, x[i + 12], 11, -421815835);
            c = md5_hh(c, d, a, b, x[i + 15], 16,  530742520);
            b = md5_hh(b, c, d, a, x[i +  2], 23, -995338651);

            a = md5_ii(a, b, c, d, x[i],       6, -198630844);
            d = md5_ii(d, a, b, c, x[i +  7], 10,  1126891415);
            c = md5_ii(c, d, a, b, x[i + 14], 15, -1416354905);
            b = md5_ii(b, c, d, a, x[i +  5], 21, -57434055);
            a = md5_ii(a, b, c, d, x[i + 12],  6,  1700485571);
            d = md5_ii(d, a, b, c, x[i +  3], 10, -1894986606);
            c = md5_ii(c, d, a, b, x[i + 10], 15, -1051523);
            b = md5_ii(b, c, d, a, x[i +  1], 21, -2054922799);
            a = md5_ii(a, b, c, d, x[i +  8],  6,  1873313359);
            d = md5_ii(d, a, b, c, x[i + 15], 10, -30611744);
            c = md5_ii(c, d, a, b, x[i +  6], 15, -1560198380);
            b = md5_ii(b, c, d, a, x[i + 13], 21,  1309151649);
            a = md5_ii(a, b, c, d, x[i +  4],  6, -145523070);
            d = md5_ii(d, a, b, c, x[i + 11], 10, -1120210379);
            c = md5_ii(c, d, a, b, x[i +  2], 15,  718787259);
            b = md5_ii(b, c, d, a, x[i +  9], 21, -343485551);

            a = safe_add(a, olda);
            b = safe_add(b, oldb);
            c = safe_add(c, oldc);
            d = safe_add(d, oldd);
        }
        return [a, b, c, d];
    }

    /*
    * Convert an array of little-endian words to a string
    */
    function binl2rstr(input) {
        var i,
            output = '';
        for (i = 0; i < input.length * 32; i += 8) {
            output += String.fromCharCode((input[i >> 5] >>> (i % 32)) & 0xFF);
        }
        return output;
    }

    /*
    * Convert a raw string to an array of little-endian words
    * Characters >255 have their high-byte silently ignored.
    */
    function rstr2binl(input) {
        var i,
            output = [];
        output[(input.length >> 2) - 1] = undefined;
        for (i = 0; i < output.length; i += 1) {
            output[i] = 0;
        }
        for (i = 0; i < input.length * 8; i += 8) {
            output[i >> 5] |= (input.charCodeAt(i / 8) & 0xFF) << (i % 32);
        }
        return output;
    }

    /*
    * Calculate the MD5 of a raw string
    */
    function rstr_md5(s) {
        return binl2rstr(binl_md5(rstr2binl(s), s.length * 8));
    }

    /*
    * Calculate the HMAC-MD5, of a key and some data (raw strings)
    */
    function rstr_hmac_md5(key, data) {
        var i,
            bkey = rstr2binl(key),
            ipad = [],
            opad = [],
            hash;
        ipad[15] = opad[15] = undefined;
        if (bkey.length > 16) {
            bkey = binl_md5(bkey, key.length * 8);
        }
        for (i = 0; i < 16; i += 1) {
            ipad[i] = bkey[i] ^ 0x36363636;
            opad[i] = bkey[i] ^ 0x5C5C5C5C;
        }
        hash = binl_md5(ipad.concat(rstr2binl(data)), 512 + data.length * 8);
        return binl2rstr(binl_md5(opad.concat(hash), 512 + 128));
    }

    /*
    * Convert a raw string to a hex string
    */
    function rstr2hex(input) {
        var hex_tab = '0123456789abcdef',
            output = '',
            x,
            i;
        for (i = 0; i < input.length; i += 1) {
            x = input.charCodeAt(i);
            output += hex_tab.charAt((x >>> 4) & 0x0F) +
                hex_tab.charAt(x & 0x0F);
        }
        return output;
    }

    /*
    * Encode a string as utf-8
    */
    function str2rstr_utf8(input) {
        return unescape(encodeURIComponent(input));
    }

    /*
    * Take string arguments and return either raw or hex encoded strings
    */
    function raw_md5(s) {
        return rstr_md5(str2rstr_utf8(s));
    }
    function hex_md5(s) {
        return rstr2hex(raw_md5(s));
    }
    function raw_hmac_md5(k, d) {
        return rstr_hmac_md5(str2rstr_utf8(k), str2rstr_utf8(d));
    }
    function hex_hmac_md5(k, d) {
        return rstr2hex(raw_hmac_md5(k, d));
    }

    function md5(string, key, raw) {
        if (!key) {
            if (!raw) {
                return hex_md5(string);
            }
            return raw_md5(string);
        }
        if (!raw) {
            return hex_hmac_md5(key, string);
        }
        return raw_hmac_md5(key, string);
    }

    if (typeof define === 'function' && define.amd) {
        define(function () {
            return md5;
        });
    } else {
        $.md5 = md5;
    }
}(this));
