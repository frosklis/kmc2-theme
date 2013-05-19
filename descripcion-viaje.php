<?php
/*
Template Name: Descripción de viajes
*/
?>

<?php get_header(); ?>
			
			<div id="content">

				<div id="inner-content" class="wrap clearfix">

				    <div id="main" class="ninecol first clearfix" role="main">





<div class="nav-container">		
				<nav role="navigation" class="clearfix">


<?php
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
							?>
				</nav>
			</div>







					    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					
					    <article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">
					
						    <section class="entry-content">
							    <?php the_content(); ?>
						    </section> <!-- end article section -->
						
						    <footer class="article-footer">
							    <p class="clearfix"><?php the_tags('<span class="tags">' . __('Tags:', 'kmc2theme') . '</span> ', ', ', ''); ?></p>
						    </footer> <!-- end article footer -->

					    </article> <!-- end article -->
					
					    <?php endwhile; 

// Poner la lista de todos los posts relacionados
//*************** CÓDIGO COPIADO DE blog-viaje.php

						$args = array(
							'category' => $_GET["cat"],
							'post_type' => 'post',
							'paged' => $paged
						  );

						$list_of_posts = new WP_Query( $args );
					    if ($list_of_posts->have_posts()) : while ($list_of_posts->have_posts()) : $list_of_posts->the_post(); ?>
					
					    <article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">
						
						    <header class="article-header">
							
							    <h1 class="h2"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
                  <p class="byline vcard"><?php
                    printf(__('Posted <time class="updated" datetime="%1$s" pubdate>%2$s</time> by <span class="author">%3$s</span> <span class="amp">&</span> filed under %4$s.', 'kmc2theme'), get_the_time('Y-m-j'), get_the_time(get_option('date_format')), kmc2_get_the_author_posts_link(), get_the_category_list(', '));
                  ?></p>
						
						    </header> <!-- end article header -->
					
						    <section class="entry-content clearfix">
							    <?php the_content(); ?>
						    </section> <!-- end article section -->
						
						    <footer class="article-footer">
    							<p class="tags"><?php the_tags('<span class="tags-title">' . __('Tags:', 'kmc2theme') . '</span> ', ', ', ''); ?></p>

						    </footer> <!-- end article footer -->
						    
						    <?php // comments_template(); // uncomment if you want to use them ?>
					
					    </article> <!-- end article -->
					
					    <?php endwhile; ?>	
					
					        <?php if (function_exists('kmc2_page_navi')) { ?>
					            <?php kmc2_page_navi(); ?>
					        <?php } else { ?>
					            <nav class="wp-prev-next">
					                <ul class="clearfix">
					        	        <li class="prev-link"><?php next_posts_link(__('&laquo; Older Entries', "kmc2theme")) ?></li>
					        	        <li class="next-link"><?php previous_posts_link(__('Newer Entries &raquo;', "kmc2theme")) ?></li>
					                </ul>
					            </nav>
					        <?php } 

//*************** FIN CÓDIGO COPIADO de blog-viaje.php
					    ?>	
					
					    <?php else : ?>
					
        					<article id="post-not-found" class="hentry clearfix">
        					    <header class="article-header">
        						    <h1><?php _e("Oops, Post Not Found!", "kmc2theme"); ?></h1>
        						</header>
        					    <section class="entry-content">
        						    <p><?php _e("Uh Oh. Something is missing. Try double checking things.", "kmc2theme"); ?></p>
        						</section>
        						<footer class="article-footer">
        						    <p><?php _e("This is the error message in the descripcion-viaje.php template.", "kmc2theme"); ?></p>
        						</footer>
        					</article>
					
					    <?php endif; ?>
			
				    </div> <!-- end #main -->
    
				    <?php get_sidebar(); ?>
				    
				</div> <!-- end #inner-content -->
    
			</div> <!-- end #content -->

<?php get_footer(); ?>
