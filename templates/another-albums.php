<?php
$how = 6;

$args_album = array(
	'numberposts' => -1,
	'orderby' => 'rand'
);

$args_cat = array(
	'numberposts' => $how,
	'orderby' => 'rand',
	'category' => get_the_category()[0]->cat_ID
);

foreach (get_posts($args_album) as $post_album) {
	if (get_post_meta($post_album->ID, 'band_id', true) == get_post_custom()['band_id'][0] && $post_album->ID != get_queried_object_id()) {
		$sample[] = [
			'title' => $post_album->post_title,
			'id' => $post_album->ID,
			'url' => get_permalink($post_album->ID)
		];
	}
	if (count($sample) == $how) break;
}

$description = 'Другие альбомы группы';

if (!$sample) {
	$description = 'Похожие записи';
	foreach (get_posts($args_cat) as $post_cat) {
		if ($post_cat->ID != get_queried_object_id()) {
			$sample[] = [
				'title' => $post_cat->post_title,
				'id' => $post_cat->ID,
				'url' => get_permalink($post_cat->ID)
			];
		}
	}
}

$quantity = count($sample);
?>

<?php if ($quantity): ?>
	<aside class="ui raised segments">
		<h4 class="ui segment header">
			<?php echo $description; ?>
		</h4>
		<ul class="ui segment another-block">
			<?php foreach($sample as $cur): ?>
				<li class="another-one-fix">
					<a class="another-one" href="<?php echo $cur['url']; ?>">
						<?php if (get_the_post_thumbnail($cur['id'])): ?>
							<?php echo get_the_post_thumbnail($cur['id'], array(28, 28), array('class' => "ui avatar image")); ?>
						<?php elseif (get_post_meta($cur['id'], 'img_url', true)): ?>
								<img width="28" height="28" class="ui avatar image" src="<?php echo get_post_meta($cur['id'], 'img_url', true); ?>" alt="<?php echo get_post_meta($cur['id'], 'img_alt', true); ?>">
							<?php else: ?>
								<img width="28" height="28" class="ui avatar image" src="<?php echo get_template_directory_uri().'/img/noimg.jpg' ?>" alt="<?php echo get_post_meta($cur['id'], 'img_alt', true); ?>">
						<?php endif; ?>
						<span class="another-band"><?php echo $cur['title']; ?></span>
						<span class="another-album"><?php echo get_post_meta($cur['id'], 'album', true); ?> - <?php echo get_post_meta($cur['id'], 'year', true); ?></span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</aside>
<?php endif; ?>