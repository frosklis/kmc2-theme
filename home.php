<?php get_header(); ?>
			
			<div id="content">
			
				<div id="inner-content" class="wrap clearfix">
			
					<!--Cover banner-->
					<div id="cover_banner" class="home-slideshow">
						<?php 
						if ( is_active_sidebar( 'cover1' ) ) :
							dynamic_sidebar( 'cover1' );
						else : ?>
							<div class="alert help">
								<p><?php _e("Please activate some Widgets.", "kmc2theme");  ?></p>
							</div>
						<?php endif; ?>
					</div>

					<!--Four widgetized areas-->
					<?php
					$widgets = array("cover2", "cover3", "cover4", "cover5");
					$i = 0;
					foreach ($widgets as &$widget) { 
						$i = $i+1?>
						<div class="home-widgetarea<?php echo $i; ?>">
						<?php
					    if (is_active_sidebar($widget)):
					    	dynamic_sidebar($widget);
					    else: ?>
							<div class="alert help">
								<p><?php _e("Please activate some Widgets.", "kmc2theme");  ?></p>
							</div>
						<?php endif; ?>
						</div>
					<?php
					} // foreach
					unset($widget);
					?>
				    
				</div> <!-- end #inner-content -->
    
			</div> <!-- end #content -->

<?php get_footer(); ?>
