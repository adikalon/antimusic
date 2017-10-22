<?php
add_action('add_meta_boxes', 'my_deskey_fields', 1);

function my_deskey_fields() {
	add_meta_box('deskey_fields', 'Keywords и Description', 'deskey_fields_box_func', 'post', 'normal', 'high');
}

// код блока
function deskey_fields_box_func($post) { ?>
	<div>
		<div class="label-block">Keywords <span class="label-meta">Символов: <span id="keyNum">0</span> из 150 (250) допустимых</span></div>
		<div class="deskey-block">
			<div class="deskey-sub-block">
				<input type="text" class="deskey-pole" name="deskey[keywords]" value="<?php echo get_post_meta($post->ID, 'keywords', true); ?>" id="deskey_keywords">
			</div>
			<div class="deskey-button-block">
				<div class="button deskey-button deskey-input deskey-key-styles" id="keyword_generate">Сгенерировать</div>
			</div>
		</div>
		<div class="label-block">Description <span class="label-meta">Символов: <span id="descNum">0</span> из 160 (200) допустимых</span></div>
		<div class="deskey-block">
			<div class="deskey-sub-block">
				<textarea name="deskey[description]" class="deskey-pole deskey-description" id="deskey_description"><?php echo get_post_meta($post->ID, 'description', true); ?></textarea>
			</div>
			<div class="deskey-button-block">
				<div class="button deskey-button deskey-description deskey-desk-styles" id="descript_generate">Сгенерировать</div>
			</div>
		</div>
	</div>
	<input type="hidden" name="deskey_fields_nonce" value="<?php echo wp_create_nonce(__FILE__); ?>" />
<?php }

// включаем обновление полей при сохранении
add_action('save_post', 'my_deskey_fields_update', 0);

/* Сохраняем данные, при сохранении поста */
function my_deskey_fields_update($post_id) {
	if (!isset($_POST['deskey_fields_nonce']) || !wp_verify_nonce($_POST['deskey_fields_nonce'], __FILE__))
		return false; // проверка
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return false; // если это автосохранение
	if (!current_user_can('edit_post', $post_id))
		return false; // если юзер не имеет право редактировать запись
	if (!isset($_POST['deskey']))
		return false;

	// Все ОК! Теперь, нужно сохранить/удалить данные
	$_POST['deskey'] = array_map('trim', $_POST['deskey']);
	foreach ($_POST['deskey'] as $key => $value) {
		if (empty($value)) {
			delete_post_meta($post_id, $key); // удаляем поле если значение пустое
			continue;
		}
		update_post_meta($post_id, $key, $value); // add_post_meta() работает автоматически
	}
	return $post_id;
}
