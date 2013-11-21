<?php get_header(); ?>
<?php    					
$category = get_category(intval(get_query_var('cat')));
$tipo = get_query_var('tipo');
?>			
			<div id="content">
				<div id="inner-content" class="wrap clearfix">

				<?php if ($tipo != "fotos" and is_active_sidebar( 'category_widgets' ) ) { ?>
					<div id="main" class="ninecol first clearfix category-page" role="main">
				<?php } else { ?>
					<div id="main" class="twelvecol single first clearfix category-page" role="main">
				<?php } ?>
						<nav role="navigation" class="clearfix">
							<ul id="menu-menu-principal" class="nav top-nav clearfix">
								<li class="menu-item menu-item-type-custom menu-item-object-custom current-menu-ancestor 
									<?php if ($tipo == '') echo ("current-menu-item"); ?>
									">
									<a href=<?php
										$url = home_url(); 
										$url .= '/tree/';
										$url .= $category->slug;

										echo($url); 
									?>><?php echo($category->name); ?></a>
								</li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom current-menu-ancestor 
									<?php if ($tipo == 'notas') echo ("current-menu-item"); ?>
									">
									<a href=<?php
										$url = home_url(); 
										$url .= '/tree/';
										$url .= $category->slug;
										$url .= '/notas';

										echo($url); 
									?>>Notas</a>
								</li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom current-menu-ancestor 
									<?php if ($tipo == 'diario') echo ("current-menu-item"); ?>
									">
									<a href=<?php
										$url = home_url(); 
										$url .= '/tree/';
										$url .= $category->slug;
										$url .= '/diario';

										echo($url); 
									?>>Diario</a>
								</li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom current-menu-ancestor 
									<?php if ($tipo == 'fotos') echo ("current-menu-item"); ?>
									">
									<a href=<?php
										$url = home_url(); 
										$url .= '/tree/';
										$url .= $category->slug;
										$url .= '/fotos';

										echo($url); 
									?>>Fotos</a>
								</li>
							</ul>
						</nav>
						<?php
						// if ($tipo == "") {
						// 	if ( is_active_sidebar( 'category_widgets' ) ) dynamic_sidebar( 'category_widgets' ); 
						// }
						if ($tipo != "fotos") {
							global $wp_query;
	    					$list_of_posts = $wp_query;
	    					display_posts($list_of_posts, true); 
    					} else { 
						
							// The Query
							$args = array(
								'posts_per_page' => 1,
							);
							$query = new WP_Query( $args );

							// The Loop
							if ( $query->have_posts() ) {
								$query->the_post();
	    						$cat = get_query_var('cat');
	    						display_pictures($cat);
							}
    					}
    					?>
					</div> <!-- end #main -->
    				
    				<?php if ($tipo != "fotos") get_sidebar();  ?>
				    
				</div> <!-- end #inner-content -->
    
			</div> <!-- end #content -->

<?php get_footer(); ?>
