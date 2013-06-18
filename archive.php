<?php get_header(); ?>
			
			<div id="content">
			
				<div id="inner-content" class="wrap clearfix">
				
				    <div id="main" class="ninecol first clearfix" role="main">
				
					    <?php if (is_category()) { ?>
						    <h1 class="archive-title h2">
						    	Etiqueta
							    <span><?php _e("Posts Categorized:", "kmc2theme"); ?></span> <?php single_cat_title(); ?>
					    	</h1>
					    
					    <?php } elseif (is_tag()) { ?> 
						    <h1 class="archive-title h2">
							    <span><?php _e("Posts Tagged:", "kmc2theme"); ?></span> <?php single_tag_title(); ?>
						    </h1>
					    
					    <?php } elseif (is_author()) { 
					    	global $post;
					    	$author_id = $post->post_author;
					    ?>
						    <h1 class="archive-title h2">

						    	<span><?php _e("Posts By:", "kmc2theme"); ?></span> <?php echo get_the_author_meta('display_name', $author_id); ?>

						    </h1>
					    <?php } elseif (is_day()) { ?>
						    <h1 class="archive-title h2">
	    						<span><?php _e("Daily Archives:", "kmc2theme"); ?></span> <?php the_time('l, F j, Y'); ?>
						    </h1>
		
		    			<?php } elseif (is_month()) { ?>
			    		    <h1 class="archive-title h2">
				    	    	<span><?php _e("Monthly Archives:", "kmc2theme"); ?></span> <?php the_time('F Y'); ?>
					        </h1>
					
					    <?php } elseif (is_year()) { ?>
					        <h1 class="archive-title h2">
					    	    <span><?php _e("Yearly Archives:", "kmc2theme"); ?></span> <?php the_time('Y'); ?>
					        </h1>
					    <?php } ?>

					    <?php 
					    global $wp_query;
    					$list_of_posts = $wp_query;
    					display_posts($list_of_posts, true); 
    					?>
			
    				</div> <!-- end #main -->
    
	    			<?php get_sidebar(); ?>
                
                </div> <!-- end #inner-content -->
                
			</div> <!-- end #content -->

<?php get_footer(); ?>
