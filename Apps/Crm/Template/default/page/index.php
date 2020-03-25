<?php
/**
 * Treat: F4N70M
 * Version: 0.1
 * Date: 03.02.2020
 */
?>

<?php
$userId = $this->Fw->Account->getCurrentId();
$projects = $this->Fw->ProjectManager->get(['user'=>$userId]);
?>

<section>
	<div class="limit part">
		<div class="part-min">
			<h1 class="ta-c"><?=$data['title'];?></h1>
		</div>
		<div class="part-min">
			<a href="<?= APP_PREFIX; ?>/new-project" class="btn">Создать проект</a>
		</div>
		<div class="part-min">
			<h2>Мои проекты</h2>
		</div>
		<div class="part-min">
			<?php
			?>
			<div class="d-g gg-1">
				<?php foreach ($projects as $project): ?>
					<a href="<?= APP_PREFIX; ?>/project-<?= $project['id']; ?>" class="btn"><?= $project['title']; ?></a>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>