<?php
/*
  Template Name: Обратная связь
 */
?>
<?php
if (isset($_POST['fb_name']) && isset($_POST['fb_mail']) && isset($_POST['fb_subject']) && isset($_POST['fb_message'])) {
	$fb_name = trim($_POST['fb_name']);
	$fb_mail = trim($_POST['fb_mail']);
	$fb_subject = trim($_POST['fb_subject']);
	$fb_message = trim($_POST['fb_message']);
	
	if (!preg_match('/^([A-Za-zА-Яа-яё]{3,20})$/iu', $_POST['fb_name'])) {
		$fb_error['name'] = 'Укажите корректное имя (от 3 до 20 символов)';
	}

	if (!preg_match('/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/i', $_POST['fb_mail'])) {
		$fb_error['mail'] = 'Некорректный Email';
	}

	if (!preg_match('/^(.{5,150})$/i', $_POST['fb_subject'])) {
		$fb_error['subject'] = 'Тема от 5 до 150 сиволов';
	}

	if (!preg_match('/^(.{5,1000})$/i', $_POST['fb_message'])) {
		$fb_error['message'] = 'Сообщение от 5 до 1000 символов';
	}
	
	if (!isset($fb_error)) {
        $headers = 'From: '.$fb_name.' <'.$fb_mail.'>' . "\r\n" . 'Reply-To: ' . $fb_mail;
		$send_mail = wp_mail(get_option('admin_email'), $fb_subject, $fb_message, $headers);
		if ($send_mail) {
			unset($fb_name, $fb_mail, $fb_subject, $fb_message);
			$send_success = 'Мы обязательно откликнемся!';
		} else {
			$fb_error['server'] = 'Попробуйте повторить попытку позже или напишите на '.get_option('admin_email');
		}
	}
}
?>
<?php get_header(); ?>
<section>
	<div class="ui raised segments">
		<div class="ui segment">
			<div class="ui center aligned icon header feedback-size">
				<i class="circular mail icon"></i>
				<div class="content">
					<h2 class="feedback-caption">Напишите нам</h2>
					<div class="sub header feedback-description">
						Имеются пожелания или предложения? Найдена ошибка или что-то не работает? Напишите нам и мы обязательно ответим или починим неисправность
					</div>
				</div>
			</div>
		</div>
		<div class="ui segment">
			<?php if (isset($send_success)): ?>
				<div class="ui success message feedback-size feedback-message" id="fb_success">
					<i class="close icon" onclick="$('#fb_success').slideUp(300)"></i>
					<div class="header">Письмо успешно отправлено</div>
					<ul>
						<?php if (isset($send_success)) echo '<li>' . $send_success; ?>
					</ul>
				</div>
			<?php endif; ?>
			<?php if (isset($fb_error)): ?>
				<div class="ui negative message feedback-size feedback-message" id="fb_warning">
					<i class="close icon" onclick="$('#fb_warning').slideUp(300)"></i>
					<div class="header">Произошла ошибка</div>
					<ul>
						<?php if (isset($fb_error['name'])) echo '<li>' . $fb_error['name']; ?>
						<?php if (isset($fb_error['mail'])) echo '<li>' . $fb_error['mail']; ?>
						<?php if (isset($fb_error['subject'])) echo '<li>' . $fb_error['subject']; ?>
						<?php if (isset($fb_error['message'])) echo '<li>' . $fb_error['message']; ?>
						<?php if (isset($fb_error['server'])) echo '<li>' . $fb_error['server']; ?>
					</ul>
				</div>
			<?php endif; ?>
			<form method="post">
				<div class="ui form feedback-size">
					<div class="two fields">
						<div class="field">
							<label>Имя</label>
							<input type="text" placeholder="Имя" name="fb_name" value="<?php if (isset($fb_name)) echo $fb_name; ?>">
						</div>
						<div class="field">
							<label>Email</label>
							<input type="text" placeholder="Email" name="fb_mail" value="<?php if (isset($fb_mail)) echo $fb_mail; ?>">
						</div>
					</div>
					<div class="field">
						<label>Тема</label>
						<input type="text" placeholder="Тема письма" name="fb_subject" value="<?php if (isset($fb_subject)) echo $fb_subject; ?>">
					</div>
					<div class="field">
						<label>Сообщение</label>
						<textarea placeholder="Текст сообщения..." name="fb_message"><?php if (isset($fb_message)) echo $fb_message; ?></textarea>
					</div>
					<button type="submit" class="ui primary submit labeled icon button"><i class="mail outline icon"></i>Отправить</button>
				</div>
			</form>
		</div>
	</div>
</section>
<?php get_footer(); ?>