<?php get_header(); ?>
			
			<div id="content">

				<div id="inner-content" class="wrap clearfix">
			
					<div id="main" class="ninecol first single clearfix" role="main">
						<?php 
						if ( function_exists('yoast_breadcrumb') ) {
							yoast_breadcrumb('<p id="breadcrumbs">','</p>');
						} ?>
						<?php
						global $wp_query;
    					$list_of_posts = $wp_query;
    					display_posts($list_of_posts); 
    					?>
			
					</div> <!-- end #main -->

				</div> <!-- end #inner-content -->
    
			</div> <!-- end #content -->

<?php get_footer(); ?>
