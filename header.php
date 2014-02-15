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
		if ($google_analytics != '') {
		?>
			<script type="text/javascript">
				/* <![CDATA[ */
				var _gaq = _gaq || [];
				_gaq.push(['_setAccount', '<?php echo($google_analytics); ?>']);
				_gaq.push(['_trackPageview']);

				(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
				})();

				(function(b){(function(a){"__CF"in b&&"DJS"in b.__CF?b.__CF.DJS.push(a):"addEventListener"in b?b.addEventListener("load",a,!1):b.attachEvent("onload",a)})(function(){"FB"in b&&"Event"in FB&&"subscribe"in FB.Event&&(FB.Event.subscribe("edge.create",function(a){_gaq.push(["_trackSocial","facebook","like",a])}),FB.Event.subscribe("edge.remove",function(a){_gaq.push(["_trackSocial","facebook","unlike",a])}),FB.Event.subscribe("message.send",function(a){_gaq.push(["_trackSocial","facebook","send",a])}));"twttr"in b&&"events"in twttr&&"bind"in twttr.events&&twttr.events.bind("tweet",function(a){if(a){var b;if(a.target&&a.target.nodeName=="IFRAME")a:{if(a=a.target.src){a=a.split("#")[0].match(/[^?=&]+=([^&]*)?/g);b=0;for(var c;c=a[b];++b)if(c.indexOf("url")===0){b=unescape(c.split("=")[1]);break a}}b=void 0}_gaq.push(["_trackSocial","twitter","tweet",b])}})})})(window);
				/* ]]> */
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
