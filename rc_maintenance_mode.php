<?php

/*
*	 Plugin Name: Maintenance Mode by IP Address
*	 Plugin URI: http://rcit.consulting/
*	 Description: Maintenance Mode by IP Address allows you to exclude your site from visitors other than you, by using your IP address, while doing maintenance on your site.
*	 Version: 1.0
*	 Author: RC IT Consulting Firm LLC
*	 Author URI: http://rcit.consulting/
*/

/*
 * Assign global variables
*/

global $rc_mmip_plugin_url;
global $rc_mmip_options;

$rc_mmip_plugin_url = plugin_dir_url( __FILE__ );
$rc_mmip_options = array();


//PLUGIN ACTIVATION

function rc_mmip_activation(){
	
	$rc_mmip_page_title = 'Maintenance by IP Address';
	$rc_mmip_page_slug = 'maintenance_mode-ip';
	
	//Create a page, 
	if ( null == get_page_by_title( $rc_mmip_page_title )) {
	
		$new_post = array(
			'post_title' => $rc_mmip_page_title,
			'post_content' => '',
			'post_status' => 'publish',
			'post_type' => 'page',
			'comment_status'   => 'closed',
			'ping_status'   => 'closed',
			'post_author' 	=> $author_id,
			'post_name'    => $rc_mmip_page_slug,
			'page_template' => plugin_dir_url( __FILE__ ). 'templates/single-rc_mmip.php'
		);
		
		$rc_mmip_pageid = wp_insert_post($new_post);
	}
	//Page settings
	$rc_mmip_options['rc_mmip_pageid'] = $rc_mmip_pageid;
	$rc_mmip_options['rc_mmip_page_title'] = $rc_mmip_page_title;
	$rc_mmip_options['rc_mmip_page_slug'] = $rc_mmip_page_slug;

	//Inside Default Template Options
	$rc_mmip_options['rc_mmip_inside_title'] = '%blog_name% site is coming soon...';
	$rc_mmip_options['rc_mmip_inside_date'] = '';
	$rc_mmip_options['rc_mmip_inside_msg'] = '';
	$rc_mmip_options['rc_mmip_inside_ctopt'] = 0;
	$rc_mmip_options['rc_mmip_inside_bgopt'] = 0;
	$rc_mmip_options['rc_mmip_default_bg'] = '';
	$rc_mmip_options['rc_mmip_inside_custom_img'] = '';

	//Maintenance settings
	$rc_mmip_options['rc_mmip_switch'] = 0;
	$rc_mmip_options['rc_mmip_allowedip'] = array();
	$rc_mmip_options['rc_mmip_whereto'] = 0;
	$rc_mmip_options['rc_mmip_url'] = '';
	$rc_mmip_options['rc_mmip_topage'] = $rc_mmip_page_slug;

	//Mailchimp Settings
	$rc_mmip_options['rc_mmip_mchimp_opt'] = 0;
	$rc_mmip_options['rc_mmip_mchimp_api'] = '';
	$rc_mmip_options['rc_mmip_mchimp_list'] = '';

	//Social Profiles	
	$rc_mmip_options['rc_mmip_social'] = '';

	update_option('rc_mmip_settings', $rc_mmip_options);

}

register_activation_hook(__FILE__, 'rc_mmip_activation');

register_deactivation_hook( __FILE__, 'rc_mmip_deactivate' );

function rc_mmip_deactivate(){
	
	$rc_mmip_options = get_option('rc_mmip_settings');
	$rc_mmip_pageid = $rc_mmip_options['rc_mmip_pageid'];
	
	wp_delete_post($rc_mmip_pageid, true);
	delete_option( 'rc_mmip_settings' );
}



//add link to plugin in the menu page - admin
function rc_mmip_menu(){

	$icon_url = plugin_dir_url( __FILE__ ) . 'images/menu_icon20x20.png';
	
	add_menu_page(
		'Maintenance Mode by IP',
		'Maintenance Mode by IP',
		'manage_options',
		'rc-mmip',
		'rc_mmip_options_page', //function
		$icon_url,
		99
	);
	
}
add_action('admin_menu', 'rc_mmip_menu');

