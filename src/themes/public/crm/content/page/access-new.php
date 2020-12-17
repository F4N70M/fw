<?php
$types = [
    "provider"  => "Хостинг-провайдер",
    "ftp"   => "FTP",
    "sftp"   => "sFTP",
    "sql"   => "MySQL",
    "ssl"   => "SSL",
    "admin"   => "Админ-панель",
    "mail"  => "Почта",
    "other"  => "Другое"
];
//$user = $this->data['id'];

$otherProjects = $this->Fw->Db->select()->from('objects')->where(['user'=>$this->data['id'], 'type'=>'project'])->result();
//debug($otherProjects);

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
                            <select name="class" class="w-100p" onchange="this.form.submit()">
                                <option value="" style="display: none">Выберите тип...</option>
                                <?php foreach ($types as $key => $value): ?>
                                    <option value="<?=$key;?>" <?=isset($_GET['class']) && $_GET['class']==$key ? 'selected' : null;?>><?=$value;?></option>
                                <?php endforeach; ?>
                            </select>
                            <!--							<button type="submit">Продолжить</button>-->
                        </form>
                        <?php if (isset($_GET['class']) && array_key_exists($_GET['class'], $types)) : ?>
                            <form method="post" class="d-g gg-1">
                                <input type="hidden" name="request" value="accessCreate">
                                <!--                            <input type="hidden" name="user" value="--><?//= $this->Fw->Account->getCurrentId(); ?><!--">-->
                                <!--                            <input type="hidden" name="project" value="--><?//= $this->data['id'] ?><!--">-->
                                <input type="hidden" name="redirect" value="<?= APP_PREFIX; ?>/accesses-<?=$this->data['id'];?>">

                                <?php
                                switch ($_GET['class'])
                                {
                                    case "":
                                        break;
                                    case "provider":
                                    ?>
                                        <input type="hidden" name="class" value="<?=$_GET['class'];?>">
                                        <input type="text" class="w-100p" name="link" placeholder="Ссылка" required>
                                        <input type="text" class="w-100p" name="login" placeholder="Логин" required>
                                        <input type="text" class="w-100p" name="password" placeholder="Пароль" required>
                                        <?php
                                        break;
                                    case "admin":
                                    ?>
                                        <input type="hidden" name="class" value="<?=$_GET['class'];?>">
                                        <input type="text" class="w-100p" name="link" placeholder="Ссылка" value="/admin" required>
                                        <input type="text" class="w-100p" name="login" placeholder="Логин" required>
                                        <input type="text" class="w-100p" name="password" placeholder="Пароль" required>
                                    <?php
                                    break;
                                    case "mail":
                                    ?>
                                        <input type="hidden" name="class" value="<?=$_GET['class'];?>">
                                        <input type="text" class="w-100p" name="link" placeholder="Cсылка" required>
                                        <input type="text" class="w-100p" name="login" placeholder="Email" required>
                                        <input type="text" class="w-100p" name="password" placeholder="Пароль" required>
                                    <?php
                                    break;
                                    case "ftp": case "sftp":
                                    ?>
                                        <input type="hidden" name="class" value="<?=$_GET['class'];?>">
                                        <input type="text" class="w-100p" name="host" placeholder="Сервер" required>
                                        <input type="text" class="w-100p" name="port" placeholder="Порт">
                                        <input type="text" class="w-100p" name="login" placeholder="Логин" required>
                                        <input type="text" class="w-100p" name="password" placeholder="Пароль" required>
                                    <?php
                                    break;
                                    case "sql":
                                        ?>
                                        <input type="hidden" name="class" value="<?=$_GET['class'];?>">
                                        <input type="text" class="w-100p" name="link" placeholder="phpMyAdmin (ссылка)" required>
                                        <input type="text" class="w-100p" name="host" placeholder="Сервер" value="localhost" required>
                                        <input type="text" class="w-100p" name="login" placeholder="Логин" required>
                                        <input type="text" class="w-100p" name="password" placeholder="Пароль" required>
                                        <input type="text" class="w-100p" name="db_name" placeholder="База данных" required>
                                        <?php
                                        break;

                                    default:
                                        ?>
                                        <input type="hidden" name="class" value="other">
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