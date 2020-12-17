<?php
/**
 * Treat: F4N70M
 * Version: 0.1
 * Date: 03.02.2020
 */
?>

<?php
$user = $this->Fw->Account->getCurrent();
//debug($user);
//$projects = $this->Fw->ProjectManager->get(['user'=>$this->data['id']]);
$projects = $this->Fw->Db
    ->select()
    ->from('objects')
    ->where(['type'=>'project','user'=>$user['id']])
    ->result();
//debug($projects);
?>

<section>
	<div class="limit part">
		<div class="part-min">
			<h1 class="ta-c">Проекты</h1>
		</div>
        <?php /*
        <div class="part-min">
            <a href="<?= APP_PREFIX; ?>/new-project-<?=$user['id'];?>" class="btn">Создать проект</a>
        </div>
        */ ?>
        <div class="part-min">
            <?php if ($projects && is_array($projects) && !empty($projects)) : ?>
                <div class="d-g gtc-4 gg-1">
                    <?php foreach ($projects as $project): ?>
                        <a href="<?= APP_PREFIX; ?>/project-<?= $project['id']; ?>" class="btn bg-2"><?= $project['title']; ?></a>
                    <?php endforeach; ?>
                </div>
            <?php else:?>
                <p class="ta-c c-g">У вас пока нет проектов</p>
            <?php endif; ?>
		</div>
	</div>
</section>