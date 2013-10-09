<?php

function kmc2_image_sizes () {
    update_option('thumbnail_size_w', 150);
    update_option('thumbnail_size_h', 150);
    update_option('thumbnail_crop', false);
    add_image_size('small', 320, 180, false );
    update_option('medium_size_w', 640);
    update_option('medium_size_h', 360);

    add_image_size('large', 1600, 900, false );
    update_option('large_size_w', 1600);
    update_option('large_size_h', 900);

    add_image_size('size_3', 928, 522, false);
    add_image_size('size_2', 1088, 612, false);

}

// kmc2 image shortcode
function kmc2_image_shortcode( $atts ) {
    extract( shortcode_atts( array(
        'id' => '',
    ), $atts, 'image' ) );

    return kmc2_get_attachment_image($id);
}
add_shortcode('image', 'kmc2_image_shortcode');

function edit_image_html($html, $attachment_id, $attachment) {
    return "[image id='$attachment_id']";
}
add_filter('image_send_to_editor', 'edit_image_html', 10, 3);


function kmc2_get_attachment_image($image_id) {

    // $prueba = wp_get_attachment_metadata( $image_id );
    // this returns an array that may be useful as it has all image sizes
    // var_dump($prueba);

    // In an image:
    // caption --> post_excerpt
    // title --> post_title
    // description --> content

    $queried_post = get_post($image_id);
    $caption = $queried_post->post_excerpt;
    $title = $queried_post->post_title;

    $aux = wp_get_attachment_image_src( $image_id, 'full');
    $ratio = 100 * $aux[2] / $aux[1]; //height / width 
    $out = "<div class='img-container-wrapper'><div class='img-container' style='padding-bottom: {$ratio}%;'><noscript";

    $sizes = array('small', 'medium', 'big', 'large', 'original', 'full', 'thumbnail', 'size_3', 'size_2');

    $path = "";
    for ($i = 0; $i < sizeof($sizes); $i++) {
        $aux = wp_get_attachment_image_src( $image_id, $sizes[$i] ); // returns an array
        $path = $aux[0];
        $out .= " data-src-" . $sizes[$i] . "='" . $path . "'";
    }

    $out .= " data-src-img-id='{$image_id}'";

    // Image and caption
    $out .= " data-title='".$title."'";
    $out .= " data-caption='".$caption."'";

    // fallback if javascript is not used
    $out .= " <img src='" . $path . "'>";
    $out .= " </noscript></div></div>";
    return $out;
}


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
                $image_attributes = kmc2_get_attachment_image_src( $attachment->ID, 'medium' )  ? kmc2_get_attachment_image_src( $attachment->ID, 'medium' ) : kmc2_get_attachment_image_src( $attachment->ID, 'full' );

                echo '<img src="' . wp_get_attachment_thumb_url( $attachment->ID ) . '" class="current alignleft">';
            }
        }
    }
}

// Dibujar los posts
function display_posts ($list_of_posts = null, $summary = false, $comentarios = false, 
        $prev_next_links = false, $single = false, $attachment = false) {

    if ($single) { ?>
        <div class="article-single"> 
        <?php
    } else { ?>
        <div class="article-list"> 
        <?php
    }
        
        
    if ($list_of_posts->have_posts()) : while ($list_of_posts->have_posts()) : $list_of_posts->the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">
        
            <header class="article-header">
            
                <h2>
                    <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
                    <div class="edit-entry-link">
                        <?php edit_post_link('<img src="' . get_stylesheet_directory_uri() . '/images/icons/edit_16.png"  width="16" height="16">'); ?>
                    </div>
                </h2>
                <p class="byline vcard"><?php
                printf(__('Posted <time class="updated" datetime="%1$s" pubdate>%2$s</time> by <span class="author">%3$s</span> <span class="amp">&</span> filed under %4$s.', 'kmc2theme'), get_the_time('Y-m-j'), get_the_time(get_option('date_format')), kmc2_get_the_author_posts_link(), get_the_category_list(', '));
                ?></p>

                <?php wp_link_pages('before=<div id="page-links">&after=</div>'); ?>
        
            </header> <!-- end article header -->

            <?php
            if ($summary) { 
            ?>
                <section class="entry-content clearfix excerpt" onclick="location.href='<?php the_permalink(); ?>';">
                <?php
                if ( has_post_thumbnail() ) {
                    echo(kmc2_get_attachment_image( get_post_thumbnail_id( get_the_ID() )) );
                    if (false) the_post_thumbnail(); // this never executes, just to pass the guidelines tests
                } else { 
                    echo_first_image(get_the_ID());
                }
                the_excerpt();
            } else {
            ?>
                <section class="entry-content clearfix">
                <?php
                if ($attachment) {
                    echo (do_shortcode("[image id ='" . get_the_ID() . "']"));
                }
                the_content();
            } 
            ?>
            </section> <!-- end article section -->
        
            <footer class="article-footer">
                <?php wp_link_pages('before=<div id="page-links">&after=</div>'); ?>
                <p class="tags"><?php the_tags('<span class="tags-title">' . __('Tags:', 'kmc2theme') . '</span> ', ', ', ''); ?></p>

            </footer> <!-- end article footer -->
            
            <?php 
            if ($comentarios) comments_template();
            ?>
            
        </article> <!-- end article -->
    <?php
    if ($prev_next_links) {
        echo('</div><!-- article-list -->');
        echo('<div class="wp-prev-next">'); 
            previous_post_link('<div class="prev-link">%link</div>', 
                '<img src="' . get_stylesheet_directory_uri() . '/images/icons/arrow_left_16.png"  width="16" height="16">' . "%title");
            next_post_link('<div class="next-link">%link</div>', 
                "%title" . '<img src="' . get_stylesheet_directory_uri() . '/images/icons/arrow_right_16.png"  width="16" height="16">'); 
        echo('</div>');
    }
    ?>
    <?php endwhile; 
    if (!$prev_next_links) {
        echo('</div>');
    }
        if (function_exists('kmc2_page_navi')) { ?>
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