//Get current options, if any.
function rc_mmip_options_page(){

	if(!current_user_can('manage_options')){
		wp_die('You do not have sufficient permissions to access this page.');
	}
	
	$rc_mmip_options = get_option('rc_mmip_settings');

	if($rc_mmip_options != ''){
		
		//page settings
		$rc_mmip_pageid = $rc_mmip_options['rc_mmip_pageid'];
		$rc_mmip_page_title = $rc_mmip_options['rc_mmip_page_title'];
		$rc_mmip_page_slug = $rc_mmip_options['rc_mmip_page_slug'];
		
		
		//Maintenance Mode Options
		$rc_mmip_switch = $rc_mmip_options['rc_mmip_switch'];
		
		if($rc_mmip_options['rc_mmip_allowedip'] != ''){ 
			$rc_mmip_allowedip = implode("\n", $rc_mmip_options['rc_mmip_allowedip']);
		}else{
			$rc_mmip_allowedip = '';
		}
		$rc_mmip_inside_ctopt = $rc_mmip_options['rc_mmip_inside_ctopt'];
		$rc_mmip_whereto = $rc_mmip_options['rc_mmip_whereto'];
		$rc_mmip_url = $rc_mmip_options['rc_mmip_url'];
		$rc_mmip_topage = $rc_mmip_options['rc_mmip_topage'];
		
		//Inside Default Template Options
		$rc_mmip_inside_title = $rc_mmip_options['rc_mmip_inside_title'];
		$rc_mmip_inside_date = $rc_mmip_options['rc_mmip_inside_date'];
		$rc_mmip_inside_msg = $rc_mmip_options['rc_mmip_inside_msg'];
		$rc_mmip_inside_bgopt = $rc_mmip_options['rc_mmip_inside_bgopt'];
		$rc_mmip_default_bg = $rc_mmip_options['rc_mmip_default_bg'];
		$rc_mmip_inside_custom_img = $rc_mmip_options['rc_mmip_inside_custom_img'];
		
		//Mailchimp Options
		$rc_mmip_mchimp_opt = $rc_mmip_options['rc_mmip_mchimp_opt'];
		$rc_mmip_mchimp_api = $rc_mmip_options['rc_mmip_mchimp_api'];
		$rc_mmip_mchimp_list = $rc_mmip_options['rc_mmip_mchimp_list'];
		
		//Social Profiles
		$rc_mmip_social = $rc_mmip_options['rc_mmip_social'];
		
	}

	$images_url = plugin_dir_url( __FILE__ );
	require('includes/admin_opt.php');
}

