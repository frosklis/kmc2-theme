<?php
/*
Template Name: Blog del viaje
*/
?>

<?php get_header(); ?>
			
			<div id="content">
			
				<div id="inner-content" class="wrap clearfix">

				    <div id="main" class="ninecol first clearfix" role="main">
<p>blog-viaje.php</p>

						<div class="nav-container">		
							<nav role="navigation" class="clearfix">
								<?php
								if (have_posts()) : while (have_posts()) : the_post(); 
									if (isset($_GET['cat']) ) {
										$current_viaje = $_GET['cat'];
									}
									$es_diario = has_tag( "diario", $post );
								?>

								<ul id="menu-menu-principal" class="nav top-nav clearfix">
									<li class="menu-item menu-item-type-custom menu-item-object-custom current-menu-ancestor
										<?php
										if ($es_diario) {
											echo (" current-menu-item ");
										} 
										?> ">

										<a href=<?php
											
											$url = bloginfo('siteurl'); 
											$url .= '/blog-de-viaje?';
											// Calcular los parametros
											$url .= 'cat=' . $current_viaje;
											$url .= '&';
											$url .= 'tag=diario';

											echo($url); 
										?>>Diario</a>
									</li>
									<li class="menu-item menu-item-type-custom menu-item-object-custom current-menu-ancestor">
										<a href="#">Top 10</a>
									</li>
									<li class="menu-item menu-item-type-custom menu-item-object-custom current-menu-ancestor">
										<a href=<?php
											
											$url = bloginfo('siteurl'); 
											$url .= '/blog-de-viaje?';
											// Calcular los parametros
											$url .= 'cat=' . $current_viaje;
											$url .= '&';
											$url .= 'tag=notas';

											echo($url); 
										?>>Notas y apuntes</a>
									</li>
								</ul>
								<?php
								endwhile;
								endif;
								?>
							</nav>
						</div> <!-- end nav-container -->

						<?php 
						$args = array(
							'category_name' => $_GET["cat"],
							'tag' => $_GET["tag"],
							'post_type' => 'post',
							'paged' => $paged
						  );

						$list_of_posts = new WP_Query( $args );
					    
					    display_posts($list_of_posts, true);
					    ?>
				    </div> <!-- end #main -->
    
				    <?php get_sidebar(); ?>
				    
				</div> <!-- end #inner-content -->
    
			</div> <!-- end #content -->

<?php get_footer(); ?>
