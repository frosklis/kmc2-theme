			<footer class="footer" role="contentinfo">
			
				<div id="inner-footer" class="wrap clearfix">

					<!--Four widgetized areas-->
					<?php
					$widgets = array("cover2", "cover3", "cover4", "cover5");
					$i = 0;
					foreach ($widgets as &$widget) { 
						$i = $i+1?>
						<div class="footer-widgetarea<?php echo $i; ?>">
						<?php
					    if (is_active_sidebar($widget)):
					    	dynamic_sidebar($widget);
					    else: ?>
							<p><?php //_e("Please activate some Widgets.", "kmc2theme");  ?></p>
						<?php endif; ?>
						</div>
					<?php
					} // foreach
					unset($widget);
					?>
					
					<nav role="navigation">
    					<?php kmc2_footer_links(); ?>
	                </nav>
	                		
					<p class="source-org copyright"><a href="<?php echo home_url(); ?>" rel="nofollow">&copy; <?php echo date('Y'); ?> <?php //bloginfo('name'); ?>km c<sup>2</sup><?php //bloginfo('name'); ?>.</a></p>
				
				</div> <!-- end #inner-footer -->
				
			</footer> <!-- end footer -->
		
		</div> <!-- end #container -->
		
		<!-- all js scripts are loaded in library/kmc2.php -->
		<?php wp_footer(); ?>

	</body>

</html> <!-- end page. what a ride! -->
