<?php last_modified(); ?>
<!doctype html>
<html lang="ru">
	<head>
		<meta charset="UTF-8">
		<title><?php echo get_title(); ?></title>
		<?php the_deskey(); ?>
		<?php the_canonic(); ?>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
	</head>
	<body>
		<script>
			var content = [];
			function in_content(string, object) {
				for (var i = 0; i < content.length; i++) {
					if (content[i].title == string) {
						return true;
					}
				}
				return false;
			}
		</script>
		<div class="ui sidebar vertical menu" id="sidebar_menu"></div>
		<div class="pusher">
			<header class="ui basic segment main-header">
			<ul class="ui inverted menu header-menu">
				<li class="item link header-menu-link">
					<button class="item media-menu-button display-flex" id="media_menu_button"><i class="content icon"></i></button>
				</li>
				<?php foreach (get_menu_items() as $item): ?>
					<?php if ($item['current']): ?>
						<li class="active item link header-menu-link">
							<span class="item header-menu-href display-none to-sidebar-menu"><?php echo $item['title']; ?></span>
						</li>
					<?php else: ?>
						<li class="item header-menu-link">
							<a class="item header-menu-href display-none to-sidebar-menu" href="<?php echo $item['url']; ?>"><?php echo $item['title']; ?></a>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>
				<li class="item right menu header-menu-link header-ul-search">
					<?php get_search_form(); ?>
				</li>
			</ul>
			<?php if (home_url().'/' != get_current_url()): ?>
				<a href="<?php echo home_url(); ?>" id="main_url" class="main-url"></a>
			<?php endif; ?>
			<div class="main-header-description">
				<p class="main-header-data"><?php bloginfo('name'); ?></p>
				<h1 class="main-header-caption"><?php bloginfo('description'); ?></h1>
			</div>
		</header>
		<div class="main-container">
			<section class="main-sidebar display-none">
				<?php get_sidebar(); ?>
			</section>
			<main class="main-content">