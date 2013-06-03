<?php get_header(); ?>
			
			<div id="content">
				<div id="inner-content" class="wrap clearfix">
					<div id="main" class="ninecol first clearfix" role="main">
    					<?php 
    					
    					//$mi_cat = $wp_query['category_name'];
						$categoria = get_query_var('category_name');
						?>

						<nav role="navigation" class="clearfix">
							<ul id="menu-menu-principal" class="nav top-nav clearfix">
								<li class="menu-item menu-item-type-custom menu-item-object-custom current-menu-ancestor">
									<a href=<?php
										$url = bloginfo('siteurl'); 
										$url .= '/tree/';
										// Calcular los parametros
										$url .= $categoria;
										$url .= '/notas';

										echo($url); 
									?>>Notas</a>
								</li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom current-menu-ancestor">
									<a href=<?php
										$url = bloginfo('siteurl'); 
										$url .= '/tree/';
										// Calcular los parametros
										$url .= $categoria;
										$url .= '/diario';

										echo($url); 
									?>>Diario</a>
								</li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom current-menu-ancestor">
									<a href=<?php
										$url = bloginfo('siteurl'); 
										$url .= '/tree/';
										// Calcular los parametros
										$url .= $categoria;
										$url .= '/top-10';

										echo($url); 
									?>>Top 10</a>
								</li>
							</ul>
						</nav>

						<?php
						global $wp_query;
    					$list_of_posts = $wp_query;
    					display_posts($list_of_posts); 
    					?>
					</div> <!-- end #main -->
    				
    				<?php get_sidebar(); ?>
				    
				</div> <!-- end #inner-content -->
    
			</div> <!-- end #content -->

<?php get_footer(); ?>
