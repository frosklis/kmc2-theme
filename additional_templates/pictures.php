<?php
/*
This template shows all pictures
*/
?>
<?php get_header(); ?>
<?php
$category = get_category(intval(get_query_var('cat')));
$tipo = get_query_var('tipo');
?>
			<div id="content">
				<div id="inner-content" class="clearfix">
					<div id="main" class="twelvecol single first clearfix category-page" role="main">
						<?php
						display_pictures(-1);
						?>
					</div> <!-- end #main -->

				</div> <!-- end #inner-content -->

			</div> <!-- end #content -->

<?php get_footer(); ?>

