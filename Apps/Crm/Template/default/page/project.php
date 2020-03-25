<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 05.03.2020
 */
?>

<?php
$project = $this->Fw->Project($data['id']);
$projectUser = $project->get('user');
$im = $projectUser == $this->Fw->Account->getCurrentId();

$breadcrumbs = [
	'' => 'Проекты'
];
if (!$im)
{
	$user = $this->Fw->Client($projectUser);
	array_shift($breadcrumbs);
	$breadcrumbs = [''  => 'Клиенты','/projects-'.$user->get('id') => $user->get('name')] + $breadcrumbs;
}
?>

<section>
    <div class="limit">
        <div class="part">
            <div class="part-min">
		        <?php
//		        $i = 1;
		        foreach ($breadcrumbs as $link => $title) : ?>
                    <a href="<?=APP_PREFIX.$link;?>"><?=$title;?></a>
<!--			        --><?php //if ($i < count($breadcrumbs)) : ?>
                        >
<!--			        --><?php //endif; $i++; ?>
		        <?php endforeach; ?>
                <?=$project->get('title');?>
            </div>
            <div class="part-min">
                <div class="d-g gaf-c jc-sb gg-1">
                    <div>
                        <h1>Проект: "<?=$project->get('title');?>"</h1>
                    </div>
                    <div class="d-g gaf-c jc-sb gg-1">
                        <div><a href="<?= APP_PREFIX; ?>/accesses-<?= $data['id']; ?>" class="btn">Доступы</a></div>
                        <form method="post">
                            <input type="hidden" name="request" value="projectDelete">
                            <input type="hidden" name="id" value="<?= $data['id']; ?>">
                            <input type="hidden" name="redirect" value="<?= APP_PREFIX; ?>">
                            <button type="submit">Удалить проект</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="limit">
        <div class="part">
            <div class="part-min">
                <a href="<?= APP_PREFIX; ?>/new-ticket-<?= $data['id']; ?>" class="btn">Создать обращение</a>
            </div>
            <div class="part-min">
                <h2>Мои обращения</h2>
            </div>
            <div class="part-min">
                <div class="d-g gg-1">
	                <?php
	                $tickets = $this->Fw->TicketManager->get(['project'=>$project->get('id')]);
	                if (is_array($tickets))
	                {
		                foreach ($tickets as $ticket) :
			                ?>
                            <a href="ticket-<?= $ticket['id']; ?>" class="btn"><?= $ticket['title']; ?></a>
		                <?php
		                endforeach;
	                }
	                ?>
                </div>
            </div>
        </div>
    </div>
</section>



