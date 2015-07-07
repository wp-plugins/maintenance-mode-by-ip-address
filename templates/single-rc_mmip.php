<?php

/* Template Name: Maintenance Mode by IP Default Template */ 

wp_head(); 

$template_url =  plugin_dir_url( __FILE__ );
$blog_name = get_bloginfo( 'name', 'display' );
$rc_mmip_options = get_option('rc_mmip_settings');

if($rc_mmip_options != ''){

	//Inside Default Template Options
	$rc_mmip_inside_title = $rc_mmip_options['rc_mmip_inside_title'];
	$rc_mmip_inside_date = $rc_mmip_options['rc_mmip_inside_date'];
	$rc_mmip_inside_bgopt = $rc_mmip_options['rc_mmip_inside_bgopt'];
	$rc_mmip_default_bg = $rc_mmip_options['rc_mmip_default_bg'];
	$rc_mmip_inside_custom_img = $rc_mmip_options['rc_mmip_inside_custom_img'];

	//Mailchimp Options
	$rc_mmip_mchimp_opt = $rc_mmip_options['rc_mmip_mchimp_opt'];
	$rc_mmip_inside_msg = $rc_mmip_options['rc_mmip_inside_msg'];
	$rc_mmip_inside_bgopt = $rc_mmip_options['rc_mmip_inside_bgopt'];
	$rc_mmip_inside_ctopt = $rc_mmip_options['rc_mmip_inside_ctopt'];
	$rc_mmip_default_bg = $rc_mmip_options['rc_mmip_default_bg'];
	$rc_mmip_inside_custom_img = $rc_mmip_options['rc_mmip_inside_custom_img'];

	//Social Profiles
	$rc_mmip_social = $rc_mmip_options['rc_mmip_social'];

}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="description" content="Maintenance Mode By IP - <?php echo esc_html($blog_name); ?>">
    <meta name="author" content="<?php echo esc_html($blog_name); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title><?php echo esc_html($blog_name); ?></title>
	  <!--- need this to overwrite the styles of themes --->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<link href="<? echo $template_url; ?>assets/css/soon.css" rel="stylesheet">
	  
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="<? echo $template_url; ?>assets/js/html5shiv.js"></script>
      <script src="<? echo $template_url; ?>assets/js/respond.min.js"></script>
    <![endif]-->
	  
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,300,700' rel='stylesheet' type='text/css'>
  </head>

  <body class="nomobile">

    <!-- START HEADER -->
    <section id="header" style="height: auto !important;">
        <div class="container">
            <header>
                <h1 data-animated="GoIn"><? echo str_replace('%blog_name%', esc_html($blog_name), esc_html($rc_mmip_inside_title)); ?></h1>
            </header>

            <div id="timer" data-animated="FadeIn">
				
				<?php if($rc_mmip_inside_msg != ''): ?>
					<div id="message"><?php  echo html_entity_decode($rc_mmip_inside_msg); ?></div>
				<?php endif; ?>
                <?php if($rc_mmip_inside_ctopt != 0): ?>
					<div id="days" class="timer_box"></div>
					<div id="hours" class="timer_box"></div>
					<div id="minutes" class="timer_box"></div>
					<div id="seconds" class="timer_box"></div>
				<?php endif; ?>
            </div>

			<?php if($rc_mmip_mchimp_opt == 1): ?>
				<div class="col-xs-12 col-ms-4 col-ms-offset-4 col-md-4 col-md-offset-4 mt text-center">
					<h4>LET ME KNOW WHEN YOU LAUNCH</h4>
					  <div class="form-group">
						<label class="sr-only" for="exampleInputEmail2">Email address</label>
						<input type="email" name="rc_maint_mailchimpsubscriberemail" class="form-control rc_maint_mc_email" id="exampleInputEmail2" placeholder="Enter email">
					  </div>
					  <button type="submit" name="rc_maint_mailchimpsubscriber" class="btn btn-info rc_submit_mailchimp">Submit</button>

					<div class="thank_you_msg" style="margin-top: 10px;">

					</div>
				</div>
			<? endif; ?>
			<?php if(is_array($rc_mmip_social) && !empty($rc_mmip_social)){ ?>
				<div class="col-xs-12 col-ms-4 col-ms-offset-4 col-md-4 col-md-offset-4 mt text-center">
					<h3>Follow us</h3>
					<div class="row rc_mmip_social_profiles" >
					<?php 
						$qty = count($rc_mmip_social);
						$col_num = 12/$qty;

						foreach($rc_mmip_social as $key=>$value){
							if($key == 'fb'){
								$link = 'https://facebook.com/'. esc_html($value['username']);
							}else if($key == 'tw'){
								$link = 'https://twitter.com/'. esc_html($value['username']);
							}else if($key == 'gp'){
								$link = 'https://plus.google.com/+'. esc_html($value['username']);
							}else if($key == 'ig'){
								$link = 'https://instagram.com/'. esc_html($value['username']);
							}

						?>
							<div class="col-xs-<? echo $col_num; ?> col-md-<? echo $col_num; ?>" id="<? echo substr(esc_html($value['icon']), 3); ?>">
								<a href="<?php echo $link; ?>" target="_blank"><i class="fa <? echo esc_html($value['icon']); ?> fa-3x"></i></a>
							</div>
						<?
						}	

					?>
					</div>
				</div>
			<?php } ?>
        </div>
        <!-- LAYER OVER THE SLIDER TO MAKE THE WHITE TEXT READABLE -->
        <div id="layer"></div>
        <!-- END LAYER -->

        <div id="slider" class="rev_slider">
            <ul>
              <li data-transition="slideleft" data-slotamount="1" data-thumb="<?php if ( $rc_mmip_inside_bgopt == 0){ echo $rc_mmip_default_bg; }else{ echo $rc_mmip_inside_custom_img; } ?>">
                <img src="<?php if ( $rc_mmip_inside_bgopt == 0){ echo esc_url($rc_mmip_default_bg); }else{ echo esc_url($rc_mmip_inside_custom_img); } ?>">
              </li>
            </ul>
        </div>
    </section>
    <!-- END HEADER -->
  </body>
  <!-- END BODY -->
</html>

<?php wp_footer(); ?>