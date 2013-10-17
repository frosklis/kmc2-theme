<!doctype html>  
<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->	
	<head>
		<meta charset="utf-8">
		
		<title><?php wp_title(''); ?></title>
		
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		
		<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
				
  		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
		

		<?php wp_head(); ?>
		
	</head>
	
	<body <?php body_class(); ?>>
		<header id="topbar"> 
			<div class="pullmenu">
				<span class="icon-list"></span>
				<?php _e('Menu', 'kmc2theme'); ?>
			</div>
			<div class="search">
				<?php
				$form = "";
				echo(kmc2_wpsearch($form));
				?>
			</div>
			<nav class="nav clearfix">
				<?php
				kmc2_main_nav(); 
				?> 
				<div class="pullmenu">
					<span class="icon-arrow-up"></span>
					<?php _e('Hide menu', 'kmc2theme'); ?>
				</div>
			</nav>

			<div class="socialbar aligncenter" style="display:none;">
				<p>
				    <a href="<?php bloginfo('rss2_url'); ?>"><img src="<?php echo(get_stylesheet_directory_uri()); ?>/images/icons/rss-64.png" height="32" width="32"></a>
				    <a href="<?php echo('https://twitter.com/' . get_option('kmc2_twitter_user', '' )); ?>"><img src="<?php echo(get_stylesheet_directory_uri()); ?>/images/icons/twitter-bird-dark-bgs-64.png" height="32" width="32"></a>
					<a href="//plus.google.com/102083218914804503792?prsrc=3"
					   rel="publisher" target="_top"><img src="<?php echo(get_stylesheet_directory_uri()); ?>/images/icons/gplus-64.png" height="32" width="32">
					</a>
				</p>
			</div> 
		</header>