//process form at post...
function rc_mmip_process_form(){
	
	//get saved options
	$rc_mmip_options = get_option('rc_mmip_settings');
	
	//page settings
	$rc_mmip_pageid = $rc_mmip_options['rc_mmip_pageid'];
	$rc_mmip_page_title = $rc_mmip_options['rc_mmip_page_title'];
	$rc_mmip_page_slug = $rc_mmip_options['rc_mmip_page_slug'];

	//Check if the form has being submitted
	if(isset($_POST['rc_maint_mode_form_submitted'])){
		
		$hidden_field = esc_html($_POST['rc_maint_mode_form_submitted']);
		
		//Check if hidden
		if($hidden_field == 'Y'){
			
			$_POST['rc_mmip_topage'] = sanitize_text_field($_POST['rc_mmip_topage']);
			
			$protocols = array('http', 'https');
			$_POST['rc_mmip_url'] = esc_url_raw($_POST['rc_mmip_url'], $protocols);

			$_POST['rc_mmip_fb'] = sanitize_text_field($_POST['rc_mmip_fb']);
			$_POST['rc_mmip_tw'] = sanitize_text_field($_POST['rc_mmip_tw']);
			$_POST['rc_mmip_gp'] = sanitize_text_field($_POST['rc_mmip_gp']);
			$_POST['rc_mmip_ig'] = sanitize_text_field($_POST['rc_mmip_ig']);
			
			$_POST['rc_mmip_fb_icon'] = sanitize_text_field($_POST['rc_mmip_fb_icon']);
			$_POST['rc_mmip_tw_icon'] = sanitize_text_field($_POST['rc_mmip_tw_icon']);
			$_POST['rc_mmip_gp_icon'] = sanitize_text_field($_POST['rc_mmip_gp_icon']);
			$_POST['rc_mmip_ig_icon'] = sanitize_text_field($_POST['rc_mmip_ig_icon']);
			
			$rc_mmip_social = array();
			
			if($_POST['rc_mmip_fb'] != ''){
				$rc_mmip_social['fb'] = array("username"=>$_POST['rc_mmip_fb'], "icon"=>$_POST['rc_mmip_fb_icon']);
				
			}
			
			if($_POST['rc_mmip_tw'] != ''){
				$rc_mmip_social['tw'] = array("username"=>$_POST['rc_mmip_tw'], "icon"=>$_POST['rc_mmip_tw_icon']);
				
			}
			
			if($_POST['rc_mmip_gp'] != ''){
				$rc_mmip_social['gp'] = array("username"=>$_POST['rc_mmip_gp'], "icon"=>$_POST['rc_mmip_gp_icon']);
				
			}
			
			if($_POST['rc_mmip_ig'] != ''){
				$rc_mmip_social['ig'] = array("username"=>$_POST['rc_mmip_ig'], "icon"=>$_POST['rc_mmip_ig_icon']);
				
			}

			if(isset($_POST['rc_mmip_switch']) && $_POST['rc_mmip_switch'] == 1){
				
				$_POST['rc_mmip_switch'] = (int) $_POST['rc_mmip_switch'];
				$rc_mmip_switch = (int) $_POST['rc_mmip_switch'];
				
				if ( null == get_page_by_title( $rc_mmip_page_title )) {
	
					$new_post = array(
						'post_title' => $rc_mmip_page_title,
						'post_content' => '',
						'post_status' => 'publish',
						'post_type' => 'page',
						'comment_status'   => 'closed',
						'ping_status'   => 'closed',
						'post_author' 	=> $author_id,
						'post_name'    => $rc_mmip_page_slug,
						'page_template' => plugin_dir_url( __FILE__ ). 'templates/single-rc_mmip.php'
					);

					$rc_mmip_pageid = wp_insert_post($new_post);
				}else{
					$current_page_status = get_post_status( $rc_mmip_pageid );
					if($current_page_status == 'trash' || $current_page_status == 'draft'){
						
						$new_status = array();
						$new_status['ID'] = $rc_mmip_pageid;
						$new_status['post_status'] = 'publish';

						wp_update_post($new_status);	
					}
				}	
			}else{
				
				$rc_mmip_switch = 0;
			}
			
			if(isset($_POST['rc_mmip_allowedip']) && $_POST['rc_mmip_allowedip'] != ''){

				$rc_mmip_allowedip = array_map( 'sanitize_text_field', explode( "\n", $_POST['rc_mmip_allowedip'] ) );

			}else{
				$rc_mmip_allowedip = array($_SERVER['REMOTE_ADDR']);
			}
			
			if(isset($_POST['rc_mmip_whereto'])){
				
				$_POST['rc_mmip_whereto'] = (int) $_POST['rc_mmip_whereto'];
				
				//Check if user selected a page or url
				if($_POST['rc_mmip_whereto'] == 1){
					$rc_mmip_url = $_POST['rc_mmip_url'];
					$rc_mmip_whereto = 1;
				}else{
					$rc_mmip_whereto = 0;
					$rc_mmip_url = '';
				}
				
			}else{
				$rc_mmip_whereto = 0;
				$rc_mmip_url = '';
			}
			
			if(isset($_POST['rc_mmip_mchimp_opt'])){
				//If chimp option is yes/1
				if($_POST['rc_mmip_mchimp_opt'] == 1 && isset($_POST['rc_mmip_mchimp_api']) && $_POST['rc_mmip_mchimp_api'] != '' && isset($_POST['rc_mmip_list_id']) ){
					
						
						$_POST['rc_mmip_mchimp_api'] = sanitize_text_field($_POST['rc_mmip_mchimp_api']);
						$_POST['rc_mmip_list_id'] = sanitize_text_field($_POST['rc_mmip_list_id']);
						
						$rc_mmip_mchimp_opt = 1;
						$rc_mmip_mchimp_api = $_POST['rc_mmip_mchimp_api'];
						$rc_mmip_list_id = $_POST['rc_mmip_list_id'];
					
				}else{
					$rc_mmip_mchimp_opt = 0;
					$rc_mmip_mchimp_api = '';
					$rc_mmip_list_id = '';
				}
				
			}else{
				//No post data found. We still need to keep the variables in the options database.
				$rc_mmip_mchimp_opt = 0;
				$rc_mmip_mchimp_api = '';
				$rc_mmip_list_id = '';
			}
			
			if(isset($_POST['rc_mmip_inside_title']) && $_POST['rc_mmip_inside_title'] != '' ){
				$rc_mmip_inside_title = sanitize_text_field($_POST['rc_mmip_inside_title']);
			}else{
				$rc_mmip_inside_title = '%blog_name% site is coming soon...';
			}
			if(isset($_POST['rc_mmip_inside_ctopt']) && $_POST['rc_mmip_inside_ctopt'] == 1){
				
				$rc_mmip_inside_ctopt = (int) $_POST['rc_mmip_inside_ctopt'];
				
				if(isset($_POST['rc_mmip_inside_date']) && $_POST['rc_mmip_inside_date'] != ''){
					$rc_mmip_inside_date = date("F d, Y H:i:s", strtotime($_POST['rc_mmip_inside_date']));
				}else{
					$rc_mmip_inside_date = date("F d, Y H:i:s", strtotime('+30 minutes', current_time( 'timestamp', 0 ))); 
				}	
			}else{
				$rc_mmip_inside_ctopt = 0;
				$rc_mmip_inside_date = ''; 
			}
			
			
			if(isset($_POST['rc_mmip_inside_msg']) && $_POST['rc_mmip_inside_msg']){
				$rc_mmip_inside_msg = esc_textarea($_POST['rc_mmip_inside_msg']);
			}else{
				$rc_mmip_inside_msg = '';
			}
			
			//Background settings
			if(isset($_POST['rc_mmip_inside_bgopt']) && $_POST['rc_mmip_inside_bgopt'] == 0){
				$rc_mmip_inside_bgopt = (int) $_POST['rc_mmip_inside_bgopt'];
				$rc_mmip_default_bg = esc_url_raw($_POST['rc_mmip_default_bg'], $protocols);
				$rc_mmip_inside_custom_img = '';
			}else if($_POST['rc_mmip_inside_bgopt'] == 1){
				
				if(isset($_POST['rc_mmip_inside_custom_img']) && $_POST['rc_mmip_inside_custom_img'] != ''){
					$rc_mmip_inside_bgopt = (int) $_POST['rc_mmip_inside_bgopt'];
					$rc_mmip_default_bg = '';
					$rc_mmip_inside_custom_img = esc_url_raw($_POST['rc_mmip_inside_custom_img'], $protocols);	
				}else{
					$rc_mmip_inside_bgopt = 0;
					$rc_mmip_default_bg = '';
					$rc_mmip_inside_custom_img = '';
				}
				
			}else{
				$rc_mmip_inside_bgopt = '0';
				$rc_mmip_default_bg = '';
				$rc_mmip_inside_custom_img = '';
			}
			
			//Page settings
			$rc_mmip_options['rc_mmip_pageid'] = $rc_mmip_pageid;
			$rc_mmip_options['rc_mmip_page_title'] = $rc_mmip_page_title;
			$rc_mmip_options['rc_mmip_page_slug'] = $rc_mmip_page_slug;

			//Maintenance settings
			$rc_mmip_options['rc_mmip_switch'] = $rc_mmip_switch;
			$rc_mmip_options['rc_mmip_allowedip'] = $rc_mmip_allowedip;
			$rc_mmip_options['rc_mmip_whereto'] = $rc_mmip_whereto;
			$rc_mmip_options['rc_mmip_url'] = $rc_mmip_url;
			$rc_mmip_options['rc_mmip_topage'] = $rc_mmip_page_slug;
			
			//Inside Default Template Options
			$rc_mmip_options['rc_mmip_inside_title'] = $rc_mmip_inside_title;
			$rc_mmip_options['rc_mmip_inside_date'] = $rc_mmip_inside_date;
			$rc_mmip_options['rc_mmip_inside_msg'] = $rc_mmip_inside_msg;
			$rc_mmip_options['rc_mmip_inside_ctopt'] = $rc_mmip_inside_ctopt;
			$rc_mmip_options['rc_mmip_inside_bgopt'] = $rc_mmip_inside_bgopt;
			$rc_mmip_options['rc_mmip_default_bg'] = $rc_mmip_default_bg;
			$rc_mmip_options['rc_mmip_inside_custom_img'] = $rc_mmip_inside_custom_img;

			//Mailchimp Settings
			$rc_mmip_options['rc_mmip_mchimp_opt'] = $rc_mmip_mchimp_opt;
			$rc_mmip_options['rc_mmip_mchimp_api'] = $rc_mmip_mchimp_api;
			$rc_mmip_options['rc_mmip_mchimp_list'] = $rc_mmip_list_id;

			//Social Profiles
			$rc_mmip_options['rc_mmip_social'] = $rc_mmip_social;
			
			update_option('rc_mmip_settings', $rc_mmip_options);
			wp_redirect('admin.php?page=rc-mmip');
		}
		
	}//end of checking for post data


	//Maintenance Mode Options
	$rc_mmip_switch = $rc_mmip_options['rc_mmip_switch'];

	if($rc_mmip_options['rc_mmip_allowedip'] != ''){ 
		$rc_mmip_allowedip = implode("\n", $rc_mmip_options['rc_mmip_allowedip']);
	}else{
		$rc_mmip_allowedip = '';
	}

	$rc_mmip_whereto = $rc_mmip_options['rc_mmip_whereto'];
	$rc_mmip_url = $rc_mmip_options['rc_mmip_url'];
	$rc_mmip_topage = $rc_mmip_options['rc_mmip_topage'];
	
	//Inside Default Template Options
	$rc_mmip_inside_title = $rc_mmip_options['rc_mmip_inside_title'];
	$rc_mmip_inside_date = $rc_mmip_options['rc_mmip_inside_date'];
	$rc_mmip_inside_msg = $rc_mmip_options['rc_mmip_inside_msg'];
	$rc_mmip_inside_ctopt = $rc_mmip_options['rc_mmip_inside_ctopt'];
	$rc_mmip_inside_bgopt = $rc_mmip_options['rc_mmip_inside_bgopt'];
	$rc_mmip_default_bg = $rc_mmip_options['rc_mmip_default_bg'];
	$rc_mmip_inside_custom_img = $rc_mmip_options['rc_mmip_inside_custom_img'];

	//Mailchimp Options
	$rc_mmip_mchimp_opt = $rc_mmip_options['rc_mmip_mchimp_opt'];
	$rc_mmip_mchimp_api = $rc_mmip_options['rc_mmip_mchimp_api'];
	$rc_mmip_mchimp_list = $rc_mmip_options['rc_mmip_mchimp_list'];
	
	//Social Profiles
	$rc_mmip_social = $rc_mmip_options['rc_mmip_social'];
	
	
	//Check if maintenance mode is on
	if($rc_mmip_switch != 0){
		
		$remote =  rc_maint_get_ip_address();
		
		if(!defined('DOING_AJAX')){
          	
			if(!(in_array($remote, $rc_mmip_options['rc_mmip_allowedip']))){

				if($rc_mmip_whereto == 0){
					
					if ( trim( $_SERVER['REQUEST_URI'], '/' ) != $rc_mmip_page_slug ) {
						show_admin_bar(false);
						wp_redirect( home_url($rc_mmip_page_slug), 301 );
						exit();
					}
					
				}else if($rc_mmip_whereto == 1){
					
					header('Location: ' . $rc_maint_mode_url);
					exit();
				}

			} 
		}
	}
}

