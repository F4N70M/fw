<?php
$types = [
	"hosting"  => "Хостинг",
	"domen"   => "Домен",
	"sql"   => "SSL сертификат",
	"other"  => "Другое"
];


$ServiceManager = $this->Fw->ServiceManager();
//$services = $ServiceManager->get(['project'=>$data['id']]);

//$project = $this->Fw->Project($data['id']);
//$im = $project->get('user') == $this->Fw->Account->getCurrentId();
$breadcrumbs = [
	'' => 'Проекты',
//	'/project-'.$data['id'] => $project->get('title')
];
//if (!$im)
//{
//	$user = $this->Fw->Client($project->get('user'));
//	array_shift($breadcrumbs);
//	$breadcrumbs = [''  => 'Клиенты','/projects-'.$user->get('id') => $user->get('name')] + $breadcrumbs;
//}
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
				Услуги
			</div>
			<div class="part-min">
				<a href="<?= APP_PREFIX; ?>/new-service<?=$data['id']?'-'.$data['id']:'';?>" class="btn">добавить услугу</a>
			</div>
			<div class="part-min">
				<h1>Услуги</h1>
			</div>
			<div class="part-min">
				<div class="d-g gg-1 gtc-3">
					<?php foreach ($accesses as $key => $access): ?>
						<div class="p-1 bg-1">
							<?php
							switch ($access['accessType']) {
								case "provider":
									?>
									<h3><?=$types[$access['accessType']];?></h3>
									<div class="d-g gg-1 gtc-2">
										<div>Ссылка</div><div><a href="<?=$access['link'];?>" target="_blank"><?=$access['link'];?></a></div>
										<div>Логин</div><div><?=$access['login'];?></div>
										<div>Пароль</div><div><?=$access['password'];?></div>
									</div>
									<?php
									break;
								case "ftp":
									?>
									<h3><?=$types[$access['accessType']];?></h3>
									<div class="d-g gg-1 gtc-2">
										<div>Сервер</div><div><?=$access['host'];?></div>
										<?php if(!empty($access['port'])): ?>
											<div>Порт</div><div><?=$access['port'];?></div>
										<?php endif; ?>
										<div>Логин</div><div><?=$access['login'];?></div>
										<div>Пароль</div><div><?=$access['password'];?></div>
									</div>
									<?php
									break;
								case "sql":
									?>
									<h3><?=$types[$access['accessType']];?></h3>
									<div class="d-g gg-1 gtc-2">
										<div>phpMyAdmin (ссылка)</div><div><a href="<?=$access['link'];?>" target="_blank"><?=$access['link'];?></a></div>
										<div>Сервер</div><div><?=$access['host'];?></div>
										<div>Логин</div><div><?=$access['login'];?></div>
										<div>Пароль</div><div><?=$access['password'];?></div>
										<div>База данных</div><div><?=$access['database'];?></div>
									</div>
									<?php
									break;
								case "mail":
									?>
									<h3><?=$types[$access['accessType']];?></h3>
									<div class="d-g gg-1 gtc-2">
										<div>Cсылка</div><div><a href="<?=$access['link'];?>" target="_blank"><?=$access['link'];?></a></div>
										<div>Email</div><div><?=$access['login'];?></div>
										<div>Пароль</div><div><?=$access['password'];?></div>
									</div>
									<?php
									break;

								default:
									?>
									<h3><?=$types[$access['accessType']];?></h3>
									<div class="d-g gg-1 gtc-2">
										<div>Cсылка</div><div><a href="<?=$access['link'];?>" target="_blank"><?=$access['link'];?></a></div>
										<div>Логин</div><div><?=$access['login'];?></div>
										<div>Пароль</div><div><?=$access['password'];?></div>
									</div>
									<?php
									break;
							}
							?>
						</div>
					<?php endforeach; ?>
				</div>

			</div>
		</div>
	</div>
</section>