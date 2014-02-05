<?php
function kmc2_AddHomeSlide() {


	$args = array(
		'posts_per_page'   => 1,
		'offset'           => 0,
		'category'         => '',
		'orderby'          => 'rand',
		'order'            => 'DESC',
		'include'          => '',
		'exclude'          => '',
		'meta_key'         => '',
		'meta_value'       => '',
		'post_type'        => 'attachment',
		'post_mime_type'   => '',
		'post_parent'      => '',
		// 'post_status'      => 'publish',
		'suppress_filters' => true
	);
	$image_list = get_posts($args);
	$results = "";
	foreach ($image_list as $image ) {
		$results .= '<li style="display: block; opacity: 0; z-index: 1">';
		$results .= kmc2_get_attachment_image($image->ID);
		$results .= "</li>";
	}
	die($results);
}
// creating Ajax call for WordPress
add_action( 'wp_ajax_kmc2_AddHomeSlide', 'kmc2_AddHomeSlide' );
add_action( 'wp_ajax_nopriv_kmc2_AddHomeSlide', 'AddHomeSlide' );




function kmc2_infinite_scroll(){
    $paged           = $_GET['page_no'];
    $posts_per_page  = get_option('posts_per_page');

    # Load the posts
    query_posts(array('paged' => $paged ));

	$args = array(
		'post_type' => 'post',
		'paged' => $paged,
		'posts_per_page' => 20
	);

	$list_of_posts = new WP_Query( $args );

	$args = array(
	    'list_of_posts' => $list_of_posts,
	    'tiles' => true,
	    'infinite_scroll' => true,
	    'ajax' => true
	);

    ob_start();
	$check = display_posts($args);

	$results = ob_get_contents();
	ob_end_clean();

	if ($check == -1) {
		$results = "0";
	}
    die($results);
}
add_action('wp_ajax_kmc2_infinite_scroll', 'kmc2_infinite_scroll');           // for logged in user
add_action('wp_ajax_nopriv_kmc2_infinite_scroll', 'kmc2_infinite_scroll');    // if user not logged in

?>