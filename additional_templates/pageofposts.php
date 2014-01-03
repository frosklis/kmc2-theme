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
						'paged' => $paged
					  );

					$list_of_posts = new WP_Query( $args );
					?>
	
					<div id="main" class="ninecol first clearfix" role="main">
    					<?php 
    					$args = array(
				            'list_of_posts' => $list_of_posts,
				            'summary' => true,
				        );
    					display_posts($args); 
    					?>
					</div> <!-- end #main -->
    				
    				<?php get_sidebar(); ?>
				    
				</div> <!-- end #inner-content -->
    
			</div> <!-- end #content -->

<?php get_footer(); ?>
