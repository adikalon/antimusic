<?php get_header(); ?>
<section>
	<div class="ui segment">
		<div class="ui breadcrumb">
			<span class="section goto-main" id="goto_main">Главная</span>
			<div class="divider"> / </div>
			<a class="section" href="<?php echo get_permalink('/'); ?>/tag/">Теги</a>
			<div class="divider"> / </div>
			<h2 class="active section category-header"><?php single_tag_title(); ?></h2>
		</div>
		<div class="ui search search-block">
			<div class="ui icon input quick-search-pole" id="quick_search_pole">
				<input class="prompt search-input" type="text" placeholder="Быстрый поиск..." id="second_search_input">
				<i class="search link icon" id="second_search"></i>
			</div>
			<button class="circular ui icon basic button quick-search-button" id="quick_search_button"><i class="search icon link"></i></button>
			<div class="results"></div>
		</div>
	</div>
	<?php if (have_posts()) : ?>
		<div class="ui special cards cards-block">
			<?php while (have_posts()) : the_post(); ?>
				<?php echo '<script>content.push({title:"'.get_the_title().' - '.get_post_meta($post->ID, 'album', true).' - '.get_post_meta($post->ID, 'year', true).'", href:"'.get_permalink().'"});</script>'; ?>
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
		<?php echo get_pagination(); ?>
	<?php else: ?>
	<?php endif; ?>
</section>
<?php get_footer(); ?>