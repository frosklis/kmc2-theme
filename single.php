<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="clearfix">

					<div id="main" class="ninecol first single clearfix" role="main">
						<?php
						global $wp_query;
    					$list_of_posts = $wp_query;
    					$args = array(
					            'list_of_posts' => $list_of_posts,
					            'summary' => false,
					            'comments' => true,
					            'prev_next_links' => true,
					            'single' => true,
					            'attachment' => true
					        );
    					display_posts($args);

    					?>

					</div> <!-- end #main -->
				</div> <!-- end #inner-content -->

			</div> <!-- end #content -->

<?php get_footer(); ?>
