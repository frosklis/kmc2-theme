<?php get_header(); ?>

<div id="content">
	<div id="inner-content" class="clearfix">
		<?php 
		if ( is_active_sidebar( 'homepage_widgets' ) ) dynamic_sidebar( 'homepage_widgets' ); 
		else {
			?>
			<div id="main" class="twelvecol clearfix" role="main">
			    <?php 
			    global $wp_query;
				$list_of_posts = $wp_query;
				$args = array(
		            'list_of_posts' => $list_of_posts,
		            'summary' => true,
		            'tiles' => true,
		            'pages' => false,
		        );
				display_posts($args);
				?>
	
		    </div> 
		<?php
		}
		?>
	</div> <!-- end #inner-content -->

</div> <!-- end #content -->

<?php get_footer(); ?>
