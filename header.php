<!doctype html>  
<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->
	
	<head>
		<script>
			document.cookie='resolution='+Math.max(screen.width,screen.height)+'; path=/';
			document.cookie='device_width='+screen.width+'; path=/';
			document.cookie='device_height='+screen.height+'; path=/';
			document.cookie='device_pixel_ratio='+devicePixelRatio+'; path=/';
		</script>
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
			<nav class="nav clearfix">
				<?php
				kmc2_main_nav(); 
				?>
				<a href="#" id="pull">Menú</a>
			</nav>
		</header>

		<div id="container">