jQuery(document).ready(function($) {

	$("#rc_mmip_inside_date").datetimepicker({
		formatDate: 'm/d/Y',
		minDate: '0',
		step: 30
	});
	
	$(".set_custom_images").click(function(e){
		
		if ( typeof wp !== 'undefined' && wp.media && wp.media.editor) {
			e.preventDefault();
			var custom_uploader;
				//Extend the wp.media object
			custom_uploader = wp.media.frames.file_frame = wp.media({
				title: 'Choose Image',
				button: {
					text: 'Choose Image'
				},
				multiple: false
			});

			//When a file is selected, grab the URL and set it as the text field's value
			custom_uploader.on('select', function() {
				attachment = custom_uploader.state().get('selection').first().toJSON();
				$('#rc_mmip_inside_custom_img').val(attachment.url);
			});

			//Open the uploader dialog
			custom_uploader.open();
			return false;
		}
	});
	
	$('#rc_maint_whereto').change(function() {
		var value = $(this).val();
		if (value == '0') {
			$(".rc_maint_mode_pg_container").fadeIn();
			$(".rc_maint_mode_url_container").hide();
		} else {
			$(".rc_maint_mode_url_container").fadeIn();
			$(".rc_maint_mode_pg_container").hide();
		}
	});

	$("#rc_mmip_mchimp_opt").change(function() {
		var v = $(this).val();
		if (v != 0) {
			$(".rc_mmip_mchimp_field").fadeIn();
		} else {
			$(".rc_mmip_mchimp_field").hide();
		}
	});

	$('#rc_maint_mode_submit').click(function() {

		if ($('#rc_maint_whereto').val() == '1' && $('#rc_maint_mode_url').val() == '') {
			alert('Please enter a URL to redirect the unauthorized user to.');
			$('#rc_maint_mode_url').focus();
			return false;
		}
	});
	
	$("#load_mchimp_list_btn").click(function(){
		
		var chimp_api = $('#rc_mmip_mchimp_api').val();
		
		if( chimp_api != ''){
			var data = {
				'action': 'rc_mmip_mchimp_lists',
				'chimp_api': chimp_api
			};


			$.post(rcmmipAjaxurl,data, function(res){
				$(".list_spot").html(res);
				$(".rc_mmip_mchimp_lists").fadeIn();
			});	
			
		}else{
			alert('Please enter your MailChimp API.');
		}
		
		
	});
	
	$(".rc_nav_tab").click(function(){
		
		var id = $(this).attr('id');
		var className = id.replace('_tab', '');

		$('.inside:visible').not('.sidebar_rc_maint, .rc_mmip_save_btn_container').hide();
		$(".rc_mmip_tab_wrapper").find('.nav-tab-active').removeClass('nav-tab-active');
		$(this).addClass('nav-tab-active');
		$(".inside." + className).fadeIn();
		
	});
	
	$("#rc_mmip_inside_bgopt").change(function(){
		var x = $(this).val();
		
		if(x != 1){
			$(".rc_mmip_default_bg_container").fadeIn();
			$(".rc_mmip_default_custom_container").hide();
			$(".rc_mmip_custom_img_preview").hide();
			$(".rc_mmip_inside_custom_img").val("");
			$(".d_bg_label").fadeIn();
			$(".c_bg_label").hide();
		}else{
			$(".rc_mmip_default_custom_container").fadeIn();
			$(".rc_mmip_default_bg_container").hide();
			$(".c_bg_label").fadeIn();
			$(".d_bg_label").hide();
		}
	});
	
	$(".rc_mmip_images_layout li img").click(function(){
		var url = $(this).attr('src');
		$(".rc_mmip_images_layout").find('.active_li').removeClass('active_li');
		$(this).parent('li').addClass('active_li');
		$("#rc_mmip_default_bg").val(url);
	});
	
	
	$("#rc_mmip_inside_ctopt").change(function(){
		var z = $(this).val();
		if(z == 1){
			$(".rc_mmip_countdown_date_picker").fadeIn();
		}else{
			$(".rc_mmip_countdown_date_picker").hide();
		}
	});	
});