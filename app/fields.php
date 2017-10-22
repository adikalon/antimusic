<?php
add_action('add_meta_boxes', 'my_extra_fields', true);

function my_extra_fields() {
	add_meta_box('extra_fields', 'Дополнительные поля', 'extra_fields_box_func', 'post', 'normal', 'high');
}

// код блока
function extra_fields_box_func($post) { ?>
	<div class="properties-block">
		<div class="cover" id="extra_cover"></div>
		<div class="right-block">
			<div class="album-year-block">
				<div class="album-block">
					<div class="label-block">Альбом</div>
					<input type="text" name="extra[album]" value="<?php echo get_post_meta($post->ID, 'album', true); ?>" id="extra_album" class="input-100">
				</div>
				<div class="country-block">
					<div class="label-block">Страна</div>
					<input type="text" name="extra[country]" value="<?php echo get_post_meta($post->ID, 'country', true); ?>" id="extra_country" class="input-100">
				</div>
				<div class="year-block">
					<div class="label-block">Год</div>
					<input type="text" name="extra[year]" value="<?php echo get_post_meta($post->ID, 'year', true); ?>" id="extra_year" class="input-100">
				</div>
			</div>
			<div>
				<div class="label-block">URL Обложки</div>
				<input type="text" name="extra[img_url]" value="<?php echo get_post_meta($post->ID, 'img_url', true); ?>" id="extra_img" class="input-100">
			</div>
			<div>
				<div class="label-block">ALT Обложки</div>
				<input type="text" name="extra[img_alt]" value="<?php echo get_post_meta($post->ID, 'img_alt', true); ?>" id="extra_alt" class="input-100">
			</div>
			<div>
				<div class="label-block">Сгенерированный URL для записи</div>
				<input type="text" id="extra_url" class="input-100">
			</div>
			<div class="url-ids-block">
				<div class="band-id-block">
					<div class="label-block">URL ID Исполнителя</div>
					<input type="text" name="extra[band_id]" value="<?php echo get_post_meta($post->ID, 'band_id', true); ?>" id="extra_band_id" class="input-100">
				</div>
				<div class="country-id-block">
					<div class="label-block">URL ID Страны</div>
					<input type="text" name="extra[country_id]" value="<?php echo get_post_meta($post->ID, 'country_id', true); ?>" id="extra_country_id" class="input-100">
				</div>
			</div>
		</div>
	</div>
	<div class="label-block songs-label-first">Ссылка на скачку</div>
	<input type="text" class="songs-input" name="extra[download]" value="<?php echo get_post_meta($post->ID, 'download', true); ?>">
	<div class="label-block songs-label-second">Песни</div>
	<div class="songs-second-block">
		<textarea name="extra[jsong]" class="songs-area" id="songs_area"><?php echo get_post_meta($post->ID, 'jsong', true); ?></textarea>
		<span class="button songs-button" id="songs_get_json">Чистый JSON</span>
	</div>
	<input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__); ?>" />
<?php }

// включаем обновление полей при сохранении
add_action('save_post', 'my_extra_fields_update', 0);

/* Сохраняем данные, при сохранении поста */
function my_extra_fields_update($post_id) {
	if (!isset($_POST['extra_fields_nonce']) || !wp_verify_nonce($_POST['extra_fields_nonce'], __FILE__))
		return false; // проверка
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return false; // если это автосохранение
	if (!current_user_can('edit_post', $post_id))
		return false; // если юзер не имеет право редактировать запись
	if (!isset($_POST['extra']))
		return false;

	// Все ОК! Теперь, нужно сохранить/удалить данные
	$_POST['extra'] = array_map('trim', $_POST['extra']);
	foreach ($_POST['extra'] as $key => $value) {
		if (empty($value)) {
			delete_post_meta($post_id, $key); // удаляем поле если значение пустое
			continue;
		}
		update_post_meta($post_id, $key, $value); // add_post_meta() работает автоматически
	}
	return $post_id;
}
