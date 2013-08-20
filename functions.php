<?php
/*
Author: Claudio Noguera
URL: htp://blog.claudionoguera.tk
*/

if ( ! isset( $content_width ) ) $content_width = 1980;

// we're firing all out initial functions at the start


function kmc2_ahoy() {
    // launching operation cleanup
    add_action('init', 'kmc2_head_cleanup');
    
    add_action('init', 'tipo_taxonomy' );


    // Set featured image
    add_action('the_post', 'autoset_featured');
    add_action('save_post', 'autoset_featured');
    add_action('draft_to_publish', 'autoset_featured');
    add_action('new_to_publish', 'autoset_featured');
    add_action('pending_to_publish', 'autoset_featured');
    add_action('future_to_publish', 'autoset_featured');

    // remove WP version from RSS
    add_filter('the_generator', 'kmc2_rss_version');
    // remove pesky injected css for recent comments widget
    add_filter( 'wp_head', 'kmc2_remove_wp_widget_recent_comments_style', 1 );
    // clean up comment styles in the head
    add_action('wp_head', 'kmc2_remove_recent_comments_style', 1);
    // clean up gallery output in wp
    add_filter('gallery_style', 'kmc2_gallery_style');

    // ie conditional wrapper
    add_filter( 'style_loader_tag', 'kmc2_ie_conditional', 10, 2 );

    // enqueue base scripts and styles
    add_action('wp_enqueue_scripts', 'kmc2_scripts_and_styles', 999);

    // launching this stuff after theme setup
    add_action('after_setup_theme','kmc2_theme_support');
    // adding sidebars to Wordpress (these are created in functions.php)
    add_action( 'widgets_init', 'kmc2_register_sidebars' );
    // adding the kmc2 search form (created in functions.php)
    add_filter( 'get_search_form', 'kmc2_wpsearch' );

    // cleaning up random code around images
    add_filter('the_content', 'kmc2_filter_ptags_on_images');

    // no mostrar admin bar
    add_filter('show_admin_bar', '__return_false' );

    //add_filter('wp_nav_menu_items','add_social_bar_to_menu', 10, 2);


    add_filter( 'wp_nav_menu_items', 'add_logo_to_menu', 10, 2 );
} /* end kmc2 ahoy */
add_action('after_setup_theme','kmc2_ahoy', 15);

