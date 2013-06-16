<?php get_header(); ?>
			<div id="content">
				<div id="inner-content" class="wrap clearfix">

					Esta es la página principal...

					<p><?php echo get_locale(); ?></p>
					<p><?php echo get_template_directory() ."/library/translation/$locale.php"; ?></p>


				</div> <!-- end #inner-content -->
    
			</div> <!-- end #content -->

<?php get_footer(); ?>
