
<div class="wrap">
		
	<div id="icon-options-general" class="icon32"></div>
	<h2>
		Maintenance Mode By IP Address - <span class="rc_maint_title_small">Your IP Address: <? echo $_SERVER['REMOTE_ADDR']; ?></span>
	</h2>
	<h2 class="nav-tab-wrapper rc_mmip_tab_wrapper">
		<a class="nav-tab rc_nav_tab nav-tab-active" id="rc_mmip_general_tab">General</a>
		<a class="nav-tab rc_nav_tab" id="rc_mmip_design_tab">Design</a>
		<a class="nav-tab rc_nav_tab" id="rc_mmip_social_tab">Social</a>
		<a class="nav-tab rc_nav_tab" id="rc_mmip_mchimp_tab">MailChimp</a>
	</h2>
	
	
	<div id="poststuff">

		<div id="post-body" class="metabox-holder columns-2">

			<!-- main content -->
			<div id="post-body-content">

				<div class="meta-box-sortables ui-sortable">

					<form name="rc_maint_mode_form" id="rc_maint_mode_form" method="post" action="">
					<input type="hidden" name="rc_maint_mode_form_submitted" value="Y">
						
					<div class="postbox">
						
						<div class="inside rc_mmip_general">
							
								<div class="rc_options_title">
									>> Basic Settings
								</div>
								<table class="form-table">
									<tr>
										<td colspan="2"><label for="rc_mmip_switch" id="label_one"> Maintenance Mode </label>
										<select name="rc_mmip_switch" id="rc_mmip_switch" >
											<option value="0" <? selected( $rc_mmip_switch, 0); ?>>OFF</option>
											<option value="1" <? selected( $rc_mmip_switch, 1); ?>>ON</option>
										</select></td>
									</tr>
									<tr>
										<td>
											<p class="rc_maint_fieldlabel">
												IP Address(es) that should have access to the site.
											</p>
											<textarea id="rc_mmip_allowedip" name="rc_mmip_allowedip" cols="80" rows="10" class="all-options"><? echo $rc_mmip_allowedip; ?></textarea>
											<p class="rc_maint_small_txt">
												Please enter one IP Address per line. If maintenance mode is activated, and no IP address is provided, your current IP address will be used. 
											</p>
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<label id="label_two">Send unauthorized visitors to:</label>
											<select name="rc_mmip_whereto" id="rc_maint_whereto" >
												<option value="0" <? selected( $rc_mmip_whereto, 0); ?>>Page</option>
												<option value="1" <? selected( $rc_mmip_whereto, 1); ?>>URL</option>
											</select>
										</td>
									</tr>
									<tr class="rc_maint_mode_pg_container" <? if($rc_mmip_url != ''): ?> style="display:none;" <? endif; ?>>
										<td colspan="2"><label>Page to use: </label>
										<?
												$args = array(
													'sort_order' => 'asc',
													'sort_column' => 'post_title',
													'hierarchical' => 1,
													'exclude' => '',
													'include' => '',
													'meta_key' => '',
													'meta_value' => '',
													'authors' => '',
													'child_of' => 0,
													'parent' => -1,
													'exclude_tree' => '',
													'number' => '',
													'offset' => 0,
													'post_type' => 'page',
													'post_status' => 'publish'
												); 
												$pages = get_pages($args); 
												//echo count($pages);
											?>
											<select name="rc_mmip_topage" id="rc_maint_mode_page" >
												<?
													foreach($pages as $page){
														?>
															<option value="<? echo $page->post_name ; ?>" <? selected( $rc_mmip_topage, $page->post_name ); ?>> <? echo $page->post_title; ?></option>
														<?	
													}
												?>
												
											</select>
										</td>
										<tr class="rc_maint_mode_url_container" <? if($rc_mmip_url != ''): ?> style="display:block;" <? endif; ?> >
											<td colspan="2">
												<label id="label_three">URL:</label>
												<input type="url" name="rc_mmip_url" id="rc_maint_mode_url" class="regular-text" value="<?php echo $rc_mmip_url;  ?>">
												<p class="rc_maint_small_txt">
													Include http:// or https:// 
												</p>
											</td>
										</tr>
									</tr>
								</table>		
						</div>
						<!-- .inside -->

						<div class="inside rc_mmip_design">
							<div class="rc_options_title">
								>> Design Settings
							</div>
							<table class="form-table">
								<tr>
									<td colspan="2">
										Customize the page your visitors will see during maintenance mode. 
										</td>
								</tr>
								<tr>
									<td><label>Page Title</label></td>
									<td><input type="text" name="rc_mmip_inside_title" id="rc_mmip_inside_title" class="regular-text" value="<?php echo $rc_mmip_inside_title; ?>">
									<p class="rc_maint_small_txt">
										To add your blog name in the title, add '%blog_name%' to the text.
									</p>
									</td>
								</tr>
								<tr>
									<td><label>Show Countdown?</label></td>
									<td>
										<select name="rc_mmip_inside_ctopt" id="rc_mmip_inside_ctopt">
											<option value="0" <? selected( $rc_mmip_inside_ctopt, 0); ?>>NO</option>
											<option value="1" <? selected( $rc_mmip_inside_ctopt, 1); ?>>YES</option>
										</select>
									</td>
								</tr>
								<tr class="rc_mmip_countdown_date_picker"  style=" display: <? if($rc_mmip_inside_ctopt == 1) { echo 'table-row;'; }else{ echo 'none;'; } ?>">
									<td><label>When will your site be ready?</label>
										<p class="rc_maint_small_txt">
											Date and time.
										</p>
									</td>
									<td>
										<input type="text" name="rc_mmip_inside_date" id="rc_mmip_inside_date" class="regular-text" value="<?php  if($rc_mmip_inside_date != '') : echo date('Y/m/d H:i', strtotime($rc_mmip_inside_date)); endif; ?>">
										<p class="rc_maint_small_txt">
											If no date/time is provided, and maintenance mode is set to 'ON', a default date will be used. Default: current date/time + 30 minutes. 
											 Make sure to select the proper timezone in the Wordpress Settings Page.
										</p>
									</td>
								</tr>
								<tr>
									<td><label>Custom Message</label></td>
									<td class="rc_mmip_inside_msg_textarea">
										<?php
											$content = $rc_mmip_inside_msg;
											$editor_id = 'rcmmipinsidemsg';
											$settings = array(
												'wpautop' => false,
												'media_buttons' =>  false,
												'textarea_name' => 'rc_mmip_inside_msg',
												'textarea_rows' => 30,
												'tabindex' => '0',
												'editor_css' => '',
												'editor_class' => '',
												'teeny' => false,
												'dfw' => false,
												'quicktags' => false
											);
											wp_editor( $content, $editor_id , $settings );
										
										?>
									<p class="rc_maint_small_txt">
										
									</p>
									</td>
								</tr>
								<tr>
									<td><label>Background</label></td>
									<td><select name="rc_mmip_inside_bgopt" id="rc_mmip_inside_bgopt">
											<option value="0" <? selected( $rc_mmip_inside_bgopt, 0); ?>>Predefined Backgrounds</option>
											<option value="1" <? selected( $rc_mmip_inside_bgopt, 1); ?>>Custom Background</option>
										</select>
									</td>
								</tr>
								<tr>
									
									<td>
										
										<div class="d_bg_label" style="display: <?php if($rc_mmip_inside_bgopt == 0){ ?>  block; <? }else{  ?> none; <? } ?>" >
											<label>Default Backgrounds:</label>
										</div>
											
										
										<div class="c_bg_label" style="display: <?php if($rc_mmip_inside_bgopt == 1){ ?>  block; <? }else{  ?> none; <? } ?>">
											<label>Upload a background:</label>
											<p class="rc_maint_small_txt">
												Minimum Size 1300x867 pixels
											</p>	
										</div>
									</td>
									
									<? 
										$img = substr($rc_mmip_default_bg, -5);
									?>
									<td class="rc_mmip_default_bg_container" style="display: <?php if($rc_mmip_inside_bgopt == 0){ ?>  block; <? }else{  ?> none; <? } ?>">
										<ul class="rc_mmip_images_layout">
											<li class="<? if($img == '1.jpg'){ echo 'active_li'; }else if($rc_mmip_default_bg == ''){ echo 'active_li'; } ?>"><img src="<?php echo $images_url; ?>templates/assets/img/slider/1.jpg" width="200"></li>
											<li class="<? if($img == '2.jpg'): echo 'active_li'; endif; ?>"><img src="<?php echo $images_url; ?>templates/assets/img/slider/2.jpg" width="200"></li>
											<li class="<? if($img == '3.jpg'): echo 'active_li'; endif; ?>"><img src="<?php echo $images_url; ?>templates/assets/img/slider/3.jpg" width="200"></li>
											<li class="<? if($img == '4.jpg'): echo 'active_li'; endif; ?>"><img src="<?php echo $images_url; ?>templates/assets/img/slider/4.jpg" width="200"></li>
										</ul>
										<input type="hidden" name="rc_mmip_default_bg" id="rc_mmip_default_bg" value="<?php if($rc_mmip_default_bg != ''): echo $rc_mmip_default_bg; else: echo $images_url . 'templates/assets/img/slider/1.jpg'; endif; ?>">
									</td>
									
									<td class="rc_mmip_default_custom_container" style="display: <?php if($rc_mmip_inside_bgopt == 1){ ?>  block; <? }else{  ?> none; <? } ?>">
										<input type="text" class="regular-text process_custom_images" id="rc_mmip_inside_custom_img" name="rc_mmip_inside_custom_img" value="<?php echo $rc_mmip_inside_custom_img; ?>">
										<button class="set_custom_images button">Select Image</button>
									</td>
								</tr>
								<?php if($rc_mmip_inside_bgopt == 1) { ?>
								<tr class="rc_mmip_custom_img_preview">
									<td><label>Preview:</label></td>
									<td>
										<p>
											<img src="<?php echo $rc_mmip_inside_custom_img; ?>" width="200">
										</p>
									</td>
								</tr>
								<?php } ?>
							</table>

						</div><!-- End of inside.rc_mmip_design -->
						
						<div class="inside rc_mmip_social">

							<div class="rc_options_title">
								>> Social Profiles
							</div>
							<table class="form-table rc-mmip-table">
								<tr>
									<td colspan="2"><label id="rc-fb">Facebook</label></td>
								</tr>
								<tr>
									<td colspan="2">
										https://facebook.com/ <input type="text" name="rc_mmip_fb" id="rc_mmip_fb" class="regular-text" value="<?php if(is_array($rc_mmip_social) && !empty($rc_mmip_social)){ echo $rc_mmip_social['fb']['username']; } ?>">
										<input type="hidden" name="rc_mmip_fb_icon" value="fa-facebook">
									</td>
								</tr>
								<tr>
									<td colspan="2"><label id="rc-tw">Twitter</label></td>
								</tr>
								<tr>
									<td colspan="2">
										https://twitter.com/ <input type="text" name="rc_mmip_tw" id="rc_mmip_tw" class="regular-text" value="<?php if(is_array($rc_mmip_social) && !empty($rc_mmip_social)){ echo $rc_mmip_social['tw']['username']; } ?>">
										<input type="hidden" name="rc_mmip_tw_icon" value="fa-twitter">
									</td>
								</tr>
								<tr>
									<td><label id="rc-gg">Google+</label></td>
								</tr>
								<tr>
									<td colspan="2">
										https://plus.google.com/+ <input type="text" name="rc_mmip_gp" id="rc_mmip_gp" class="regular-text" value="<?php if(is_array($rc_mmip_social) && !empty($rc_mmip_social)){ echo $rc_mmip_social['gp']['username']; } ?>">
										<input type="hidden" name="rc_mmip_gp_icon" value="fa-google-plus">
									</td>
								</tr>
								<tr>
									<td colspan="2"><label id="rc-ig">Instagram</label></td>
								</tr>
								<tr>
									<td colspan="2">
										https://instagram.com/ <input type="text" name="rc_mmip_ig" id="rc_mmip_ig" class="regular-text" value="<?php if(is_array($rc_mmip_social) && !empty($rc_mmip_social)){ echo $rc_mmip_social['ig']['username'];  } ?>">
										<input type="hidden" name="rc_mmip_ig_icon" value="fa-instagram">
									</td>
								</tr>
							</table>	
						</div><!-- End of inside.rc_mmip_social -->
							
						<div class="inside rc_mmip_mchimp">
								
								<div class="rc_options_title">
									>> Mailchimp Options
								</div>
								<table class="form-table">
									<tr>
										<td colspan="2">
											<label>Include Mailchimp Subscribe Field?</label>
											<select name="rc_mmip_mchimp_opt" id="rc_mmip_mchimp_opt">
												<option value="1" <? selected( $rc_mmip_mchimp_opt, 1); ?>>YES</option>
												<option value="0" <? selected( $rc_mmip_mchimp_opt, 0); ?>>NO</option>
											</select>
										</td>
									</tr>
									<tr class="rc_mmip_mchimp_field" <? if($rc_mmip_mchimp_api != ''): ?> style="display:block;" <? endif; ?>>
										<td colspan="2"><label>API Key: </label>
											<input type="text" name="rc_mmip_mchimp_api" id="rc_mmip_mchimp_api" class="regular-text" value="<?php echo $rc_mmip_mchimp_api;  ?>"> 
											<input class="button-primary" type="button" name="load_mchimp_list_btn" id="load_mchimp_list_btn" value="<?php esc_attr_e( 'Load Lists' ); ?>" />
												<p class="rc_maint_small_txt">
													Please enter your MailChimp API. To find more about how to get an API from Mailchimp, <a href="http://kb.mailchimp.com/accounts/management/about-api-keys" target="_blank">click here</a>.
												</p>
										</td>
									</tr>
									<tr class="rc_mmip_mchimp_lists" <? if($rc_mmip_mchimp_list != ''): ?> style="display:block;" <? endif; ?>>
										<td><label><? if($rc_mmip_mchimp_list != ''): echo 'List selected: '; else: echo 'Select a List'; endif; ?> </label></td>
										<td class="list_spot">
											<? if($rc_mmip_mchimp_list != ''): rc_mmip_mchimp_lists();  endif; ?> 
										</td>
									</tr>
								</table>
						</div>
							<!-- .inside -->
						<div class="inside rc_mmip_save_btn_container">
							<table class="form-table">
							<tr>
								<td colspan="2">
									<p>
										<input class="button-primary" type="submit" id="rc_maint_mode_submit" name="rc_maint_mode_submit" value="<?php esc_attr_e( 'Save' ); ?>" />
									</p>
								</td>
							</tr>
							</table>
						</div>
				</form>
					</div>
					<!-- .postbox -->

				</div>
				<!-- .meta-box-sortables .ui-sortable -->

			</div>
			<!-- post-body-content -->

			<!-- sidebar -->
			<div id="postbox-container-1" class="postbox-container ">

				<div class="meta-box-sortables">

					<div class="postbox">

						<h3 class="hndle"><span><?php esc_attr_e(
									'Plugin Info', 'wp_admin_style'
								); ?></span></h3>

						<div class="inside sidebar_rc_maint">
							<p class="rc_maint_info">
								Name: Maintenance Mode by IP v1.0 <br />
								Author: Roberto Cabrera <br />
								Website: <a href="http://rcit.consulting" target="_blank">http://rcit.consulting</a>
							</p>
						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->


					
					<div class="postbox">

						<h3 class="hndle"><span><?php esc_attr_e(
									'Resources', 'wp_admin_style'
								); ?></span></h3>

						<div class="inside sidebar_rc_maint">
							<p class="rc_maint_info">
								<a href="http://getbootstrap.com/" target="_blank">Bootstrap</a>, 
								<a href="http://fortawesome.github.io/Font-Awesome/" target="_blank">FontAwesome</a>, 
								<a href="http://xdsoft.net/jqplugins/datetimepicker/" target="_blank">Date Picker</a>,
								<a href="http://designmodo.com/flat-free/">Flat UI Free</a>, FlowType.JS 
							</p>
							<p>
								If you found this plugin helpful, please consider rating us. Also, if you feel that rating us is not enough, you can click on 
								the button below to make a donation. It will helps us to pay for hosting fees. Thanks!
							</p>
							<p>
								<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
									<input type="hidden" name="cmd" value="_s-xclick">
									<input type="hidden" name="hosted_button_id" value="AUMPPD6ALDPRQ">
									<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
									<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
								</form>
							</p>
						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->
					<div class="postbox">

						<h3 class="hndle"><span><?php esc_attr_e(
									'Support', 'wp_admin_style'
								); ?></span></h3>

						<div class="inside sidebar_rc_maint">
							<p class="rc_maint_info">
								If you need support/assistant with this plugin, check out our FAQ. If the FQAs could not answer your questions, 
								click <a href="mailto:wplugins@rcit.consulting">HERE</a> to send us an email with your issue. Please try to be as clear as possible. 
								Thank your for choosing 'Maintenance Mode By IP Address' plugin.
							</p>
						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->

				</div>
				<!-- .meta-box-sortables -->

			</div>
			<!-- #postbox-container-1 .postbox-container -->

		</div>
		<!-- #post-body .metabox-holder .columns-2 -->

		<br class="clear">
	</div>
	<!-- #poststuff -->
	






	
</div> <!-- .wrap -->