<?php
//debug($this->data);
$data =
    (
        $resultRequest = $this->Fw->Db->select()->from('objects')->where(['id'=>$this->data['id'], 'type'=>'requisite'])->result()
    )
        ? $resultRequest[0]
        : false;
$data['content'] = json($data['content'], false);

$user =
    (
    $resultRequest = $this->Fw->Db
        ->select()
        ->from('relations')
        ->where([
            'parent_table'  =>  'users',
            'child_table'   =>  'objects',
            'child_id'      =>  $this->data['id']
        ])
        ->result()
    )
        ? $resultRequest[0]
        : false;
//debug($user['parent_id']);

if ($data['content']) :
    foreach ($data['content'] as $key => $value)
    {
        $data['content'][$key] = str_replace('"', "&quot;", $value);
    }
endif;
//debug($data['requisites']);
$types = [
    'company'   =>  'Юр. лицо',
    'sole'      =>  'ИП',
    'person'    =>  'Физ. лицо'
];
$inputs = [
    'name'=>'Наименование фирмы',
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
                <h1 class="ta-c">Реквизиты </h1>
            </div>
            <div class="part-min">
                <div>
                    <form action="?" method="post" class="d-g gg-1">
                        <input type="hidden" name="request" value="requisiteEdit">
                        <input type="hidden" name="id" value="<?=$this->data['id'];?>">
                        <input type="hidden" name="redirect" value="<?=APP_PREFIX;?>/requisites-<?=$user['parent_id'];?>">

                        <div>
                            <div>Тип:</div>
                            <select name="requisites[type]" class="w-100p">
                                <?php foreach ($types as $key => $title) : ?>
                                    <option value="<?=$key;?>" <?=$data['content']['type'] == $key ? 'selected' : null;?>><?=$title;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <?php foreach ($inputs as $key => $title) : ?>
                            <div>
                                <div><?=$title;?>:</div>
                                <input class="w-100p" type="text" name="requisites[<?=$key;?>]" value="<?=$data['content'][$key];?>" place-holder="<?=$title;?>">
                            </div>
                        <?php endforeach; ?>
                        <button type="submit">Сохранить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>




