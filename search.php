<?php get_header(); ?>
			
			<div id="content">
			
				<div id="inner-content" class="clearfix">
			
				    <div id="main" class="twelvecol first clearfix" role="main">
<!-- ver http://codex.wordpress.org/Creating_a_Search_Page -->
					    <?php 
					    global $wp_query;
    					$list_of_posts = $wp_query;

    					// if ($list_of_posts->have_posts()) {
    					// 	$args = array(
					    //         'list_of_posts' => $list_of_posts,
					    //         'summary' => true,
					    //     );
    					// 	display_posts($args);
    					// } else {
    					// 	echo("<p>" . __("Seems like we couldn't find it. Sorry.",'kmc2theme') . "</p>");
    					// }
    					?>

    					<p align="center">
    					<?php 
    						_e("Showing results for '", 'kmc2theme');
    						echo("<big>");
    						echo get_search_query(); 
    						echo("</big>");
    						echo("'");
    					?>
    					<p>
    					<div class="article-list">
						<?php
						while ($list_of_posts->have_posts()) : $list_of_posts->the_post(); 
							$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), "medium");
							$thumbnail = $thumbnail[0];
						?>
							<a class="article-thumb" href="<?php the_permalink(); ?>">
								<div class="background" style="background-image: url(<?php echo($thumbnail ); ?>);"></div>

								<div class="content">
									<div class="title"><h3><?php the_title(); ?></h3></div>
									<?php echo('<div class="meta"><p><span class="icon-calendar"></span> ');
                					printf('<time class="updated" datetime="%1$s" pubdate>%2$s</time></p></div>', 'kmc2theme', get_the_time('Y-m-j'), get_the_time(get_option('date_format'))); ?>
                				</div>
							</a>
    					<?php endwhile; ?>
    					</div>
			
				    </div> <!-- end #main -->
    
				    <?php get_sidebar(); ?>
				    
				</div> <!-- end #inner-content -->
    
			</div> <!-- end #content -->

<?php get_footer(); ?>
