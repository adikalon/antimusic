<h3 class="ui segment horizontal divider header sidebar-caption">Навигация</h3>
<nav class="ui large vertical menu">
	<h4 class="ui horizontal divider top attached header populars-header">По жанрам</h4>
	<?php foreach (get_categories() as $cat): ?>
		<?php if (is_category( $cat->cat_name )): ?>
			<span class="active item link to-sidebar-cat">
				<span class="ui small blue label"><?php echo $cat->count ?></span>
				<?php echo $cat->cat_name ?>
			</span>
		<?php else: ?>
			<a class="item to-sidebar-cat" href="<?php echo get_category_link( $cat->cat_ID ); ?>">
				<span class="ui small label"><?php echo $cat->count ?></span>
				<?php echo $cat->cat_name ?>
			</a>
		<?php endif; ?>
	<?php endforeach; ?>
</nav>
<aside class="ui segment populars-block">
	<h4 class="ui horizontal divider top attached header populars-header">Топ альбомов</h4>
	<span class="top-divider"></span>
	<?php $populargb = new WP_Query('showposts=10&meta_key=post_views_count&orderby=meta_value_num'); ?>
	<span class="ui middle aligned divided selection list">
		<?php while ($populargb->have_posts()): ?>
		<?php $populargb->the_post(); ?>
		<?php if (get_permalink() == get_current_url()): ?>
			<span class="active item">
				<?php if (has_post_thumbnail()): ?>
					<?php the_post_thumbnail(array(28, 28), array('class' => "ui avatar image")); ?>
				<?php elseif (get_post_meta($post->ID, 'img_url', 1)): ?>
					<img width="28" height="28" class="ui avatar image" src="<?php echo get_post_meta($post->ID, 'img_url', 1); ?>" alt="<?php echo get_post_meta($post->ID, 'img_alt', 1); ?>">
				<?php else: ?>
					<img width="28" height="28" class="ui avatar image" src="<?php echo get_template_directory_uri() . '/img/noimg.jpg' ?>" alt="">
				<?php endif; ?>
				<span class="content sidebar-top-content">
					<span class="header"><?php the_title(); ?></span>
					<?php echo get_post_meta($post->ID, 'album', 1); ?><?php echo ' - '.get_post_meta($post->ID, 'year', 1); ?>
				</span>
			</span>
		<?php else: ?>
			<a class="item" href="<?php the_permalink(); ?>">
				<?php if (has_post_thumbnail()): ?>
					<?php the_post_thumbnail(array(28, 28), array('class' => "ui avatar image")); ?>
				<?php elseif (get_post_meta($post->ID, 'img_url', 1)): ?>
					<img width="28" height="28" class="ui avatar image" src="<?php echo get_post_meta($post->ID, 'img_url', 1); ?>" alt="<?php echo get_post_meta($post->ID, 'img_alt', 1); ?>">
				<?php else: ?>
					<img width="28" height="28" class="ui avatar image" src="<?php echo get_template_directory_uri() . '/img/noimg.jpg' ?>" alt="">
				<?php endif; ?>
				<span class="content sidebar-top-content">
					<span class="header"><?php the_title(); ?></span>
					<?php echo get_post_meta($post->ID, 'album', 1); ?><?php echo ' - '.get_post_meta($post->ID, 'year', 1); ?>
				</span>
			</a>
		<?php endif; ?>
		<?php endwhile; ?>
	</span>
</aside>
<?php if (!dynamic_sidebar( 'sidebar' )): ?>
<?php endif; ?>