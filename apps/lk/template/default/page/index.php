<?php
/**
 * Project: F4N70M
 * Version: 0.1
 * Date: 03.02.2020
 */
?>

<h1><?=$data['title'];?></h1>

<a href="<?= APP_PREFIX; ?>/new-project"><button>Создать</button></a>
<?php
$projects = $this->Fw->Projects->getUserProjects($this->Fw->Account->getCurrent()['id']);
// debug($projects);
?>
<h3>Проекты</h3>
    <ul>
<?php foreach ($projects as $project): ?>
    <li>
        <a href="<?= APP_PREFIX; ?>/project-<?= $project['id']; ?>"><button><?= $project['title']; ?></button></a>
    </li>
<?php endforeach; ?>
    </ul>
<?php
//debug($data);
//debug($this->Fw->TemplateManager->getTemplatesForApp('lk'));

//$this->Fw->Entity->verifyPassword(1, 'msf34egs');
//debug(password_hash('ralavada',PASSWORD_DEFAULT));
?>