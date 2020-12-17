<?php

$user = $this->Fw->Account->getCurrent();
$projects = $this->Fw->Db
    ->select()
    ->from('objects')
    ->where(['type'=>'project','user'=>$user['id']])
    ->result();

?>
<section>
    <div class="limit">
        <div class="part">
            <div class="part-min">
                <h1 class="ta-c">Новая задача</h1>
            </div>
            <div class="part-min">
                <div>
                    <form method="post" class="d-g gg-1" enctype="multipart/form-data">
                        <input type="hidden" name="request" value="taskNew">
                        <!--						<input type="hidden" name="user" value="--><?//= $this->Fw->Account->getCurrentId(); ?><!--">-->
                        <!--						<input type="hidden" name="project" value="--><?//= $this->data['id']; ?><!--">-->
                        <input type="hidden" name="redirect" value="<?=APP_PREFIX;?>">

                        <select name="project" class="w-100p" required>
                            <option value="" style="display: none">Проект *</option>
                            <?php
                            //                            $clients = $this->Fw->ClientManager->get(['type'=>'client']);
                            //                            debug($clients);
                            ?>
                            <?php foreach ($projects  as $item): ?>
                                <option value="<?=$item['id'];?>"><?=$item['title'];?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" class="w-100p" name="title" placeholder="Тема *" required>
                        <textarea name="content" rows="6" placeholder="Подробное описание задачи *" required></textarea>

                        <select name="status" class="w-100p" required>
                            <option value="default">Не срочно</option>
                            <option value="quickly">Срочно</option>
                            <option value="pause">Пауза</option>
                        </select>
                        <!--                        <div>-->
                        <!--                            <input type="file" class="w-100p" id="files" name="files[]" multiple title="Выбрать файлы">-->
                        <!--                        </div>-->
                        <button type="submit">Создать</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>