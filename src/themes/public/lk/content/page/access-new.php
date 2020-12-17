<?php
$types = [
	"provider"  => "Хостинг-провайдер",
    "ftp"   => "FTP",
    "sftp"   => "sFTP",
	"sql"   => "MySQL",
	"ssl"   => "SSL",
	"mail"  => "Почта",
	"other"  => "Другое"
];

$user = $this->Fw->Account->getCurrent();

$otherProjects = $this->Fw->Db->select()->from('objects')->where(['user'=>$user['id'], 'type'=>'project'])->result();

//debug($project['user'],$otherProjects);
?>
<section>
	<div class="limit">
		<div class="part">
			<div class="part-min">
				<h1 class="ta-c">Добавить доступ</h1>
			</div>
			<div class="part-min">
				<div class="maxw-20 m-a">
                    <div class="d-g gg-1">
                        <?php /*if (!isset($_GET['type']) || (isset($_GET['type']) && !in_array($_GET['type'], array_flip($types)))):*/ ?>
                        <form method="get" class="d-g gg-1">
                            <select name="access_type" class="w-100p" onchange="this.form.submit()">
                                <option value="" style="display: none">Выберите тип...</option>
                                <?php foreach ($types as $key => $value): ?>
                                    <option value="<?=$key;?>" <?=isset($_GET['access_type']) && $_GET['access_type']==$key ? 'selected' : null;?>><?=$value;?></option>
                                <?php endforeach; ?>
                            </select>
                            <!--							<button type="submit">Продолжить</button>-->
                        </form>
                        <?php if (isset($_GET['access_type']) && array_key_exists($_GET['access_type'], $types)) : ?>
                        <form method="post" class="d-g gg-1">
                            <input type="hidden" name="request" value="accessCreate">
                            <!--                            <input type="hidden" name="user" value="--><?//= $this->Fw->Account->getCurrentId(); ?><!--">-->
                            <!--                            <input type="hidden" name="project" value="--><?//= $this->data['id'] ?><!--">-->
                            <input type="hidden" name="redirect" value="<?= APP_PREFIX; ?>/accesses">

                            <?php
                            switch ($_GET['access_type'])
                            {
                                case "":
                                    break;
                                case "provider":
                                    ?>
                                    <input type="hidden" name="access_type" value="<?=$_GET['access_type'];?>">
                                    <input type="text" class="w-100p" name="link" placeholder="Ссылка" required>
                                    <input type="text" class="w-100p" name="login" placeholder="Логин" required>
                                    <input type="text" class="w-100p" name="password" placeholder="Пароль" required>
                                    <?php
                                    break;
                                case "ftp": case "sftp":
                                    ?>
                                    <input type="hidden" name="access_type" value="<?=$_GET['access_type'];?>">
                                    <input type="text" class="w-100p" name="host" placeholder="Сервер" required>
                                    <input type="text" class="w-100p" name="port" placeholder="Порт">
                                    <input type="text" class="w-100p" name="login" placeholder="Логин" required>
                                    <input type="text" class="w-100p" name="password" placeholder="Пароль" required>
                                    <?php
                                    break;
                                case "sql":
                                    ?>
                                    <input type="hidden" name="access_type" value="<?=$_GET['access_type'];?>">
                                    <input type="text" class="w-100p" name="link" placeholder="phpMyAdmin (ссылка)" required>
                                    <input type="text" class="w-100p" name="host" placeholder="Сервер" value="localhost" required>
                                    <input type="text" class="w-100p" name="login" placeholder="Логин" required>
                                    <input type="text" class="w-100p" name="password" placeholder="Пароль" required>
                                    <input type="text" class="w-100p" name="database" placeholder="База данных" required>
                                    <?php
                                    break;
                                case "mail":
                                    ?>
                                    <input type="hidden" name="access_type" value="<?=$_GET['access_type'];?>">
                                    <input type="text" class="w-100p" name="link" placeholder="Cсылка" required>
                                    <input type="text" class="w-100p" name="login" placeholder="Email" required>
                                    <input type="text" class="w-100p" name="password" placeholder="Пароль" required>
                                    <?php
                                    break;

                                default:
                                    ?>
                                    <input type="hidden" name="access_type" value="other">
                                    <input type="text" class="w-100p" name="link" placeholder="Cсылка" required>
                                    <input type="text" class="w-100p" name="login" placeholder="Логин" required>
                                    <input type="text" class="w-100p" name="password" placeholder="Пароль" required>
                                    <?php
                                    break;

                            }
                            ?>
                            <textarea name="description" rows="3" placeholder="Комментарии"></textarea>
                            <div>
                                <?php foreach ($otherProjects as $key => $item) : ?>
                                    <div>
                                        <input
                                                type="checkbox"
                                                id="checkbox-<?=$key;?>"
                                                name="projects[]"
                                                value="<?=$item['id'];?>"
                                                <?=($key==0) ? 'checked' : null;?>
                                        >
                                        <label for="checkbox-<?=$key;?>"><?=$item['title'];?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="submit">Создать</button>
                        </form>
                        <?php endif; ?>
                    </div>

				</div>

			</div>
		</div>
	</div>
</section>