 <?php if ( is_active_sidebar( 'sidebar1' ) ) {
	echo('<div id="sidebar1" class="sidebar threecol last clearfix" role="complementary">');

	dynamic_sidebar( 'sidebar1' );

	echo('</div>');
}
?>
