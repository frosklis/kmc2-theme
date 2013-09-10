<?php
// GET the first image of a post
function echo_first_image( $postID ) {

    if ( class_exists('KmC2_Responsive_Images') ) {
        $images_functions = new KmC2_Responsive_Images();
        $lista_imagenes = $images_functions->get_post_images( $postID );

        if (count($lista_imagenes)>0) {
            $img_id = $lista_imagenes[0];
            echo '<img src="' . wp_get_attachment_thumb_url( $img_id ) . '" class="current alignleft">';
        }
    } 
    else {
        $args = array(
            'numberposts' => 1,
            'order' => 'ASC',
            'post_mime_type' => 'image',
            'post_parent' => $postID,
            'post_status' => null,
            'post_type' => 'attachment',
        );

        $attachments = get_children( $args );

        if ( $attachments ) {
            foreach ( $attachments as $attachment ) {
                $image_attributes = wp_get_attachment_image_src( $attachment->ID, 'medium' )  ? wp_get_attachment_image_src( $attachment->ID, 'medium' ) : wp_get_attachment_image_src( $attachment->ID, 'full' );

                echo '<img src="' . wp_get_attachment_thumb_url( $attachment->ID ) . '" class="current alignleft">';
            }
        }
        else {
            echo 'No hay imágenes';
        }
}
}

// Dibujar los posts
function display_posts ($list_of_posts = null, $resumen = false, $comentarios = false, $prev_next_links = false) {

        if ($list_of_posts->have_posts()) : while ($list_of_posts->have_posts()) : $list_of_posts->the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">
        
            <header class="article-header">
            
                <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
                <p class="byline vcard"><?php
                printf(__('Posted <time class="updated" datetime="%1$s" pubdate>%2$s</time> by <span class="author">%3$s</span> <span class="amp">&</span> filed under %4$s.', 'kmc2theme'), get_the_time('Y-m-j'), get_the_time(get_option('date_format')), kmc2_get_the_author_posts_link(), get_the_category_list(', '));
                ?></p>
                <p><?php edit_post_link( "Editar entrada"); ?></p>
        
            </header> <!-- end article header -->

            <?php
            if ($resumen) { 
            ?>
                <section class="entry-content clearfix excerpt" onclick="location.href='<?php the_permalink(); ?>';">
                <?php
                if ( has_post_thumbnail() ) {
                    the_post_thumbnail("medium",array('class' => 'alignleft'));
                } else { 
                    echo_first_image(get_the_ID());
                }
                the_excerpt();
            } else {
            ?>
                <section class="entry-content clearfix">
                <?php
                the_content();
            } 
            ?>
            </section> <!-- end article section -->
        
            <footer class="article-footer">
                <p class="tags"><?php the_tags('<span class="tags-title">' . __('Tags:', 'kmc2theme') . '</span> ', ', ', ''); ?></p>

            </footer> <!-- end article footer -->
            
            <?php if ($comentarios) comments_template();  ?>
            
        </article> <!-- end article -->
        <?php
        if ($prev_next_links) {
            echo('<div class="wp-prev-next">'); 
                previous_post_link('<div class="prev-link">  ≪ %link</div>');
                next_post_link('<div class="next-link">%link ≫  </div>'); 
            echo('</div>');
        }
        ?>
        <?php endwhile; ?>  

            <?php if (function_exists('kmc2_page_navi')) { ?>
                <?php kmc2_page_navi(); ?>
            <?php } else { ?>
                <nav class="wp-prev-next">
                    <ul class="clearfix">
                        <li class="prev-link"><?php next_posts_link(__('&laquo; Older Entries', "kmc2theme")) ?></li>
                        <li class="next-link"><?php previous_posts_link(__('Newer Entries &raquo;', "kmc2theme")) ?></li>
                    </ul>
                </nav>
            <?php } ?>      

        <?php else : ?>
        
            <article id="post-not-found" class="hentry clearfix">
                <header class="article-header">
                    <h1><?php _e("Oops, Post Not Found!", "kmc2theme"); ?></h1>
                </header>
                <section class="entry-content">
                    <p><?php _e("Uh Oh. Something is missing. Try double checking things.", "kmc2theme"); ?></p>
                </section>
                <footer class="article-footer">
                    <p><?php _e("This is the error message in the display_posts function.", "kmc2theme"); ?></p>
                </footer>
            </article>

        <?php endif; ?>


<?php
}

// Mostrar las imágenes asociadas a una categoría
function display_pictures($cat_id) {
?>    

    <article <?php post_class('clearfix'); ?> role="article">
    
        <!-- <header class="article-header">
        
            <h2><a href="" rel="bookmark" title="Fotos">Fotos</a></h2>

    
        </header> --><!-- end article header -->

        <section class="entry-content clearfix">

        <?php 
        //echo $cat_id . " - id de categoría \n";
        
        $args = array(
            'posts_per_page' => -1,
            'cat' => $cat_id,
        );
        $list_of_posts = new WP_Query( $args );

        $lista_id = array();

        $number_of_posts = 0;
        $number_of_pictures = 0;

        if ( class_exists('KmC2_Responsive_Images') ) {
            $images_functions = new KmC2_Responsive_Images();

            if ($list_of_posts->have_posts()) : while ($list_of_posts->have_posts()) : $list_of_posts->the_post(); 
                $number_of_posts += 1;

                $postID = get_the_ID();


                $lista_imagenes = $images_functions->get_post_images( $postID );
                
                for($i=0; $i<count($lista_imagenes); $i++) {
                    array_push($lista_id, $lista_imagenes[$i]);
                    $number_of_pictures += 1;
                }
            endwhile;
            endif;
        } 
        else {

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
        }
        // Limpiar aray de ids
        $lista_id = array_unique($lista_id);

        // Poner en orden aleatorio para que sea más interesante de mostrar
        shuffle($lista_id);

        $str_ids = "";

        foreach ($lista_id as $l) {
            $str_ids .= $l . ",";
        }

        
        // $gallery = '[gallery type=rectangular columns=5 ids="';
        $gallery = '[gallery maxdim=300 type="masonry" ids="';
        $gallery .= $str_ids . '"]';

        echo do_shortcode($gallery);

        ?>
        </section> <!-- end article section -->

    </article> <!-- end article -->



<?php
}
?>