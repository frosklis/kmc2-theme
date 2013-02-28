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
	
		<div id="container">
			
			<header class="header" role="banner">
			
				<div id="inner-header" class="wrap clearfix">
					
					<div id="logo">
						<span class="h1">
						<?php
						global $current_viaje;
						if ( is_page_template('descripcion-viaje.php') ) {
							if (have_posts()) : while (have_posts()) : the_post();
							
							// También crear o alterar la variable global $current_viaje
							$aux = get_post_custom_values("Categoría asociada");
							$current_viaje = $aux[0];

							echo('<a href="' . get_permalink() . '"><'.get_the_title() ."</a>");

							endwhile;
							endif;
						} elseif (is_page_template('blog-viaje.php')) {
							echo('<a href="' . home_url() . '/' . $_GET['cat'] . '"><' .$_GET['cat'] . "</a>");
						} 
						else {
						?>
							<a href="<?php echo(home_url()); ?>" rel="nofollow">km c<sup>2</sup><?php //bloginfo('name'); ?></a>
						<?php bloginfo('description'); 
						} // Si no estamos en descripcion-viaje?>
						</span>
					</div>
				</div> <!-- end #inner-header -->
			</header> <!-- end header -->





			<div class="nav-container">		
				<nav role="navigation" class="wrap" "clearfix">
					<?php
						if ( is_page_template('descripcion-viaje.php') || is_page_template('blog-viaje.php') ) {
							if (have_posts()) : while (have_posts()) : the_post(); 
								if (isset($_GET['cat']) ) {
									$current_viaje = $_GET['cat'];
								}
							?>

							<ul id="menu-menu-principal" class="nav top-nav clearfix">
								<li class="menu-item menu-item-type-custom menu-item-object-custom current-menu-ancestor">
									<a href=<?php
										$url = '"http://www.kmc2.tk/blog-de-viaje?';
										// Calcular los parametros
										$url .= 'cat=' . $current_viaje;
										$url .= '&';
										$url .= 'tag=diario';
										$url .= '"';

										echo($url); 
									?>>Diario</a>
								</li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom current-menu-ancestor">
									<a href="#">Top 10</a>
								</li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom current-menu-ancestor">
									<a href=<?php
										$url = '"http://www.kmc2.tk/blog-de-viaje?';
										// Calcular los parametros
										$url .= 'cat=' . $current_viaje;
										$url .= '&';
										$url .= 'tag=notas';
										$url .= '"';

										echo($url); 
									?>>Notas y apuntes</a>
								</li>
							</ul>
							<?php
							endwhile;
							endif;
						} else {
							kmc2_main_nav(); 
					} // Si no estamos en descripcion-viaje?>
				</nav>
			</div>
