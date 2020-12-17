<?php

$project = $this->Fw->Project($this->data['id']);
$im = $project->get('user') == $this->Fw->Account->getCurrentId();
$breadcrumbs = [
	'' => 'Проекты',
	'/project-'.$this->data['id'] => $project->get('title'),
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
                Новое обращение
            </div>
			<div class="part-min">
				<h1 class="ta-c">Новое обращение</h1>
			</div>
			<div class="part-min">
				<div class="maxw-20 m-a">
					<form method="post" class="d-g gg-1">
						<input type="hidden" name="request" value="ticketCreate">
						<input type="hidden" name="user" value="<?= $this->Fw->Account->getCurrentId(); ?>">
						<input type="hidden" name="project" value="<?= $this->data['id']; ?>">
						<input type="hidden" name="redirect" value="<?= APP_PREFIX; ?>/project-<?= $this->data['id']; ?>">

						<input type="text" class="w-100p" name="title" placeholder="Тема" required>
						<textarea name="content" rows="3" placeholder="Сообщение" required></textarea>
						<button type="submit">Создать</button>
					</form>
				</div>

			</div>
		</div>
	</div>
</section>