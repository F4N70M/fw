<?php
$clients = $this->Fw->Db->select()->from('users')->where(['type'=>'client'])->result('id');
//debug($_POST);

$requisites = [];

$clientIDs = [];
foreach ( $clients as $key => $item)
{
    $clientIDs[] = $item['id'];
}

$relations = $this->Fw->Db->select()->from('relations')->where(['parent_table'=>'users', 'parent_id'=>['in',$clientIDs], 'child_table'=>'objects'])->result();

$objectIDs = [];
foreach ( $relations as $key => $item)
{
    $objectIDs[$item['parent_id']][] = $item['child_id'];
}

foreach ($objectIDs as $clientID => $reqiusiteIds)
{
    $objectIDs[$item['parent_id']][] = $item['child_id'];
    $resultRequest = $this->Fw->Db->select()->from('objects')->where(['type'=>'requisite', 'id'=>['in',$reqiusiteIds]])->result();
    if ($resultRequest)
    {
        foreach ($resultRequest as $item)
        {
            $requisites[$item['id']] = $clients[$clientID]['name'] . ' – ' . json($item['content'], false)['name'];
        }
    }
}
?>
<section>
    <div class="limit">
        <div class="part">
            <div class="part-min">
                <h1 class="ta-c">Создать счет</h1>
            </div>
            <div class="part-min">
                <div>
                    <form method="post" class="d-g gg-2" enctype="multipart/form-data">
                        <input type="hidden" name="request" value="billNew">
                        <input type="hidden" name="redirect" value="<?=APP_PREFIX;?>/bills">

                        <div class="d-g gg-05">
                            <lebal for="from">Плательщик:</lebal>
                            <select id="from" name="from" class="w-100p" required>
                                <option value="" style="display: none">Выберите из списка *</option>
                                <?php foreach ($requisites as $id => $title): ?>
                                    <option value="<?=$id;?>"><?=$title;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="d-g gg-05">
                            <lebal for="to">Получатель:</lebal>
                            <select name="to" class="w-100p" required>
                                <option value="" style="display: none">Выберите из списка *</option>
                                <?php foreach ($requisites as $id => $title): ?>
                                    <option value="<?=$id;?>"><?=$title;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="d-g gg-05">
                            <label>Услуги:</label>
                            <div class="pl-2 d-g gg-1">
                                <div class="serviceItem d-g gg-1" style="grid-template-columns: 1fr 9rem;">
                                    <textarea name="service[title][]" rows="1" placeholder="Услуга"></textarea>
    <!--                                <select class="w-100p" type="number" name="service[unit][]" placeholder="Ед" required>-->
    <!--                                    <option value="" class="d-n">Ед.</option>-->
    <!--                                    <option value="шт.">шт.</option>-->
    <!--                                    <option value="ч.">ч.</option>-->
    <!--                                </select>-->
    <!--                                <input class="w-100p" type="number" name="service[count][]" placeholder="Кол-во" min="1" step="0.01" value="1">-->
                                    <input class="w-100p" type="number" name="service[price][]" placeholder="Стоимость" min="0.0" step="0.01">
                                </div>
                                <div>
                                    <div class="btn copyHtml bg-2" data-copy="serviceItem">Добавить Услугу</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-g gg-05">
                            <lebal for="date">Дата:</lebal>
                            <input id="date" class="w-100p" type="date" name="date" value="<?=date('Y-m-d');?>">
                        </div>

                        <button type="submit">Создать</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>