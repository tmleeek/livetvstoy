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
		
		if(t.indexOf('A-Z') != -1){
		//if(t == 'A-Z ' || t == 'A-Z0-9 ' || t == '0-9A-Z ') {
			obj.value = obj.value.toUpperCase();
		}
		
		if(t != '') {
			//Additional characters
			t = t + '/\\&-';
			var nReg = new RegExp('[^'+t+']', "g");	//enables only specified allowed characters
		} else {
			var nReg = new RegExp("[~]", "g");	//enable all characters except tilde
		}
	
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