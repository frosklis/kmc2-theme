<!DOCTYPE html>
<!--[if lt IE 7]><html <?php language_attributes(); ?> class="lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> ><!--<![endif]-->
	<head>
		<meta charset="utf-8">

		<title><?php bloginfo('name'); ?> <?php wp_title(); ?></title>

		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

		<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">

  		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
		<link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700&#038;subset=latin,latin-ext' type='text/css'/>

		<?php
		wp_head();
		$google_analytics = get_option('google_analytics_tracking_code', '' );
		if ($google_analytics != '' && !is_admin()) {
		?>
			<script>
				(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
				(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
				m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
				})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

				ga('create', '<?php echo($google_analytics); ?>', 'kmc2.tk');
				ga('send', 'pageview');

			</script>
		<?php } ?>
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
