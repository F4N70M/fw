<?php
$types = [
    'company'   =>  'Юр. лицо',
    'sole'      =>  'ИП',
    'person'    =>  'Физ. лицо'
];
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
?>
<section>
    <div class="limit">
        <div class="part">
            <div class="part">
                <h1 class="ta-c">Добавить реквизиты </h1>
            </div>
            <div class="part-min">
                <div>
                    <form action="?" method="post" class="d-g gg-1">
                        <input type="hidden" name="request" value="requisiteNew">
                        <input type="hidden" name="user" value="<?=$this->data['id'];?>">
                        <input type="hidden" name="redirect" value="requisites-<?=$this->data['id'];?>">

                        <div>
                            <div>Тип:</div>
                            <select name="type" class="w-100p">
                                <?php foreach ($types as $key => $title) : ?>
                                    <option value="<?=$key;?>"><?=$title;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <?php foreach ($inputs as $key => $title) : ?>
                            <div>
                                <div><?=$title;?>:</div>
                                <input class="w-100p" type="text" name="requisites[<?=$key;?>]">
                            </div>
                        <?php endforeach; ?>
                        <button type="submit">Сохранить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>




