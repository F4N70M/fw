<?php
$clients = $this->Fw->ClientManager->get(['type'=>'client']);
?>

<section>
	<div class="limit part">
        <div class="part-min">
            Клиенты
        </div>
		<div class="part-min">
			<h1 class="ta-c">Клиенты</h1>
		</div>
        <div class="part-min">
            <a href="<?= APP_PREFIX; ?>/new-client" class="btn">Новый клиент</a>
        </div>
		<div class="part-min">
			<?php
			?>
			<div class="d-g gg-1">
				<?php foreach ($clients as $client): ?>
					<a href="<?= APP_PREFIX; ?>/projects-<?= $client['id']; ?>" class="btn"><?= $client['name']; ?></a>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>