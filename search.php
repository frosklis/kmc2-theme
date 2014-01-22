<?php get_header(); ?>
			
			<div id="content">
			
				<div id="inner-content" class="clearfix">
			
					<?php if (!is_active_sidebar( 'sidebar1' ) ) { ?>
						<div id="main" class="twelvecol first clearfix" role="main">
					<?php } else { ?>
						<div id="main" class="ninecol single first clearfix" role="main">
					<?php } ?>
    					<p align="center">
    					<?php 
    						_e("Showing results for '", 'kmc2theme');
    						echo("<big>");
    						echo get_search_query(); 
    						echo("</big>");
    						echo("'");
    					?>
    					<p>
					    <?php 
					    global $wp_query;
    					$list_of_posts = $wp_query;

    					if ($list_of_posts->have_posts()) {
    						$args = array(
					            'list_of_posts' => $list_of_posts,
					            'tiles' => true,
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
