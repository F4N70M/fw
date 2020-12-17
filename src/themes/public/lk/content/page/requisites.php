<?php
$data = $this->Fw->Account->getCurrent();
$data['requisites'] = json($data['requisites'], false);

//debug($data);

if ($data['requisites']) :
    foreach ($data['requisites'] as $key => $value)
    {
        $data['requisites'][$key] = str_replace('"', "&quot;", $value);
    }
endif;
//debug($data['requisites']);
$inputs = [
    'name'=>'Наименование фирмы',
    'legal-address'=>'Юридический адрес',
    'actual-address'=>'Фактический адрес',
    'general-director'=>'Генеральный директор',
    'phone-fax'=>'Телефон, факс',
    'email'=>'Электронная почта',
    'inn-kpp'=>'ИНН / КПП',
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
                <h1 class="ta-c"><?=$data['name'];?>: Реквизиты </h1>
            </div>
            <div class="part-min">
                <div>
                    <?php if (!array_key_exists('edit', $_GET)) : ?>
                        <div class="part-min">
                            <div class="d-g gg-1">
                                <?php foreach ($inputs as $key => $title) : ?>
                                    <?php if (empty($data['requisites'][$key])) continue; ?>
                                    <div>
                                        <div><?=$title;?>:</div>
                                        <div><?=$data['requisites'][$key];?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="part-min">
                            <a href="?edit" class="btn">Редактировать</a>
                        </div>
                    <?php else:?>
                        <form action="?" method="post" class="d-g gg-1">
                            <input type="hidden" name="request" value="requisites">
                            <input type="hidden" name="user" value="<?=$data['id'];?>">

                            <?php foreach ($inputs as $key => $title) : ?>
                                <div>
                                    <div><?=$title;?>:</div>
                                    <input class="w-100p" type="text" name="requisites[name]" value="<?=$data['requisites'][$key];?>" place-holder="<?=$title;?>" required>
                                </div>
                            <?php endforeach; ?>
                            <button type="submit">Сохранить</button>
                        </form>
                    <?php endif; ?>

                </div>

            </div>
        </div>
    </div>
</section>