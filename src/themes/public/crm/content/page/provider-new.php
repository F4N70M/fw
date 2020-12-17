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
$list = $this->Fw->ProjectManager->get();


$projects = [];
foreach ($list as $item)
{
	$projects[$item['user']][$item['id']] = $item;
}


$project = $this->Fw->Project($this->data['id']);
//$userId = $project->get('user');
$accesses = $this->Fw->AccessManager->get(['project'=>$this->data['id']]);
//debug($accesses);
?>
<section>
	<div class="limit">
		<div class="part">
			<div class="part-min">
				<h1 class="ta-c">Добавить провайдера услуг</h1>
			</div>
			<div class="part-min">
				<div class="maxw-20 m-a">
					<form method="post" class="d-g gg-1">
						<input type="hidden" name="request" value="providerNew">
						<input type="hidden" name="user" value="<?=$this->data['id'];?>">
						<input type="hidden" name="redirect" value="<?= APP_PREFIX; ?>">

						<select name="project" class="w-100p" required>
							<option value="" style="display: none">Проект *</option>
							<?php
							$clients = $this->Fw->ClientManager->get(['type'=>'client']);
							//                            debug($clients);
							?>
							<?php foreach ($clients as $client): ?>
								<optgroup label="<?=$client['name'];?>">
									<?php foreach ($this->Fw->ProjectManager->get(['user'=>$client['id']]) as $item): ?>
										<option value="<?=$item['id'];?>" <?=$this->data['id'] == $item['id'] ? 'selected' : '';?>><?=$item['title'];?></option>
									<?php endforeach; ?>
								</optgroup>
							<?php endforeach; ?>
						</select>

						<input type="text" name="title" class="w-100p" placeholder="Название *" required>
                        <input type="text" name="link" class="w-100p" placeholder="Ссылка *" value="https://" required>

                        <div class="ta-c">Личный кабинет</div>
                        <input type="text" name="link-lk" class="w-100p" placeholder="Ссылка ЛК (если есть)">
						<input type="text" name="login" class="w-100p" placeholder="Логин *" required>
						<input type="text" name="password" class="w-100p" placeholder="Пароль *" required>

						<button type="submit">Продолжить</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>