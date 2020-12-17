<?php
$im = $this->data['id'] == $this->Fw->Account->getCurrentId();
$user = is_array($users = $this->Fw->Db->select(['id','name','login'])->from('users')->where(['id'=>$this->data['id']])->result())
    ? $users[0]
    : false;
$projects = $this->Fw->Db->select(['id','type','title','user'])->from('objects')->where(['type'=>'project', 'user'=>$this->data['id']])->result();

//debug($user);
?>

<section>
	<div class="limit">
        <div class="part">
            <h1 class="ta-c"><?=$user['name'];?> <span class="c-g">(<?=$user['login'];?>)</span></h1>
        </div>
        <div class="part">
            <div class="part-min">
<!--                <a href="--><?//= APP_PREFIX; ?><!--/new-project---><?//=$this->data['id'];?><!--" class="btn bg-2">Сменить пароль</a>-->
            </div>
            <div class="part-min">
                <a href="<?=APP_PREFIX;?>/new-project-<?=$this->data['id'];?>" class="btn bg-g-50">Создать проект</a>
                <a href="<?=APP_PREFIX;?>/change-password-<?=$this->data['id'];?>" class="popup-link btn bg-g-50">Сменить пароль</a>
            </div>
            <div class="part-min">
                <a href="<?=APP_PREFIX;?>/services-<?=$this->data['id'];?>" class="btn bg-g-50">Услуги</a>
                <a href="<?=APP_PREFIX;?>/accesses-<?=$this->data['id'];?>" class="btn bg-g-50">Доступы</a>
                <a href="<?=APP_PREFIX;?>/requisites-<?=$this->data['id'];?>" class="btn bg-g-50">Реквизиты</a>
            </div>
            <div class="part-min">
                <h2 class="ta-c"><?=$this->data['title'];?></h2>
            </div>
            <div class="part-min">
		        <?php
		        ?>
                <div class="d-g gg-2 gtc-4">
			        <?php foreach ($projects as $project): ?>
                        <a href="<?= APP_PREFIX; ?>/project-<?= $project['id']; ?>" class="bg-w bsh-05">
                            <div class="ratio-3-2">
                                <div class="d-g ac-c ta-c">
                                    <div class="tt-u"><?= $project['title']; ?></div>
                                </div>
                            </div>
                        </a>
			        <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>