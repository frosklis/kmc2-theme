<!doctype html>  

<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->
	
	<head>
		<meta charset="utf-8">
		
		<title><?php wp_title(''); ?></title>
		
		<!-- Google Chrome Frame for IE -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		
		<!-- mobile meta (hooray!) -->
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		
		<!-- icons & favicons (for more: http://themble.com/support/adding-icons-favicons/) -->
		<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
				
  		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
		
		<!-- wordpress head functions -->
		<?php wp_head(); ?>
		<!-- end of wordpress head -->
		
	</head>
	
	<body <?php body_class(); ?>>
		<header id="topbar">
			<div id="socialbar">
			    <a href="<?php bloginfo('rss2_url'); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/rss.png" height="32px" width="32px"></a>
			    <a href="https://twitter.com/_kmc2"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/twitter-bird-dark-bgs.png" height="32px" width="32px"></a>
				<a href="//plus.google.com/102083218914804503792?prsrc=3"
				   rel="publisher" target="_top"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/gplus.png" height="32px" width="32px">
				</a>
	
				<form role="search" method="get" id="searchform" action="<?php bloginfo('siteurl'); ?>">
				    <input type="text" value="" name="s" id="s" placeholder="Buscar en el blog...">
			    </form>
				
			</div>    
			<nav class="nav clearfix"><?php kmc2_main_nav(); ?>
				<a href="#" id="pull">Menú</a>
			</nav>
		</header>
	
		<div id="container">







