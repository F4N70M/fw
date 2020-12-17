<?php
$user =
    (
    $resultRequest = $this->Fw->Db
        ->select()
        ->from('users')
        ->where([
            'id'  =>  $this->data['id']
        ])
        ->result()
    )
        ? $resultRequest[0]
        : false;

$relations = $this->Fw->Db->select()
            ->from('relations')
            ->where(['parent_table'=>'users','parent_id'=>$this->data['id'],'child_table'=>'objects'])
            ->result();

$relationIDs = [];
foreach ($relations as $relation)
{
    $relationIDs[] = $relation['child_id'];
}
$requisites = $this->Fw->Db
    ->select()
    ->from('objects')
    ->where(['type'=>'requisite','id'=>['in',$relationIDs]])
    ->result();
//debug($_POST);
//debug($relationIDs);
//debug($requisites);
$inputs = [
    'name'=>'Полное наименование',
    'legal-address'=>'Юридический адрес',
    'actual-address'=>'Фактический адрес',
    'general-director'=>'Генеральный директор',
    'phone-fax'=>'Телефон, факс',
    'email'=>'Электронная почта',
    'inn'=>'ИНН',
    'kpp'=>'КПП',
    'okved'=>'Код отрасли по ОКВЭД',
    'okpo'=>'Код организации по ОКПО',
    'okfs'=>'Код отрасли по ОКФС',
    'okopf'=>'Код отрасли по ОКОПФ',
    'ogrn'=>'ОГРН',
    'ifts'=>'Код ИФНС',
    'payer'=>'Получатель/Плательщик',
    'bank'=>'Полное наименование банка',
    'checking-account'=>'Расчетный счет',
    'correspondent-account'=>'Корреспондентский счет',
    'bik'=>'БИК'
];
//debug($_SERVER);
?>
<section>
    <div class="limit">
        <div class="part">
            <div class="part">
                <h1 class="ta-c"><?=$user['name'];?>: Реквизиты </h1>
            </div>
            <div class="part-min">
                <a href="./requisite-<?=$this->data['id'];?>-new" class="popup-link btn bg-g-50">Добавить реквизиты</a>
            </div>
            <div class="part-min">
                <div class="d-g gg-2 gtc-2">
                    <?php foreach ($requisites as $requisite) : ?>
                        <div class="d-g gg-1 p-1 bg-w bsh-05" style="grid-template-rows: 1fr auto">
                            <div>
                                <?php
                                $data = json($requisite['content'], false);
                                ?>
                                <div class="d-g gg-1">
                                    <?php foreach ($inputs as $key => $title) : ?>
                                        <?php if (empty($data[$key])) continue; ?>
                                        <div>
                                            <div><?=$title;?>:</div>
                                            <div><?=$data[$key];?></div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="d-g gg-1 gtc-2">
                                <a href="./requisite-<?=$requisite['id'];?>-edit" class="popup-link btn bg-g-50">Редактировать</a>

                                <form action="?" method="post" class="d-g gg-1">
                                    <input type="hidden" name="request" value="requisiteDel">
                                    <input type="hidden" name="id" value="<?=$requisite['id'];?>">
                                    <input type="hidden" name="redirect" value="requisites-<?=$this->data['id'];?>">
                                    <button type="submit" class="bg-g-50">Удалить</button>
                                </form>
<!--                                <a href="./requisite---><?//=$requisite['id'];?><!---del" class="btn">Удалить</a>-->
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>



