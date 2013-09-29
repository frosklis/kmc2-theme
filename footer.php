			<footer class="footer" role="contentinfo">
			
				<div id="inner-footer" class="wrap clearfix">

						<div class="socialbar aligncenter">
							<p>
							    <a href="<?php bloginfo('rss2_url'); ?>"><img src="<?php echo(get_stylesheet_directory_uri()); ?>/images/rss.png" height="32px" width="32px"></a>
							    <a href="https://twitter.com/_kmc2"><img src="<?php echo(get_stylesheet_directory_uri()); ?>/images/twitter-bird-dark-bgs.png" height="32px" width="32px"></a>
								<a href="//plus.google.com/102083218914804503792?prsrc=3"
								   rel="publisher" target="_top"><img src="<?php echo(get_stylesheet_directory_uri()); ?>/images/gplus.png" height="32px" width="32px">
								</a>
							</p>
							<p>
								<?php
								$form = "";
								echo(kmc2_wpsearch($form));
								?>
							</p>
						</div> 
						<div class="footer-widgetarea">
						<?php
					    if (is_active_sidebar('footer1')):
					    	dynamic_sidebar('footer1');
					    endif; ?>
						</div>
					<?php
					unset($widget);
					?>
					
					<nav role="navigation">
    					<?php //kmc2_footer_links(); ?>
	                </nav>
	                		
					<div class="source-org copyright aligncenter">
						<p><a href="<?php echo home_url(); ?>" rel="nofollow"><img src="<?php echo(get_stylesheet_directory_uri()); ?>/images/c2-blanco-64x64.png"></a></p>
						<p><a href="<?php echo home_url(); ?>" rel="nofollow">&copy; <?php echo date('Y'); ?> <?php //bloginfo('name'); ?>km c<sup>2</sup><?php //bloginfo('name'); ?></a></p>
					</div>
				</div> <!-- end #inner-footer -->
				
			</footer> <!-- end footer -->
		
		</div> <!-- end #container -->
		
		<!-- all js scripts are loaded in library/kmc2.php -->
		<?php wp_footer(); ?>

	</body>

</html> <!-- end page. what a ride! -->
