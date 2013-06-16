<?php get_header(); ?>
			
			<div id="content">

				<div id="inner-content" class="wrap clearfix">
			
					<div id="main" class="ninecol first single clearfix" role="main">
						<?php 
						if ( function_exists('yoast_breadcrumb') ) {
							yoast_breadcrumb('<p id="breadcrumbs">','</p>');
						} ?>
						<p>Mi breadcrumb </p>
						<?php 

						$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
						$tax_term_breadcrumb_term_slug =  $term->slug;
						$tax_term_breadcrumb_taxonomy_slug = $term->taxonomy;

						echo "\n1 - " . $term;
						echo "\n2 - " . $tax_term_breadcrumb_term_slug;
						echo "\n3 - " . $tax_term_breadcrumb_taxonomy_slug;

						foreach (get_the_category() as $cat) {
							$parent = get_category($cat->category_parent);
							$parent_name = $parent->cat_name;
							$parent_slug = $parent_name;
							$parent_slug = strtolower(str_replace("(","",$parent_slug));
							$parent_slug = str_replace(')',' ', $parent_slug);
							$parent_slug = str_replace(' ','-', $parent_slug);
						}
						     
						if ( is_category($parent_name)  ) { ?>

						    <a href="<?php echo get_option('home'); ?>/" title="Home">Home</a> » <? echo $parent_name; ?>

						<?php 
						} elseif (is_tax($tax_term_breadcrumb_term_slug)) { ?>

							<a href="<?php echo get_option('home'); ?>/" title="Home">Home</a> » 
							<a href="<?php echo get_option('home');?>/ 
								<?php echo $parent_slug; ?>" 
								title="<?php echo $parent_name; ?>">
								<?php echo $parent_name; ?> </a> » 
								<?php echo $term->name; ?>

							<?php 
							} elseif ( is_front_page() ){ ?>
								Home
							<?php 
							} 
							?>
						<?php
						global $wp_query;
    					$list_of_posts = $wp_query;
    					display_posts($list_of_posts); 
    					?>
			
					</div> <!-- end #main -->

				</div> <!-- end #inner-content -->
    
			</div> <!-- end #content -->

<?php get_footer(); ?>