add_action( 'init', 'rc_mmip_process_form' );


//get ip address from server or proxy
function rc_maint_get_ip_address(){
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
        if (array_key_exists($key, $_SERVER) === true){
            foreach (explode(',', $_SERVER[$key]) as $ip){
                $ip = trim($ip); // just to be safe

                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                    return $ip;
                }
            }
        }
    }
}

function rc_maint_mchimp_action(){
	
	//get saved options
	$rc_mmip_options = get_option('rc_mmip_settings');
	$apikey = $rc_mmip_options['rc_mmip_mchimp_api'];
	$listID = $rc_mmip_options['rc_mmip_mchimp_list'];
	$rc_mmip_list_id = $rc_mmip_options['rc_mmip_mchimp_list'];
	
	if($_POST['email'] != ''){
		
		$email = sanitize_email($_POST['email']);
		
	}else{
		echo 'No email address has been provided.';
		wp_die();
	}

	$dc = explode('-', $apikey);

	$auth = base64_encode( 'user:'.$apikey );

	$data = array(
		'apikey'        => $apikey,
		'email_address' => $email,
		'status'        => 'subscribed'
	);
	
	$json_data = json_encode($data);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://'.$dc[1].'.api.mailchimp.com/3.0/lists/' . $listID . '/members/');  //<list_id>/members/
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
												'Authorization: Basic '.$auth));
	curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);                                                                                                                  

	$result = curl_exec($ch);
	$receivedJson = json_decode($result, true);

	if($receivedJson['status'] == 'subscribed'){
		echo 'Thank you for subscribing to our mailing list. We will let you know as soon as our site is up and running.';
	}else if($receivedJson['status'] == '400'){
		$error = $pieces = preg_split('/(?=[A-Z])/',$receivedJson['detail']); // explode('.',$receivedJson['detail'] );
		
		if (strpos($receivedJson['detail'], $email) !== false) {
			echo $error[0];
		}else{
			echo 'There was an error when trying to subscribe you to our mailing list. Please try again later.';	
		}
	}else if($receivedJson['status'] == '500'){
		echo 'There was a problem on MailChimp servers. Please try again later.';
	}

	wp_die();
}


