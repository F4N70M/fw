<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 05.03.2020
 */
?>

<?php
$project = $this->Fw->Projects->get($data['id']);
?>


<a href="<?= APP_PREFIX; ?>"><button>Все проекты</button></a>


<h1>Проект: "<?=$project['title'];?>"</h1>



<?php
//debug($data);
//debug($this->Fw->TemplateManager->getTemplatesForApp('lk'));
?>





<form method="post" style="display: grid; grid-gap: 1rem; width: auto;">
    <input type="hidden" name="request" value="projectDelete">
    <input type="hidden" name="id" value="<?= $data['id']; ?>">
    <input type="hidden" name="redirect" value="<?= APP_PREFIX; ?>">
    <button type="submit">Удалить</button>
</form>