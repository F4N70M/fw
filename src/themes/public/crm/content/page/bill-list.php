<section class="bg-b-5 c-b">
    <div class="limit">
        <div class="part">
            <h1 class="ta-c">Счета</h1>
        </div>
        <div class="part">
            <div class="part-min">
                <div class="d-g gg-1 gaf-c jc-sb">
                    <div>
                        <a href="<?= APP_PREFIX; ?>/bill-new" class="btn bg-g-50 bdrs-1">Создать счет</a>
                    </div>
                </div>
            </div>
            <div class="part-min">
                <?php
                $bills = $this->Fw->Db->select()->from('objects')->where(['type'=>'bill'])->result();
                usort($bills, function ($a, $b) {
                    if ($a['title'] == $b['title']) return 0;
                    return $a['title'] < $b['title'] ? 1 : -1;
                });
                $users = [];
                ?>
                <?php if(is_array($bills) && count($bills) > 0) : ?>
                    <table width="100%" border="1" cellspacing="0" cellpadding="8">
                        <thead class="bg-g-50 fw-b">
                            <tr>
                                <td>№ счета</td>
                                <td>Дата</td>
                                <td>Услуг</td>
                                <td>На сумму</td>
                                <td>Плательщик</td>
                                <td>Получатель</td>
                                <td>Счет</td>
                            </tr>
                        </thead>
                        <tbody class="bg-w">
                            <?php foreach ($bills as $key => $item): ?>
                                <tr>

                                    <?php
                                    $data = json($item['content'], false);
                                    $sum = 0;
                                    $count = count($data['services']);
                                    foreach ($data['services'] as $service)
                                    {
                                        $sum += (is_numeric($service['price'])) ? $service['price'] : 0;
                                    }
                                    ?>
                                    <td><?=$item['title'];?></td>
                                    <td><?=$data['date'];?></td>
                                    <td><?=$count;?></td>
                                    <td><?=$sum;?></td>
                                    <td><?=$data['from']['name'];?></td>
                                    <td><?=$data['to']['name'];?></td>
                                    <td>
                                        <a href="javascript: w=window.open('<?=APP_PREFIX;?>/bill-<?=$item['id'];?>-print'); w.print(); setTimeout(function() {w.close()},1000);" class="c-2">Счет</a>
<!--                                        <a href="" target="_blank">Счет</a>-->
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="c-g ta-c">Нет долгов</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>