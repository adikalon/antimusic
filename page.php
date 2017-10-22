<?php get_header(); ?>
<br>
<?php get_sidebar(); ?>
<br>
<hr>
<?php the_title(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<h3><?php the_title(); ?></h3>
<h4><?php the_time('d.m.Y') ?></h4>
<?php the_content(); ?>

<br>
<?php endwhile; ?>

<?php else: ?>

<?php endif; ?>
<?php get_footer(); ?>