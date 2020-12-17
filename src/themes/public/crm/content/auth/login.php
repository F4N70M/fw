<section>
    <div class="limit">
        <div class="part">
            <div class="part-min">
                <h1 class="ta-c">Авторизация</h1>
            </div>
            <div class="part-min">
                <div class="maxw-20 m-a">
                    <form method="post" class="d-g gg-1">
                        <input type="hidden" name="request" value="login">
                        <?php if (array_key_exists('r', $_GET) && !empty($_GET['r'])) : ?>
                        <?php endif; ?>
                        <input type="hidden" name="redirect" value="<?=(array_key_exists('r', $_GET) && !empty($_GET['r'])) ? $_GET['r'] : APP_PREFIX.'/';?>">


                        <input class="w-100p" type="text" name="login" placeholder="Логин" required>
                        <input class="w-100p" type="password" name="password" placeholder="Пароль" required>
                        <button type="submit">Вход</button>
                    </form>
                </div>
            </div>

            <!--
            <div class="part-min">
                <div class="ta-c">
                    <a href="<?= APP_PREFIX; ?>/signup">Зарегистрироваться</a>
                </div>
            </div>
            -->
        </div>
    </div>
</section>
