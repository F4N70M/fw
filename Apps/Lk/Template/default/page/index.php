<?php
/**
 * Project: F4N70M
 * Version: 0.1
 * Date: 03.02.2020
 */
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
	        $projects = $this->Fw->Projects->getUserProjects($this->Fw->Account->getCurrent()['id']);
	        ?>
            <div class="d-g gg-1">
		        <?php foreach ($projects as $project): ?>
                    <a href="<?= APP_PREFIX; ?>/project-<?= $project['id']; ?>" class="btn"><?= $project['title']; ?></a>
		        <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>




<?php
//debug($data);
//debug($this->Fw->TemplateManager->getTemplatesForApp('lk'));

//$this->Fw->Entity->verifyPassword(1, 'msf34egs');
//debug(password_hash('ralavada',PASSWORD_DEFAULT));
?>