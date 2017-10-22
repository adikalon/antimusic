<?php
		
// Last Modified
function last_modified() {
	if (!is_search() && !is_page('siteout') && !is_page('feedback')) {
		global $wp_query;
		global $post;
		if (is_page('bands') && urldecode($wp_query->query_vars['band'])) {
			$LastModified_unix = unix_time(get_posts(array('numberposts' => 1, 'orderby' => 'modified', 'meta_value' => urldecode($wp_query->query_vars['band'])))[0]->post_modified);
		} else if (is_page('countries') && urldecode($wp_query->query_vars['country'])) {
			$LastModified_unix = unix_time(get_posts(array('numberposts' => 1, 'orderby' => 'modified', 'meta_value' => urldecode($wp_query->query_vars['country'])))[0]->post_modified);
		} else if (is_page('tag') || is_paged() || (is_page('bands') && !urldecode($wp_query->query_vars['band'])) || (is_page('countries') && !urldecode($wp_query->query_vars['country']))) {
			$LastModified_unix = unix_time(get_posts(array('numberposts' => 1, 'orderby' => 'date', 'order' => 'DESC'))[0]->post_modified);
		} else {
			$LastModified_unix = unix_time($post->post_modified);
		}
		$LastModified = gmdate("D, d M Y H:i:s \G\M\T", $LastModified_unix);
		$IfModifiedSince = false;
		if (isset($_ENV['HTTP_IF_MODIFIED_SINCE']))
			$IfModifiedSince = strtotime(substr($_ENV['HTTP_IF_MODIFIED_SINCE'], 5));
		if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']))
			$IfModifiedSince = strtotime(substr($_SERVER['HTTP_IF_MODIFIED_SINCE'], 5));
		if ($IfModifiedSince && $IfModifiedSince >= $LastModified_unix) {
			header($_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified');
			exit;
		}
		header('Last-Modified: '. $LastModified);
		wp_reset_postdata();
	}
}

// Функция возвращает время в unix формате
function unix_time($time_send){
	$year_lm=substr($time_send, 0, 4);
	$mount_lm=substr($time_send, 5, 2);
	$day_lm=substr($time_send, 8, 2);
	$time_lm=substr($time_send, 10, 9);
	$time_lm_unix_in = $year_lm.'-'.$mount_lm.'-'.$day_lm.' '.$time_lm;
	$select_lm = strtotime($time_lm_unix_in);
	return $select_lm;
}

// Убираем загрузку ненужных стандартных ресурсов
remove_action('wp_head', 'wp_resource_hints', 2);
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'wp_oembed_add_host_js');
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
remove_action('wp_head', 'wp_shortlink_wp_head');


// Отключаем REST API
add_filter('rest_enabled', '__return_false');
remove_action('xmlrpc_rsd_apis', 'rest_output_rsd');
remove_action('wp_head', 'rest_output_link_wp_head', 10, 0);
remove_action('template_redirect', 'rest_output_link_header', 11, 0);
remove_action('auth_cookie_malformed', 'rest_cookie_collect_status');
remove_action('auth_cookie_expired', 'rest_cookie_collect_status');
remove_action('auth_cookie_bad_username', 'rest_cookie_collect_status');
remove_action('auth_cookie_bad_hash', 'rest_cookie_collect_status');
remove_action('auth_cookie_valid', 'rest_cookie_collect_status');
remove_filter('rest_authentication_errors', 'rest_cookie_check_errors', 100);
remove_action('init', 'rest_api_init');
remove_action('rest_api_init', 'rest_api_default_filters', 10, 1);
remove_action('parse_request', 'rest_api_loaded');
remove_action('rest_api_init', 'wp_oembed_register_route');
remove_filter('rest_pre_serve_request', '_oembed_rest_pre_serve_request', 10, 4);
remove_action('wp_head', 'wp_oembed_add_discovery_links');
//remove_action('wp_head', 'wp_oembed_add_host_js');
remove_action('wp_head', 'rel_canonical');
remove_action('template_redirect', 'wp_shortlink_header', 11);
remove_filter('term_description', 'wpautop');

// Добавить фавиконку
function my_favicon() {
	echo '<link rel="shortcut icon" href="'.get_template_directory_uri().'/favicon.ico" />';
}
add_action('wp_head', 'my_favicon');

// Удаляем все создаваемыекопии изображений (оставляем только миниатюру и оригинал)
add_filter( 'intermediate_image_sizes', 'delete_intermediate_image_sizes' );
function delete_intermediate_image_sizes($sizes) {
	return array_diff($sizes, array(
		'medium',
		'large',
		'full',
	));
}

