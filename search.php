<?php get_header(); ?>
			
			<div id="content">
			
				<div id="inner-content" class="clearfix">
			
				    <div id="main" class="ninecol first clearfix" role="main">
<!-- ver http://codex.wordpress.org/Creating_a_Search_Page -->
					    <?php 
					    global $wp_query;
    					$list_of_posts = $wp_query;

    					if ($list_of_posts->have_posts()) {
    						$args = array(
					            'list_of_posts' => $list_of_posts,
					            'summary' => true,
					        );
    						display_posts($args);
    					} else {
    						echo("<p>" . __("Seems like we couldn't find it. Sorry.",'kmc2theme') . "</p>");
    					}
    					?>
			
				    </div> <!-- end #main -->
    
				    <?php get_sidebar(); ?>
				    
				</div> <!-- end #inner-content -->
    
			</div> <!-- end #content -->

<?php get_footer(); ?>
