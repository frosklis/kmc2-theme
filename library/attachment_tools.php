<?php


add_action( 'admin_menu', 'exifize_date_menu' );
function exifize_date_menu() {
	add_submenu_page( 'tools.php', 'EXIFize Dates', 'EXIFize Dates', 'manage_options', 'exifize-my-dates', 'exifize_my_dates' );
}

function exifize_my_dates() {
	?>
	<div class="">
		<h1>EXIFize My Dates</h1>

	<?php

	if(isset($_POST['submit']) && $_POST['action'] != 'none') {
		// Check nonce if we are asked to do something...
		if( check_admin_referer('exifize_my_dates_nuclear_nonce') ){
			$action = $_POST['action'];
			exifizer_nuclear_option($action);
		} else {
			wp_die( 'What are you doing, Dave? (Invalid Request)' );
		}
	}

	$args=array(
		'public'   => true,
		//'_builtin' => false
	);
	$output = 'objects';
	$operator = 'and';
	$post_types = get_post_types($args,$output,$operator);
	?>

		<p>This tool will attempt to <em>irreversably</em> change the <em>actual</em> post date of Post Type selected below.
		<br /><small><em>Note: since this changes the actual post date, if you are using dates in your permalink structure, this will change them, possibly breaking incomming links.</small></em></p>
		</p>
		<p>The date will be changed using (in order of priority):</p>

		<form name="input" action="<?php $_SERVER['PHP_SELF'];?>" method="post">
			<?php
			if ( function_exists('wp_nonce_field') ) wp_nonce_field('exifize_my_dates_nuclear_nonce');
			?>
			<input type="hidden"  name="action" value="read-exif" />
			<input type="submit"  name="submit" value="Read EXIF metadata" />
		</form>

		<p><em>**To override the function with a custom date, create a new custom meta field with the name: 'exifize_date' and value: 'YYYY-MM-DD hh:mm:ss' -- for example: '2012-06-23 14:07:00' (no quotes). You can also enter value: 'skip' to prevent the EXIFizer from making any changes.</em></p>
		<br />
	</div>
	<?php
} //end function exifize_my_dates()


function exifizer_nuclear_option($action){
	if ( ! current_user_can( 'manage_options' ) )
		wp_die( 'What are you doing, Dave? (Insufficient Capability)' );

	echo "<h2>Working...</h2>";

	$args = array(
		'post_type' => 'attachment',
		'numberposts' => -1,
		'post_status' => 'any',
	);

	$allposts = get_posts( $args );

	foreach($allposts as $post) : setup_postdata($post);

		$exifdate = 'none'; //safety
		$postid = $post->ID;
		$attachname = $post -> post_title;
		$postdate = $post -> post_date;
		$pediturl = get_admin_url() . "post.php?post=" . $postid . "&action=edit";

		echo "<p>Processing attachment <a href = \"". $pediturl . "\" title=\"Edit " . $ptype . " \">" . $postid . ": \"" . $attachname . "\"</a> ";


		$attachid = $postid;
		echo "using EXIF date from " . $exifdetails . " id ". $attachid . ": \"" . $attachname . "\"</p>";


		$meta = get_post_meta( $attachid );

		$upload_dir = wp_upload_dir();

		$exif = exif_read_data($upload_dir['basedir'] . '/' . $meta['_wp_attached_file'][0]);

		$update_date = false;
		$update_gps = false;

		if ($exif == false) {
			echo "<div class=\"error highlight\"><p>" . __("Couldn't read EXIF data", 'kmc2theme') . "</p></div>";
		}
		else {
			echo "<div class=\"updated highlight\">";
			if (isset($exif['DateTimeOriginal'])) {
				echo "<p>" . __("Picture date: ", 'kmc2theme') . $exif['DateTimeOriginal'] . "</p>";
				$update_date = true;
			} else {
				echo "<p>" . __("Got no date!", 'kmc2theme') . "</p>";
			}
			if (isset($exif["GPSLongitude"]) && isset($exif["GPSLongitudeRef"])
				&& isset($exif["GPSLatitude"]) && isset($exif["GPSLatitudeRef"])) {
				echo "<p>" . __("The picture has GPS data.", 'kmc2theme') . "</p>";
				$update_gps = true;
			} else {
				echo "<p>" . __("No EXIF GPS data.", 'kmc2theme') . "</p>";
			}

			echo "</div>";
		}


		// Update post GPS data
		if ($update_gps && !(isset($meta['geo_latitude']) && isset($meta['geo_longitude']))) {

			$lon = getGps($exif["GPSLongitude"], $exif['GPSLongitudeRef']);
			$lat = getGps($exif["GPSLatitude"], $exif['GPSLatitudeRef']);

			update_post_meta($attachid, 'geo_latitude', $lat);
			update_post_meta($attachid, 'geo_longitude', $lon);
			echo "<div class=\"updated highlight\">";
			echo "<p>" . __("GPS data updated: ", 'kmc2theme') . $lon . ", " . $lat . "</p>";
			echo "</div>";

		} else {
			echo "<div class=\"updated highlight\">";
			echo "<p>" . __("Not updating GPS data", 'kmc2theme');
			if ($update_gps) {
				echo __(" because it is already up to date", 'kmc2theme');
			}
			echo ".</p>";
			echo "</div>";
		}

		// Update post date

		if ($update_date) {
			$exifdate = $exif['DateTimeOriginal'];
			$postdate = get_the_time('Y-m-d H:i:s', $attachid);

			echo "<div class=\"updated highlight\">";
			echo "<p>" . __("Updating date.", 'kmc2theme') . "</p>";
			echo "</div>";

			if (date_parse($exifdate) != date_parse($postdate)) {
				$update_post = array(
					'ID' => $postid,
					'post_date' => $exifdate,
					'post_date_gmt' => $exifdate
				);

				wp_update_post($update_post);
			}
		}
	endforeach;

	?>
	<h2>All done!</h2>
	<p>Please check your posts for unexpected results... Common errors include:
	<ol>
		<li>EXIF dates are wrong</li>
		<li>EXIF dates are missing</li>
		<li>The stars have mis-aligned creating a reverse vortex, inserting a bug in the program... please let me know and I'll try to fix it.</li>
	</ol>
	</p>

	<br /><hr><br />
	<h2>Again?</h2>
	<?php
} //end function exifizer_nuclear_option


?>
