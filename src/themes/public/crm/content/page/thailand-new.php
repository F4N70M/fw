<?php
$months = [
	'01'=>'январь','02'=>'февраль','03'=>'март',
	'04'=>'апрель','05'=>'май','06'=>'июнь',
	'07'=>'июль','08'=>'август','09'=>'сентябрь',
	'10'=>'октябрь','11'=>'ноябрь','12'=>'декабрь'
];

//$users = $this->Fw->UserManager->get(['type'=>'admin']);
$users = $this->Fw->Db->select(['id','name'])->from('users')->where(['type'=>'admin'])->result();
?>
<section>
	<div class="limit">
		<div class="part">
			<div class="part-min">
				<h1 class="ta-c">Новый платеж на Тайланд</h1>
			</div>
			<div class="part-min">
				<div>
					<form method="post" class="d-g gg-1" enctype="multipart/form-data">
						<input type="hidden" name="request" value="thailandNew">
						<input type="hidden" name="redirect" value="<?=APP_PREFIX;?>/thailand">

						<select name="user" class="w-100p" required>
							<option value="" style="display: none">Пользователь *</option>
							<?php foreach ($users as $user): ?>
								<option value="<?=$user['id'];?>"><?=$user['name'];?></option>
							<?php endforeach; ?>
						</select>

						<select name="date" class="w-100p" required>
							<option value="" style="display: none">Месяц *</option>
							<?php foreach ($months as $month => $monthName): ?>
								<option value="2020-<?=$month;?>-01"><?=$monthName;?></option>
							<?php endforeach; ?>
						</select>

						<input type="number" class="w-100p" name="value" placeholder="Сумма *" required>

						<button type="submit">Создать</button>
					</form>
				</div>

			</div>
		</div>
	</div>
</section>
