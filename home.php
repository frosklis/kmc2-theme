<?php get_header(); ?>

<div id="content">
	<div id="inner-content" class="clearfix">
		<?php 
		//if ( is_active_sidebar( 'homepage_widgets' ) ) dynamic_sidebar( 'homepage_widgets' ); 
		// echo('<div class="kmc2-maps-plugin"><div class="trips-visualization-map">');
		// echo('</div></div>');
		the_widget('Kmc2_Visualization');
		?>
	</div> <!-- end #inner-content -->

</div> <!-- end #content -->

<?php get_footer(); ?>
