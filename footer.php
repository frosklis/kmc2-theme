			<footer class="footer" role="contentinfo">
			
				<div id="inner-footer" class="wrap clearfix">

						<div class="socialbar aligncenter">
							<p>
							    <a href="<?php bloginfo('rss2_url'); ?>"><span class="icon-rss"></span></a>
							    <a href="<?php echo('https://twitter.com/' . get_option('kmc2_twitter_user', '' )); ?>"><span class="icon-twitter"></span></a>
								<a href="//plus.google.com/102083218914804503792?prsrc=3"
								   rel="publisher" target="_top"><span class="icon-google-plus"></span>
								</a>
							</p>
								<?php
								$form = "footersearch";
								echo(kmc2_wpsearch($form));
								?>
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

	                <div class="donation aligncenter">
	                	<p>Invítanos a un café: <a href="bitcoin:18xXsjSYr4hueUqLwb1dXuugvzAb3e3h6k" target="_blank" title="Click here to send this address to your wallet (if your wallet is not compatible you will get an empty page, close the white screen and copy the address by hand)" style="top: 28px; left: 218px;"><img src="http://coinwidget.com/widget/icon_wallet.png"></a></p>
	                </div>
	                		
					<div class="source-org copyright aligncenter">
						<p><a href="<?php echo home_url(); ?>" rel="nofollow">
						<?php require('images/svg/c2.svg'); ?>
						</a></p>
						<!-- <p><a href="<?php echo home_url(); ?>" rel="nofollow">&copy; <?php echo date('Y'); ?> km c<sup>2</sup><?php // bloginfo('name'); ?></a></p> -->
					</div>
				</div> <!-- end #inner-footer -->
				
			</footer> <!-- end footer -->
		
		
		<!-- all js scripts are loaded in library/kmc2.php -->
		<?php wp_footer();?>
	</body>

</html> <!-- end page. what a ride! -->
