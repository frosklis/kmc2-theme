<?php
// add_filter( 'image_downsize', 'kmc2_image_downsize', 10, 3 );

// modified from https://gist.github.com/Viper007Bond/2785428
 
function kmc2_image_downsize( $existing_data, $attachment_id, $size ) {

	global $_wp_additional_image_sizes;

	// Let WordPress handle named thumbnail sizes
	if ( ! is_array( $size ) ) {
		$size = $_wp_additional_image_sizes[$size];
	}

	// Safety
	$size = array_map( 'absint', $size );
 
	// Get the path to the fullsize image
	$fullsize_path = get_attached_file( $attachment_id );
 
	// Split it into parts
	$fullsize_info = pathinfo( $fullsize_path );
 
	// Create the thumbnail filename
	// Is there a helper function for all of this? I couldn't find one.
	$thumbnail_filename = str_replace( ".{$fullsize_info['extension']}", "-{$size['width']}x{$size['height']}.{$fullsize_info['extension']}", $fullsize_info['basename'] );
 
	$thumbnail_path = $fullsize_info['dirname'] . '/' . $thumbnail_filename;
 
	// If the thumbnail already exists
	if ( file_exists( $thumbnail_path ) ) {
 
		// Create the URL to the thumbnail by taking the fullsize image
		// and replacing it's filename with the thumbnail filename
		$thumbnail_url = str_replace( $fullsize_info['basename'], $thumbnail_filename, wp_get_attachment_url( $attachment_id ) );
	}

	// Okay, thumbnail doesn't exist. Make it!
	else {
		// Have to crop so that width/height is exact and findable in the future.
		$image = wp_get_image_editor( $fullsize_path );

		if ( is_wp_error( $image ) ) {
			return $existing_data;
		}

		try {

			$new_size = array_map( 'absint', $image->get_size());

			$aux = (int)($size["width"] * $new_size["height"] / $new_size["width"]);
			
			$thumbnail_filename = str_replace( ".{$fullsize_info['extension']}", 
				"-{$size['width']}x{$aux}.{$fullsize_info['extension']}", 
				$fullsize_info['basename'] ); 

			if ( file_exists( $fullsize_info['dirname'] . '/' . $thumbnail_filename ) ) {
		 
				// Create the URL to the thumbnail by taking the fullsize image
				// and replacing it's filename with the thumbnail filename
				$thumbnail_url = str_replace( $fullsize_info['basename'], $thumbnail_filename, wp_get_attachment_url( $attachment_id ) );

					return array(
						$thumbnail_url, // URL
						$size['width'],       // Width
						$aux,       // Height
						true,           // is_intermediate, i.e. exact size or will it be resized via HTML?
					);
			}

			$aux = (int)($size["height"] * $new_size["width"] / $new_size["height"]);
			$thumbnail_filename = str_replace( ".{$fullsize_info['extension']}", 
				"-{$aux}x{$size['height']}.{$fullsize_info['extension']}", 
				$fullsize_info['basename'] ); 

			if ( file_exists( $fullsize_info['dirname'] . '/' . $thumbnail_filename ) ) {
		 
				// Create the URL to the thumbnail by taking the fullsize image
				// and replacing it's filename with the thumbnail filename
				$thumbnail_url = str_replace( $fullsize_info['basename'], $thumbnail_filename, wp_get_attachment_url( $attachment_id ) );

					return array(
						$thumbnail_url, // URL
						$aux,       // Width
						$size['height'],       // Height
						true,           // is_intermediate, i.e. exact size or will it be resized via HTML?
					);
			}
		} catch (Exception $e) {
			error_log($e->getMessage());

			return $existing_data;
		}
		
		$new_thumbnail_path="";
		if ( ! is_wp_error( $image ) ) {
		    $image->resize( $size["width"], $size["height"], $size["crop"] );
		    // $image->save( 'new_image.jpg' );
		    $new_thumbnail_path = $image->save();
		    $size=$image->get_size();
		}

		if ( is_wp_error( $new_thumbnail_path ) ) {
			return $existing_data;
		}

		$new_thumbnail_path = $new_thumbnail_path["path"];



 
		// Get the thumbnail path parts, specifically the filename
		// Yeah, we created it above but let's be absolutely sure it's correct
		$new_thumbnail_info = pathinfo( $new_thumbnail_path );
 
		$thumbnail_url = str_replace( $fullsize_info['basename'], $new_thumbnail_info['basename'], wp_get_attachment_url( $attachment_id ) );
	}
 
	return array(
		$thumbnail_url, // URL
		$size['width'],       // Width
		$size['height'],       // Height
		true,           // is_intermediate, i.e. exact size or will it be resized via HTML?
	);
}





// remove_shortcode('gallery');
// add_shortcode('gallery', 'kmc2_gallery_shortcode');
//add_filter( 'use_default_gallery_style', '__return_false' );
?>