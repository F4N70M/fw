<?php
$im = $this->data['id'] == $this->Fw->Account->getCurrentId();
//$user = $this->Fw->Client($this->data['id']);
$user = ($users = $this->Fw->Db->select(['id','name'])->from('users')->where(['id'=>$this->data['id']])->result()) ? $users[0] : null;
?>
<section>
    <div class="limit">
        <div class="part">
            <h1 class="ta-c"><?=$user['name'];?></h1>
        </div>
        <div class="part">
            <div class="part-min">
                <h2 class="ta-c">Новый проект</h2>
            </div>
            <div class="part-min">
                <div class="maxw-20 m-a">
                    <form method="post" style="display: grid; grid-gap: 1rem;">
                        <input type="hidden" name="request" value="projectCreate">
                        <input type="hidden" name="user" value="<?=$this->data['id'];?>">
                        <input type="hidden" name="redirect" value="<?= APP_PREFIX; ?>">

                        <input type="text" class="w-100p" name="title" placeholder="Название" required>
                        <input type="text" class="w-100p" name="link" placeholder="Ссылка (если существует)" value="http://">
                        <textarea name="description" rows="3" placeholder="Описание"></textarea>
                        <button type="submit">Создать</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>