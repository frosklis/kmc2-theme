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


function kmc2_load_gallery() {
	$gallery = "[gallery include='" . $_GET['include'] . "' container='false']";

	$out = do_shortcode($gallery);

	die($out);
}
add_action('wp_ajax_kmc2_load_gallery', 'kmc2_load_gallery');           // for logged in user
add_action('wp_ajax_nopriv_kmc2_load_gallery', 'kmc2_load_gallery');    // if user not logged in

?>