<?php
/*
Template Name: Descripción de viajes
*/
?>

<?php get_header(); ?>
			
			<div id="content">
			
				<div id="inner-content" class="wrap clearfix">
			
				    <div id="main" class="eightcol first clearfix" role="main">

					    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					
					    <article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">
					
						    <section class="entry-content">
							    <?php the_content(); ?>
						    </section> <!-- end article section -->
						
						    <footer class="article-footer">
							    <p class="clearfix"><?php the_tags('<span class="tags">' . __('Tags:', 'kmc2theme') . '</span> ', ', ', ''); ?></p>
						    </footer> <!-- end article footer -->

					    </article> <!-- end article -->
					
					    <?php endwhile; ?>	
					
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