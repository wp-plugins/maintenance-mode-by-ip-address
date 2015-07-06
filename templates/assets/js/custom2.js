jQuery(document).ready(function($){

	
	//Get data on click.
	$(".rc_submit_mailchimp").click(function(e){
		var email = $('.rc_maint_mc_email').val();
		
		var data = {
			'action': 'rc_maint_mchimp_action',
			'email': email
		};
		$.post(rcmmipAjaxurl,data, function(res){
			
			$(".rc_maint_mc_email").val("");
			$(".thank_you_msg").html(res);
		});
	});
});