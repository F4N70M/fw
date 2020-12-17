<?php
/**
 * Treat: F4N70M
 * Version: 0.1
 * Date: 04.02.2020
 */
?>

<?php
$user = $this->Fw->Account->getCurrent();
//debug($user);
?>
<section>
    <div class="limit">
        <div class="part ta-c">
            <div class="part-min">
                <h1>403</h1>
                <h2>Доступ Запрещен</h2>
            </div>
            <div class="part-min">
                <img src="<?=THEME_URI.'/img/403.jpg';?>" style="max-height: 160px; max-width: 100%">
            </div>
            <div class="part-min">

                <p><?=$user ? 'Вы авторизованны как <span class="fw-b">'.$user['name'].'</span>' : 'Вы не авторизованны';?></p>
                <p>У Вас нет доступа к данной странице</p>
            </div>
            <div class="part-min">
                <div>
                    <a href="<?=APP_PREFIX;?>/login?r=<?=$_SERVER['REQUEST_URI'];?>" class="btn"><?='Рискнуть и пробиться';?></a>
                    <a href="<?=APP_PREFIX;?>/" class="btn bg-2">Убежать</a>
                </div>
            </div>
        </div>
    </div>
</section>
