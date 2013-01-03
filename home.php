<?php get_header(); ?>
			
			<div id="content">
			
				<div id="inner-content" class="wrap clearfix">
			
					<!--Cover banner-->
					<div id="cover_banner" class="home-slideshow">
						<div class="flex-container">
						  <div class="flexslider">
							<ul class="slides">
							  <li>
							  	<img src="./mockups/_DSC6580.jpg" />
							  	<p class="flex-caption">Esto es una foto.</p>
							  </li>
							  <li>
							  	<img src="./mockups/_DSC6259.jpg" />
							  	<p class="flex-caption">Esto es otra foto.</p>
							  </li>
							  <li>
							  	<img src="./mockups/_DSC4849.jpg" />
							  	<p class="flex-caption">Y una más.</p>
							  </li>
							</ul>
						  </div>
						</div>
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
							<p><?php _e("Please activate some Widgets.", "kmc2theme");  ?></p>
						<?php endif; ?>
						</div>
					<?php
					} // foreach
					unset($widget);
					?>
				    
				</div> <!-- end #inner-content -->
    
			</div> <!-- end #content -->

<?php get_footer(); ?>
