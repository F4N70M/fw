<?php
$user = $this->Fw->Account->getCurrentId();
$value = 1000;
$date = '2020-01-01';
//$this->Fw->ValueManager->new(['type'=>'thailand','user'=>$user,'date'=>$date,'value'=>$value]);
//$vars = $this->Fw->ValueManager->get(['type'=>'thailand']);
$vars = $this->Fw->Db->select(['id','type','value','user','date'])->from('vars')->where(['type'=>'thailand'])->result();
//debug($vars);
$values = [];
$sum = [];
$total = 0;
foreach ($vars as $var)
{
	$month = date('m',strtotime($var['date']));
	$userId = $var['user'];
	$value = $var['value'];
	if (!isset($values[$month][$userId])) $values[$month][$userId] = 0;
	$values[$month][$userId] += $value;
	if (!isset($sum[$userId])) $sum[$userId] = 0;
	$sum[$userId] += $value;
	$total += $value;
}

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
				<a href="<?= APP_PREFIX; ?>/thailand-new" class="btn bg-g-50 bdrs-1">Добавить платеж</a>
		</div>
		<div class="part">
			<table width="100%" border="1" cellspacing="0" cellpadding="8" class="ta-c">
				<thead class="bg-g-50 fw-b">
					<tr>
						<td width="25%">Дата</td>
						<?php foreach ($users as $user): ?>
							<td width="25%"><?=$user['name'];?></td>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($months as $month => $monthName): ?>
						<tr>
							<td><?=$monthName;?></td>
							<?php foreach ($users as $user): ?>
								<?php
								$userId = $user['id'];
								$value = isset($values[$month][$userId]) ? (!empty($values[$month][$userId]) ? $values[$month][$userId] : '–') : '–';
								?>
								<td><?=$value;?></td>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
					<tr class="fw-b">
						<td>Итого:</td>
						<?php foreach ($users as $user): ?>
							<?php
							$userId = $user['id'];
							$value = isset($sum[$userId]) ? (!empty($sum[$userId]) ? $sum[$userId] : 0) : 0;
							?>
							<td><?=$value;?></td>
						<?php endforeach; ?>
					</tr>
					<tr class="fw-b">
						<td>Общее:</td>
						<td colspan="3"><?=$total;?></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</section>
