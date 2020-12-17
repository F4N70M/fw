<?php
/**
 * Treat: F4N70M
 * Version: 0.1
 * Date: 03.02.2020
 */
?>

<?php
$im = $this->data['id'] == $this->Fw->Account->getCurrentId();
$projects = $this->Fw->ProjectManager->get(['user'=>$this->data['id']]);

$breadcrumbs = [

];
if (!$im)
{
	$user = $this->Fw->Client($this->data['id']);
	$breadcrumbs = [''  => 'Клиенты'] + $breadcrumbs;
}
?>

<section>
	<div class="limit part">
        <div class="part-min">
			<?php
			foreach ($breadcrumbs as $link => $title) : ?>
                <a href="<?=APP_PREFIX.$link;?>"><?=$title;?></a>
                    >
			<?php endforeach; ?>
            <?php
            if (!$im)
            {
	            $user = $this->Fw->Client($this->data['id']);
	            echo $user->get('name');
            }
            else
            {
	            echo 'Проекты';
            }
            ?>
        </div>
        <div class="part-min">
            <h1 class="ta-c">Проекты</h1>
        </div>
        <div class="part-min">
            <a href="<?= APP_PREFIX; ?>/new-project-<?=$this->data['id'];?>" class="btn">Создать проект</a>
        </div>
		<div class="part-min">
			<?php
			?>
			<div class="d-g gg-1">
				<?php foreach ($projects as $project): ?>
					<a href="<?= APP_PREFIX; ?>/project-<?= $project['id']; ?>" class="btn"><?= $project['title']; ?></a>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>