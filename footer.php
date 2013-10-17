			<footer class="footer" role="contentinfo">
			
				<div id="inner-footer" class="wrap clearfix">

						<div class="socialbar aligncenter">
							<p>
							    <a href="<?php bloginfo('rss2_url'); ?>"><img src="<?php echo(get_stylesheet_directory_uri()); ?>/images/icons/rss-64.png" height="32" width="32" alt="rss"></a>
							    <a href="<?php echo('https://twitter.com/' . get_option('kmc2_twitter_user', '' )); ?>"><img src="<?php echo(get_stylesheet_directory_uri()); ?>/images/icons/twitter-bird-dark-bgs-64.png" height="32" width="32" alt="twitter"></a>
								<a href="//plus.google.com/102083218914804503792?prsrc=3"
								   rel="publisher" target="_top"><img src="<?php echo(get_stylesheet_directory_uri()); ?>/images/icons/gplus-64.png" height="32" width="32" alt="google plus">
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
						<p><a href="<?php echo home_url(); ?>" rel="nofollow"><img src="<?php echo(get_stylesheet_directory_uri()); ?>/images/logos/c2-blanco-64x64.png" alt="kmc2-logo"></a></p>
						<p><a href="<?php echo home_url(); ?>" rel="nofollow">&copy; <?php echo date('Y'); ?> <?php //bloginfo('name'); ?>km c<sup>2</sup><?php //bloginfo('name'); ?></a></p>
					</div>
				</div> <!-- end #inner-footer -->
				
			</footer> <!-- end footer -->
		
		
		<!-- all js scripts are loaded in library/kmc2.php -->
		<?php wp_footer(); ?>
	</body>

</html> <!-- end page. what a ride! -->