// Регистрируем Меню
register_nav_menu( 'menu', 'Меню' );

// Создаем нужные страницы при активации темы
$add_pages = array(
	array(
		'title' => 'Группы',
		'link' => 'bands',
		'template' => 'templates/bands.php'
	),
	array(
		'title' => 'Страны',
		'link' => 'countries',
		'template' => 'templates/countries.php'
	),
	array(
		'title' => 'Теги',
		'link' => 'tag',
		'template' => 'templates/tags.php'
	),
	array(
		'title' => 'Связь',
		'link' => 'feedback',
		'template' => 'templates/feedback.php'
	),
	array(
		'title' => 'Переход по внешней ссылке',
		'link' => 'siteout',
		'template' => 'templates/siteout.php'
	)
);
if (isset($_GET['activated']) && is_admin()){
	foreach ($add_pages as $the_page) {
		$new_page_title = $the_page['title'];
		$new_page_content = '';
		$new_page_template = $the_page['template'];
		$page_check = get_page_by_title($new_page_title);
		$new_page = array(
			'post_type' => 'page',
			'post_title' => $new_page_title,
			'post_content' => $new_page_content,
			'post_status' => 'publish',
			'post_author' => 1,
			'post_name' => $the_page['link']
		);
		if(!isset($page_check->ID)){
			$new_page_id = wp_insert_post($new_page);
			if(!empty($new_page_template)){
				update_post_meta($new_page_id, '_wp_page_template', $new_page_template);
			}
		}
	}
}

// Функция установки title
function get_title() {
	global $wp_query;
	global $post;
	if (is_page()) {
		if (urldecode($wp_query->query_vars['country']) && is_page('countries')) {
			return 'Группы из '.get_post_meta(get_posts(array('numberposts' => 1, 'meta_value' => urldecode($wp_query->query_vars['country'])))[0]->ID, 'country', true);
		}
		else if (urldecode($wp_query->query_vars['band']) && is_page('bands')) {
			return 'Дискография '.get_posts(array('numberposts' => 1, 'meta_value' => urldecode($wp_query->query_vars['band'])))[0]->post_title;
		}
		else if (is_404()) {
			return '404 ˗ Страница не найдена';
		}
		else {
			if (is_page('bands')) {
				return 'Дискографии музыкальных групп';
			}
			else if (is_page('countries')) {
				return 'Группы из разных стран';
			}
			else if (is_page('tag')) {
				return 'Навигация по тегам';
			}
			else if (is_page('feedback')) {
				return 'Обратная связь';
			}
			else {
				return get_the_title();
			}
		}
	}
	else if (is_tag()) {
		return 'Записи с тегом: '.single_term_title('', 0);
	}
	else if (is_search()) {
		return 'Поиск: '.get_search_query();
	}
	else if (is_single()) {
		if (get_post_meta($post->ID, 'album', true)) {
			if (get_post_meta($post->ID, 'year', true)) {
				return get_post_meta($post->ID, 'album', true).' ˗ '.get_post_meta($post->ID, 'year', 1).' ˗ '.get_the_title();
			}
			else {
				return get_post_meta($post->ID, 'album', true).' ˗ '.get_the_title();
			}
		}
		else {
			return get_the_title();
		}
	}
	else if (is_category()) {
		return 'Музыка '.single_term_title('', 0);
	}
	else {
		return get_bloginfo('name');
	}
}

