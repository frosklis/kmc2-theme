<?php
/*
This template shows all posts
*/
?>
<?php get_header(); ?>
			
			<div id="content">
			
				<div id="inner-content" class="clearfix">
					<?php
					$args = array(
						'post_type' => 'post',
						'paged' => get_query_var('paged')
					  );
					
					$list_of_posts = new WP_Query( $args );

					?>
	
					<?php if (!is_active_sidebar( 'sidebar1' ) ) { ?>
						<div id="main" class="twelvecol first clearfix" role="main">
					<?php } else { ?>
						<div id="main" class="ninecol single first clearfix" role="main">
					<?php } ?>
    		
    					<?php 
    					$args = array(
				            'list_of_posts' => $list_of_posts,
				            'tiles' => true,
				        );
    					display_posts($args); 
    					?>
					</div> <!-- end #main -->
    				
    				<?php get_sidebar(); ?>
				    
				</div> <!-- end #inner-content -->
    
			</div> <!-- end #content -->

<?php get_footer(); ?>
