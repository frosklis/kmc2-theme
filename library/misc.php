<?php

/**
 * Defines theme image sizes
 * @return void
 */
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

    add_image_size('width_260', 260, 9999, false);

}

// kmc2 image shortcode
/**
 * image shortcode definiction
 * @param array $atts User defined atts
 * @return string html code of the shortcode
 */
function kmc2_image_shortcode($atts) {
    extract( shortcode_atts( array(
        'id' => '',
        'width' => '100%',
        'legend' => 'true',
        'link' => 'true',
        'align' => 'center',
        'min_width' => '',
        'max_width' => ''
    ), $atts, 'image' ) );

    $atts = array(
        'id' => $id,
        'width' => $width,
        'legend' => $legend,
        'link' => $link,
        'align' => $align,
        'min_width' => $min_width,
        'max_width' => $max_width);

    return kmc2_get_attachment_image($atts);
}
add_shortcode('image', 'kmc2_image_shortcode');

function edit_image_html($html, $attachment_id, $attachment) {
    return "[image id='$attachment_id']";
}
add_filter('image_send_to_editor', 'edit_image_html', 10, 3);

/**
 * Generates the html for displaying a picture
 *
 * The generated html defines several sources for the image and attributes such as the title, caption, etc.
 * These parameters then have to be parsed using javascript to actually generate the final html code.
 * Also, a noscript downstripped version of the code is provided in case the user chooses not to enable javascript.
 *
 * @param array $atts
 * @return string html code
 */
function kmc2_get_attachment_image($atts) {
    // In an image:
    // caption --> post_excerpt
    // title --> post_title
    // description --> content
    extract($atts);

    if (!isset($width)) $width = '100%';
    if (!isset($link)) {
        $link = true;
    }
    else {
        $link = 'true' == strtolower($link);
    }
    if (!isset($legend)) {
        $legend = true;
    }
    else {
        $legend = 'true' == strtolower($legend);
    }
    if (!isset($align)) $align = 'center';

    // width_260 omitted on purpose
    $sizes = array('small', 'medium', 'big', 'large', 'original', 'full', 'thumbnail', 'size_3', 'size_2');


    $queried_post = get_post($id);
    $caption = $queried_post->post_excerpt;
    $title = $queried_post->post_title;


    // If we are in the feed template, we just return the medium sized image
    if (is_feed()) {
        $aux = wp_get_attachment_image_src( $id, "medium" );
        $out = "<img src='" . $aux[0] . "' alt='" . $title . "'>";
        return $out;
    }

    $aux = wp_get_attachment_image_src( $id, 'full');

    $style = '';
    $align = strtolower($align);
    if ($width != '100%' || $align != 'center') {
        $style = "style='width:{$width};";

        if ($align != 'center') {
            $style .= 'float:'.$align.';';
            if ($align == "right") {
                $style .= 'margin-left: 12px';
            } else if ($align == "left") {
                $style .= 'margin-right: 12px';
            }
        }
        $style .= "'";
    }

    if ( 0 != $aux[1]){
        $ratio = 100 * $aux[2] / $aux[1]; //height / width
        $out = "<div class='img-container-wrapper' {$style}><div class='img-container not-loaded' style='padding-bottom: {$ratio}%;'><noscript";
    } else {
        $out = "<div class='img-container-wrapper' {$style}><div class='img-container not-loaded'><noscript";
    }
    $path = "";
    for ($i = 0; $i < sizeof($sizes); $i++) {
        $aux = wp_get_attachment_image_src( $id, $sizes[$i] ); // returns an array
        $path = $aux[0];
        $out .= " data-src-" . $sizes[$i] . "='" . $path . "'";
    }

    $out .= " data-src-img-id='{$id}'";

    // Image and caption
    if($legend) {
        $out .= " data-title='" . $title . "'";
        $out .= " data-caption='".$caption."'";
    }
    // Attachment page
    if($link) {
        $out .= " data-link='" . get_attachment_link($id) . "'";
    }
    // fallback if javascript is not used
    $out .= " ><img src='" . $path . "' alt='" . $title . "'>";
    $out .= " </noscript></div></div>";

    return $out;
}


