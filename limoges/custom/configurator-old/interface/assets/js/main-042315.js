/**
 * Ajax Product Designer
 * 10/09/14
 * eTrader Inc.
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
var init_var = 0; // 1 time 1st load
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

//Start Designer initial codes
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

		//start window scroll function
		JQ_AJD(window).scroll(function() {
			
			//For Desktop
			if (JQ_AJD(window).width() > 1023) {
					//func_scroll();
			}
			
			//For Mobile
			//if(mobile_device) {
			if (JQ_AJD(window).width() < 381) {
				
				// Disable Scrolling of Keynotes
				if(JQ_AJD(".designer-right-column").is(":visible")) {
					
					keynote_offset = JQ_AJD(".designer-right-column").offset();
					
					if( JQ_AJD(window).scrollTop() < keynote_offset.top ) {
						window.scrollTo(0, keynote_offset.top);
					} else if( JQ_AJD(window).scrollTop() > keynote_offset.top + JQ_AJD(".designer-right-column").height() - JQ_AJD(window).height() + 60 ) {
						window.scrollTo(0, keynote_offset.top + JQ_AJD(".designer-right-column").height() - JQ_AJD(window).height() + 60);
					}
				}
				
				
				// Disable Scrolling of Item Preview
				if(main_image_view) {
					main_image_offset = JQ_AJD("#main-image-holder").offset();
					window.scrollTo(0, main_image_offset.top);
				}
				
			}
			
		});
		//end window scroll function
		
		//Start codes if there are steps
		if(arr_prod_step.length > 0) {
			//Start step with group codes
			if(step_with_group == true) {
				w_field_width = w_field_width + 80;

				VAR_cur_opt_sel = JQ_AJD('#coselhol-'+step_view).attr("cur_opt_sel");
				VAR_cur_sopt_sel = JQ_AJD('#coselhol-'+step_view).attr("cur_sopt_sel");
						
				fin_step_view = step_view;
				
				//Start Accordion setup	
				JQ_AJD("#designer-accordion").accordion({ 
					active: parseInt(step_view), 
					autoHeight: false,
					changestart: function (event, ui) {
						lockscroll = false;
						
						if(ajd_validation(prev_click)) {
							
							if(!step_button_switch) {

								var split_n_id = ui.newContent.prevObject.attr('id').split('-');	
							
								var fin_t = false;
								JQ_AJD.each(finished_steps, function(k, v) {
									if(v == 'id_'+split_n_id[split_n_id.length - 1]){
										fin_t = true;	
									}
								});
								
								if(!fin_t) {
									lockscroll = true;
									//return false;
									return true;
								}
							}else{
								var fin_t = false;
								JQ_AJD.each(finished_steps, function(k, v) {
									if(v == 'id_'+fin_step_view){
										fin_t = true;	
									}
								});
								
								if(!fin_t) {
									finished_steps.push('id_'+fin_step_view);
									
									if(finished_steps.length == (arr_prod_step.length - 2)) {
										finished_steps.push('id_'+(arr_prod_step.length - 2));
										finished_steps.push('id_'+(arr_prod_step.length - 1));
									}
								}
								step_button_switch = false;
							}
						}else{
							lockscroll = true;
							return true;
						}
						JQ_AJD('#uploader-mover').hide();
						JQ_AJD('#upload_click').hide();
					}
				});
				//End Accordion setup	
				
				if(hide_step) {
					JQ_AJD(".ui-accordion-header").eq(0).hide().next().hide();
				}
				
				//Start Accordion onchange event
				JQ_AJD('#designer-accordion').bind('accordionchange', function(event, ui) {

					  lockscroll = true;
					  
					  var go_click = true;
					  step_view = JQ_AJD('#designer-accordion').accordion( "option", "active" );
					  fin_step_view = step_view;
					  
					  VAR_cur_opt_sel = JQ_AJD('#coselhol-'+step_view).attr("cur_opt_sel");
					  VAR_cur_sopt_sel = JQ_AJD('#coselhol-'+step_view).attr("cur_sopt_sel");
					  tmb_focus(step_view);
					  
					  
					  for (i = 0; i < arr_prod_step.length; i++){
						if(arr_prod_step[i].place_holder == 'true' && step_view == i) {
						  step_view = step_def_view;
						}
					  }
					  timer = setTimeout('clipart_tool()',500);
					  
					  num_x = parseInt(JQ_AJD('#coselhol-'+step_view+' ~ .mv_itm_x').attr("value"));
					  num_y = parseInt(JQ_AJD('#coselhol-'+step_view+' ~ .mv_itm_y').attr("value"));
					  zoom = parseInt(JQ_AJD('#coselhol-'+step_view+' ~ .mv_itm_rz').attr("value"));
					  rotate = parseInt(JQ_AJD('#coselhol-'+step_view+' ~ .mv_itm_rt').attr("value"));
					  
					  if(hide_step && JQ_AJD("#personalize-holder").is(":visible")) {
						JQ_AJD('#personalize-holder').hide();
					  }

					if((JQ_AJD(window).scrollTop() > offset.top)) {
						if(JQ_AJD(window).width() > 1023) {
							JQ_AJD('html, body').animate({
								 scrollTop: offset.top
							 }, 500);
						//} else if(mobile_device) {
						} else if (JQ_AJD(window).width() < 381) {
							JQ_AJD('html, body').animate({
								scrollTop: offset_interface.top
							 }, 500);		
						}
					}
				
				});
				//End Accordion onchange event
				
			} else {
				JQ_AJD("#ajax_designer_form :text").tooltip({
					position: "center right",
					offset: [-2, 10],
					effect: "fade",
					opacity: 0.7,
					tipClass: 'tooltip'	
				});	
			}
			//End step with group codes
		}
		//Start codes if there are steps
		
		delayed_update(1);
		
		JQ_AJD('#imgtools').hide();
		JQ_AJD('#uploadtools').hide();
		JQ_AJD('#upload_click').hide();
		
		timer = setTimeout('func_set_cats()',500);
		timer = setTimeout('clipart_tool()',1000);
		timer = setTimeout('clipart_button()',1250);
		
		JQ_AJD(".btn-personalize").click(function () {
			JQ_AJD(".product-essential").hide();
			JQ_AJD("#AJAX-designer").show();
		});
		
		// Start codes for Pads and Tablet
		if(JQ_AJD(window).width() >= 481 && JQ_AJD(window).width() < 1200) {
			if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
			  var ww = JQ_AJD(window).width();
			  var mw = 1200; // min width of site
			  var ratio =  ww / mw; //calculate ratio
			  if( ww < mw){ //smaller than minimum size
			   JQ_AJD('#vport').attr('content', 'initial-scale=' + ratio + ', maximum-scale=' + ratio*2 + ', minimum-scale=' + ratio + ', user-scalable=yes, width=' + mw);
			  }else{ //regular size
			   JQ_AJD('#vport').attr('content', 'width=device-width, minimum-scale=1, maximum-scale=1');
			  }
			}
		}
		// End codes for Pads and Tablet
		
		//Start Click functions for mobile phones
		//if(mobile_device) {
		if(JQ_AJD(window).width() <= 380) {
			
			JQ_AJD("#keynote-button").click(function() {
				if(!JQ_AJD(".designer-right-column").is(":visible")) {
					JQ_AJD("#main-image-bg").show();
					JQ_AJD(".designer-right-column").show();
					JQ_AJD(".designer-right-column").css( "top", JQ_AJD(window).scrollTop() );
					JQ_AJD('#keynote-button').html('');
					JQ_AJD('#keynote-button').append('DONE');
					
				} else {
					JQ_AJD(".designer-right-column").hide();
					JQ_AJD("#main-image-bg").hide();
					JQ_AJD('#keynote-button').html('');
					JQ_AJD('#keynote-button').append('Item Keynotes');
				}
			});
				
			JQ_AJD("#preview-button").click(function() {
				if(main_image_view) {
					setTimeout(function() {
						preview_image_close();	
					}, 100);
					photo_thumbnail(step_view, true);
				}
			});
			
			JQ_AJD("#main-image-bg").click(function() {
				if(main_image_view) {
					setTimeout(function() {
						preview_image_close();	
					}, 100);
				}
				
				if(insert_note_view) {
					setTimeout(function() {
						JQ_AJD(".insert-note-holder").hide();
						JQ_AJD("#main-image-bg").hide();
						insert_note_view = false;
					}, 100);
				}
			});
			
			JQ_AJD(".fancybox-close").click(function() {			
				if(insert_note_view) {
					setTimeout(function() {
						JQ_AJD(".insert-note-holder").hide();
						JQ_AJD("#main-image-bg").hide();
						insert_note_view = false;
					}, 100);
				}
			});
			
		}
		//End Click functions for mobile phones
		
		
	});
	//end document ready
}
//End Designer initial codes

function func_scroll() {
		
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
		var device_adjust = 50;
	} else {
		var device_adjust = 0;
	}
	
	JQ_AJD("#main-image-holder").css( "top", '0px' );
	JQ_AJD("#key_notes").css( "top", '0px' );
	JQ_AJD("#main-image-holder").css( "bottom", '');
	
	if (JQ_AJD(window).scrollLeft() < 50) {
		
		if(lockscroll) {
		
			if ( JQ_AJD(window).scrollTop() > (offset.top + 50) && JQ_AJD(window).scrollTop() < (offset.top + (JQ_AJD('#AJAX-designer').height() - JQ_AJD('#main-image-holder').height() - device_adjust)) ) {
				JQ_AJD("#main-image-holder").css( "position", 'fixed' );
			} else {
				JQ_AJD("#main-image-holder").css( "position", 'static' );
				
				if( JQ_AJD(window).scrollTop() > (offset.top + (JQ_AJD('#AJAX-designer').height() - JQ_AJD('#main-image-holder').height())) ) {
					JQ_AJD("#main-image-holder").css( "position", 'absolute' );
					JQ_AJD("#main-image-holder").css( "top", offset.top + (JQ_AJD('#AJAX-designer').height() - JQ_AJD('#main-image-holder').height() - device_adjust) );
				}
			}
			if(JQ_AJD(window).width() > 760) {			
				if (JQ_AJD(window).scrollTop() > (offset.top + 10)  && JQ_AJD(window).scrollTop() < (offset.top + (JQ_AJD('#AJAX-designer').height() - JQ_AJD('#key_notes').height() - device_adjust)) ) {
					JQ_AJD("#key_notes").css( "position", 'fixed' );
					JQ_AJD("#key_notes").css( "width", '260px' );
				} else {
					JQ_AJD("#key_notes").css( "position", 'static' );
					
					if( JQ_AJD(window).scrollTop() > (offset.top + (JQ_AJD('#AJAX-designer').height() - (JQ_AJD('#key_notes').height() + 20))) ) {
						JQ_AJD("#key_notes").css( "position", 'absolute' );
						JQ_AJD("#key_notes").css( "top", offset.top + (JQ_AJD('#AJAX-designer').height() - (JQ_AJD('#key_notes').height() + 5) - device_adjust) );
					}
				}
			}
			
		}else{
			JQ_AJD("#main-image-holder").css( "position", 'fixed' );
			JQ_AJD("#key_notes").css( "position", 'fixed' );
		}
	
	} else {
		JQ_AJD("#main-image-holder").css( "position", 'static' );
		JQ_AJD("#key_notes").css( "position", 'static' );
	}
}

function func_scroll_mobile() {
	
	var pos_top = get_thumb_position();
	
	if ( (JQ_AJD(window).scrollTop() > (offset_interface.top - 5)) && (pos_top != 0) && (fin_step_view != arr_prod_step.length - 1) ) {
		//console.log(pos_top);
		JQ_AJD("#designer_thumbs_holder").css( "top", pos_top+'px' );
		JQ_AJD("#designer_thumbs_holder").css( "position", 'fixed' );
		JQ_AJD("#designer_thumbs_holder").css( "z-index", '9999' );
		JQ_AJD("#designer_thumbs_holder").css( "background-color", '#3B5472' );
		JQ_AJD("#designer_thumbs div").css( "color", '#fff' );
		JQ_AJD("#designer_thumbs_holder").css( "width", 'auto' );
		JQ_AJD("#designer_thumbs_holder").css( "left", '225px' );
		JQ_AJD("#tmb_"+step_view).css( "border-color", 'transparent' );
		show_thumb_current_side();
	} else {
		JQ_AJD("#designer_thumbs_holder").css( "position", 'static' );
		JQ_AJD("#designer_thumbs_holder").css( "background-color", '#fff' );
		JQ_AJD("#designer_thumbs div").css( "color", '#666666' );
		JQ_AJD("#tmb_"+step_view).css( "border-color", '#f00' );
		show_all_sides();
	}
}

function ajd_validation(pc) {
	var $inputs = JQ_AJD("#"+AJD_from+' :input');

	var no_val = true;
	
	$inputs.each(function() {
		if(JQ_AJD(this).attr('type') == 'radio' && JQ_AJD(this).is(':checked')) {
			var split_cosel = JQ_AJD(this).attr('id').split('_');
			var split_id = split_cosel[1].split('-');
			if(JQ_AJD(this).attr('comp_type') == 'text') {
				if(split_id[0] == step_view) {
					if(JQ_AJD('#txt-'+split_cosel[1]).val() == '') {
						comp_selector(JQ_AJD( '#cosel_'+split_id[0]+'-'+split_id[1]+'-0' ),'none','');
					}
				}
			}
		}
	});
	
	return no_val;
}

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
				
	var lin = site_url+'custom/configurator/img/?';

	lin = lin + 'pid='+productid+'&template='+prod_template;
	lin = lin + '&GM='+gmode;
	lin = lin + '&'+(new Date()).getTime();
	
	if(arr_prod_step.length > 0) {
		var t_lin = lin;
		lin = lin + '&stid='+step_view;	
	}
	
	loaded_views[step_view] = 'go';
	
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
					
			if(init_var == 0) {		
				JQ_AJD.each(combo_list, function(k, v) {
					if(v == 'font') {
						JQ_AJD("#"+k).msDropDown({mainCSS:'dd2'});
					}else{
						JQ_AJD("#"+k).msDropDown();
					}
					
				});
				init_var = 1;
			}
/*			
			if(arr_prod_step.length <= 0) {
			
				var img2 = JQ_AJD('<img />')
					.attr('src', lin+'&SZ=0.8')
					.load(function() {
						JQ_AJD('#img_def').html('');
						JQ_AJD("#img_def").append(img2);	
						JQ_AJD("#designer_display img[rel]").overlay({mask: '#789', top: 'center'});
					});
					
			}
*/
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
				
				if(!(i==0 && hide_step)) {
					JQ_AJD('#tmb_'+i).click({value: i},function(e) {
						
							JQ_AJD(".thumb-holder").css({borderColor: '#FFF'});	
							step_view = e.data.value;
							JQ_AJD('#tmb_'+step_view).css({borderColor: '#ff0000'});
							if(step_with_group == true) {
									JQ_AJD("#designer-accordion").accordion({ active: parseInt(step_view)});
							}
							load_img_nxt(step_view);
						
					});
				}
			}
		}
	}
	
	if(step_with_group == true) {
		JQ_AJD( "#designer-accordion" ).accordion({ disabled: false });
	}
	
	//for (i = 0; i < arr_prod_step.length; i++){
			//JQ_AJD('#tmb_'+i).html('');
	//}
	
	//JQ_AJD('#tmb_0').html("<div style='width: "+ t_size +"px; height: "+ t_size +"px; background-image: url("+lin+"&SZ=T&TS="+ t_size +"&stid=0);'></div>");
	//JQ_AJD('#tmb_0').append("<div style='width: "+ t_size +"px; height: "+ t_size +"px; background-image: url("+lin+"&SZ=S&TS="+ t_size +");'></div>");
	
	var img = JQ_AJD('<img />')
		.attr('src', lin+"&SZ=S&TS="+ t_size)
		.load(function() {
			for (i = 0; i < arr_prod_step.length; i++){
				JQ_AJD('#tmb_'+i).html("<div style='width: "+ t_size +"px; height: "+ t_size +"px; background-image: url("+lin+"&SZ=S&TS="+ t_size+");'></div>");
				JQ_AJD('#tmb_'+i+" div").css("background-position", "-"+((t_size+5) * i)+"px 0");
			}
		});


	//for (i = 0; i < 1; i++){		
		
			//if(arr_prod_step[i].place_holder != 'true') {
				//JQ_AJD('#tmb_'+i).html("<div style='width: "+ t_size +"px; height: "+ t_size +"px; background-image: url("+lin+"&SZ=T&TS="+ t_size +"&stid="+i+");'></div>");
				//JQ_AJD('#tmb_0 div').clone().appendTo('#tmb_'+i);
				//JQ_AJD('#tmb_'+i+" div").css("background-position", "-"+((t_size+5) * i)+"px 0");
			//}
	//}
	//load_final_preview();
	
	//if(hide_step && JQ_AJD("#designer_thumbs div:first").is(":visible")) {
	//}
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
		
		var lin = site_url+'custom/configurator/img/?';
	
		lin = lin + 'pid='+productid+'&template='+prod_template;
		lin = lin + '&GM='+gmode;
		lin = lin + '&'+(new Date()).getTime();
		
		if(arr_prod_step.length > 0) {
			var t_lin = lin;
			lin = lin + '&stid='+step_view;	
		}
	
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

