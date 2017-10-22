<?php

add_action('widgets_init', 'register_widget_vk');

function register_widget_vk() {
    register_widget('VK_Widget');
}

class VK_Widget extends WP_Widget {
	public function __construct() {
		parent::__construct('vk_widget', 'Сообщество ВК', array('description' => 'Сообщество ВК'));
	}
	
	public function form($instance) {
		$idvk = '';
		if (!empty($instance)) {
			$idvk = $instance['idvk'];
		}
		$idId = $this->get_field_id('idvk');
		$idName = $this->get_field_name('idvk');
		echo '<label for="'.$idId.'">ID Сообщества</label><br>';
		echo '<input id="'.$idId.'" type="text" name="'.$idName.'" value="'.$idvk.'">';
	}
	
	public function update($newInstance, $oldInstance) {
		$values = array();
		$values['idvk'] = htmlentities($newInstance['idvk']);
		return $values;
	}
	
	public function widget($args, $instance) {
		$idvk = $instance['idvk'];
		echo '<script src="//vk.com/js/api/openapi.js?142"></script>';
		echo '<div id="vk_groups" class="widget-vk"></div>';
		echo '<script>VK.Widgets.Group("vk_groups", {mode: 1, width: "252"}, '.$idvk.');</script>';
	}
}