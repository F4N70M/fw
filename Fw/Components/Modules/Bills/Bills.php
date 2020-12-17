<?php


namespace Fw\Components\Modules\Bills;

use Fw\Components\Services\Database\QueryBuilder;

class Bills
{
    protected $db;

    public function __construct(QueryBuilder $db)
    {
        $this->db = $db;
    }

    public function new(array $services, array $payer, array $customer)
    {
        ob_start();
        $total = 0;
        ?>
            <h3 style="text-align: center;"><?=$payer['name'];?></h3>

            <p>Адрес: <?=$payer['legal-address']?></p>
            <p>Образец заполнения платежного поручения</p>

            <table border="1" cellpadding="8" width="100%">
                <tr>
                    <td>ИНН <?=isset($payer['correspondent-account']) && !empty($payer['correspondent-account']) ? $payer['correspondent-account'] : 'не указан'?></td>
                    <td>КПП <?=isset($payer['inn']) && !empty($payer['inn']) ? $payer['inn'] : 'не указан'?></td>
                    <td rowspan="2">№ счёта</td>
                    <td rowspan="2"><?=$payer['checking-account']?></td>
                </tr>
                <tr>
                    <td colspan="2">ИП МОДЕБАДЗЕ ГЕОРГИЙ СЛАВОВИЧ</td>
                </tr>
                <tr>
                    <td colspan="2" rowspan="2">Банк получателя </td>
                    <td>Бик</td>
                    <td><?=$payer['bik']?></td>
                </tr>
                <tr>
                    <td>К/с</td>
                    <td></td>
                </tr>
            </table>

            <h1 style="text-align: center;">Счет № 356 от 30.05.2020</h1>

            <p>Заказчик: <?=$customer['payer']?>, <?=$payer['legal-address']?></p>
            <p>Плательщик: <?=$payer['payer']?>, <?=$payer['legal-address']?></p>

            <table border="1" cellpadding="8" width="100%">
                <thead style="background-color: #ccc; font-weight: bold;">
                <tr>
                    <td>№</td>
                    <td>Наименование</td>
                    <td>Ед.</td>
                    <td>Кол-во</td>
                    <td>Цена</td>
                    <td>Сумма</td>
                </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($services as $key => $service) : ?>
                        <tr>
                            <td><?=$i;?></td>
                            <td><?=$service['title'];?></td>
                            <td><?=$service['unit'];?></td>
                            <td><?=$service['count'];?></td>
                            <td><?=$service['price'];?></td>
                            <td><?=($sum = $service['price'] * $service['count']);?></td>
                        </tr>
                        <?php $total += $sum;?>
                    <?php
                    $i++;
                    endforeach; ?>
                    <tr>
                        <td colspan="5" style="text-align: right;">Итого</td>
                        <td><?=$total;?></td>
                    </tr>
                    <tr>
                        <td colspan="5" style="text-align: right;">НДС</td>
                        <td>–</td>
                    </tr>
                    <tr>
                        <td colspan="5" style="text-align: right;">Всего к оплате</td>
                        <td><?=$total;?></td>
                    </tr>
                </tbody>
            </table>
            <p>Всего наименований <?=count($services);?>, на сумму двенадцать тысяч девятьсот рублей 00 копеек</p>
            <p style="text-align: right;"><?=$payer['name']?></p>
            <br><br><br><br><br>
            <p style="text-align: right;">319703100088212 от 09.09.2019</p>
            <p style="text-align: right;">Межрайонной инспекцией Федеральной налоговой службы № 7 по Томской области</p>
        <?
        $content = ob_get_clean();
        $lastNumber = ($request = $this->db->select(['id','title','type'])->from('objects')->where(['type'=>'bill'])->orderBy(['title'=>false])->result()) ? ((int) $request[0]['title']) + 1 : 1;
//        debug($request);
        $data = [
            'type'  =>  'bill',
            'title' =>  $lastNumber,
            'name'  =>  $this->db->getUniqueName('objects','bill'),
            'content'   =>  $content
        ];

        $id = $this->db->insert()->into('objects')->values($data)->result();
        return $id;
    }
}