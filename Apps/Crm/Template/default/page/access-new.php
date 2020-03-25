<?php
$types = [
	"provider"  => "Хостинг-провайдер",
	"ftp"   => "FTP",
	"sql"   => "MySQL",
	"ssl"   => "SSL",
	"mail"  => "Почта",
	"other"  => "Другое"
];

$project = $this->Fw->Project($data['id']);
$im = $project->get('user') == $this->Fw->Account->getCurrentId();
$breadcrumbs = [
	'' => 'Проекты',
	'/project-'.$data['id'] => $project->get('title'),
	'/accesses-'.$data['id'] => 'Доступы'
];
if (!$im)
{
	$user = $this->Fw->Client($project->get('user'));
	array_shift($breadcrumbs);
	$breadcrumbs = [''  => 'Клиенты','/projects-'.$user->get('id') => $user->get('name')] + $breadcrumbs;
}
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
                Добавить доступ
            </div>
			<div class="part-min">
				<h1 class="ta-c">Добавить доступ <?=isset($_GET['type']) && in_array($_GET['type'], array_flip($types)) ? $types[$_GET['type']] : ''; ?></h1>
			</div>
			<div class="part-min">
				<div class="maxw-20 m-a">
					<?php if (!isset($_GET['type']) || (isset($_GET['type']) && !in_array($_GET['type'], array_flip($types)))): ?>
						<form method="get" class="d-g gg-1">
							<select name="type" class="w-100p">
								<option>Выберите тип...</option>
								<?php foreach ($types as $key => $value): ?>
									<option value="<?=$key;?>"><?=$value;?></option>
								<?php endforeach; ?>
							</select>
							<button type="submit">Продолжить</button>
						</form>
					<?php else : ?>
						<form method="post" class="d-g gg-1">
							<input type="hidden" name="request" value="accessCreate">
                            <input type="hidden" name="user" value="<?= $this->Fw->Account->getCurrentId(); ?>">
                            <input type="hidden" name="project" value="<?= $data['id'] ?>">
							<input type="hidden" name="redirect" value="<?= APP_PREFIX; ?>">
							
							<?php
								switch ($_GET['type'])
								{
									case "host":
										?>
										<input type="hidden" name="type" value="<?=$_GET['type'];?>">
										<input type="text" class="w-100p" name="link" placeholder="Ссылка" required>
										<input type="text" class="w-100p" name="login" placeholder="Логин" required>
										<input type="text" class="w-100p" name="password" placeholder="Пароль" required>
										<?php
										break;
									case "ftp":
										?>
										<select name="type" class="w-100p">
											<option value="ftp" <?= $_GET['type'] == "ftp" ? 'selected' : ''; ?>>FTP</option>
											<option value="sftp" <?= $_GET['type'] == "sftp" ? 'selected' : ''; ?>>SFTP</option>
										</select>
										<input type="text" class="w-100p" name="host" placeholder="Сервер" required>
										<input type="text" class="w-100p" name="port" placeholder="Порт">
										<input type="text" class="w-100p" name="login" placeholder="Логин" required>
										<input type="text" class="w-100p" name="password" placeholder="Пароль" required>
										<?php
										break;
									case "sql":
										?>
										<input type="hidden" name="type" value="<?=$_GET['type'];?>">
										<input type="text" class="w-100p" name="link" placeholder="phpMyAdmin (ссылка)" required>
										<input type="text" class="w-100p" name="host" placeholder="Сервер" value="localhost" required>
										<input type="text" class="w-100p" name="login" placeholder="Логин" required>
										<input type="text" class="w-100p" name="password" placeholder="Пароль" required>
										<input type="text" class="w-100p" name="database" placeholder="База данных" required>
										<?php
										break;
									case "mail":
										?>
										<input type="hidden" name="type" value="<?=$_GET['type'];?>">
										<input type="text" class="w-100p" name="link" placeholder="Cсылка" required>
										<input type="text" class="w-100p" name="login" placeholder="Email" required>
										<input type="text" class="w-100p" name="password" placeholder="Пароль" required>
										<?php
										break;

									default:
										?>
										<input type="hidden" name="type" value="other">
										<input type="text" class="w-100p" name="link" placeholder="Cсылка" required>
										<input type="text" class="w-100p" name="login" placeholder="Логин" required>
										<input type="text" class="w-100p" name="password" placeholder="Пароль" required>
										<?php
										break;

								}
							?>
							<textarea name="description" rows="3" placeholder="Комментарии"></textarea>
							<button type="submit">Создать</button>
						</form>
					<?php endif ?>
				</div>

			</div>
		</div>
	</div>
</section>