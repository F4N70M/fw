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
                        <input type="hidden" name="redirect" value="<?=APP_PREFIX;?>">

                        <input class="w-100p" type="text" name="login" placeholder="Логин" required>
                        <input class="w-100p" type="password" name="password" placeholder="Пароль" required>
                        <button type="submit">Вход</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
