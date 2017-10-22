<?php
$let_id = 0;
$let_anchor = 0;
$bands = [];
$letters = [];

$args = array(
	'numberposts' => -1,
	'orderby' => 'title',
	'order' => 'ASC'
);

foreach (get_posts($args) as $the_post) {
	if (!in_two_level_array($the_post->post_title, 'title', $bands)) {
		$bands[] = array(
			'title' => $the_post->post_title,
			'id' =>$the_post->ID
		);
		if (!in_array(mb_strtoupper(mb_substr($the_post->post_title,0,1,'UTF-8')), $letters)) {
			$letters[] = mb_strtoupper(mb_substr($the_post->post_title,0,1,'UTF-8'));
		}
	}
}
?>

<div class="ui raised segments">
	<div class="ui segment">
		<div class="ui breadcrumb">
			<span class="section goto-main" id="goto_main">Главная</span>
			<div class="divider"> / </div>
			<h2 class="active section category-header"><?php wp_title(''); ?></h2>
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
	<div class="ui segment">
		<?php foreach ($letters as $l): ?>
			<div class="ui button go_to goto-buttons" data-anchor="#letter_<?php echo $let_anchor; ?>"><?php echo $l; ?></div>
			<?php $let_anchor++; ?>
		<?php endforeach; ?>
		<?php foreach ($letters as $l): ?>
			<div id="letter_<?php echo $let_id; ?>" class="letter-label"></div>
				<div class="ui horizontal divider header circular-letter-divider">
					<div class="ui circular large labels">
						<div class="ui label">
							<?php echo $l; ?>
						</div>
					</div>
				</div>
			<?php foreach ($bands as $band): ?>
				<?php echo '<script>if (!in_content("'.$band['title'].'", content)) { content.push({title:"'.$band['title'].'", href:"/bands/'.get_post_meta($band['id'], 'band_id', true).'"}); }</script>'; ?>
				<?php if (mb_strtoupper(mb_substr($band['title'],0,1,'UTF-8')) == $l): ?>
					<a class="ui blue label blue-label" href="<?php echo get_permalink('/'); ?>/bands/<?php echo get_post_meta($band['id'], 'band_id', true); ?>"><?php echo $band['title']; ?></a>
				<?php endif; ?>
			<?php endforeach; ?>
			<?php $let_id++; ?>
		<?php endforeach; ?>
	</div>
</div>