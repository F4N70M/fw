<section>
    <div class="limit">
        <div class="part">
            <div class="part-min">
                <a href="<?= APP_PREFIX; ?>"><button>Все проекты</button></a>
            </div>
            <div class="part-min">
                <h1 class="ta-c">Новый проект</h1>
            </div>
            <div class="part-min">
                <div class="maxw-20 m-a">
                    <form method="post" style="display: grid; grid-gap: 1rem;">
                        <input type="hidden" name="request" value="projectCreate">
                        <input type="hidden" name="user" value="<?= $this->Fw->Account->getCurrent()['id']; ?>">
                        <input type="hidden" name="redirect" value="/lk">

                        <input type="text" class="w-100p" name="name" placeholder="Название" required>
                        <input type="text" class="w-100p" name="link" placeholder="Ссылка (если существует)">
                        <textarea name="description" rows="3" placeholder="Описание"></textarea>
                        <button type="submit">Создать</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>


