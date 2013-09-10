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

	// Select the proper image size: it is the smallest one greater or equal than $maxdim
	$sizes = unserialize(IMAGE_SIZES);
	$pixel_ratio = $_COOKIE['device_pixel_ratio'];
	$thumbnail_size = 0;

	for ($i=0; $i<count($sizes); $i++) {
		$thumbnail_size = $sizes[$i];
		if ($thumbnail_size >= $pixel_ratio * $maxdim) break;
	}


	$i = 0;
	$min_width = 100000;
	$mason_sizes = "";
	foreach ( $attachments as $id => $attachment ) {
		$image_meta  = wp_get_attachment_metadata( $id );

		$orientation = '';
		$real_width = 0;
		$real_height = 0;
		if ( isset( $image_meta['height'], $image_meta['width'] ) ) {
			$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';
			$size = ( $orientation == 'portrait' ) ? 'Image w' : 'Image h';
			$size .= $thumbnail_size;

			$picture_ratio = $image_meta['width'] / $image_meta['height'];
			if ($orientation == 'portrait') {
				$picture_ratio = 1 / $picture_ratio;
				$real_width = $maxdim * 3 / 4;
				$real_height = $real_width * $picture_ratio;
			} else {
				$real_height = $maxdim * 3/4;
				$real_width = $real_height * $picture_ratio;
			}

			// area = width * height = picture_ratio * height ^ 2
			// picture_ratio = area / (height ^ 2)
			// height = sqrt(area / picture_ratio)
			$multiplier = pow(16 / ($picture_ratio * 9), 0.5);

			$real_height *= $multiplier;
			$real_width *= $multiplier;

		} else {
			continue;
		}
		

		if ( ! empty( $attr['link'] ) && 'none' === $attr['link'] )
			$image_output = wp_get_attachment_image( $id, $size, false );
		else
			$image_output = wp_get_attachment_link( $id, $size, true, false );

		
		$image_attributes = wp_get_attachment_image_src( $id, $size ); // returns an array
		$image_link = get_attachment_link($id);

		$output .= "<{$itemtag} class='gallery-item'>";
		$output .= "
			<{$icontag} class='gallery-icon {$orientation}'>" . '<a href="'.$image_link.'" title="'.$attachment->post_title.'"><img src="'
				. $image_attributes[0] .'" width="' . $real_width .'" height="'.$real_height.'"></a>'.
			"</{$icontag}>";
		if ( $captiontag && trim($attachment->post_excerpt) ) {
			$output .= "
				<{$captiontag} class='wp-caption-text gallery-caption'>
				" . wptexturize($attachment->post_excerpt) . " 
				</{$captiontag}>";
		} 
		$output .= "</{$itemtag}>";

		// If style==rectangular, output a blank line every $columns columns
		if ( $columns > 0 && ++$i % $columns == 0 && $type != 'masonry') {
			$output .= '<br style="clear: both" />';
		}
	}


	$size_class = sanitize_html_class( $size );
	// $gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";
	$classes = 'gallery galleryid-{$id} gallery-columns-{$columns} gallery-{$type}';
	$masonry_options = '';
	if ($type == 'masonry') {
		$classes .= ' gallery-masonry';
	}


	$gallery_div = "<div id='$selector' class='$classes' $masonry_options >";
	// $output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

	$output = $gallery_div . $output . "
			<br style='clear: both;' />
		</div>\n";

	return $output;
}

?>