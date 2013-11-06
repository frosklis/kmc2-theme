<?php get_header(); ?>

<div id="content">
	<div id="inner-content" class="wrap clearfix">

		<?php 
		if ( is_active_sidebar( 'homepage_widgets' ) ) dynamic_sidebar( 'homepage_widgets' ); 
		?>

	</div> <!-- end #inner-content -->

</div> <!-- end #content -->

<?php get_footer(); ?>
