<?php
$types = [
    "hosting"  => "Хостинг",
    "domen"   => "Домен",
    "sql"   => "SSL сертификат",
    "other"  => "Другое"
];
$periods = [
    "1"  => "1 месяц",
    "2"   => "2 месяца",
    "3"   => "3 месяца",
    "6"  => "6 месяцев",
    "12"  => "1 год",
    "24"  => "2 года"
];


$otherProjects = $this->Fw->Db
    ->select()
    ->from('objects')
    ->where([
        'user'=>$this->data['id'],
        'type'=>'project'
    ])
    ->result();


$projects = [];
$projectIds = [];
foreach ($otherProjects as $otherProject)
{
    $projects[$otherProject['id']] = $otherProject;
    $projectIds[] = $otherProject['id'];
}

$relations = [];

if (!empty($projectIds))
{
    $relations = $this->Fw->Db
        ->select()
        ->from('relations')
        ->where([
            'parent_table' => 'objects',
            'parent_id' => ['in', $projectIds],
            'child_table' => 'objects'
        ])
        ->result();
}

$accesses = [];
foreach ($relations as $relation)
{
    $access = $this->Fw->Db->select()->from('objects')->where(['type'=>'access', 'id'=>$relation['child_id']])->result();
    if ($access)
        $accesses[$access[0]['id']] = $access[0];
}
//debug($accesses);

?>
<section>
    <div class="limit">
        <div class="part">
            <div class="part-min">
                <h1 class="ta-c">Добавить Услугу</h1>
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
                                <input type="hidden" name="request" value="serviceCreate">
                                <input type="hidden" name="redirect" value="<?= APP_PREFIX; ?>/services-<?=$this->data['id'];?>">

                                <?php
                                switch ($_GET['class'])
                                {
                                    case "hosting": case "domen": case "sql":
                                        ?>
                                        <input type="hidden" name="class" value="<?=$_GET['class'];?>">
                                        <?php
                                        break;

                                    default:
                                        ?>
                                        <input type="hidden" name="class" value="other">
                                        <?php
                                        break;

                                }
                                ?>
                                <input type="text" class="w-100p" name="title" placeholder="Наименование *" required>
                                <input type="number" name="payment[value]" class="w-100p" step="0.01" placeholder="Стоимость *" required>
                                <input type="date" name="payment[date_start]" class="w-100p" placeholder="Дата начала периода" required>
                                <input type="date" name="payment[date_end]" class="w-100p" placeholder="Дата окончания периода" required>

                                <select name="value" class="w-100p">
                                    <option value="" style="display: none">Выберите доступ...</option>
                                    <?php foreach ($accesses as $key => $access): ?>
                                        <option value="<?=$access['id'];?>"><?=$access['link'];?> – <?=$access['login'];?></option>
                                    <?php endforeach; ?>
                                </select>

                                <select name="status" class="w-100p">
                                    <option value="1">Отслеживать</option>
                                    <option value="0">Не отслеживать</option>
                                </select>

                                <?php /*
                                <div>
                                    <p>Выберите связанные проекты:</p>
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
                                */ ?>

                                <button type="submit">Создать</button>
                            </form>
                        <?php endif; ?>
                    </div>

                </div>

            </div>
        </div>
    </div>
</section>



























