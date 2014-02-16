<?php
function left_admin_footer_text_output($text) {
    $text = __('Km c<sup>2</sup> theme', 'kmc2theme');
    return $text;
}
add_filter('admin_footer_text', 'left_admin_footer_text_output'); //left side

function right_admin_footer_text_output($text) {
	$text = __('by', 'kmc2theme');
	$text .= "<a href='mailto:claudio@kmc2.tk'> Claudio Noguera</a>";
	return $text;
}
add_filter('update_footer', 'right_admin_footer_text_output', 11); //right side


// function kmc2_custom_admin_menu() {
// 	add_submenu_page( 'edit.php',
// 		'',
// 		__('Drafts', 'kmc2theme'),
// 		'edit_posts',
// 		'kmc2_drafts',
// 		'my_custom_submenu_page_callback' );
// }

// function my_custom_submenu_page_callback() {

// 	echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
// 		echo '<h2>My Custom Submenu Page</h2>';
// 	echo '</div>';

// }

// add_action('admin_menu', 'kmc2_custom_admin_menu');

?>