//Add a link to the Admin Bar
function rc_maint_mode_adminbar_link( $wp_admin_bar ) {
	
	$rc_mmip_options = get_option('rc_mmip_settings');
	$rc_mmip_switch = $rc_mmip_options['rc_mmip_switch'];
	
	
	
	if($rc_mmip_switch != 0){
		
		$rc_maint_mode_text = ' is <span id="rc_maint_mode_text" class="on">ON</span>';
		$args = array(
			'id'    => 'rc_maint_mode',
			'title' => 'Maintenance Mode' . $rc_maint_mode_text,
			'href' => 'admin.php?page=rc-mmip',
			'meta'  => array( 'class' => 'rc-mmip' )
		);
		$wp_admin_bar->add_node( $args );
		
	}
	
}
add_action( 'admin_bar_menu', 'rc_maint_mode_adminbar_link', 999 );



//Enable ajax
function rc_maint_enable_frontend_ajax(){
	
	$rc_mmip_options = get_option('rc_mmip_settings');
	
	
	if($rc_mmip_options['rc_mmip_inside_date'] != ''){
		$rc_mmip_inside_date = $rc_mmip_options['rc_mmip_inside_date'];
	}else{
		$rc_mmip_inside_date = date( 'F d, Y H:i:s', current_time( 'timestamp', 0 ) );
	}
	
?>
	<script>
		
		var rcmmipAjaxurl = '<?php echo admin_url("admin-ajax.php"); ?>';
		var rcmmip_date = '<?php echo $rc_mmip_inside_date; ?>';

	</script>
<?php
}
//add to front end
add_action('wp_head','rc_maint_enable_frontend_ajax');