// Огромная функция генерирует description и keyword для страниц. Необходимо передать k или d (keyword / description)
function get_deskey($k) {
	global $wp_query;
	global $post;
	if (is_page()) {
		if (urldecode($wp_query->query_vars['country']) && is_page('countries')) {
			if ($k == 'd') {
				return 'Список музыкальных групп из страны '.get_post_meta(get_posts(array('numberposts' => 1, 'meta_value' => urldecode($wp_query->query_vars['country'])))[0]->ID, 'country', true).' Скачать mp3';
			} else if ($k == 'k') {
				return 'Музыка, Группы, '.get_post_meta(get_posts(array('numberposts' => 1, 'meta_value' => urldecode($wp_query->query_vars['country'])))[0]->ID, 'country', true);
			}
		}
		else if (urldecode($wp_query->query_vars['band']) && is_page('bands')) {
			if ($k == 'd') {
				return 'Дискография, бутлеги, редкие и концертные записи музыкальной группы '.get_posts(array('numberposts' => 1, 'meta_value' => urldecode($wp_query->query_vars['band'])))[0]->post_title.'. Скачать mp3';
			} else if ($k == 'k') {
				return 'Музыка, Дискография, Бутлеги, Концерты, '.get_posts(array('numberposts' => 1, 'meta_value' => urldecode($wp_query->query_vars['band'])))[0]->post_title;
			}
		}
		else if (is_404()) {
			if ($k == 'd') {
				return '404 - Страница не найдена';
			} else if ($k == 'k') {
				return '404, error';
			}
		}
		else {
			if ($k == 'd') {
				return get_the_title().' по списку';
			} else if ($k == 'k') {
				return get_the_title();
			}
		}
	}
	else if (is_tag()) {
		if ($k == 'd') {
			return 'Музыкальные группы, альбомы по тегам';
		} else if ($k == 'k') {
			return 'Метки, '.single_term_title('', 0);
		}
	}
	else if (is_search()) {
		if ($k == 'd') {
			return 'Поиск: '.get_search_query();
		} else if ($k == 'k') {
			return get_search_query();
		}
	}
	else if (is_single()) {
		if ($k == 'd' && get_post_meta($post->ID, 'description', true)) {
			return get_post_meta($post->ID, 'description', true);
		} else if ($k == 'k' && get_post_meta($post->ID, 'keywords', true)) {
			return get_post_meta($post->ID, 'keywords', true);
		} else {
			if ($k == 'd') {
				if (get_post_meta($post->ID, 'album', true)) {
					if (get_post_meta($post->ID, 'year', true)) {
						return get_post_meta($post->ID, 'album', true).' ˗ '.get_post_meta($post->ID, 'year', true).' ˗ '.get_the_title();
					}
					else {
						return get_post_meta($post->ID, 'album', true).' ˗ '.get_the_title();
					}
				}
				else {
					return get_the_title();
				}
			} else if ($k == 'k') {
				if (get_post_meta($post->ID, 'album', true)) {
					return get_post_meta($post->ID, 'album', true).', '.get_the_title();
				}
				else {
					return get_the_title();
				}
			}
		}
	}
	else if (is_category()) {
		if ($k == 'd') {
			if (category_description()) {
			return category_description();
			} else {
				return 'Музыкальные группы по жанру: '.single_term_title('', 0).', скачать';
			}
		} else if ($k == 'k') {
			return single_term_title('', 0).', Жанр, Стиль, Музыка';
		}
	}
	else {
		if ($k == 'd') {
			return get_bloginfo('name').' ˗ '.get_bloginfo('description').'. Скачать mp3';
		} else if ($k == 'k') {
			return 'Музыка, Скачать, Punk, Oi, Hardcore, Rock, Rap, Gabber';
		}
	}
	wp_reset_postdata();
}

// Функция установки каноничных ссылок
function the_canonic() {
	global $wp_query;
	global $post;
	if (is_page()) {
		if (urldecode($wp_query->query_vars['country']) && is_page('countries')) {
			echo '<link rel="canonical" href="'.get_home_url().'/countries/'.get_post_meta(get_posts(array('numberposts' => 1, 'meta_value' => urldecode($wp_query->query_vars['country'])))[0]->ID, 'country_id', true).'" />';
		}
		else if (urldecode($wp_query->query_vars['band']) && is_page('bands')) {
			echo '<link rel="canonical" href="'.get_home_url().'/bands/'.get_post_meta(get_posts(array('numberposts' => 1, 'meta_value' => urldecode($wp_query->query_vars['band'])))[0]->ID, 'band_id', true).'" />';
		}
		else {
			echo rel_canonical();
		}
	}
	else if (is_single()) {
		echo rel_canonical();
	}
	else if (is_category()) {
		echo '<link rel="canonical" href="'.get_category_link(get_queried_object()->term_id).'" />';
	} else if (home_url().'/' == get_current_url()) {
		echo '<link rel="canonical" href="'.home_url().'" />';
	}
	else {
		echo rel_canonical();
	}
}

// Подключаем JS и CSS админки
function jsCssAdmin() {
    $themeurl = get_bloginfo('stylesheet_directory');
    echo '<link rel="stylesheet" href="'.$themeurl.'/css/admin.css" type="text/css" media="all" />';
	echo '<script src="'.get_template_directory_uri().'/js/admin.js"></script>';
}
add_action('admin_head', 'jsCssAdmin');

// Отключаем стандартный jquery
wp_deregister_script('jquery'); 