function load_final_preview() {
			
	var lin = site_url+'custom/configurator/img/?';

	lin = lin + 'pid='+productid+'&template='+prod_template;
	lin = lin + '&SZ=P&'+(new Date()).getTime()+'&MP=';
	
	var img = JQ_AJD('<img/>')
		.attr('src', lin+'PS')
		.attr('class', 'img-fin-prev')
        .load(function() {
			JQ_AJD('#final-preview').html('');
			JQ_AJD("#final-preview").append('<a id="fin-prev-hol"></a>');	
			JQ_AJD("#fin-prev-hol").append(img);
			
			JQ_AJD("#fin-prev-hol").click(function() {
				//preview_scroll();
				JQ_AJD.fancybox.open({
					href : lin+'PL',
					type : 'iframe',
					padding : 5
				});
			});
		});
	
}

function tmb_focus(grp){
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
		//timer = setTimeout('update_vars()',500);
	}
}


function update_vars() {
	
	if(document.getElementById(AJD_from)){
		var dat = JQ_AJD('#'+AJD_from).serialize();
		var url_path = site_url+'custom/configurator/post.php?pid='+productid+'&template='+prod_template+'&metal='+material;

		JQ_AJD.ajax({
		   url: url_path,
		   type: 'POST',
		   data: dat,
		   success: function(data, textStatus, jqXHR){
			   	if(data) {
					W_ARR_list = JQ_AJD.parseJSON(data);
				}
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

function clipart_button() {
	
	JQ_AJD('.btn_mv').click(function(){
		
		var titL = JQ_AJD(this).attr('tp');
		
		if(titL == 'l'){
			rotate = rotate + rot_inc;
		}else if(titL == 'r'){
			rotate = rotate - rot_inc;
		}else if(titL == 'u'){
			num_y = num_y - incr;
		}else if(titL == 'd'){
			num_y = num_y + incr;
		}else if(titL == 'ml'){
			num_x = num_x - incr;
		}else if(titL == 'mr'){
			num_x = num_x + incr;
		}else if(titL == "p"){
			zoom = zoom + zoom_inc;
		}else if(titL == "n"){
			zoom = zoom - zoom_inc;
		}
		
		if ( rotate > 360  ){
			rotate = rotate - 360;
			}else if ( rotate < 0 ){
			rotate = 360 + rotate;
		}
		
		if(titL == 'l' || titL == 'r' ){
			JQ_AJD('#coselhol-'+step_view+' ~ .mv_itm_rt').attr("value",rotate);
		}
		
		if(titL == 'u' || titL == 'd' ){
			JQ_AJD('#coselhol-'+step_view+' ~ .mv_itm_y').attr("value",num_y);
		}
		
		if(titL == 'ml' || titL == 'mr' ){
			JQ_AJD('#coselhol-'+step_view+' ~ .mv_itm_x').attr("value",num_x);
		}
		
		if(titL == 'p' || titL == 'n' ){
			JQ_AJD('#coselhol-'+step_view+' ~ .mv_itm_rz').attr("value",zoom);
		}
		update_vars();	
	});	
}

function uploader_hide_other(split_id, i_id) {
	 
	if(i_id) {
		img_id = i_id;
	}else{
		if(split_id != null) {
			var val = JQ_AJD("#coselhi-" + split_id[0]+'-'+split_id[1]).val().split('~');
			var img_id = val[1].split('|');
			img_id = img_id[0];
		}else{
			return false;	
		}
	}
	JQ_AJD('#up-add-button .but-span').html('<i class="icon-plus icon-white"></i> Browse Photo');
	
	JQ_AJD('#uploader-files .files .template-download').each(function() {
		if(JQ_AJD(this).attr('id') !== 'up-d-'+img_id) {
			JQ_AJD(this).hide();
			JQ_AJD('#upload_click').show();
		}else{
			JQ_AJD(this).show();
			JQ_AJD('#up-add-button .but-span').html('<i class="icon-plus icon-white chk"></i> Change Photo');
			show_upload_toolbar();
			JQ_AJD('#upload_click').hide();
		}
	});
}

function uploader_delete_not_used() {
	var $inputs = JQ_AJD("#"+AJD_from+' :input');
	
	var img_ids = new Array();
	
	$inputs.each(function() {
	
		if(JQ_AJD(this).attr('type') == 'radio' && JQ_AJD(this).is(':checked')) {
		
			var split_cosel = JQ_AJD(this).attr('id').split('_');
			var split_id = split_cosel[1].split('-');
			
			if(JQ_AJD(this).attr('comp_type') == 'uploader') {
				var val = JQ_AJD("#coselhi-" + split_id[0]+'-'+split_id[1]).val().split('~');
				if(val.length > 0) {
					var img_id = val[1].split('|');
					if(img_id[0] != '' || JQ_AJD.trim(img_id[0]) != 'NONE') {
						img_ids.push(img_id[0]);
					}
				}
			}
		}
		
	});
	var cnt = 0;
	
	JQ_AJD('#uploader-files .files .template-download').each(function() {

		var split_up_id = JQ_AJD(this).attr('id').split('-');
		
		if(JQ_AJD.inArray(split_up_id[2], img_ids) == '-1'){
			JQ_AJD(this).find(':checkbox').prop('checked', true);
			cnt++;
		}
	});
	
	if(cnt > 0) {
		JQ_AJD('#upload-delete').click();
	}
}


var mouseStillDown = false;
var cur_btn = '';

function upTool_mouse_event(con) {
	
	
	if(con != 'no') {
		if (!mouseStillDown) { 
			clearTimeout(timer);
			JQ_AJD('#page_imagePort-image').hide();
			return; 
		} 
	}
	
	var titL = cur_btn;
	var img_sizer_w_2;
	var img_sizer_h_2;
	
	if(titL == 'l' || titL == 'r'){
		
		mouseStillDown = false;
		
		//Calculate zoom value
		//Get width and rotated image using original size and previous rotation
		var orig_width = (_imageWidth * Math.abs(Math.cos(Math.PI*parseInt(img_sizer_r)/180))) + (_imageHeight * Math.abs(Math.sin(Math.PI*parseInt(img_sizer_r)/180)));
		// Get zoom value
		var up_zoom = marquee.get ('width') / orig_width;
		
		if(titL == 'l'){
			img_sizer_r += up_rot_inc;
		} else if(titL == 'r'){
			img_sizer_r -= up_rot_inc;
		}
		
		if(img_sizer_r >= 360) {
			img_sizer_r = 0;
		}
		
		if(img_sizer_r < 0) {
			img_sizer_r = 360 + parseInt(img_sizer_r);
		}
		
		//Get center of image using current width and height
		var xcenter = marquee.get ('left') + (marquee.get ('width')/2);
		var ycenter = marquee.get ('top') + (marquee.get ('height')/2);
		
		// Get width and height uisng new rotation
		img_sizer_w_2 = (_imageWidth * Math.abs(Math.cos(Math.PI*parseInt(img_sizer_r)/180))) + (_imageHeight * Math.abs(Math.sin(Math.PI*parseInt(img_sizer_r)/180)));
		img_sizer_h_2 = (_imageHeight * Math.abs(Math.cos(Math.PI*parseInt(img_sizer_r)/180))) + (_imageWidth * Math.abs(Math.sin(Math.PI*parseInt(img_sizer_r)/180)));
		// Apply zoom
		img_sizer_w_2 *= up_zoom;
		img_sizer_h_2 *= up_zoom;
		
		//Get new position of rotated image using previous center position	
		var img_sizer_x_2 = xcenter - (img_sizer_w_2/2);
		var img_sizer_y_2 = ycenter - (img_sizer_h_2/2);
		// Always centered on locket
		
		marquee.set ({width:img_sizer_w_2}) ;
		marquee.set ({height:img_sizer_h_2}) ;
		marquee.set ({left:img_sizer_x_2}) ;
		marquee.set ({top:img_sizer_y_2}) ;
		marquee.set ({aspectRatio:img_sizer_w_2/img_sizer_h_2}) ;
	}else if(titL == 'u'){
		marquee.set ({top:marquee.get ('top') - up_move_inc}) ;
	}else if(titL == 'd'){
		marquee.set ({top:marquee.get ('top') + up_move_inc}) ;
	}else if(titL == 'ml'){
		marquee.set ({left:marquee.get ('left') - up_move_inc}) ;
	}else if(titL == 'mr'){
		marquee.set ({left:marquee.get ('left') + up_move_inc}) ;
	}else if(titL == "p"){
		marquee.set ({width:marquee.get ('width') + up_zoom_inc}) ;
		marquee.set ({height:marquee.get ('height') + (up_zoom_inc * marquee.get ('height') / marquee.get ('width'))}) ;
		marquee.set ({top:marquee.get ('top') - ((up_zoom_inc/2) * marquee.get ('height') / marquee.get ('width'))}) ;
		marquee.set ({left:marquee.get ('left') - (up_zoom_inc/2)}) ;
	}else if(titL == "n"){
		if(marquee.get('width') >= 50) {
			marquee.set ({width:marquee.get ('width') - up_zoom_inc}) ;
			marquee.set ({height:marquee.get ('height') - (up_zoom_inc * marquee.get ('height') / marquee.get ('width'))}) ;
			marquee.set ({top:marquee.get ('top') + ((up_zoom_inc/2) * marquee.get ('height') / marquee.get ('width'))}) ;
			marquee.set ({left:marquee.get ('left') + (up_zoom_inc/2)}) ;
		}
	}
	
	imagePort.setNodeStyle ('',marquee.getCoords ());
	imagePort.updateUi();
	
	if(con != 'no') {
		if (mouseStillDown) {  
			clearTimeout(timer);
			timer = setTimeout(function() { upTool_mouse_event(); },20);
			JQ_AJD('#page_imagePort-image').show();
			JQ_AJD('#covers_holder').show();
		}
	}
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
	
	photo_thumbnail_hide(step_view);
	
	if(type == 'cliparts') {
		
		JQ_AJD("#mover_" + split_cosel[1]).find('li .selector-item').css({borderColor: '#DDD'});
		var cur_item = JQ_AJD("#mover_" + split_cosel[1]).find("[ajd_id='" + sel_val + "']");
		cur_item.css({borderColor: 'red'});
		clipart_tool();
		
	}
	clipart_tool();
}

function alpha(e) {
	var k;
	document.all ? k = e.keyCode : k = e.which;
	return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 8);
}

function txt_update(id) {
	
	var split_id = id.split('-');
	
	var str = split_id[2]+'~'+JQ_AJD("#txt-" + id ).val();
	
	JQ_AJD("#coselhi-" + split_id[0]+'-'+split_id[1]).val(str);
		
	JQ_AJD('#cosel_'+id).attr("onclick", "javascript: comp_selector(this,'text','"+JQ_AJD("#txt-" + id ).val()+"');");
	sopt_update(id);
	delayed_update();
}


function func_selector_js(hold, com_type, com_id, def_val, grp, opt_id) {

	//console.log('func_selector_js def_val:'+def_val);
	//console.log('func_selector_js grp:'+grp);
	//console.log('func_selector_js opt_id:'+opt_id);
	
	var split_id = com_id.split('-');

	/**********************************************
	** Update Clipart Category List Selector upon click
	***********************************************/
	
	hold.find('.categorylist').click(function(){
		var id = JQ_AJD(this).attr("listid");
		hold.find('.categorylist').removeClass("active");
		JQ_AJD(this).addClass("active");
		
		// CUSTOM
		if(JQ_AJD(this).attr("ajd_type") == 'chains') {
			chain_cat_sel = JQ_AJD(this).attr("ajd_id");
			chain_cat_id = JQ_AJD(this).attr("ajd_catid");
		}		
		//CUSTOM
		
		if(id == 'none') {
			JQ_AJD('.selector-item-chains').attr('style', '');
			JQ_AJD('.selector-item-chains').attr('sel', '');
			JQ_AJD('.chain-hidden').val('');
		}
		
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
			
			if(com_type == 'chains' && id == 'none') {
				JQ_AJD("#coselhi-" + split_id[0]+'-'+split_id[1]).val(1);
				chain_cat_id = 'N/A';
				chain_cat_sel = 'N/A';
				chain_opt_sel = 'N/A';
				chain_sopt_sel = 'N/A';
			}else if( com_type == 'cliparts' ){
				//console.log('selected clipart category: '+JQ_AJD(this).attr("val"));
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
		
		cur_item.css({borderColor: 'red'});	
		cur_item.attr('sel', 'true');
		
		// CUSTOM
		if(cur_item.attr("ajd_type") == 'chains') {
			JQ_AJD('#selector-category').find('.categorylist').each(function(index){
				if(JQ_AJD(this).attr('listid') == cur_item.attr("cat_list")) {
					chain_cat_sel = JQ_AJD(this).attr("ajd_id");
					chain_cat_id = JQ_AJD(this).attr("ajd_catid");
				}
			});
			chain_opt_sel = cur_item.attr("ajd_id");
			chain_sopt_sel = JQ_AJD(this).find('.chain_opts :selected').val();
			cur_item.css({borderColor: '#a9c4d8'});	
			cur_item.css({backgroundColor: '#e3eff7'});	
			
		}
		//CUSTOM
		
		 if( com_type == 'cliparts' ){
				//console.log('selected clipart name: '+JQ_AJD(this).attr("title"));
				JQ_AJD("#clip-"+opt_id).attr('clip_name',JQ_AJD(this).attr("title"));
				JQ_AJD("#clip-"+opt_id).val(JQ_AJD(this).attr("title"));
				var n = cur_item.attr("value");
				var lab = cur_item.attr("title");
		}else if(com_type != 'chains') {
			var n = cur_item.attr("value");
			var lab = cur_item.attr("title");
		}else{
			var n = chain_cat_sel+'-'+chain_opt_sel+'-'+chain_sopt_sel;
			var lab = JQ_AJD(this).find('.chain_opts :selected').text();
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
				
				if(JQ_AJD(window).width() <= 380) {
						preview_image_open();
				}
			}
		}
		return false;
	});
	
	hold.find('li .selector-item').dblclick(function(){
		hold.find('li .selector-item').css({borderColor: '#DDD'});
		hold.find('li .selector-item').attr('sel', '');
		hold.find('li .selector-item-chains').attr('style', '');
		
		var cur_item = JQ_AJD(this);
		
		cur_item.css({borderColor: 'red'});	
		cur_item.attr('sel', 'true');
		
		// CUSTOM
		if(cur_item.attr("ajd_type") == 'chains') {
			JQ_AJD('#selector-category').find('.categorylist').each(function(index){
				if(JQ_AJD(this).attr('listid') == cur_item.attr("cat_list")) {
					chain_cat_sel = JQ_AJD(this).attr("ajd_id");
					chain_cat_id = JQ_AJD(this).attr("ajd_catid");
				}
			});
			chain_opt_sel = cur_item.attr("ajd_id");
			chain_sopt_sel = JQ_AJD(this).find('.chain_opts :selected').val();
			cur_item.css({borderColor: '#a9c4d8'});	
			cur_item.css({backgroundColor: '#e3eff7'});	
			
		}
		//CUSTOM

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
		setTimeout(function() {
			photo_thumbnail(step_view);
		}, 2000);
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

function sopt_selector(soptsel, sel_val, update) {
	
	var split_soptsel = JQ_AJD(soptsel).attr('id').split('_');
	var split_id = split_soptsel[1].split('-');
	var sopt_id = split_id[0]+'-'+split_id[1]+'-'+split_id[2];
	
	var po_arr = JQ_AJD("#coselhi-" + split_id[0]+'-'+split_id[1]).val().split('~');
	var str = po_arr[0] + '~' + po_arr[1] + '~' + sel_val + '~' + po_arr[3];
	
	JQ_AJD(soptsel).parent().attr("class","fontstyle-selector active");
	JQ_AJD(soptsel).parent().siblings().attr("class","fontstyle-selector");	
	JQ_AJD('input:radio[name=sopt_'+ sopt_id +']')[sel_val].checked = true;
	
	JQ_AJD("#coselhi-" + split_id[0]+'-'+split_id[1]).val(str);
	
	if(JQ_AJD('input:radio[name=sopt_'+sopt_id+']:checked').val() == 0) {
		JQ_AJD('#uphol-'+sopt_id).addClass("show");
		JQ_AJD('#uphol-'+sopt_id).removeClass("hide");
	} else {
		JQ_AJD('#uphol-'+sopt_id).addClass("hide");
		JQ_AJD('#uphol-'+sopt_id).removeClass("show");
	}
	
	if(update)
		delayed_update(1);
	
}

function sopt2_selector(soptsel, sel_val, update) {
	
	var split_soptsel = JQ_AJD(soptsel).attr('id').split('_');
	var split_id = split_soptsel[1].split('-');
	var sopt_id = split_id[0]+'-'+split_id[1]+'-'+split_id[2];
	
	var po_arr = JQ_AJD("#coselhi-" + split_id[0]+'-'+split_id[1]).val().split('~');
	var str = po_arr[0] + '~' + po_arr[1] + '~' + po_arr[2] + '~' + sel_val;
	
	JQ_AJD(soptsel).parent().attr("class","insert-selector active");
	JQ_AJD(soptsel).parent().siblings().attr("class","insert-selector");	
	JQ_AJD('input:radio[name=sopt2_'+ sopt_id +']')[sel_val].checked = true;
	
	JQ_AJD("#coselhi-" + split_id[0]+'-'+split_id[1]).val(str);
	if(JQ_AJD('input:radio[name=sopt2_'+sopt_id+']:checked').val() == 0) {
		JQ_AJD('#colorlaser-'+sopt_id).show();
		JQ_AJD('#laser-'+sopt_id).hide();
		JQ_AJD('#paperphoto-'+sopt_id).hide();
	} else if(JQ_AJD('input:radio[name=sopt2_'+sopt_id+']:checked').val() == 1) {
		JQ_AJD('#laser-'+sopt_id).show();
		JQ_AJD('#colorlaser-'+sopt_id).hide();
		JQ_AJD('#paperphoto-'+sopt_id).hide();
	} else {
		JQ_AJD('#paperphoto-'+sopt_id).show();
		JQ_AJD('#colorlaser-'+sopt_id).hide();
		JQ_AJD('#laser-'+sopt_id).hide();
	}
	var point_pos = JQ_AJD('#uppoint_'+sopt_id).offset();
	
	JQ_AJD('#uploader-mover').css('top', (point_pos.top + 45) + 'px');
	
	if(update) {
		delayed_update(1);
		clearTimeout(timer);
		timer = setTimeout("photo_thumbnail(step_view)",2000);
	}
	
}

function get_metal() {
	sel_metal = '';
	var inputs = JQ_AJD("#"+AJD_from+' :input');
	inputs.each(function() {
		if(JQ_AJD(this).attr('ajd_type') == 'metal') {
			var met = JQ_AJD(this).val().split('-');
			sel_metal = met[1];
		}
	});
}

function metal_update(cbo) {
	
	var tmp = JQ_AJD(cbo).val().split('-');
	
	delayed_update(1);
}


function txt_area_delay() {
	update_vars();
}


function delayed_area_update(){
	clearTimeout(timer);
	timer = setTimeout('txt_area_delay()',500);
}

function txt_area_update(id) {
	add_multi_line(id);
	delayed_area_update();
}

function txt_area_update2(id, e) {
	clearTimeout(timer);
	if (e.keyCode == 13) {
		add_multi_line(id);
		timer = setTimeout('txt_area_delay()',500);
	} else {
		add_multi_line(id);
		timer = setTimeout('txt_area_delay()',5000);
	}
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

function preview_scroll() {
	if( (JQ_AJD(window).scrollTop() > offset.top) && (JQ_AJD(window).width() < 768) ) {
		JQ_AJD('html, body').animate({
			scrollTop: offset.top
		}, 500);
	}
}

function preview_image_open() {
	JQ_AJD("#main-image-bg").show();
	setTimeout(function() {
		JQ_AJD("#main-image-holder").hide();
		window.scrollTo( 0, JQ_AJD(window).scrollTop() - main_image_height );
		JQ_AJD("#designer_thumbs_holder").hide();
		JQ_AJD("#total-price").hide();
		//JQ_AJD("#finished-cart").hide();
		JQ_AJD(".upload-tool-note").hide();
		JQ_AJD("#main-image-holder").css( "position", "absolute" );
		JQ_AJD("#main-image-holder").css( "z-index", "99997" );
		JQ_AJD("#main-image-holder").css( "width", JQ_AJD(window).width() - 20 );
		JQ_AJD("#main-image-holder").css( "height", JQ_AJD(window).height() - 60 );
		
	}, 500);
	setTimeout(function() {
		JQ_AJD("#main-image-holder").css( "top", JQ_AJD(window).scrollTop() + 10 );
		JQ_AJD("#main-image-holder").show();
	}, 1000);
	JQ_AJD("#keynote-button").css( "display", "none" );
	JQ_AJD("#images-button-holder").css( "display", "block" );
	JQ_AJD("#images-button-holder").css( "bottom", "25px" );
	main_image_view = true;
}

function preview_image_close() {
	JQ_AJD("#main-image-holder").css( "height", main_image_height );
	JQ_AJD("#main-image-holder").css( "position", "static" );
	JQ_AJD("#keynote-button").css( "display", "block" );
	JQ_AJD("#images-button-holder").css( "display", "none" );
	JQ_AJD("#total-price").show();
	window.scrollTo(0, JQ_AJD(window).scrollTop() + main_image_height );
	setTimeout(function() {
		JQ_AJD("#main-image-bg").hide();
	}, 1000);
	main_image_view = false;
	JQ_AJD('#acc-checker-'+step_view).show();
}

function show_thumb_current_side() {
	
		for (i = 0; i < arr_prod_step.length; i++){
			if(arr_prod_step[i].place_holder != 'true') {
				
				if(i == step_view) {
					JQ_AJD("#tmb_holder_"+i).show();
				} else {
					JQ_AJD("#tmb_holder_"+i).hide();
				}				
			}
		}
}

function show_all_sides() {
	
	for (i = 0; i < arr_prod_step.length; i++){
		if(arr_prod_step[i].place_holder != 'true') {
				
			JQ_AJD("#tmb_holder_"+i).show();			
		}
	}
}

function get_thumb_position() {
	var $inputs = JQ_AJD("#"+AJD_from+' :input');

	var pos_top = 0;

	$inputs.each(function() {
		if(JQ_AJD(this).attr('type') == 'radio' && JQ_AJD(this).is(':checked')) {
			var split_cosel = JQ_AJD(this).attr('id').split('_');
			var split_id = split_cosel[1].split('-');
			
			if(split_id[0] == step_view) {
				if(JQ_AJD(this).attr('comp_type') == 'text') {
					var offset_text = JQ_AJD("#cfield_"+split_cosel[1]).offset();
					pos_top = offset_text.top - JQ_AJD(window).scrollTop() + 70;
				} else if(JQ_AJD(this).attr('comp_type') == 'wording') {
					var offset_text = JQ_AJD("#tarea_"+split_cosel[1]).offset();
					pos_top = offset_text.top - JQ_AJD(window).scrollTop();
				} else if(JQ_AJD(this).attr('comp_type') == 'cliparts') {
					var offset_text = JQ_AJD("#cfield_"+split_cosel[1]).offset();
					pos_top = offset_text.top - JQ_AJD(window).scrollTop() + 50;
				} else if(JQ_AJD(this).attr('comp_type') == 'uploader') {
					var offset_text = JQ_AJD("#cfield_"+split_cosel[1]).offset();
					pos_top = offset_text.top - JQ_AJD(window).scrollTop() + 265;
				}	
			}
		}
	});
	
	return pos_top;
}

function next_step(current_step) {
	 step_button_switch = true;
	 JQ_AJD('#designer-accordion').accordion( 'option', 'active', current_step + 1);
	 JQ_AJD('#acc-checker-'+current_step).show();
}

function previous_step(current_step) {
	 step_button_switch = true;
	 JQ_AJD('#designer-accordion').accordion( 'option', 'active', current_step - 1);
}

function photo_thumbnail(current_step, confirm_photo) {
	//if(mobile_device) {
	if (JQ_AJD(window).width() < 381) {
		JQ_AJD('#photo-thumb-'+current_step).html('');
		JQ_AJD('#tmb_'+current_step+' div').clone().appendTo('#photo-thumb-'+current_step);
		JQ_AJD('#photo-thumb-'+current_step+' div').css('margin-top', '-15px');
		JQ_AJD('#photo-thumb-'+current_step+' div').css('margin-left', '-5px');
		
		var cur_up_image_arr = JQ_AJD("#coselhi-" + current_step+'-'+current_step).val().split("~")[1].split("|");
		
		if( confirm_photo || (cur_up_image_arr.length > 2 && JQ_AJD('input:radio[name=sopt_'+ + current_step+'-'+current_step+'-3]:checked').attr('ajd_id') == 'upload') ) {
			
			JQ_AJD('#photo-thumb-'+current_step).append("<div style='text-align: center; font-size: 11px; text-decoration: underline; color: #3D9ACC; padding-bottom: 3px;'><a href='javascript: preview_image_open()'>EDIT PHOTO</a></div>");
		}
		JQ_AJD('#photo-thumb-'+current_step).show();
	}
}

function photo_thumbnail_hide(current_step) {
	JQ_AJD('#photo-thumb-'+current_step).html('');
	JQ_AJD('#photo-thumb-'+current_step).hide();
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
		
		var nReg = new RegExp('[^'+t+']', "g");
	
		obj.value = obj.value.replace(nReg,'');
	}
	
		
	if(grp != 'N') {
		JQ_AJD('#tmb_'+grp).click();
	}
	
	if(e.type == "keyup"){
		delayed_update();
	}

}