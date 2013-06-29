				<div id="sidebar1" class="sidebar threecol last clearfix" role="complementary">
					<div class="socialbar">
					    <a href="<?php bloginfo('rss2_url'); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/rss.png" height="32px" width="32px"></a>
					    <a href="https://twitter.com/_kmc2"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/twitter-bird-dark-bgs.png" height="32px" width="32px"></a>
						<a href="//plus.google.com/102083218914804503792?prsrc=3"
						   rel="publisher" target="_top"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/gplus.png" height="32px" width="32px">
						</a>
			
						<form role="search" method="get" id="searchform" action="<?php bloginfo('siteurl'); ?>">
						    <input type="text" value="" name="s" id="s" placeholder="Buscar en el blog...">
					    </form>
						
					</div>  
					<?php if ( is_active_sidebar( 'sidebar1' ) ) : ?>

						<?php dynamic_sidebar( 'sidebar1' ); ?>

					<?php else : ?>

						<!-- This content shows up if there are no widgets defined in the backend. -->
						
						<div class="alert help">
							<p><?php _e("Please activate some Widgets.", "kmc2theme");  ?></p>
						</div>

					<?php endif; ?>

				</div>