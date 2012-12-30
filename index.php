<?php get_header(); ?>
			
			<div id="content">
			
				<div id="inner-content" class="wrap clearfix">
			
				    <div id="main" class="eightcol first clearfix" role="main">
					<?php if ( is_active_sidebar( 'cover1' ) ) : ?>

						<?php dynamic_sidebar( 'cover1' ); ?>

					<?php else : ?>

						<!-- This content shows up if there are no widgets defined in the backend. -->
						
						<div class="alert help">
							<p><?php _e("Please activate some Widgets.", "kmc2theme");  ?></p>
						</div>

					<?php endif; ?>
				    </div> <!-- end #main -->
    
				    <?php // No sidebar get_sidebar(); ?>
				    
				</div> <!-- end #inner-content -->
    
			</div> <!-- end #content -->

<?php get_footer(); ?>