// GET the first image of a post
function echo_first_image( $postID ) {

    if ( class_exists('KmC2_Responsive_Images') ) {
        $images_functions = new KmC2_Responsive_Images();
        $lista_imagenes = $images_functions->get_post_images( $postID );

        if (count($lista_imagenes) > 0) {
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


/**
 * Creates the gallery
 * @param type $cat_id
 * @return type
 */
function display_pictures($cat_id) {
?>

    <article <?php post_class('clearfix'); ?> role="article">


        <section class="entry-content clearfix">

        <?php
        //echo $cat_id . " - id de categoría \n";

        $id_list = array();

        if ($cat_id > -1) {
            $args = array(
                'posts_per_page' => -1,
                'cat' => $cat_id,
                'post_type' => array('attachment', 'post'),
                'post_status' => array('publish', 'inherit')
            );
            $list_of_posts = new WP_Query( $args );

            $post_id_list = array();

            if ($list_of_posts->have_posts()) {
                while ($list_of_posts->have_posts()) {
                    $list_of_posts->the_post();
                    $postID = get_the_ID();
                    if (strpos(get_post_mime_type($postID), "image") !== false) {
                        $id_list[] = $postID;
                    }
                    elseif (get_post_type($postID) != 'attachment') {
                        $post_id_list[] = $postID;
                    }
                }

                // Get the images from the posts
                global $wpdb;
                $prefix = $wpdb->prefix;
                $table = $prefix . 'kmc2_imagerel';

                $postlist = implode(',', $post_id_list);
                $post_ids = $wpdb->get_col("select image_id from $table where post_id in ( $postlist ) ");
                $id_list = array_merge($id_list, $post_ids);
            }

        } else {

            $args = array(
                'post_type' => 'attachment', 'post_mime_type' =>'image', 'post_status' => 'inherit', 'posts_per_page' => -1,
            );
            $list_of_posts = new WP_Query( $args );

            foreach ( $list_of_posts->posts as $attachment ) {
                array_push($id_list, $attachment->ID);
            }

        }

        // Limpiar aray de ids
        $id_list = array_unique($id_list);

        // Poner en orden aleatorio para que sea más interesante de mostrar
        shuffle($id_list);

        $str_ids = "";

        foreach ($id_list as $l) {
            $str_ids .= $l . ",";
        }


        $gallery = '[gallery type="masonry" autoload="true" include="';
        $gallery .= $str_ids . '"]';

        echo do_shortcode($gallery);

        ?>
        </section> <!-- end article section -->

    </article> <!-- end article -->



<?php
}


function kmc2_gallery_shortcode( $out, $atts ) {
    if (!isset($atts['autoload']) && !isset($atts['container']) && !isset($atts['type'])) return $out;

    if (isset($atts['autoload'])) {
        if ($atts['autoload'] == 'true') {

            $images = explode(',', $atts['include']);
            $loadchunk = 10;
            $chunks = intval((count($images) - 1) / $loadchunk);

            if ($chunks == 0) return $out;

            $first = array_slice($images, 0, $loadchunk);
            $ids = implode(',', $first);

            if (!isset($atts['type'])){
                $output = do_shortcode('[gallery include="' . $ids . '"]');
            }
            else {
                $output = do_shortcode('[gallery type="' . $atts['type'] . '" include="' . $ids . '"]');
            }
            $moregalleries = '<div data-chunks="' . $chunks . '"';
            $shortcodes = array();

            foreach (range(1, $chunks) as $i) {
                $next = array_slice($images, 10 * (1 + $chunks - $i), 10);
                $moregalleries .= "data-include-$i='" . implode(',', $next) . "' ";
            }
            unset($images);

            $pos = strpos($output, "<div");

            $output = substr($output, 0, $pos) . $moregalleries . substr($output, $pos + 4);

            $output .= "<script>
                jQuery(window).scroll(function () {
                    if (jQuery(window).scrollTop() === jQuery(document).height() - jQuery(window).height()) {
                        loadGallery();
                    }
                });
            </script>";

            return $output;
        }
    }

    if (isset($atts['container'])) {
        if ($atts['container'] == 'false') {
            if (!isset($atts['type'])){
                $out = do_shortcode("[gallery include='" . $atts['include'] . "']");
            }
            else {
                $out = do_shortcode('[gallery type="' . $atts['type'] . '" include="' . $atts['include'] . '"]');
            }
            $pos = strpos($out, "</style>");
            if ($pos > -1) $out = substr($out, $pos + 8);

            $pos = strpos($out, ">");
            $out = substr($out, $pos + 1);
            $pos = strrpos($out, "</div>");
            $out = substr($out, 0, $pos);

            return $out;
        }
    }

    if (isset($atts['type'])) {
        if (strtolower($atts['type']) == 'masonry') {
            $out = '<div class="gallery gallery-masonry">';

            $imageids = explode(',', $atts['include']);
            foreach ($imageids as $id) {
                $out .= '<div class="gallery-item">';

                $out .= do_shortcode('[image legend="false" id="' . $id . '"]');

                $out .= '</div>'; // gallery-item
            }
            $out .= '</div>'; // gallery class
        }
    }
    return $out;
}
add_filter( 'post_gallery', 'kmc2_gallery_shortcode', 10, 3 );


function kmc2_update_image_table($post_id) {
    global $wpdb;
    $prefix = $wpdb->prefix;
    $table = $prefix . 'kmc2_imagerel';

    if($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
        kmc2_create_image_table();
        return false;
    }
    // Drop and create table
    $wpdb->query("delete from " . $table . " where post_id = " . strval($post_id));


    $list_of_posts = new WP_Query( 'p=' . strval($post_id) );

    $pattern = get_shortcode_regex();

    foreach ($list_of_posts->posts as $post) {
        if (   preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches )
            && array_key_exists( 2, $matches )
            && in_array( 'image', $matches[2] ) )
        {
            foreach ($matches[0] as $key => $value) {
                $value = str_replace('[', '', $value);
                $value = str_replace(']', '', $value);
                $atts = shortcode_parse_atts($value);

                $id = intval($atts['id']);

                $q = "insert into ". $table . " (post_id, image_id) values (" . strval($post_id) . ", " . strval($id) . ")";
                $wpdb->query($q);
            }
        }
    }
}
add_action( 'save_post', 'kmc2_update_image_table' );
function kmc2_create_image_table()
{

    global $wpdb;
    $prefix = $wpdb->prefix;
    $table = $prefix . 'kmc2_imagerel';
    // Drop and create table
    $wpdb->query("drop table if exists $table");
    $wpdb->query("create table $table (
            image_id integer,
            post_id integer
        )");

    $args = array(
        'post_type' => 'post',
        'nopaging' => true
      );

    $list_of_posts = new WP_Query( $args );

    $pattern = get_shortcode_regex();


    foreach ($list_of_posts->posts as $post) {
        if (   preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches )
            && array_key_exists( 2, $matches )
            && in_array( 'image', $matches[2] ) )
        {
            kmc2_update_image_table($post->ID);
        }
    }
}
// add_action( 'wp', 'kmc2_detect_image_shortcode' );

/**
 * If the post has images, it returns one random image
 * @param int $post_id
 * @return int an image id
 */
function kmc2_get_random_image_id($post_id) {
    global $wpdb;
    $prefix = $wpdb->prefix;
    $table = $prefix . 'kmc2_imagerel';

    // Drop and create table
    return $wpdb->get_var("select image_id from $table where post_id = $post_id order by rand() limit 1");
}

add_filter ('the_content', 'kmc2_posts_with_attachment');

function kmc2_posts_with_attachment($content) {
    if (!is_attachment()) {
        return $content;
    }

    global $wpdb;
    $prefix = $wpdb->prefix;
    $table = $prefix . 'kmc2_imagerel';

    // Drop and create table
    $image_id = get_the_ID();
    $post_ids = $wpdb->get_col("select post_id from $table where image_id = $image_id order by rand()");

    if (!$post_ids) {
        return $content;
    }

    $related = "<h3>" . __("Posts with this file", "kmc2theme") . "</h3>";
    $related .= "<ol>";

    foreach ( $post_ids as $id )
    {
        $related .= "<li><a href='";
        $title = get_the_title($id);
        $related .= get_permalink($id) . "'  title='" . trim(strip_tags($title)) . "'>" . $title;
        $related .= "</a></li>";

    }

    $related .= "</ol>";

    return $content . $related;
}
// // Autocreate a menu
// function kmc2_automenu() {
//     $menuname = __('KM C2 Default menu', 'kmc2theme');

//     // Does the menu exist already?
//     $menu = wp_get_nav_menu_object( $menuname );

//     // If it doesn't exist, let's create it.
//     if( $menu) {
//         wp_delete_nav_menu($menu->term_id );

//         return false;
//     }

//     $menu_id = wp_create_nav_menu($menuname);

//     wp_update_nav_menu_item($menu_id, 0, array(
//         'menu-item-title' =>  __('Front page', kmc2theme),
//         'menu-item-classes' => 'home',
//         'menu-item-url' => home_url( '/' ),
//         'menu-item-status' => 'publish'));

//     $id_cat = wp_update_nav_menu_item($menu_id, 0, array(
//         'menu-item-title' =>  __('Categories', kmc2theme),
//         'menu-item-classes' => 'categories',
//         'menu-item-url' => '#',
//         'menu-item-status' => 'publish'));

//     wp_update_nav_menu_item($menu_id, 0, array(
//         'menu-item-title' =>  __('All', kmc2theme),
//         'menu-item-classes' => 'All',
//         'menu-item-url' => '#',
//         'menu-item-status' => 'publish',
//         'menu-item-parent-id' => $id_cat));

//     wp_update_nav_menu_item($menu_id, 0, array(
//         'menu-item-title' =>  __('Blog', kmc2theme),
//         'menu-item-classes' => 'blog',
//         'menu-item-url' => home_url( '/blog/' ),
//         'menu-item-status' => 'publish'));


// }

// if (is_admin()) {
//     kmc2_automenu();
// }

?>