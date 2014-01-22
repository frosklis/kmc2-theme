<?php get_header(); ?>
			
			<div id="content">
			
				<div id="inner-content" class="clearfix">
			
				    <?php if (!is_active_sidebar( 'sidebar1' ) ) { ?>
						<div id="main" class="twelvecol first clearfix" role="main">
					<?php } else { ?>
						<div id="main" class="ninecol single first clearfix" role="main">
					<?php } ?>
					    <?php 
					    global $wp_query;
    					$list_of_posts = $wp_query;
    					$args = array(
				            'list_of_posts' => $list_of_posts,
				            'summary' => true,
				            'tiles' => true,
				        );
    					display_posts($args);
    					?>
			
				    </div> <!-- end #main -->
    
				    <?php get_sidebar(); ?>
				    
				</div> <!-- end #inner-content -->
    
			</div> <!-- end #content -->

<?php get_footer(); ?>
