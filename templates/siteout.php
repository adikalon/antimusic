<?php
if (!isset($_GET['url'])) {
	$wp_query->set_404();
	status_header( 404 );
	nocache_headers();
	get_template_part('404');
	exit();
} else {
	$url = str_replace('..', '.', substr_replace($url, $_GET['url'], 0));
}
/*
  Template Name: Переход
 */
?>
<?php get_header(); ?>
<section>
	<div class="ui raised segments">
		<div class="ui segment">
			<div class="ui center aligned icon header">
				<i class="circular sign out icon"></i>
				<div class="content">
					<h2>Переход по внешней ссылке</h2>
					<div class="sub header siteout-description">
						Переход по ссылке произойдет автоматически через <span id="timer" class="siteout-timer">10</span> секунд.<br>
						Если переход не произошел или вы не хотите ждать, нажмите на ссылку:
						<a href="#" class="siteout-url" onclick="siteoutUrl()"><?php echo $url; ?></a>
					</div>
				</div>
			</div>
		</div>
		
	</div>
</section>
<script type="text/javascript">
	window.onload = function () {
		var timer = document.getElementById("timer");
		var delay = 10;
		var location = "<?php echo $url; ?>";
		var interval = setInterval(function () {
			if (delay) {
				delay--;
			}

			timer.innerHTML = delay;
			if (delay <= 0) {
				clearInterval(interval);
				window.location.href=location;
			}
		}, 1000);
	};
	
	function siteoutUrl() {
		window.location.href="<?php echo $url; ?>";
	}
</script>
<?php get_footer(); ?>