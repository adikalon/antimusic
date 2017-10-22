<?php get_header(); ?>
<?php if (have_posts()): ?>
	<section>
		<h2 class="ui segment horizontal divider header main-caption">Новые альбомы</h2>
		<div class="ui special cards cards-block">
			<?php while (have_posts()) : the_post(); ?>
				<article class="card one-card">
					<div class="blurring dimmable image">
						<div class="ui dimmer">
							<div class="content">
								<div class="center">
									<span class="ui inverted button">Скачать</span>
								</div>
							</div>
						</div>
						<?php if (has_post_thumbnail()): ?>
							<?php the_post_thumbnail('', array('class' => 'ui fluid image', 'alt' => trim(strip_tags( $wp_postmeta->_wp_attachment_image_alt )))); ?>
						<?php elseif (get_post_meta($post->ID, 'img_url', 1)): ?>
							<img class="ui fluid image" src="<?php echo get_post_meta($post->ID, 'img_url', 1); ?>" alt="<?php echo get_post_meta($post->ID, 'img_alt', 1); ?>">
						<?php else: ?>
							<img class="ui fluid image" src="<?php echo get_template_directory_uri() . '/img/noimg.jpg' ?>" alt="<?php the_title(); ?> - <?php echo get_post_meta($post->ID, 'album', true); ?><?php echo ' - '.get_post_meta($post->ID, 'year', 1); ?>">
						<?php endif; ?>
					</div>
					<header class="content">
						<h3 class="card-link-h">
							<a class="card-link" href="<?php the_permalink(); ?>">
								<?php the_title(); ?>
								<span class="card-album"><?php echo get_post_meta($post->ID, 'album', true); ?><?php echo ' - '.get_post_meta($post->ID, 'year', 1); ?></span>
							</a>
						</h3>
						<div class="meta display-none">
							<i class="tags icon"></i><?php the_category(',&nbsp;&nbsp;', 'single'); ?>
						</div>
						<div class="description display-none"><?php the_excerpt(); ?></div>
					</header>
					<footer class="extra content display-none">
						<time class="right floated" datetime="<?php the_modified_date('Y-m-d') ?>"><i class="wait icon"></i><?php the_modified_date('d.m.Y') ?></time>
						<a href="<?php the_permalink() ?>#comments"><span class="card-footer"><?php comments_number('<i class="talk outline icon"></i>нет комментариев', '<i class="talk icon"></i>1 комменатрий', '<i class="talk icon"></i>% комментариев'); ?></span></a>
					</footer>
				</article>
			<?php endwhile; ?>
		</div>
	</section>
	<?php echo get_pagination(); ?>
<?php else: ?>
<?php endif; ?>
<?php get_footer(); ?>