<?php
$breadcrumbs = [
        ''  => 'Клиенты'
];
?>
<section>
	<div class="limit">
		<div class="part">
			<div class="part-min">
                <?php
                foreach ($breadcrumbs as $link => $title) : ?>
                    <a href="<?=APP_PREFIX.$link;?>"><?=$title;?></a>
                        >
                <?php endforeach; ?>
                Новый клиент
			</div>
			<div class="part-min">
				<h1 class="ta-c">Новый клиент</h1>
			</div>
			<div class="part-min">
				<div class="maxw-20 m-a">
					<form method="post" style="display: grid; grid-gap: 1rem;">
						<input type="hidden" name="request" value="clientCreate">
						<input type="hidden" name="user" value="<?= $this->Fw->Account->getCurrentId(); ?>">
						<input type="hidden" name="redirect" value="<?= APP_PREFIX; ?>">

						<input type="text" class="w-100p" name="login" placeholder="Логин (только латинские буквы)" pattern="^[a-z]+$" required>
						<input type="text" class="w-100p" name="name" placeholder="Имя" required>
						<input type="text" class="w-100p" name="password" placeholder="Пароль" value="<?=$this->Fw->UserManager->generatePassword();?>" required>
						<button type="submit">Создать</button>
					</form>
				</div>

			</div>
		</div>
	</div>
</section>