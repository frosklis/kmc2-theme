<?php get_header(); ?>
			
			<div id="content">
			
				<div id="inner-content" class="wrap clearfix">
			
				    <div id="main" class="ninecol first clearfix" role="main">
<!-- ver http://codex.wordpress.org/Creating_a_Search_Page -->
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
