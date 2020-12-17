<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr>
        <td>ИНН <?=isset($data['to']['inn']) && !empty($data['to']['inn']) ? $data['to']['inn'] : 'не указан'?></td>
        <td>КПП <?=isset($data['to']['kpp']) && !empty($data['from']['kpp']) ? $data['to']['kpp'] : 'не указан'?></td>
        <td rowspan="2">№ счёта</td>
        <td rowspan="2"><?=$data['to']['checking-account']?></td>
    </tr>
    <tr>
        <td colspan="2"><?=$data['to']['name']?></td>
    </tr>
    <tr>
        <td colspan="2" rowspan="2">Банк получателя <br> <?=isset($data['to']['bank']) && !empty($data['to']['bank']) ? $data['to']['bank'] : 'не указан'?></td>
        <td>Бик</td>
        <td><?=$data['from']['bik']?></td>
    </tr>
    <tr>
        <td>К/с</td>
        <td><?=isset($data['to']['correspondent-account']) && !empty($data['to']['correspondent-account']) ? $data['to']['correspondent-account'] : 'не указан'?></td>
    </tr>
</table>

<h1 style="text-align: center;">Счет № <?=$data['id'];?> от <?=date('d.m.Y',strtotime($data['date']));?></h1>

<p><strong>Заказчик</strong>: <?=$data['to']['name']?>, <?=$data['to']['legal-address']?></p>
<p><strong>Плательщик</strong>: <?=$data['from']['name']?>, <?=$data['from']['legal-address']?></p>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <thead style="background-color: #ccc; font-weight: bold;">
    <tr>
        <td width="24" style="text-align:right;">№</td>
        <td>Наименование услуги</td>
        <td width="15%" style="text-align:right;">Стоимость</td>
    </tr>
    </thead>
    <tbody>
    <?php
    $i = 1;
    ?>
    <?php foreach ($data['services'] as $key => $service) : ?>
        <tr>
            <td style="text-align:right;"><?=$i;?></td>
            <td><?=$service['title'];?></td>
            <td style="text-align:right;"><?=(is_numeric($service['price'])) ? $service['price'] : 0;?></td>
        </tr>
        <?php
        $total += (is_numeric($service['price'])) ? $service['price'] : 0;
        $i++;
        ?>
    <?php endforeach; ?>
    <tr style="font-weight: bold;">
        <td colspan="2" style="text-align: right;">Итого</td>
        <td style="text-align:right;"><?=$total;?></td>
    </tr>
    <?php /*
        <tr>
            <td colspan="2" style="text-align: right;">НДС</td>
            <td>–</td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: right;">Всего к оплате</td>
            <td><?=$total;?></td>
        </tr>
        */ ?>
    </tbody>
</table>
<p>Всего наименований <?=count($data['services']);?>, на сумму <?=str_price($total);?></p>
<br><br>
<p style="display:grid; grid-gap: .5rem; grid-template-columns: auto 1fr auto;">
    <strong>Предприниматель:</strong>
    <span style="border-bottom: solid 1px black;"></span>
    <span><?=$data['to']['payer']?></span>
</p>