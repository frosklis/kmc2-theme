<?php
function AddHomeSlide() {


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
add_action( 'wp_ajax_AddHomeSlide', 'AddHomeSlide' );
add_action( 'wp_ajax_nopriv_AddHomeSlide', 'AddHomeSlide' );

?>