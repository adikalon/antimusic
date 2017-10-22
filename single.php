<?php $n = 1; ?>
<?php get_header(); ?>
<?php get_sidebar(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<?php setPostViews(get_the_ID()); ?>
	<section>
		<div class="ui raised segments">
			<header class="ui segment">
				<div class="ui breadcrumb">
					<span class="section goto-main" id="goto_main">Главная</span>
					<div class="divider"> / </div>
					<?php the_category(',&nbsp;&nbsp;', 'single'); ?>
					<div class="divider"> / </div>
					<a class="section" href="<?php echo get_permalink('/'); ?>/bands/<?php echo get_post_meta($post->ID, 'band_id', true); ?>"><?php the_title(); ?></a>
					<div class="divider"> / </div>
					<div class="active section"><?php echo get_post_meta($post->ID, 'album', true); ?><?php echo ' - '.get_post_meta($post->ID, 'year', 1); ?></div>
				</div>
			</header>
			<div class="ui segment">
				<div class="single-container">
					<div class="single-player">
						<h2 class="ui horizontal divider header single-header">
							<?php the_title(); ?>: "<?php echo get_post_meta($post->ID, 'album', true); ?><?php echo ' - '.get_post_meta($post->ID, 'year', true); ?>"
						</h2>
						<?php if (get_the_content()): ?>
							<article class="ui message">
								<h3 class="header single-header-info">Описание:</h3>
								<?php the_content(); ?>
							</article>
						<?php endif; ?>
						<?php if (get_post_meta($post->ID, 'jsong', true)): ?>
							<?php $jsong = json_decode(get_post_meta($post->ID, 'jsong', true)); ?>
							<span class="single-header-list">Треклист:</span>
							<ul class="single-songs-list">
							<?php foreach ($jsong as $song): ?>
								<li class="single-one-song">
									<span class="single-song-name"><?php echo get_number($n++).' - '.$song->name; ?></span>
									<span class="single-song-time"><?php echo $song->time; ?></span>
								</li>
							<?php endforeach; ?>
							</ul>
						<?php endif; ?>
						<time datetime="<?php the_modified_date('Y-m-d') ?>"></time>
						<?php if (get_post_meta($post->ID, 'download', true)): ?>
						<a class="ui primary submit labeled icon button single-download-button" target="blank" href="<?php echo get_permalink('/'); ?>/siteout?url=<?php echo str_replace('.', '..', get_post_meta($post->ID, 'download', true)); ?>"><i class="download icon"></i><strong>Скачать</strong> / <strong>Download</strong></a>
						<?php endif; ?>
						<div class="player-to-data" id="player_to_data"><i class="angle left icon"></i></div>	
					</div>
					<div class="single-description">
						<div class="data-to-player" id="data_to_player"><i class="angle right icon"></i></div>
						<?php if (has_post_thumbnail()): ?>
							<?php the_post_thumbnail('', array('class' => 'ui fluid rounded image', 'alt' => trim(strip_tags( $wp_postmeta->_wp_attachment_image_alt )))); ?>
						<?php elseif (get_post_meta($post->ID, 'img_url', 1)): ?>
							<img class="ui fluid rounded image" src="<?php echo get_post_meta($post->ID, 'img_url', 1); ?>" alt="<?php echo get_post_meta($post->ID, 'img_alt', 1); ?>">
						<?php else: ?>
							<img class="ui fluid rounded image" src="<?php echo get_template_directory_uri() . '/img/noimg.jpg' ?>" alt="<?php the_title(); ?> - <?php echo get_post_meta($post->ID, 'album', true); ?><?php echo ' - '.get_post_meta($post->ID, 'year', 1); ?>">	
						<?php endif; ?>
						<ul class="ui message">
							<li class="header single-header-info">Исполнитель: <span class="single-data-info"><?php the_title(); ?></span></li>
							<li class="header single-header-info">Альбом: <span class="single-data-info"><?php echo get_post_meta($post->ID, 'album', true); ?></span></li>
							<li class="header single-header-info">Год: <span class="single-data-info"><?php echo get_post_meta($post->ID, 'year', true); ?></span></li>
							<li class="header single-header-info">Стиль: <span class="single-data-info"><?php echo get_cat_string($post->ID, '&nbsp;/&nbsp;'); ?></span></li>
							<li class="header single-header-info">Страна: <span class="single-data-info"><?php echo get_post_meta($post->ID, 'country', true); ?></span></li>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<?php if (has_tag()): ?>
			<aside class="ui raised segments">
				<h4 class="ui segment header">
					Метки альбома
				</h4>
				<div class="ui segment tags-block">
					<?php the_tags('<strong class="ui blue tag label single-tag">', '</strong><strong class="ui blue tag label single-tag">', '</strong>'); ?>
				</div>
			</aside>
		<?php endif; ?>

		<?php endwhile; ?>
		<?php include 'templates/another-albums.php'; ?>
		<?php comments_template(); ?>
	</section>
<?php else: ?>
<?php endif; ?>
<?php get_footer(); ?>