//add to back end
add_action('admin_footer', 'rc_maint_enable_frontend_ajax');

add_action( 'wp_ajax_rc_maint_mchimp_action', 'rc_maint_mchimp_action' );
add_action( 'wp_ajax_nopriv_rc_maint_mchimp_action', 'rc_maint_mchimp_action' );

//Get MailChimp List for current api
function rc_mmip_mchimp_lists(){
	
	if(isset($_POST['chimp_api'])){
		$mchimp_api = esc_html($_POST['chimp_api']);
	}else{
		//get saved options
		$rc_mmip_options = get_option('rc_mmip_settings');
		$mchimp_api = $rc_mmip_options['rc_mmip_mchimp_api'];
		$rc_mmip_mchimp_list = $rc_mmip_options['rc_mmip_mchimp_list'];
	}

	$dc = explode('-', $mchimp_api);

	$auth = base64_encode( 'user:'.$mchimp_api );
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://'.$dc[1].'.api.mailchimp.com/3.0/lists/');
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization: Basic '.$auth));
	curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);                                                                                                                 

	$result = curl_exec($ch);
	$receivedJson = json_decode($result, true);
	
	if(isset($_POST['chimp_api'])){
		//Get List ID
		if(is_array($receivedJson['lists'])){
			$output .= '<select name="rc_mmip_list_id" id="rc_mmip_list_id">';

			foreach($receivedJson['lists'] as $list){
				$output .= "<option value='". $list['id']."'>". $list['name']."</option>";
			}
			$output .= '</select>';
			echo $output;
		}else{
			echo '<br />' . $receivedJson['title'];
		}	
		
	}else{
		if(is_array($receivedJson['lists'])){
			echo '<select name="rc_mmip_list_id" id="rc_mmip_list_id">';

			foreach($receivedJson['lists'] as $list){
				echo "<option value='". $list['id']."'" . selected($rc_mmip_mchimp_list, $list['id']) . ">". $list['name']."</option>";
			}
			echo '</select>';
		}else{
			echo '<br />' . $receivedJson['title'];
		}	
	}

	if(isset($_POST['chimp_api'])){
		wp_die();
	}
	
}

