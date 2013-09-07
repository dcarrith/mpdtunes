$('body').on('pagebeforeshow', '#setup', function(event, ui) {

	$('body').attr('class', 'ui-mobile-viewport ui-overlay-a');
});

$('body').on('pagebeforeshow', '#edit_site_config', function(event, ui) {

	if (typeof theme != 'undefined') {

		$('body').attr('class', 'ui-mobile-viewport ui-overlay-'+theme.body);

	} else {

		$('body').attr('class', 'ui-mobile-viewport ui-overlay-a');
	}

	// If there are form validation errors, then highlight the border of the input red
	$('div.required-field-error').prev().css('border', '2px solid #CC0000');
});

$('body').on('pagebeforeshow', '#error', function(event, ui) {

	if (typeof theme != 'undefined') {

		$('body').attr('class', 'ui-mobile-viewport ui-overlay-'+theme.body);

	} else {

		$('body').attr('class', 'ui-mobile-viewport ui-overlay-a');
	}
});

$('body').on('pagebeforeshow', '#setup_success', function(event, ui) {

	$('body').attr('class', 'ui-mobile-viewport ui-overlay-a');
});

$('body').on('pagebeforeshow', '#login', function(event, ui) {

	$('body').attr('class', 'ui-mobile-viewport ui-overlay-a');

	// add the green hover state to the submit button's parent div
	$('#login_form a.ui-btn').removeClass('ui-btn-hover-g');

	// If there are form validation errors, then highlight the border of the input red
	$('div.required-field-error').prev().css('border', '2px solid #CC0000');
});

$('body').on('pageinit', '#register_form', function(event, ui) {

	Recaptcha.destroy();	

	Recaptcha.create( recaptcha_public_key, recaptcha_widget_name, recaptcha_options );

	set_recaptcha_padding_and_width();
});

$('body').on('pagebeforeshow', '#register_form', function(event, ui) {

	$('body').attr('class', 'ui-mobile-viewport ui-overlay-a');

	// If there are form validation errors, then highlight the border of the input red
	$('div.required-field-error').prev().css('border', '2px solid #CC0000');
});

$('body').on('pagehide', '#register_form', function(event, ui) {

	//Recaptcha.destroy();
});

$('body').on('pageshow', '#register_form', function(event, ui) {

	Recaptcha.destroy();	

	Recaptcha.create( recaptcha_public_key, recaptcha_widget_name, recaptcha_options );

	set_recaptcha_padding_and_width();

	// If there are form validation errors, then highlight the border of the input red
	$('div.required-field-error').prev().css('border', '2px solid #CC0000');
});

$('body').on('pagebeforeshow', '#paypal', function(event, ui) {

	$('body').attr('class', 'ui-mobile-viewport ui-overlay-a');
});

$('body').on('pagebeforeshow', '#paypal_payment_cancelled', function(event, ui) {

	$('body').attr('class', 'ui-mobile-viewport ui-overlay-a');
});

$('body').on('click', '#login_form a.ui-btn', function(evt) {

	// add the green hover state to the submit button's parent div
	$(this).addClass('ui-btn-hover-g');
});

$('body').on('click', '#register_form a.ui-btn', function(evt) {

	// add the green hover state to the submit button's parent div
	$(this).addClass('ui-btn-hover-g');
});

$('body').on('click', '#login_submit', function(evt) {

	// add the green hover state to the submit button's parent div
	$('#login_submit').parent().addClass('ui-btn-hover-g');
});

$('body').on('click', '#register_submit', function(evt) {

	// add the green hover state to the submit button's parent div
	$('#register_submit').parent().addClass('ui-btn-hover-g');
});

$('body').on('click', '#paypal_submit', function(evt) {

	// add the green hover state to the submit button's parent div
	$('#paypal_submit').parent().addClass('ui-btn-hover-g');
});

$('body').on('click', '#refresh_captcha', function(evt) {

	evt.preventDefault();

	// add the green hover state to the refresh link's parent div
	$('#refresh_captcha').parent().addClass('ui-btn-hover-g');

	refresh_recaptcha();
});

