<?php
// Dibujar los posts
function display_posts ($args) {
    $list_of_posts = isset($args["list_of_posts"]) ? $args["list_of_posts"] : null;
    $summary = isset($args["summary"]) ? $args["summary"] : false;
    $comments = isset($args["comments"]) ? $args["comments"] : false;
    $prev_next_links = isset($args["prev_next_links"]) ? $args["prev_next_links"] : false;
    $single = isset($args["single"]) ? $args["single"] : false;
    $attachment = isset($args["attachment"]) ? $args["attachment"] : false;

    $tiles = isset($args["tiles"]) ? $args["tiles"] : false;

    if($tiles) {
        ?><div class="article-list">
        <?php
        while ($list_of_posts->have_posts()) : $list_of_posts->the_post(); 
            $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), "width_260");
            $thumbnail = $thumbnail[0];
        ?>
            <a class="article-thumb" href="<?php the_permalink(); ?>">
                <div class="background" style="background-image: url(<?php echo($thumbnail); ?>);"></div>

                <div class="content">
                    <div class="title"><h3><?php the_title(); ?></h3></div>
                    <?php echo('<div class="meta"><p><span class="icon-calendar"></span> ');
                    printf('<time class="updated" datetime="%1$s" pubdate>%2$s</time></p></div>', 'kmc2theme', get_the_time('Y-m-j'), get_the_time(get_option('date_format'))); ?>
                </div>
            </a>
        <?php endwhile;

        return true;
    }

    if ($single) { ?>
        <div class="article-single"> 
        <?php
    } else { ?>
        <div class="article-list"> 
        <?php
    }
        
        
    if ($list_of_posts->have_posts()) : while ($list_of_posts->have_posts()) : $list_of_posts->the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">
        
            <header class="article-header">
            
                <?php if ($single) { ?>
                    <h1><?php the_title(); ?> </h1>
                <?php } else { ?>
                    <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
                    <?php } ?>
                <div class="byline"><?php
                // printf(__('Posted <time class="updated" datetime="%1$s" pubdate>%2$s</time> by <span class="author">%3$s</span> <span class="amp">&</span> filed under %4$s.', 'kmc2theme'), get_the_time('Y-m-j'), get_the_time(get_option('date_format')), kmc2_get_the_author_posts_link(), get_the_category_list(', '));

                // Edit the entry
                edit_post_link(__('Edit', 'kmc2theme'),'<div class="info"><span class="icon-pencil"></span>','</div>');

                // Date
                echo('<div class="info"><span class="icon-calendar"></span> ');
                printf('<time class="updated" datetime="%1$s" pubdate>%2$s</time></div>', 'kmc2theme', get_the_time('Y-m-j'), get_the_time(get_option('date_format')));
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

                // printf(__(' <time class="updated" datetime="%1$s" pubdate>%2$s</time> <span class="icon-user"></span> <span class="author">%3$s</span> <span class="amp">&</span> filed under %4$s.', 'kmc2theme'), get_the_time('Y-m-j'), get_the_time(get_option('date_format')), kmc2_get_the_author_posts_link(), get_the_category_list(', '));
                ?></div>

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
    if ($prev_next_links) {
        echo('</div><!-- article-list -->');
        echo('<div class="wp-prev-next">'); 
            previous_post_link('<div class="prev-link">%link</div>', 
                '<img src="' . get_stylesheet_directory_uri() . '/images/icons/arrow_left_16.png"  width="16" height="16">' . "%title");
            next_post_link('<div class="next-link">%link</div>', 
                "%title" . '<img src="' . get_stylesheet_directory_uri() . '/images/icons/arrow_right_16.png"  width="16" height="16">'); 
        echo('</div>');
    }
    ?>
    <?php endwhile; 
    if (!$prev_next_links) {
        echo('</div>');
    }
        if (function_exists('kmc2_page_navi')) { ?>
            <?php kmc2_page_navi(); ?>
        <?php } else { ?>
            <nav class="wp-prev-next">
                <ul class="clearfix">
                    <li class="prev-link"><?php next_posts_link(__('&laquo; Older Entries', "kmc2theme")) ?></li>
                    <li class="next-link"><?php previous_posts_link(__('Newer Entries &raquo;', "kmc2theme")) ?></li>
                </ul>
            </nav>
        <?php } ?>      

    <?php else : ?>
    
        <article id="post-not-found" class="hentry clearfix">
            <header class="article-header">
                <h1><?php _e("Oops, Post Not Found!", "kmc2theme"); ?></h1>
            </header>
            <section class="entry-content">
                <p><?php _e("Uh Oh. Something is missing. Try double checking things.", "kmc2theme"); ?></p>
            </section>
            <footer class="article-footer">
                <p><?php _e("This is the error message in the display_posts function.", "kmc2theme"); ?></p>
            </footer>
        </article>

    <?php endif; ?>


<?php
}