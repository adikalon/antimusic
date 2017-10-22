<?php
/*
  Template Name: Теги
 */
?>
<?php get_header(); ?>
<section>
	<div class="ui raised segments">
		<div class="ui segment">
			<div class="ui breadcrumb">
				<span class="section goto-main" id="goto_main">Главная</span>
				<div class="divider"> / </div>
				<h2 class="active section category-header"><?php wp_title(''); ?></h2>
			</div>
		</div>
		<div class="ui search search-block">
			<div class="ui icon input quick-search-pole" id="quick_search_pole">
				<input class="prompt search-input" type="text" placeholder="Быстрый поиск..." id="second_search_input">
				<i class="search link icon" id="second_search"></i>
			</div>
			<button class="circular ui icon basic button quick-search-button" id="quick_search_button"><i class="search icon link"></i></button>
			<div class="results"></div>
		</div>
		<div class="ui segment">
			<div class="ui tag labels tags-block">
				<?php foreach (get_tags(array('order' => 'ASC')) as $tag): ?>
					<?php echo '<script>content.push({title:"'.$tag->name.'", href:"/tag/'.$tag->name.'"});</script>'; ?>
					<a class="ui blue label" href="<?php echo get_tag_link($tag->term_id); ?>"><?php echo $tag->name; ?><span class="detail"><?php echo $tag->count; ?></span></a>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
<?php get_footer(); ?>