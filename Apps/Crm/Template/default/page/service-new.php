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
$project = $this->Fw->Project($data['id']);
//$userId = $project->get('user');
$accesses = $this->Fw->AccessManager->get(['project'=>$data['id']]);
//debug($accesses);
?>
<section>
	<div class="limit">
		<div class="part">
			<div class="part-min">
				<h1 class="ta-c">Добавить услугу</h1>
			</div>
			<div class="part-min">
				<div class="maxw-20 m-a">
					<form method="post" class="d-g gg-1">
                        <input type="hidden" name="request" value="serviceNew">
                        <input type="hidden" name="user" value="<?=$data['id'];?>">
                        <input type="hidden" name="redirect" value="<?= APP_PREFIX; ?>">

						<select name="type" class="w-100p" required>
							<option style="display: none;" value="">Тип *</option>
							<?php foreach ($types as $key => $value): ?>
								<option value="<?=$key;?>"><?=$value;?></option>
							<?php endforeach; ?>
						</select>
                        <input type="text" name="title" class="w-100p" placeholder="Название *" required>
                        <select name="period" class="w-100p" required>
                            <option style="display: none;" value="">Период оплаты *</option>
							<?php foreach ($periods as $key => $value): ?>
                                <option value="<?=$key;?>"><?=$value;?></option>
							<?php endforeach; ?>
                        </select>
                        <input type="number" name="cost" class="w-100p" step="0.01" placeholder="Стоимость *" required>
                        <input type="date" name="end-date" class="w-100p" placeholder="Дата окончания" required>
                        <select name="access" class="w-100p" required>
                            <option style="display: none;" value="">Доступ *</option>
                            <option value="">Нет доступа</option>
							<?php foreach ($accesses as $key => $item): ?>
                                <option value="<?=$item['id'];?>"><?=$item['accessType'].' '.$item['title'].' '.$item['login'];?></option>
							<?php endforeach; ?>
                        </select>
                        <button type="submit">Продолжить</button>
					</form>