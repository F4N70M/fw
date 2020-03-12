<section>
    <?php
    $user = $this->Fw->Account->getCurrent();

    if ($user) :
        ?>
        Пользователь: <b><?= $user['name']; ?></b> [<?= $user['type']; ?>] (<a href="/lk/logout">выйти</a>)
        <?php
    endif;
    ?>
</section>
<section>HEADER</section>