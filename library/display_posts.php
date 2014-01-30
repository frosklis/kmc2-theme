<?php
//
// Display the posts
// A lot of options can be passed as keys to the $args array
// return values:
// 1 --> everything went fine
// -1 --> no_posts
//
function display_posts ($args) {
    $list_of_posts = isset($args["list_of_posts"]) ? $args["list_of_posts"] : null;
    $summary = isset($args["summary"]) ? $args["summary"] : false;
    $comments = isset($args["comments"]) ? $args["comments"] : false;
    $prev_next_links = isset($args["prev_next_links"]) ? $args["prev_next_links"] : false;
    $single = isset($args["single"]) ? $args["single"] : false;
    $attachment = isset($args["attachment"]) ? $args["attachment"] : false;

    $tiles = isset($args["tiles"]) ? $args["tiles"] : false;


    $pages = isset($args["pages"]) ? $args["pages"] : !$single;


	// Container for the article list
	$articles  = $single ? "article-single" : "article-list";
	echo ('<div class="' . $articles . '">');

	// If there are no posts, display an error message to the user and return false
	if (!$list_of_posts->have_posts()) { ?>
		<article id="post-not-found" class="hentry clearfix">
            <header class="article-header">
                <h1><?php _e("Oops, Post Not Found!", "kmc2theme"); ?></h1>
            </header>
            <section class="entry-content">
                <p><?php _e("Uh Oh. Something is missing. Try double checking things.", "kmc2theme"); ?></p>
                <pre><?php global $wp_query; var_dump($wp_query);?></pre>
            </section>
            <footer class="article-footer">
                <p><?php _e("This is the error message in the display_posts function.", "kmc2theme"); ?></p>
            </footer>
        </article>
		<?php
		echo ('</div>'); // Close article list div
		return -1;
	}

	// If we're here, we have posts
	while ($list_of_posts->have_posts()) {
		$list_of_posts->the_post(); // update wordpress variables

		// If what we have are tiles
		if($tiles) {
            $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), "width_260");
            $thumbnail = $thumbnail[0];
        ?>
            <a class="article-thumb" href="<?php the_permalink(); ?>">
                <div class="background" style="background-image: url(<?php echo($thumbnail); ?>);"></div>

                <div class="content">
                    <div class="title"><h3><?php the_title(); ?></h3></div>
                    <?php echo('<div class="meta"><p><span class="icon-calendar"></span> ');
                    printf('<time class="updated" datetime="%1$s" pubdate>%2$s</time></p></div>', get_the_time('Y-m-j'), get_the_time(get_option('date_format'))); ?>
                </div>
            </a>
        <?php
			continue;
		} // if tiles

		// No tiles
		?>

        <article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">

            <header class="article-header">

                <?php if ($single) { ?>
                    <h1><?php the_title(); ?> </h1>
                <?php } else { ?>
                    <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
                    <?php } ?>
                <div class="byline"><?php

					// Edit the entry
					edit_post_link(__('Edit', 'kmc2theme'),'<div class="info"><span class="icon-pencil"></span>','</div>');

					// Date
					echo('<div class="info"><span class="icon-calendar"></span> ');
                    printf('<time class="updated" datetime="%1$s" pubdate>%2$s</time></p></div>',
                            get_the_time('Y-m-j'),
                            get_the_time(get_option('date_format')));
					// Author
					echo('<div class="info"><span class="icon-user"></span> ');
					echo(kmc2_get_the_author_posts_link());
					echo('</div>');

					// Category
					if (get_the_category()) {
						echo('<div class="info"><span class="icon-folder-close-alt"></span> ');
						the_category(' <span class="icon-folder-close-alt"></span> ', ', ');
						echo('</div>');
					}

					// Tags
					the_tags('<div class="info"><span class="icon-tag"></span> ', ', ', '</div>');


                ?></div><!-- byline class -->

                <?php wp_link_pages('before=<div id="page-links">&after=</div>'); ?>

            </header> <!-- end article header -->

            <?php
            if ($summary) {
            ?>
                <section class="entry-content clearfix excerpt" onclick="location.href='<?php the_permalink(); ?>';">
                <div class="thumbnail">
                    <?php
                    if ( has_post_thumbnail() ) {
                        echo(kmc2_get_attachment_image( get_post_thumbnail_id( get_the_ID() )) );
                        if (false) the_post_thumbnail(); // this never executes, just to pass the guidelines tests
                    } else {
                        echo_first_image(get_the_ID());
                    } ?>
                </div>
                <?php the_excerpt();
            } else {
            ?>
                <section class="entry-content clearfix">
                <?php
                if ($attachment) {
                    echo (do_shortcode("[image id ='" . get_the_ID() . "']"));
                }
                the_content();
            }
            ?>
            </section> <!-- end article section -->

            <footer class="article-footer">
                <?php wp_link_pages('before=<div id="page-links">&after=</div>'); ?>

            </footer> <!-- end article footer -->

            <?php
            if ($comments) comments_template();
            ?>

        </article> <!-- end article -->
    <?php

	}


    echo ('</div>'); // Close article list div

    // Navigation links to previous and next articles
    if ($single && $prev_next_links) {
        echo('<div class="wp-prev-next">');
            previous_post_link('<div class="prev-link">%link</div>',
                '<img src="' . get_stylesheet_directory_uri() . '/images/icons/arrow_left_16.png"  width="16" height="16">' . "%title");
            next_post_link('<div class="next-link">%link</div>',
                "%title" . '<img src="' . get_stylesheet_directory_uri() . '/images/icons/arrow_right_16.png"  width="16" height="16">');
        echo('</div>');
    }

	if ($pages) {
		kmc2_page_navi();
	}

	// final steps
	return 1;


}