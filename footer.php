			</main>
		</div>
		<footer class="main-footer">
			<?php if(!dynamic_sidebar('footer')): ?>
			<?php endif; ?>
			<div class="footer-head">© 2017 - <?php bloginfo('name'); ?></div>
			<div class="footer-description">Все аудио композиции опубликованы в целях ознакомления. Всякое распространение запрещено!</div>
			<div id="scroller">
				<i class="angle up icon scroller-icon-font"></i>
				<div class="scroller-text-font">Вверх</div>
			</div>
		</footer>
		</div>
		<?php wp_footer(); ?>
	</body>
</html>