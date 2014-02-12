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
				</div> <!-- end #inner-footer -->

			</footer> <!-- end footer -->


		<!-- all js scripts are loaded in library/kmc2.php -->
		<?php wp_footer();?>
	</body>

</html> <!-- end page. what a ride! -->
