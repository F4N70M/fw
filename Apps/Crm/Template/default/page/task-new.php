<?php
$list = $this->Fw->ProjectManager->get();
$projects = [];
foreach ($list as $item)
{
	$projects[$item['user']][$item['id']] = $item;
}
$executors = $this->Fw->UserManager->get(['type'=>'admin']);
?>
<section>
	<div class="limit">
		<div class="part">
			<div class="part-min">
				<h1 class="ta-c">Новая задача</h1>
			</div>
			<div class="part-min">
				<div>
					<form method="post" class="d-g gg-1" enctype="multipart/form-data">
						<input type="hidden" name="request" value="taskNew">
<!--						<input type="hidden" name="user" value="--><?//= $this->Fw->Account->getCurrentId(); ?><!--">-->
<!--						<input type="hidden" name="project" value="--><?//= $data['id']; ?><!--">-->
						<input type="hidden" name="redirect" value="<?=APP_PREFIX;?>">

						<select name="project" class="w-100p">
							<option value="" style="display: none">Проект...</option>
							<?php foreach ($projects as $userId => $items): ?>
								<?php
								$user = $this->Fw->User($userId)->info();
								?>
								<optgroup label="<?=$user['name'];?>">
								<?php foreach ($items as $projectId => $item): ?>
									<option value="<?=$projectId;?>"><?=$item['title'];?></option>
								<?php endforeach; ?>
								</optgroup>
							<?php endforeach; ?>
						</select>
						<input type="text" class="w-100p" name="title" placeholder="Тема" required>
						<textarea name="content" rows="6" placeholder="Подробное описание задачи" required></textarea>

						<select name="executor" class="w-100p">
							<option value="" style="display:none;">Выберите исполнителя</option>
							<?php foreach ($executors as $key => $executor): ?>
								<option value="<?=$executor['id'];?>"><?=$executor['name'];?></option>
							<?php endforeach; ?>
						</select>

						<select name="status" class="w-100p">
							<option value="default">Не срочно</option>
							<option value="quickly">Срочно</option>
							<option value="pause">Пауза</option>
						</select>
                        <input type="file" class="w-100p" name="file" title="Загрузите один файлов">
                        <input type="file" class="w-100p" name="files[]" multiple title="Загрузите несколько файлов">
						<button type="submit">Создать</button>
					</form>
				</div>

			</div>
		</div>
	</div>
</section>