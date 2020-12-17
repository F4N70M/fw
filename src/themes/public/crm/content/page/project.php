<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 05.03.2020
 */
?>

<?php
//$project = $this->Fw->Project($this->data['id']);
$project =
    (
        $resultRequest = $this->Fw->Db->select()->from('objects')->where(['id'=>$this->data['id'], 'type'=>'project'])->result()
    ) && isset($resultRequest[0])
        ? $resultRequest[0]
        : null;
$projectUser = $project['user'];
$im = ($projectUser == $this->Fw->Account->getCurrentId());

?>

<section>
    <div class="limit">
        <div class="part">
            <div class="part-min">
                <div class="d-g gaf-c jc-sb gg-1">
                    <div>
                        <h1>Проект: "<?=$project['title'];?>"</h1>
                    </div>
                    <div class="d-g gaf-c jc-sb gg-1">
                        <div><a href="<?= APP_PREFIX; ?>/accesses-<?= $this->data['id']; ?>" class="btn">Доступы</a></div>
                        <form method="post">
                            <input type="hidden" name="request" value="projectDelete">
                            <input type="hidden" name="id" value="<?= $this->data['id']; ?>">
                            <input type="hidden" name="redirect" value="<?= APP_PREFIX; ?>">
                            <button type="submit">Удалить проект</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section>
</section>



