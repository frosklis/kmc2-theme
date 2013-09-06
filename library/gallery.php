<?php
remove_shortcode('gallery');
add_shortcode('gallery', 'kmc2_gallery_shortcode');
//add_filter( 'use_default_gallery_style', '__return_false' );

/**
 * The Gallery shortcode.
 *
 * This implements the functionality of the Gallery Shortcode for displaying
 * WordPress images on a post.
 *
 * @since 2.5.0
 *
 * @param array $attr Attributes of the shortcode.
 * @return string HTML content to display gallery.
 */
function kmc2_gallery_shortcode($attr) {
	$post = get_post();

	static $instance = 0;
	$instance++;

	if ( ! empty( $attr['ids'] ) ) {
		// 'ids' is explicitly ordered, unless you specify otherwise.
		if ( empty( $attr['orderby'] ) )
			$attr['orderby'] = 'post__in';
		$attr['include'] = $attr['ids'];
	}

	// Allow plugins/themes to override the default gallery template.
	$output = apply_filters('post_gallery', '', $attr);
	if ( $output != '' )
		return $output;

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post ? $post->ID : 0,
		'itemtag'    => 'dl',
		'icontag'    => 'dt',
		'captiontag' => 'dd',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => '',
		'type'       => 'rectangular',
		'maxdim'     => 295
	), $attr, 'gallery'));

	$id = intval($id);
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( !empty($include) ) {
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	}

	if ( empty($attachments) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		return $output;
	}

	$itemtag = tag_escape($itemtag);
	$captiontag = tag_escape($captiontag);
	$icontag = tag_escape($icontag);
	$valid_tags = wp_kses_allowed_html( 'post' );
	$type = tag_escape($type);
	$maxdim = intval($maxdim);

	if ( ! isset( $valid_tags[ $itemtag ] ) )
		$itemtag = 'dl';
	if ( ! isset( $valid_tags[ $captiontag ] ) )
		$captiontag = 'dd';
	if ( ! isset( $valid_tags[ $icontag ] ) )
		$icontag = 'dt';

	$columns = intval($columns);
	$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
	$float = is_rtl() ? 'right' : 'left';

	$selector = "gallery-{$instance}";

	$gallery_style = $gallery_div = '';
	if ( apply_filters( 'use_default_gallery_style', true ) && $type != 'masonry') {
		$gallery_style = "
		<style type='text/css'>
			#{$selector} {
				margin: auto;
			}
			#{$selector} .gallery-item {
				float: {$float};
				margin-top: 10px;
				text-align: center;
				width: {$itemwidth}%;
			}
			#{$selector} img {
				border: 2px solid #cfcfcf;
			}
			#{$selector} .gallery-caption {
				margin-left: 0;
			}
			/* see gallery_shortcode() in wp-includes/media.php */
		</style>";
	}

	$output = "";

	// Seleccionar el tamaño adecuado de la imagen
	$sizes = unserialize(IMAGE_SIZES);
	$pixel_ratio = $_COOKIE['device_pixel_ratio'];
	$thumbnail_size = 0;

	for ($i=0; $i<count($sizes); $i++) {
		$thumbnail_size = $sizes[$i];
		if ($thumbnail_size >= $pixel_ratio * $maxdim) break;
	}


	$i = 0;
	$min_width = 100000;
	foreach ( $attachments as $id => $attachment ) {
		$image_meta  = wp_get_attachment_metadata( $id );

		$orientation = '';
		$real_width = 0;
		if ( isset( $image_meta['height'], $image_meta['width'] ) ) {
			$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';
			$size = ( $image_meta['height'] > $image_meta['width'] ) ? 'Image h' : 'Image w';
			$size .= $thumbnail_size;

			$real_width = $thumbnail_size / $pixel_ratio;
			if ($orientation == 'portrait') {
				$real_width *= $image_meta['width'] / $image_meta['height'];
			} 
		}

		if ($min_width > $real_width) $min_width = $real_width;

		if ( ! empty( $attr['link'] ) && 'none' === $attr['link'] )
			$image_output = wp_get_attachment_image( $id, $size, false );
		else
			$image_output = wp_get_attachment_link( $id, $size, true, false );


		$output .= "<{$itemtag} class='gallery-item'>";
		$output .= "
			<{$icontag} class='gallery-icon {$orientation}'>
				$image_output
			</{$icontag}>";
		if ( $captiontag && trim($attachment->post_excerpt) ) {
			$output .= "
				<{$captiontag} class='wp-caption-text gallery-caption'>
				" . wptexturize($attachment->post_excerpt) . " 
				</{$captiontag}>";
		} 
		$output .= "</{$itemtag}>";

		// Si el estilo es rectangular, hay que poner una línea en blanco cada $columns columnas
		if ( $columns > 0 && ++$i % $columns == 0 && $type != 'masonry') {
			$output .= '<br style="clear: both" />';
		}
	}


	$size_class = sanitize_html_class( $size );
	// $gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";
	$classes = 'gallery galleryid-{$id} gallery-columns-{$columns} gallery-{$type}';
	$masonry_options = '';
	if ($type == 'masonry') {
		$classes .= ' js-masonry';
		// $masonry_options = "data-masonry-options='{ ". '"item-selector": ".gallery-item", "gutter": 5, "isFitWidth": true }' . "'";
		$masonry_options = "data-masonry-options='{ ". '"isFitWidth": true, "columnWidth": ' . $min_width . '}' . "'";
	}


	$gallery_div = "<div id='$selector' class='$classes' $masonry_options >";
	// $output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

	$output = $gallery_div . $output . "
			<br style='clear: both;' />
		</div>\n";

	$tams = IMAGE_SIZES;
	$output = "<p>Mansonry options: $masonry_options </p>". $output;
	return $output;
}

?>