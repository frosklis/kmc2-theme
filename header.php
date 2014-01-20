<!DOCTYPE html>  
<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->	
	<head>
		<meta charset="utf-8">
		
		<title><?php bloginfo('name'); ?> <?php wp_title(); ?></title>
		
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		
		<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
				
  		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
		<link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700&#038;subset=latin,latin-ext' type='text/css'/>

		<?php wp_head(); ?>
		
	</head>
	
	<body <?php body_class(); ?>>
		<header><div  id="topbar" class="wrap">
			<div class="logo">
				<a href="<?php echo home_url(); ?>" rel="nofollow"><?php require('images/svg/c2.svg'); ?></a>
			</div>
			<div class="utils">
				<nav class="nav clearfix">
					<?php
					kmc2_main_nav(); 
					?> 
					<div class="pullmenu">
						<span class="icon-arrow-up"></span>
						<?php _e('Hide menu', 'kmc2theme'); ?>
					</div>
				</nav>
				<div id="topbar-search">
					<?php
					$form = "headersearch";
					echo(kmc2_wpsearch($form));
					?>
				</div>
			</div>
		</div></header>
