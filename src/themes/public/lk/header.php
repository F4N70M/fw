<?php
$user = $this->Fw->Account->getCurrent();
?>
<?php if ($user['rank'] >= 1) : ?>
    <section>
        <div class="bg-2 d-g gg-2 h-4  ph-2" style="grid-template-columns: auto 1fr auto;">
            <div class="d-g ac-c" style="width: calc(50vw - 71rem / 2 - 2rem); min-width: 10rem;">
                DX
            </div>
            <div class="d-g gg-2 gaf-c jc-l ac-c">
                <a href="<?=APP_PREFIX;?>/">Задачи</a>
                <a href="<?=APP_PREFIX;?>/accesses">Доступы</a>
                <a href="<?=APP_PREFIX;?>/projects">Проекты</a>
<!--                <a href="--><?//=APP_PREFIX;?><!--/tasks">Задачи</a>-->
                <a href="<?=APP_PREFIX;?>/requisites">Реквизиты</a>
            </div>
            <div class="d-g ac-c ta-r" style="width: calc(50vw - 71rem / 2 - 2rem); min-width: 10rem;">
                <div>
                    <?php
                    $id = $this->Fw->Account->getCurrentId();
                    if ($id)
                    {
                        $user = $this->Fw->Account->getCurrent();
                        if ($user) :
                            ?>
                            <b><?= $user['name']; ?></b> [<?= $user['type']; ?>] (<a href="<?= APP_PREFIX; ?>/logout">выйти</a>)
                        <?php
                        endif;
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>