// Загружаемые стили и скрипты
function load_style_script() {
	wp_enqueue_style('style', get_template_directory_uri() . '/style.css');
	wp_enqueue_script( 'my-scrits', get_template_directory_uri() . '/script.js', '', false, true);
}
add_action( 'wp_enqueue_scripts', 'load_style_script' );

// Поддержка миниатюр
add_theme_support( 'post-thumbnails' );

// Функция возвращающая массив меню
function get_menu_items() {
	$munu_properties = [];
	foreach (get_nav_menu_locations() as $menu) {
		if ($menu_items = wp_get_nav_menu_items($menu)) {
			foreach ($menu_items as $item) {
				$munu_properties[] = array(
					'title' => $item->title,
					'url' => $item->url,
					'current' => $item->url == strtolower(get_home_url().$_SERVER['REQUEST_URI'])
				);
			}
		}
	}
	return $munu_properties;
}

// Функция возвращающая ссылку на текущую страницу
function get_current_url() {
	return strtolower(get_home_url().$_SERVER['REQUEST_URI']);
}

// Функция на проверку значения по ключу в двух уровневом массиве
function in_two_level_array($val, $key, $arr) {
	foreach ($arr as $a) {
		if ($a[$key] == $val) {
			return true;
		}
	}
	return false;
}

// Виджеты сайдбара
register_sidebar( array(
	'name' => 'Виджеты сайдбара',
	'id' => 'sidebar',
	'description' => 'Виджеты сайдбара',
	'before_widget' => '<div>',
	'after_widget' => '</div>',
	'before_title' => '<h2>',
	'after_title' => '</h2>'
) );

// Виджеты футера
register_sidebar( array(
	'name' => 'Виджеты футера',
	'id' => 'footer',
	'description' => 'Виджеты футера',
	'before_widget' => '<div>',
	'after_widget' => '</div>',
	'before_title' => '<h2>',
	'after_title' => '</h2>'
) );

// Удаляем стандартные виджеты
function unregister_default_wp_widgets() {
    unregister_widget('WP_Widget_Pages');
    unregister_widget('WP_Widget_Calendar');
    unregister_widget('WP_Widget_Archives');
    unregister_widget('WP_Widget_Links');
    unregister_widget('WP_Widget_Meta');
    unregister_widget('WP_Widget_Search');
    unregister_widget('WP_Widget_Text');
    unregister_widget('WP_Widget_Categories');
    unregister_widget('WP_Widget_Recent_Posts');
    unregister_widget('WP_Widget_Recent_Comments');
    unregister_widget('WP_Widget_RSS');
    unregister_widget('WP_Widget_Tag_Cloud');
    unregister_widget('WP_Nav_Menu_Widget');
}
add_action('widgets_init', 'unregister_default_wp_widgets', 1);

// Подключаем виджет сообщества ВК
include 'app/widget_vk.php';

// ЧПУ для поиска
/*
function true_rewrite_search_results_permalink() {
	global $wp_rewrite;
	// обязательно проверим, включены ли чпу, чтобы не закосячить весь поиск
	if ( !isset( $wp_rewrite ) || !is_object( $wp_rewrite ) || !$wp_rewrite->using_permalinks() )
		return;
	if ( is_search() && !is_admin() && strpos( $_SERVER['REQUEST_URI'], "/search/") === false && ! empty( $_GET['s'] ) ) {
		wp_redirect( site_url() . "/search/" . urlencode( get_query_var( 's' ) ) );
		exit;
	}	
}
add_action( 'template_redirect', 'true_rewrite_search_results_permalink' );
 
// ЧПУ поиска на кириллице
function true_urldecode_s($query) {
	if (is_search()) {
		$query->query_vars['s'] = urldecode( $query->query_vars['s'] );
	}
	return $query;
}
add_filter('parse_query', 'true_urldecode_s');
*/

// ЧПУ для исполнителей
add_action('init', 'rewrite_bands');
function rewrite_bands() {
	add_rewrite_tag('%band%','([^/]*)');
	add_rewrite_rule('^bands/([^/]*)/?$','index.php?page_id='.get_page_by_path('bands')->ID.'&band=$matches[1]','top');
}

// ЧПУ для стран
add_action('init', 'rewrite_countries');
function rewrite_countries() {
	add_rewrite_tag('%country%','([^/]*)');
	add_rewrite_rule('^countries/([^/]*)/?$','index.php?page_id='.get_page_by_path('countries')->ID.'&country=$matches[1]','top');
}

