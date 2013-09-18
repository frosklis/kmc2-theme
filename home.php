<?php get_header(); ?>
			<div id="content">
				<div id="inner-content" class="wrap clearfix">

					<?php 
					if ( is_active_sidebar( 'homepage_widgets' ) ) dynamic_sidebar( 'homepage_widgets' ); 
					?>
					<div id="home-categories">

						<?php
						$url = get_bloginfo('url') . '/tree/';

						$args = array(
							'type'                     => 'post',
							'child_of'                 => 0,
							'parent'                   => '',
							'orderby'                  => 'name',
							'order'                    => 'ASC',
							'hide_empty'               => 1,
							'hierarchical'             => 1,
							'exclude'                  => '',
							'include'                  => '',
							'number'                   => '',
							'taxonomy'                 => 'category',
							'pad_counts'               => false );
						$categories = get_categories( $args );

						// shuffle($categories);

						$cat_tiles = array();

						foreach ($categories as $category) {
							if ($category->count == 0) continue;

							$tile = array();

							echo '<div class="home-category">';

							$cad = "";

							$cat_url = $url . $category->slug;

							// 1st tile
							// Poner un resumen de la categoría, con links
							$cad .= '<div class="tile textcontent">';
							$cad .= '<h2><a href="' . get_category_link( $category->term_id ) . '" title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '" ' . '>' . $category->name.'</a> </h2> ';
						 //    $cad .= '<nav class="nav clearfix wrap"><ul>';
							// $cad .= '<li><a href="'.$cat_url.'/diario">Diario</a></li>';
							// $cad .= '<li><a href="'.$cat_url.'/notas">Notas</a></li>';
							// $cad .= '<li><a href="'.$cat_url.'/fotos">Fotos</a></li>';
							// $cad .= "</ul></nav>";
							$cad .= '<p>'. $category->description . '</p>';
						    // $cad .= '<p>' . $category->count . ' ' . __("entries", "kmc2theme"). '</p>';


							$cad .= '<ul>';

							$args = array(
					            'posts_per_page' => 6,
					            'cat' => $category->cat_ID,
					        );
					        $list_of_posts = new WP_Query( $args );

					        if ($list_of_posts->have_posts()) : while ($list_of_posts->have_posts()) : $list_of_posts->the_post(); 
								$cad .= '<li><a href="' . get_permalink() . '" rel="bookmark" title="';
								$cad .= get_the_title() . '">'. get_the_title() . '</a></li>'; 
                
					        endwhile;
					        endif;

							$cad .= "</ul></div>";

							array_push($tile, $cad);


							// 2nd tile
							$cad = '<div class="tile">';


							$args = array(
					            'posts_per_page' => -1,
					            'cat' => $category->cat_ID,
					        );
					        $list_of_posts = new WP_Query( $args );

					        // var_dump($category);
					        // var_dump($args);

					        $lista_id = array();

					        $number_of_posts = 0;
					        $number_of_pictures = 0;

					        if ($list_of_posts->have_posts()) : while ($list_of_posts->have_posts()) : $list_of_posts->the_post(); 
					            $number_of_posts += 1;

					            $postID = get_the_ID();

					            $args = array(
					                'posts_per_page' => -1,
					                'order' => 'ASC',
					                'post_mime_type' => 'image',
					                'post_parent' => $postID,
					                'post_status' => null,
					                'post_type' => 'attachment',
					            );

					            $attachments = get_children( $args );

					            if ( $attachments ) {
					                foreach ( $attachments as $attachment ) {
					                    array_push($lista_id, $attachment->ID);
					                    $number_of_pictures += 1;
					                }
					            }


					        endwhile;
					        endif;

					        // Limpiar aray de ids
					        $lista_id = array_unique($lista_id);

					        // Poner en orden aleatorio para que sea más interesante de mostrar
					        shuffle($lista_id);

					        if (count($lista_id) > 0) $cad .= kmc2_get_attachment_image( $lista_id[0], 'thumbnail');

							$cad .= "</div>";

							array_push($tile, $cad);

							$cad = '<div class="tile">';
							if (count($lista_id) > 1) $cad .= kmc2_get_attachment_image( $lista_id[1], 'thumbnail' );
					        $cad .= "</div>";
							array_push($tile, $cad);


							// Mostrar las tiles
							//shuffle($tile);

							foreach ($tile as $t) {
								echo $t;
							}

							// Cerrar la div .home-category
							echo "</div>";
						}
						?>
					</div>

				</div> <!-- end #inner-content -->
    
			</div> <!-- end #content -->

<?php get_footer(); ?>
