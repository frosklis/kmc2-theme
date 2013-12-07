<?php
/*
YARPP Template: KMC2 yarpp template
Description: This template gives you a random other post in case there are no related posts
Author: Claudio Noguera
*/ ?>
<h3><?php _e("Related posts", 'kmc2theme'); ?></h3>
<?php if (have_posts()):?>
<ol>
	<?php while (have_posts()) : the_post(); ?>
	<li><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a><!-- (<?php the_score(); ?>)--></li>
	<?php endwhile; ?>
</ol>

<?php else:
query_posts("orderby=rand&order=asc&posts_per_page=3");
?>
<p><?php _e("No related posts were found, so here's a consolation prize: ", 'kmc2theme'); ?></p>

<ol>
	<?php while (have_posts()) : the_post(); ?>
	<li><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a><!-- (<?php the_score(); ?>)--></li>
	<?php endwhile; ?>
</ol>

<?php endif; ?>
