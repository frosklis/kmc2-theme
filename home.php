<?php get_header(); ?>
			
			<div id="content">
				<div id="inner-content" class="wrap clearfix">
					<!--Cover banner-->
					<div id="cover_banner" class="home-slideshow">
						<div class="flex-container">
						  <div class="flexslider">
							<ul class="slides">
			
								<?php			
								$media_items = get_attachments_by_media_tags('media_tags=portada');
								
								foreach ($media_items as $imagen) { ?>
									<li>
										<!-- <img src="thumbnail.php?file=<?php // echo($imagen->guid ); ?>&width=50&height=50"> -->
										<img src="<?php 
											//echo($imagen->guid );
											echo (str_replace($imagen->guid, ".jpg", "-960x540.jpg")); 
										?>">
										<p class="flex-caption"><?php // var_dump($imagen); ?></p>
									</li> 
								<?php
								} // foreach
								?>

							</ul>
						  </div>
						</div>
					</div>

				    
				</div> <!-- end #inner-content -->
    
			</div> <!-- end #content -->

<?php get_footer(); ?>