$('body').on('click', '#switch_type_audio', function(evt) {

	evt.preventDefault();

	// add the green hover state to the refresh link's parent div
	$('#switch_type_audio').parent().addClass('ui-btn-hover-g');

	setTimeout(function(){

		// remove the green hover state to the refresh link's parent div
		$('#switch_type_audio').parent().removeClass('ui-btn-hover-g');
	
	}, 500);

	Recaptcha.switch_type('audio');
});

$('body').on('click', '#switch_type_image', function(evt) {

	evt.preventDefault();

	// add the green hover state to the refresh link's parent div
	$('#switch_type_image').parent().addClass('ui-btn-hover-g');

	setTimeout(function(){

		// remove the green hover state to the refresh link's parent div
		$('#switch_type_image').parent().removeClass('ui-btn-hover-g');
	
	}, 500);

	Recaptcha.switch_type('image');
});

$('body').on('orientationchange', '#register_form', function(event, ui) {

	set_recaptcha_padding_and_width();
});

function set_recaptcha_padding_and_width() {

	// Use this if the register page is a page
	//var page_width = $('html,body').width();

	// Use this is the register page is a dialog
	var page_width = $('div[role="dialog"]').width();

	page_width = Math.floor(page_width * .98);

	var recaptcha_image_width = 280;

	if (page_width > recaptcha_image_width) {

		var width_diff = page_width - recaptcha_image_width;

		var padding = Math.floor(width_diff / 2);

		$('#recaptcha_image').css('padding-left', padding+'px');
		$('#recaptcha_image img').css('cursor', 'pointer');
		$('#recaptcha_image img').css('width', recaptcha_image_width+'px !important');
		$('#recaptcha_image img:hover').css('width', recaptcha_image_width+'px !important');
	}
}

function refresh_recaptcha() {

	$('#recaptcha_image').fadeOut(100, function() {

		$('#recaptcha_image').parent().append('<img style=\'padding-top:15px;\' src=\'images/ajax-loader-fast.gif\' />'); 

		Recaptcha.reload();

		set_recaptcha_padding_and_width();
	}); 	

	setTimeout(function(){ $('img:last').remove(); $('#recaptcha_image').fadeIn(500); $('#register_form div.ui-btn').removeClass('ui-btn-hover-g'); }, 2000);
}

function change_page(to, transition, reload, showmsg, changeHash, type, data, reverse, allowSPT) {
	
	to 		= to 		|| "home";
	transition 	= transition 	|| "fade";
	reload 		= reload 	|| false;
	showmsg 	= showmsg 	|| false;
	changeHash 	= changeHash 	|| false;
	type 		= type 		|| "get";
	data 		= data 		|| "";
	reverse		= reverse	|| false;
	allowSPT 	= allowSPT 	|| false; 
	
	$.mobile.changePage(	to, { 
					transition				: transition,
					reloadPage				: reload,
					showLoadMsg				: showmsg,
					changeHash				: changeHash,
					type 					: type,
					data 					: data,
					reverse					: reverse,
					allowSamePageTransition			: allowSPT
				    } 
			    ); 
}

function incremental_scroll_up() {

	var scroll_increment = $('html,body').height();

	current_scroll_position = $(window).scrollTop();

	var scroll_up_position = current_scroll_position - scroll_increment;

	if (scroll_up_position < 0) {
		
		scroll_up_position = 0;
	}

	$('html,body').animate({scrollTop:scroll_up_position}, 1000, function() {
	
		//$.mobile.fixedToolbars.show();
	});
}

function incremental_scroll_down(section) {

	var scroll_increment = $('html,body').height();

	current_scroll_position = $(window).scrollTop();

	var scroll_down_position = 0;

	if (current_scroll_position > 0) {
		
		scroll_down_position = current_scroll_position;
	}

	scroll_down_position += scroll_increment;

	$('html,body').animate({scrollTop:scroll_down_position}, 1000, function() {
	
		//$.mobile.fixedToolbars.show();
	});

	//alert("inside incremental_scroll_down section = "+section);

	// Manually make a call to the public version of the _load function
	$( "#index" ).lazyloader( "loadMore" );
}

function attach_window_scroll_event(section) {

	$(window).scroll(function() {

		if (!window_scroll_event_just_fired) {

			if (!mousewheel_event_just_fired) {

				window_scroll_event_just_fired = true;

				//lazy_load_more_list_items(section, 200);
			}	
		}

		setTimeout(function() {

			window_scroll_event_just_fired = false;

		}, 1000);

	});
}