function custom_excerpt_length( $length ) {
    return 60;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

function new_excerpt_more( $more ) {
    return '<a class="read-more" href="'. get_permalink( get_the_ID() ) . '"> ... '.__(' keep reading','kmc2theme').'</a>';
}
add_filter( 'excerpt_more', 'new_excerpt_more' );




function autoset_featured() {
    global $post;
    $already_has_thumb = has_post_thumbnail($post->ID);
    if (!$already_has_thumb)  {
        $attached_image = get_children( "post_parent=$post->ID&post_type=attachment&post_mime_type=image&numberposts=1" );
        if ($attached_image) {
            foreach ($attached_image as $attachment_id => $attachment) {
                set_post_thumbnail($post->ID, $attachment_id);
            }
        }
    }
}


function tipo_taxonomy() {

    global $wp_rewrite;
    register_taxonomy(
        'tipo',
        'post',
        array(
            'hierarchical' => true,
            'label' => 'Tipo',
            'query_var' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'rewrite' => array('slug' => 'tipo', 'with_front' => true),
            )
        );

    $tipo1_structure = '/tree/%category%/%tipo%';
    $wp_rewrite->add_permastruct('category_tipo', $tipo1_structure);
    //$wp_rewrite->generate_rewrite_rules();
    $wp_rewrite->flush_rules();
}

/*********************
WP_HEAD GOODNESS
The default wordpress head is
a mess. Let's clean it up by
removing all the junk we don't
need.
*********************/

function kmc2_head_cleanup() {
    // category feeds
    // remove_action( 'wp_head', 'feed_links_extra', 3 );
    // post and comment feeds
    // remove_action( 'wp_head', 'feed_links', 2 );
    // EditURI link
    remove_action( 'wp_head', 'rsd_link' );
    // windows live writer
    remove_action( 'wp_head', 'wlwmanifest_link' );
    // index link
    remove_action( 'wp_head', 'index_rel_link' );
    // previous link
    remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
    // start link
    remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
    // links for adjacent posts
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
    // WP version
    remove_action( 'wp_head', 'wp_generator' );
    // remove WP version from css
    add_filter( 'style_loader_src', 'kmc2_remove_wp_ver_css_js', 9999 );
    // remove Wp version from scripts
    add_filter( 'script_loader_src', 'kmc2_remove_wp_ver_css_js', 9999 );

} /* end kmc2 head cleanup */

// remove WP version from RSS
function kmc2_rss_version() { return ''; }

// remove WP version from scripts
function kmc2_remove_wp_ver_css_js( $src ) {
    if ( strpos( $src, 'ver=' ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}

// remove injected CSS for recent comments widget
function kmc2_remove_wp_widget_recent_comments_style() {
   if ( has_filter('wp_head', 'wp_widget_recent_comments_style') ) {
      remove_filter('wp_head', 'wp_widget_recent_comments_style' );
   }
}

// remove injected CSS from recent comments widget
function kmc2_remove_recent_comments_style() {
  global $wp_widget_factory;
  if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
    remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
  }
}

// remove injected CSS from gallery
function kmc2_gallery_style($css) {
  return preg_replace("!<style type='text/css'>(.*?)</style>!s", '', $css);
}


/*********************
SCRIPTS & ENQUEUEING
*********************/

// loading modernizr and jquery, and reply script
function kmc2_scripts_and_styles() {

    // Sintaxis: 
    // wp_register_script( $handle, $src, $deps, $ver, $in_footer ); 

    if (!is_admin()) {

        // register main stylesheet
        wp_register_style( 'kmc2-stylesheet', get_stylesheet_directory_uri() . '/library/css/style.css', array(), '', 'all' );

        // ie-only style sheet
        wp_register_style( 'kmc2-ie-only', get_stylesheet_directory_uri() . '/library/css/ie.css', array(), '' );

        // comment reply script for threaded comments
        if ( is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
            wp_enqueue_script( 'comment-reply' );
        }

        //adding scripts file in the footer
        wp_register_script( 'kmc2-js', get_stylesheet_directory_uri() . '/library/js/scripts.js', array( 'jquery' ), '', true );


        // enqueue styles and scripts
        wp_enqueue_style('kmc2-stylesheet');
        wp_enqueue_style('kmc2-ie-only');
        wp_enqueue_script('jquery');
        wp_enqueue_script('kmc2-js');

    }
}

// adding the conditional wrapper around ie stylesheet
// source: http://code.garyjones.co.uk/ie-conditional-style-sheets-wordpress/
function kmc2_ie_conditional( $tag, $handle ) {
    if ( 'kmc2-ie-only' == $handle )
        $tag = '<!--[if lt IE 9]>' . "\n" . $tag . '<![endif]-->' . "\n";
    return $tag;
}

/*********************
THEME SUPPORT
*********************/

// Adding WP 3+ Functions & Theme Support
function kmc2_theme_support() {

    // wp thumbnails (sizes handled in functions.php)
    add_theme_support('post-thumbnails');

    // default thumb size
    set_post_thumbnail_size(400, 400, true);

    // wp custom background (thx to @bransonwerner for update)
    add_theme_support( 'custom-background',
        array(
        'default-image' => '',  // background image default
        'default-color' => '', // background color default (dont add the #)
        'wp-head-callback' => '_custom_background_cb',
        'admin-head-callback' => '',
        'admin-preview-callback' => ''
        )
    );

    // rss thingy
    add_theme_support('automatic-feed-links');

    // to add header image support go here: http://themble.com/support/adding-header-background-image-support/

    // adding post format support
    add_theme_support( 'post-formats',
        array(
            //'aside',             // title less blurb
            //'gallery',           // gallery of images
            //'link',              // quick link to other site
            //'image',             // an image
            //'quote',             // a quick quote
            //'status',            // a Facebook like status update
            //'video',             // video
            //'audio',             // audio
            //'chat'               // chat transcript
        )
    );

    // wp menus
    add_theme_support( 'menus' );

    // registering wp3+ menus
    register_nav_menus(
        array(
            'main-nav' => __( 'The Main Menu', 'kmc2theme' ),   // main nav in header
            'footer-links' => __( 'Footer Links', 'kmc2theme' ) // secondary nav in footer
        )
    );


} /* end kmc2 theme support */


/*********************
MENUS & NAVIGATION
*********************/
function add_logo_to_menu ( $items, $args ) {
    //$img_logo = '<img src="' . get_bloginfo('stylesheet_directory') . '/images/c2-blanco-64x64.png">';

    //$items = '<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-9">' . $img_logo .'</li>' . $items;

    return $items;
}
// the main menu
function kmc2_main_nav() {
    // display the wp3 menu if available

    wp_nav_menu(array(
        'container' => false,                           // remove nav container
        'container_class' => 'nav clearfix',           // class of container (should you choose to use it)
        'menu' => __( 'The Main Menu', 'kmc2theme' ),  // nav name
        'menu_class' => 'nav top-nav clearfix',         // adding custom nav class
        'theme_location' => 'main-nav',                 // where it's located in the theme
        'before' => '',                                 // before the menu
        'after' => '',                                  // after the menu
        'link_before' => '',                            // before each link
        'link_after' => '',                             // after each link
        //'items_wrap' => '<ul><li id="menu-logo" class="menu-item">Menu: </li>%3$s</ul>',
        'depth' => 0,                                   // limit the depth of the nav
        'fallback_cb' => 'kmc2_main_nav_fallback'      // fallback function
    ));
} /* end kmc2 main nav */

// the footer menu (should you choose to use one)
function kmc2_footer_links() {
    // display the wp3 menu if available
    wp_nav_menu(array(
        'container' => '',                              // remove nav container
        'container_class' => 'footer-links clearfix',   // class of container (should you choose to use it)
        'menu' => __( 'Footer Links', 'kmc2theme' ),   // nav name
        'menu_class' => 'nav footer-nav clearfix',      // adding custom nav class
        'theme_location' => 'footer-links',             // where it's located in the theme
        'before' => '',                                 // before the menu
        'after' => '',                                  // after the menu
        'link_before' => '',                            // before each link
        'link_after' => '',                             // after each link
        'depth' => 0,                                   // limit the depth of the nav
        'fallback_cb' => 'kmc2_footer_links_fallback'  // fallback function
    ));
} /* end kmc2 footer link */

// this is the fallback for header menu
function kmc2_main_nav_fallback() {
    wp_page_menu( array(
        'show_home' => true,
        'menu_class' => 'nav footer-nav clearfix',      // adding custom nav class
        'include'     => '',
        'exclude'     => '',
        'echo'        => true,
        'link_before' => '',                            // before each link
        'link_after' => ''                             // after each link
    ) );
}

// this is the fallback for footer menu
function kmc2_footer_links_fallback() {
    /* you can put a default here if you like */
}


/*********************
PAGE NAVI
*********************/

// Numeric Page Navi (built into the theme by default)
function kmc2_page_navi($before = '', $after = '') {
    global $wpdb, $wp_query;
    $request = $wp_query->request;
    $posts_per_page = intval(get_query_var('posts_per_page'));
    $paged = intval(get_query_var('paged'));
    $numposts = $wp_query->found_posts;
    $max_page = $wp_query->max_num_pages;
    if ( $numposts <= $posts_per_page ) { return; }
    if(empty($paged) || $paged == 0) {
        $paged = 1;
    }
    $pages_to_show = 7;
    $pages_to_show_minus_1 = $pages_to_show-1;
    $half_page_start = floor($pages_to_show_minus_1/2);
    $half_page_end = ceil($pages_to_show_minus_1/2);
    $start_page = $paged - $half_page_start;
    if($start_page <= 0) {
        $start_page = 1;
    }
    $end_page = $paged + $half_page_end;
    if(($end_page - $start_page) != $pages_to_show_minus_1) {
        $end_page = $start_page + $pages_to_show_minus_1;
    }
    if($end_page > $max_page) {
        $start_page = $max_page - $pages_to_show_minus_1;
        $end_page = $max_page;
    }
    if($start_page <= 0) {
        $start_page = 1;
    }
    echo $before.'<nav class="page-navigation"><ol class="kmc2_page_navi clearfix">'."";
    if ($start_page >= 2 && $pages_to_show < $max_page) {
        $first_page_text = __( "First", 'kmc2theme' );
        echo '<li class="bpn-first-page-link"><a href="'.get_pagenum_link().'" title="'.$first_page_text.'">'.$first_page_text.'</a></li>';
    }
    echo '<li class="bpn-prev-link">';
    previous_posts_link('<<');
    echo '</li>';
    for($i = $start_page; $i  <= $end_page; $i++) {
        if($i == $paged) {
            echo '<li class="bpn-current">'.$i.'</li>';
        } else {
            echo '<li><a href="'.get_pagenum_link($i).'">'.$i.'</a></li>';
        }
    }
    echo '<li class="bpn-next-link">';
    next_posts_link('>>');
    echo '</li>';
    if ($end_page < $max_page) {
        $last_page_text = __( "Last", 'kmc2theme' );
        echo '<li class="bpn-last-page-link"><a href="'.get_pagenum_link($max_page).'" title="'.$last_page_text.'">'.$last_page_text.'</a></li>';
    }
    echo '</ol></nav>'.$after."";
} /* end page navi */

/*********************
RANDOM CLEANUP ITEMS
*********************/

// remove the p from around imgs (http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/)
function kmc2_filter_ptags_on_images($content){
   return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}

/*
 * This is a modified the_author_posts_link() which just returns the link.
 *
 * This is necessary to allow usage of the usual l10n process with printf().
 */
function kmc2_get_the_author_posts_link() {
    global $authordata;
    if ( !is_object( $authordata ) )
        return false;
    $link = sprintf(
        '<a href="%1$s" title="%2$s" rel="author">%3$s</a>',
        get_author_posts_url( $authordata->ID, $authordata->user_nicename ),
        esc_attr( sprintf( __( 'Posts by %s' ), get_the_author() ) ), // No further l10n needed, core will take care of this one
        get_the_author()
    );
    return $link;
}





/*
2. library/custom-post-type.php
    - an example custom post type
    - example custom taxonomy (like categories)
    - example custom taxonomy (like tags)
*/
// require_once('library/custom-post-type.php'); // you can disable this if you like
/*
3. library/admin.php
    - removing some default WordPress dashboard widgets
    - an example custom dashboard widget
    - adding custom login css
    - changing text in footer of admin
*/
//require_once('library/admin.php'); // this comes turned off by default
/*
4. library/translation/translation.php
    - adding support for other languages
*/
require_once('library/translation/translation.php'); // this comes turned off by default
/*
5. library/options.php
    - adds options to the wordpress dashboard
See http://net.tutsplus.com/tutorials/wordpress/how-to-create-a-better-wordpress-options-panel/
*/
require_once('library/options.php');

/************* THUMBNAIL SIZE OPTIONS *************/

// Thumbnail sizes
add_image_size( 'kmc2-thumb-600', 600, 150, false );
add_image_size( 'kmc2-thumb-300', 300, 100, false );
add_image_size( 'kmc2-thumb-960', 960, 540, false );
/* 
to add more sizes, simply copy a line from above 
and change the dimensions & name. As long as you
upload a "featured image" as large as the biggest
set width or height, all the other sizes will be
auto-cropped.

To call a different size, simply change the text
inside the thumbnail function.

For example, to call the 300 x 300 sized image, 
we would use the function:
<?php the_post_thumbnail( 'kmc2-thumb-300' ); ?>
for the 600 x 100 image:
<?php the_post_thumbnail( 'kmc2-thumb-600' ); ?>

You can change the names and dimensions to whatever
you like. Enjoy!
*/
add_filter( 'post_thumbnail_html', 'remove_width_attribute', 10 );
add_filter( 'image_send_to_editor', 'remove_width_attribute', 10 );
add_filter( 'image_send_to_editor', 'remove_class_attribute', 10 );
add_filter( 'image_send_to_editor', 'remove_alt_attribute', 10 );
 
function remove_width_attribute( $html ) {
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html );
    return $html;
} 
function remove_class_attribute( $html ) {
    $html = preg_replace('/class=".*?"/', "", $html );
    return $html;
} 
function remove_alt_attribute( $html ) {
    $html = preg_replace('/alt=".*?"/', "", $html );
    return $html;
}

/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetizes Areas
function kmc2_register_sidebars() {
    register_sidebar(array(
    	'id' => 'sidebar1',
    	'name' => __('Sidebar 1', 'kmc2theme'),
    	'description' => __('The first (primary) sidebar.', 'kmc2theme'),
    	'before_widget' => '<div id="%1$s" class="widget %2$s">',
    	'after_widget' => '</div>',
    	'before_title' => '<h4 class="widgettitle">',
    	'after_title' => '</h4>',
    ));
    
    // This is where the banner will be
    register_sidebar(array(
        'id' => 'footer1',
        'name' => __('Footer 1', 'kmc2theme'),
        'description' => __('The footer', 'kmc2theme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widgettitle">',
        'after_title' => '</h4>',
    ));

    // This is where the banner will be
    register_sidebar(array(
        'id' => 'homepage_widgets',
        'name' => __('Homepage widgets', 'kmc2theme'),
        'description' => __('These widgets go right below the navbar', 'kmc2theme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widgettitle">',
        'after_title' => '</h4>',
    ));    // This is where the banner will be

    register_sidebar(array(
        'id' => 'category_widgets',
        'name' => __('Category page widgets', 'kmc2theme'),
        'description' => __('These widgets go right below the navbar in the category pages', 'kmc2theme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widgettitle">',
        'after_title' => '</h4>',
    ));

} // don't remove this bracket!

/************* COMMENT LAYOUT *********************/
		
// Comment Layout
function kmc2_comments($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?>>
		<article id="comment-<?php comment_ID(); ?>" class="clearfix">
			<header class="comment-author vcard">
			    <?php 
			    /*
			        this is the new responsive optimized comment image. It used the new HTML5 data-attribute to display comment gravatars on larger screens only. What this means is that on larger posts, mobile sites don't have a ton of requests for comment images. This makes load time incredibly fast! If you'd like to change it back, just replace it with the regular wordpress gravatar call:
			        echo get_avatar($comment,$size='32',$default='<path_to_url>' );
			    */ 
			    ?>
			    <!-- custom gravatar call -->
			    <?php
			    	// create variable
			    	$bgauthemail = get_comment_author_email();
			    ?>
			    <img data-gravatar="http://www.gravatar.com/avatar/<?php echo md5($bgauthemail); ?>?s=32" class="load-gravatar avatar avatar-48 photo" height="32" width="32" src="<?php echo get_template_directory_uri(); ?>/library/images/nothing.gif" />
			    <!-- end custom gravatar call -->
				<?php printf(__('<cite class="fn">%s</cite>', 'kmc2theme'), get_comment_author_link()) ?>
				<time datetime="<?php echo comment_time('Y-m-j'); ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time(__('F jS, Y', 'kmc2theme')); ?> </a></time>
				<?php edit_comment_link(__('(Edit)', 'kmc2theme'),'  ','') ?>
			</header>
			<?php if ($comment->comment_approved == '0') : ?>
       			<div class="alert info">
          			<p><?php _e('Your comment is awaiting moderation.', 'kmc2theme') ?></p>
          		</div>
			<?php endif; ?>
			<section class="comment_content clearfix">
				<?php comment_text() ?>
			</section>
			<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
		</article>
    <!-- </li> is added by WordPress automatically -->
<?php
} // don't remove this bracket!

/************* SEARCH FORM LAYOUT *****************/

// Search Form
function kmc2_wpsearch($form) {
    $form = '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
    <input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="'.esc_attr__('Search the Site...','kmc2theme').'" />
    </form>';
    return $form;
} // don't remove this bracket!



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

        
        $gallery = '[gallery type=rectangular columns=5 ids="';
        $gallery .= $str_ids . '"]';

        echo do_shortcode($gallery);

        ?>
        </section> <!-- end article section -->

    </article> <!-- end article -->



<?php
}
?>