/*
// ЧПУ для перехода по внешним ссылкам
add_action('init', 'rewrite_siteout');
function rewrite_siteout() {
	add_rewrite_tag('%url%','([.]*)');
	add_rewrite_rule('^siteout/([.]*)$','index.php?page_id='.get_page_by_path('siteout')->ID.'&url=$matches[1]','top');
}
*/

// Подключаем поля дополнительного описания альбома
include 'app/fields.php';

// Подключаем поля мета кейвордс и дескрипшн
include 'app/meta.php';

// Три функции подключающие поиск по произвольным полям
function cf_search_join($join) {
	global $wpdb;
	if (is_search()) {    
		$join .=' LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
	}
	remove_filter('posts_join', 'cf_search_join');
	return $join;
}
add_filter('posts_join', 'cf_search_join');
function cf_search_where($where) {
	global $wpdb;
	if (is_search()) {
		$where = preg_replace(
			"/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
			"(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1 AND (".$wpdb->postmeta.".meta_key LIKE 'album' OR ".$wpdb->postmeta.".meta_key LIKE 'country' OR ".$wpdb->postmeta.".meta_key LIKE 'year'))", $where);
	}
	return $where;
}
add_filter('posts_where', 'cf_search_where');
function cf_search_distinct($where) {
	global $wpdb;
	if (is_search()) {
		return "DISTINCT";
	}
	return $where;
}
add_filter('posts_distinct', 'cf_search_distinct');

// Подсчет количества просмотров
function getPostViews($postID){
	$count_key = 'post_views_count';
	$count = get_post_meta($postID, $count_key, true);
	if($count==''){
		delete_post_meta($postID, $count_key);
		add_post_meta($postID, $count_key, '0');
		return "0";
	}
	return $count;
}
function setPostViews($postID) {
	$count_key = 'post_views_count';
	$count = get_post_meta($postID, $count_key, true);
	if($count==''){
		$count = 0;
		delete_post_meta($postID, $count_key);
		add_post_meta($postID, $count_key, '0');
	}else{
		$count++;
		update_post_meta($postID, $count_key, $count);
	}
}

// Функция возвращает пагинацию
function get_pagination() {
	global $wp_query;
	$paginate = paginate_links(array(
		'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
		'format' => '?paged=%#%',
		'current' => max(1, get_query_var('paged')),
		'total' => $wp_query->max_num_pages,
		'prev_text' => '&laquo;',
		'next_text' => '&raquo;'
	));
	$before = array("span", "page-numbers", "current", "dots");
	$after = array("a", "item", "active", "disabled");
	if ($wp_query->max_num_pages > 1) {
		return '<div class="ui basic center aligned segment"><div class="ui pagination menu">'.str_replace($before, $after, $paginate).'</div></div>';
	}
}

// Функция возвращает строку из имен категорий записи. Принимает id и разделитель
function get_cat_string($id, $com) {
	$str;
	$com = urlencode($com);
	foreach (get_the_category($id) as $cat) {
		$str .= $cat->name.$com;
	}
	$str = preg_replace("/$com$/", '', $str);
	return urldecode($str);
}

// Функция для букв аватаров в комментариях
function get_letter($login) {
	$login = mb_strtolower(mb_substr($login,0,1,'UTF-8'));
	$cyr = array('а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
	$lat = array('aa', 'bb', 'vv', 'gg', 'dd', 'ee', 'yo', 'je', 'ze', 'ii', 'ij', 'kk', 'll', 'mm', 'nn', 'oo', 'pp', 'rr', 'ss', 'tt', 'uu', 'ff', 'hh', 'cc', 'che', 'sha', 'scha', 'tvd', 'yy', 'mgk', 'ie', 'yu', 'ya');
	$login = str_replace($cyr, $lat, $login);
	if (preg_match("/^[a-zа-яё0-9]{1,4}$/iu", $login)) {
		return $login;
	} else {
		return 'no';
	}
}

// Функция выводящая description и keywords
function the_deskey() {
	echo '<meta name="description" content="'. get_deskey('d').'" />';
	echo '<meta name="keywords" content="'.get_deskey('k').'" />';
}

// Функция склонения "комментариев"
function plural_form($number, $after) {
    $cases = array (2, 0, 1, 1, 1, 2);
    echo $number.' '.$after[ ($number%100>4 && $number%100<20)? 2: $cases[min($number%10, 5)] ];
}

// Функция дописывающая ноль в начале числа
function get_number($n) {
	if ($n<10)
		return (int)'0'.$n;
	return $n;
}