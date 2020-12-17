<?php



//$executors = $this->Fw->UserManager->get(['type'=>'admin']);
$admins = $this->Fw->Db->select(['id','name'])->from('users')->where(['type'=>'admin'])->result();
$clients = $this->Fw->Db->select(['id','name'])->from('users')->where(['type'=>'client'])->result();
?>
<section>
	<div class="limit">
		<div class="part">
			<div class="part-min">
				<h1 class="ta-c">Новый долг</h1>
			</div>
			<div class="part-min">
				<div>
					<form method="post" class="d-g gg-1" enctype="multipart/form-data">
						<input type="hidden" name="request" value="debtNew">
						<input type="hidden" name="redirect" value="<?=APP_PREFIX;?>">

						<select name="borrower" class="w-100p" required>
							<option value="" style="display: none">Заемщик *</option>
							<?php foreach ($clients as $client): ?>
								<option value="<?=$client['id'];?>"><?=$client['name'];?></option>
							<?php endforeach; ?>
						</select>

						<select name="lender" class="w-100p" required>
							<option value="" style="display: none">Кредитор *</option>
							<?php foreach ($admins as $admin): ?>
								<option value="<?=$admin['id'];?>"><?=$admin['name'];?></option>
							<?php endforeach; ?>
						</select>

						<textarea name="content" rows="6" placeholder="Описание *" required></textarea>

						<input type="number" class="w-100p" name="value" placeholder="Сумма *" min="0.1" step="0.1" required>

						<button type="submit">Создать</button>
					</form>
				</div>

			</div>
		</div>
	</div>
</section>