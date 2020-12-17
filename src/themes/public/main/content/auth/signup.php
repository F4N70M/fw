<section>
	<div class="limit">
		<div class="part">
			<div class="part-min">
				<h1 class="ta-c">Регистрация</h1>
			</div>
			<div class="part-min">
				<div class="maxw-20 m-a">
					<form method="post" class="d-g gg-1">
						<input type="hidden" name="request" value="signUp">
						<input type="hidden" name="redirect" value="<?= APP_PREFIX; ?>/login">

						<input class="w-100p" type="text" name="login" placeholder="Придумайте логин" required>
						<input class="w-100p" type="password" name="password" placeholder="Придумайте пароль" required>
						<input class="w-100p" type="email" name="email" placeholder="Email" required>
<!--						<input class="w-100p" type="password" name="password_confirm" placeholder="Повторите пароль" required>-->
						<button type="submit">Зарегистрироваться</button>
					</form>
				</div>
			</div>

			<div class="part-min">
				<div class="ta-c">
					<a href="<?= APP_PREFIX; ?>/login">Авторизироваться</a>
				</div>
			</div>

		</div>
	</div>
</section>
