<?php
if (urldecode($wp_query->query_vars['country'])) {
	if(get_posts(array('numberposts' => 1, 'meta_value' => urldecode($wp_query->query_vars['country'])))[0] == NULL) {
		$wp_query->set_404();
		status_header( 404 );
		nocache_headers();
		get_template_part('404');
		exit();
	}
}
/*
  Template Name: Страны
 */
?>
<?php get_header(); ?>
<?php get_sidebar(); ?>
<section>
<?php
if (urldecode($wp_query->query_vars['country'])) {
	include 'country-page.php';
}
else {
	include 'countries-page.php';
}
?>
</section>
<?php get_footer(); ?>