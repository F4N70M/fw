<?php
$project = $this->Fw->Project($this->data['id']);
$user = $this->Fw->User($this->Fw->Account->getCurrentId());
?>
<section>
	<div class="limit">
		<div class="part">
			<div class="part-min">
				<h1 class="ta-c">Добавить файл</h1>
			</div>
			<div class="part-min">
				<div>
					<form method="post" class="d-g gg-1" enctype="multipart/form-data">
						<input type="hidden" name="request" value="taskAddFiles">
						<input type="hidden" name="user" value="<?= $this->Fw->Account->getCurrentId(); ?>">
						<input type="hidden" name="task" value="<?= $this->data['id']; ?>">
						<input type="hidden" name="redirect" value="<?=APP_PREFIX;?>/task-<?=$this->data['id'];?>">
						<div>
							<input type="file" class="w-100p" id="files" name="files[]" multiple title="Выбрать файлы" required>
						</div>
						<button type="submit">Добавить</button>
					</form>
				</div>

			</div>
		</div>
	</div>
</section>