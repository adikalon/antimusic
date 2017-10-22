<?php if (post_password_required()) return; ?>

<?php function get_all_comments($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class('ui comments no-marker comment-width'); ?> id="comment-<?php comment_ID(); ?>">
	<span class="comment">
		<span class="avatar">
			<?php //echo get_avatar($comment); ?>
			<img src="<?php echo get_template_directory_uri() . '/img/avatars/'.get_letter(get_comment_author_link()).'.png' ?>" alt="">
		</span>
		<span class="content">
			<a class="author" href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)); ?>"><?php echo get_comment_author_link(); ?></a>
			<span class="metadata">
				<span class="date"><?php printf('%1$s в %2$s', get_comment_date(), get_comment_time()); ?></span>
				<span class="rating"><?php if ($comment->comment_approved == '0') echo '<i class="hide icon"></i>Ваш комментарий ожидает проверки'; ?></span>
			</span>
			<span class="text comment-text"><?php echo get_comment_text(); ?></span>
			<span class="actions">
				<span class="reply">
					<?php comment_reply_link(array_merge($args, array(
						'depth' => $depth,
						'max_depth' => $args['max_depth'],
						'reply_text' => '<i class="reply icon"></i>Ответить'
					))); ?>
				</span>
				<span class="save">
					<?php edit_comment_link('<i class="write icon"></i>Редактировать', '  ', ''); ?>
				</span>
			</span>
		</span>
	</span>
<?php } ?>

<?php
add_filter('comment_form_fields', 'reorder_comment_fields' );
function reorder_comment_fields( $fields ){
	$new_fields = array();
	$myorder = array('author','email','url','comment');
	foreach( $myorder as $key ){
		$new_fields[ $key ] = $fields[ $key ];
		unset( $fields[ $key ] );
	}
	if( $fields )
		foreach( $fields as $key => $val )
			$new_fields[ $key ] = $val;
	return $new_fields;
}
?>

<?php
$defaults = array(
	'fields' => array(
		'author' => '<div class="two fields"><div class="field"><label for="author">'.__('Name').'</label><input id="author" name="author" type="text" value="'.esc_attr($commenter['comment_author']).'"size="30"'.$aria_req.$html_req.'></div>',
		'email' => '<div class="field"><label for="email">'.__('Email').'<small>&nbsp;-&nbsp;будет виден только вам</small></label><input id="email" name="email"'.($html5 ? 'type="email"' : 'type="text"').' value="'.esc_attr($commenter['comment_author_email']).'" size="30" aria-describedby="email-notes"'.$aria_req.$html_req.'></div></div>'
	),
	'comment_field' => '<div class="field"><label for="comment">'._x('Comment', 'noun').'</label><textarea id="comment" name="comment" cols="45" rows="8" required="required"></textarea></div>',
	'must_log_in' => '<div class="ui secondary segment"><p>'.sprintf(__( 'You must be <a href="%s">logged in</a> to post a comment.'), wp_login_url(apply_filters('the_permalink', get_permalink($post_id)))).'</p></div>',
	'logged_in_as' => '',
	'comment_notes_before' => '',
	'comment_notes_after' => '',
	'id_form' => 'commentform',
	'id_submit' => 'submit',
	'class_form' => 'comment-form',
	'class_submit' => 'ui primary submit labeled icon button',
	'name_submit' => 'submit',
	'title_reply' => __( '' ),
	'title_reply_to' => '<div class="ui label"><i class="reply icon"></i>Ответ для:</div><div class="ui blue label">%s</div>', // Начало кнопки кому отвечаю
	'title_reply_before' => '',
	'title_reply_after' => '',
	'cancel_reply_before' => '',
	'cancel_reply_after' => '',
	'cancel_reply_link' => '<div class="ui red label"><i class="delete icon"></i>&nbsp;&nbsp;</div>', // Конец кнопки кому отвечаю
	'label_submit' => __( 'Отправить' ),
	'submit_button' => '<button name="%1$s" type="submit" id="%2$s" class="%3$s"><i class="icon edit"></i>%4$s</button>',
	'submit_field' => '<p class="form-submit">%1$s %2$s</p>'
);
?>

<?php
$max_page = get_comment_pages_count();
$page = get_query_var('cpage');
if (!$page) $page = 1;
$paginate = paginate_comments_links(array(
	'base'    => add_query_arg( 'cpage', '%#%' ),
	'format'  => null,
	'total'   => $max_page,
	'current' => $page,
	'echo'    => false,
	'add_fragment' => '#comments',
	'prev_text' => '&laquo;',
	'next_text' => '&raquo;'
));
$before = array("span", "page-numbers", "current", "dots");
$after = array("a", "item", "active", "disabled");
$comment_paginate = '<div class="ui pagination menu">'.str_replace($before, $after, $paginate).'</div>';
?>

<div id="comments">
	<?php if (have_comments()) : ?>
		<section class="ui raised segments">
			<h4 class="ui header segment">
				<?php printf(esc_html(plural_form(get_comments_number(), array('комментарий','комментария','комментариев')))); ?>
			</h4>
			<ul class="ui segment comment-ul">
				<?php wp_list_comments('type=comment&callback=get_all_comments'); ?>
			</ul>
			<?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
			<div class="ui segment">
				<div class="ui basic center aligned segment">
					<?php echo $comment_paginate; ?>
				</div>
			</div>
			<?php endif; ?>
		</section>
	<?php endif; ?>

	<section class="ui raised segments">
		<h4 class="ui header segment">
			Добавить комментарий
		</h4>
		<?php if (!comments_open() && post_type_supports(get_post_type(), 'comments')) : ?>
			<div class="ui secondary segment">
				<p><?php esc_html_e('Возможность комментирования этой записи отключена'); ?></p>
			</div>
		<?php else: ?>
			<div class="ui segment">
				<div class="ui form">
					<?php comment_form( $defaults ); ?>
				</div>
			</div>
		<?php endif; ?>
	</section>
</div>