add_action( 'wp_ajax_rc_mmip_mchimp_lists', 'rc_mmip_mchimp_lists' );


//Add css stylesheet to admin head.
function rc_maint_mode_styles(){
	
	wp_enqueue_style('rc_maint_frontend_css2',  plugin_dir_url( __FILE__ ) . 'templates/assets/css/jquery.datetimepicker.css');
	wp_enqueue_style('rc_maint_backend_css',  plugin_dir_url( __FILE__ ) . 'rc_maint_backend.css');
	wp_enqueue_script('rc_maint_backend_js',  plugin_dir_url( __FILE__ ) . 'rc_maint_backend.js', array('jquery'),'', true);
	wp_enqueue_script('rc_maint_frontend_js2',  plugin_dir_url( __FILE__ ) . 'templates/assets/js/jquery.datetimepicker.js', array('jquery'),'', true);
	wp_enqueue_media ();
}

add_action('admin_enqueue_scripts','rc_maint_mode_styles');


function template_chooser($template){
	
    global $wp_query;
	global $rc_maint_custom_ptitle;
	
    $plugindir = dirname(__FILE__);
	
	if(is_page($rc_maint_custom_ptitle)){
		return $plugindir . '/templates/single-rc_mmip.php';
	}
	
    return $template;   
}
add_filter('template_include', 'template_chooser');



function rc_maint_frontend_required_styles(){
	
	wp_enqueue_script('rc_maint_frontend_js1',  plugin_dir_url( __FILE__ ) . 'templates/assets/js/modernizr.custom.js', array('jquery'),'', true);
	wp_enqueue_script('rc_maint_frontend_js2',  plugin_dir_url( __FILE__ ) . 'templates/assets/js/bootstrap.min.js', array('jquery'),'', true);
	wp_enqueue_script('rc_maint_frontend_js7',  plugin_dir_url( __FILE__ ) . 'templates/assets/js/flowtype.js', array('jquery'),'', true);
	wp_enqueue_script('rc_maint_frontend_js3',  plugin_dir_url( __FILE__ ) . 'templates/assets/js/soon/plugins.js', array('jquery'),'', true);
	wp_enqueue_script('rc_maint_frontend_js4',  plugin_dir_url( __FILE__ ) . 'templates/assets/js/soon/jquery.themepunch.revolution.min.js', array('jquery'),'', true);
	wp_enqueue_script('rc_maint_frontend_js5',  plugin_dir_url( __FILE__ ) . 'templates/assets/js/soon/custom.js', array('jquery'),'', true);
	wp_enqueue_script('rc_maint_frontend_js6',  plugin_dir_url( __FILE__ ) . 'templates/assets/js/custom2.js', array('jquery'),'', true);
	wp_enqueue_style('rc_maint_frontend_css2', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
	
	
}
add_action('wp_enqueue_scripts','rc_maint_frontend_required_styles');




?>