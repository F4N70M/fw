<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 05.03.2020
 */
?>

<?php
$ticket = $this->Fw->Ticket($this->data['id']);
//debug($treat);

$project = $this->Fw->Project($ticket->get('project'));
$im = $project->get('user') == $this->Fw->Account->getCurrentId();
$breadcrumbs = [
	'' => 'Проекты',
	'/project-'.$project->get('id') => $project->get('title'),
];
if (!$im)
{
	$user = $this->Fw->Client($project->get('user'));
	array_shift($breadcrumbs);
	$breadcrumbs = [''  => 'Клиенты','/projects-'.$user->get('id') => $user->get('name')] + $breadcrumbs;
}
?>

<section>
	<div class="limit">
		<div class="part">
            <div class="part-min">
				<?php
				foreach ($breadcrumbs as $link => $title) : ?>
                    <a href="<?=APP_PREFIX.$link;?>"><?=$title;?></a>
                    >
				<?php endforeach; ?>
                Обращение "<?=$ticket->get('title');?>"
            </div>
			<div class="part-min">
				<div class="d-g gaf-c jc-sb">
					<div>
						<h1>Обращение</h1>
						<h2>Тема: "<?=$ticket->get('title');?>"</h2>
					</div>
					<div>
						<form method="post">
							<input type="hidden" name="request" value="ticketDelete">
							<input type="hidden" name="id" value="<?= $this->data['id']; ?>">
							<input type="hidden" name="redirect" value="<?= APP_PREFIX; ?>">
							<button type="submit">Удалить обращение</button>
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
				<h2>Сообщения</h2>
			</div>
			<div class="part-min">
				<?php
				$messages = $this->Fw->MessageManager->get(['ticket'=>$ticket->get('id')]);
				?>
				<div class="d-g gg-2">
					<?php foreach ($messages as $message): ?>

						<div class="d-g gg-1 p-1 bg-3">
							<div class="d-g gaf-c jc-sb gg-1">
								<div>
									<?php
									$user = $this->Fw->User($message['user']);
									if ($user)
										echo 'Автор: '.$user->get('name');
									?>
								</div>
								<div>
									<form method="post" class="d-g gg-1">
										<input type="hidden" name="request" value="ticketMessageDelete">
										<input type="hidden" name="message" value="<?= $message['id']; ?>">
										<button type="submit">Удалить</button>
									</form>
								</div>
							</div>
							<div><?= $message['content']; ?></div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="part-min">
				<h3>Новое сообщение</h3>
			</div>
			<div class="part-min">
				<form method="post" class="d-g gg-1">
					<input type="hidden" name="request" value="ticketMessageCreate">
					<input type="hidden" name="user" value="<?= $this->Fw->Account->getCurrentId(); ?>">
					<input type="hidden" name="ticket" value="<?= $this->data['id']; ?>">

					<textarea name="content" rows="3" placeholder="Сообщение" required></textarea>
					<button type="submit">Отправить</button>
				</form>
			</div>
		</div>
	</div>
</section>



