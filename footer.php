			<footer class="footer" role="contentinfo">
			
				<div id="inner-footer" class="wrap clearfix">
						<div class="footer-widgetarea">
						<?php
					    if (is_active_sidebar('footer1')):
					    	dynamic_sidebar('footer1');
					    else: ?>
							<p><?php //_e("Please activate some Widgets.", "kmc2theme");  ?></p>
						<?php endif; ?>
						</div>
					<?php
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
