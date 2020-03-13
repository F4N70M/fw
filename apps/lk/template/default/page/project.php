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

<section>
    <div class="limit">
        <div class="part">
            <div class="part-min">
                <a href="<?= APP_PREFIX; ?>" class="btn">Все проекты</a>
            </div>
            <div class="part-min">
                <h1>Проект: "<?=$project['title'];?>"</h1>
            </div>
            <div class="part-min">
                <form method="post">
                    <input type="hidden" name="request" value="projectDelete">
                    <input type="hidden" name="id" value="<?= $data['id']; ?>">
                    <input type="hidden" name="redirect" value="<?= APP_PREFIX; ?>">
                    <button type="submit">Удалить</button>
                </form>
            </div>
        </div>
    </div>
</section>




