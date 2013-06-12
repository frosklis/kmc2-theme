<?php get_header(); ?>
			
			<div id="content">

				<div id="inner-content" class="wrap clearfix">
			
					<div id="main" class="ninecol fisrt single clearfix" role="main">
						<?php
						global $wp_query;
    					$list_of_posts = $wp_query;
    					display_posts($list_of_posts); 
    					?>
			
					</div> <!-- end #main -->

				</div> <!-- end #inner-content -->
    
			</div> <!-- end #content -->

<?php get_footer(); ?>
