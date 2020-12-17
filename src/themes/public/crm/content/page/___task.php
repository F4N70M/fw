<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 25.03.2020
 */
?>
<?php
$task = $this->Fw->Task($this->data['id']);
$info = $task->info();

$months = [
    '01'=>'яневаря',
    '02'=>'февраля',
    '03'=>'марта',
    '04'=>'апреля',
    '05'=>'мая',
    '06'=>'июня',
    '07'=>'июля',
    '08'=>'августа',
    '09'=>'сентября',
    '10'=>'октября',
    '11'=>'ноября',
    '12'=>'декабря'
];
$states = [
	'new'=>'Новая',
	'relevant'=>'Актуальная',
	'complete'=>'Выполненная',
	'confirm'=>'Принятая',
	'payment'=>'К оплате',
	'bonus'=>'Бонус',
	'archive'=>'Архив'
];
$statuses = [
    "default"=>"Не срочно",
    "quickly"=>"Срочно",
    "pause"=>"Пауза"
];

$status = array_key_exists($info['status'], $statuses) ? $info['status'] : 'default';

$time = time($info['date']);
$date = explode('.',date('d.m.Y',$time));
$date =  $date[0] . ' ' . $months[$date[1]] . ' ' . $date[2];
$executorId = $info['executor'];
$executor = $this->Fw->User($executorId);
$project = $this->Fw->Project($info['project']);
$client = $this->Fw->User($project->get('user'));

$messages = $this->Fw->MessageManager->get(['task'=>$this->data['id']]);

$files = [];
foreach ($messages as $message)
{
    $tmp = [];
    $messageFiles = $this->Fw->Relations->getList('objects',$message['id'],'files');
    foreach ($messageFiles as $fileId)
    {
	    $tmp[] = $this->Fw->File($fileId);
    }
    $files[$message['id']] = $tmp;
}
?>
<section id="<?=$this->data['id'];?>" class="task">
	<div class="limit">
		<div class="part">
            <div class="part-min">
                <div class="d-g gtc-2 gg-2 minh-30">
                    <!-- task info -->
                    <div class="task__info">
                        <div class="part-min">
                            <div class="mv-1">
                                <div class="d-g gaf-c jc-sb gg-1">
                                    <div><?=$date;?></div>
                                    <div class="d-g gaf-c gg-1">
                                        <div>
                                            <span class="p-05 bg-2"><?=$states[$info['state']];?></span>
                                        </div>
                                        <div>
                                            <span class="p-05 bg-2"><?=$statuses[$status];?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mv-1">
                                <div class="d-g gaf-c jc-l gg-1">
                                        <div class="c-b-50">Проект:</div>
                                        <div>
                                            <?=$client->get('name');?>
                                            /
                                            <?=$project->get('title');?>
                                        </div>
                                </div>
                            </div>
                            <div class="mv-1">
                                <div class="d-g gaf-c jc-l gg-1">
                                        <div class="c-b-50">Исполнитель:</div>
                                        <div><?=$executor->get('name');?></div>
                                </div>
                            </div>
                        </div>
                        <div class="part-min">
                            <div class="mv-1">
                                <div class="c-b-50">Тема:</div>
                                <div class="fw-b"><?=$task->get('title');?></div>
                            </div>
                            <div class="mv-1">
                                <div class="c-b-50">Задача:</div>
                                <?=$task->getContent();?>
                            </div>
                        </div>
                        <div class="part-min">
                            <div class="d-g gg-1">
                                <div class="c-b-50">Прикрепленные файлы:</div>
                                <div class="d-g gg-1">
                                    <?php foreach ($files as $messageId => $messageFiles): ?>
                                    	<?php foreach ($messageFiles as $file): ?>
		                                    <?php
		                                    ?>
                                            <a href="<?=$file->get('url');?>" target="_blank" class="d-g gg-1" style="grid-template-columns: auto 1fr;">
                                                <div class="w-3 h-3" style="background-image: url(<?=$file->icon();?>);"></div>
                                                <div class="d-g ac-c">
                                                    <div><?=$file->get('title').'.'.$file->get('extension');?></div>
                                                </div>
                                            </a>
                                    	<?php endforeach; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <?php if (!empty($info['spent_time'])) : ?>
                            <div class="part-min">
                                <div class="mv-1">
                                    <span class="c-b-50">Затраченное время:</span>
                                    <?php
                                    $spentT = (int) $task->get('spent_time');
                                    $spentH = intdiv($spentT, 3600);
                                    $spentM = intdiv($spentT - $spentH * 3600, 60);
                                    echo (!empty($spentH) ? $spentH.'ч': '') . ' ' . (!empty($spentM) ? $spentM.'мин': '');
                                    ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <!-- / task info -->

                    <!-- task chat -->
                    <div class="task__chat pos-r">
                        <div class=" pos-a w-100p h-100p">

                            <div class="d-g bg-g-25 maxh-100p" style="grid-template-rows: 1fr auto;">
                                <div class="pv-1">
                                    <div class="h-100p ovy-s ph-1">
                                        <div class="d-g gg-1">
					                        <?php
					                        $messages = $this->Fw->MessageManager->get(['task'=>$this->data['id']]);
					                        ?>
					                        <?php foreach ($messages as $key => $message): ?>
                                                <div class="bg-g-25 p-1 d-g gg-1">
                                                    <div><?=implode('<br>', explode("\r\n", $message['content']));?></div>
	                                                <?php if (count($files[$message['id']]) > 0) : ?>
                                                        <div>
                                                            <?php foreach ($files[$message['id']] as $file): ?>
                                                                <a href="<?=$file->get('url');?>" target="_blank" class="td-u c-2">
                                                                    <small><?=$file->get('title').'.'.$file->get('extension');?></small>
                                                                </a>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="d-g gaf-c jc-sb gg-1 c-b-50">
                                                        <small><?=$this->Fw->User($message['user'])->get('name');?></small>
                                                        <small><?=date('d/m/Y H:i', time($message['date']));?></small>
                                                    </div>
                                                </div>
					                        <?php endforeach; ?>
                                        </div>

                                    </div>
                                </div>
                                <div>
                                    <form method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="request" value="taskAddMessage">
                                        <input type="hidden" name="user" value="<?= $this->Fw->Account->getCurrentId(); ?>">
                                        <input type="hidden" name="task" value="<?= $this->data['id']; ?>">
                                        <input type="hidden" name="redirect" value="<?=APP_PREFIX;?>/task-<?=$this->data['id'];?>">
                                        <div class="d-g">
                                            <textarea name="content" rows="3" class="w-100p" placeholder="Новое сообщение" required></textarea>
                                            <div class="d-g gtc-2">
                                                <div>
                                                    <input type="file" class="w-100p" id="files" name="files[]" multiple title="Добавить файлы">
                                                </div>
                                                <button type="submit">Отправить</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- / task chat -->
                </div>
            </div>
            <div class="part-min">


                <div class="mv-1">
                    <div class="c-b-50">Изменить срочность:</div>
                </div>
                <div class="mv-1">
					<?php foreach ($statuses as $key => $value): ?>
                        <form method="post" class="d-ib">
                            <input type="hidden" name="request" value="taskChangeStatus">
                            <input type="hidden" name="id" value="<?=$this->data['id'];?>">
                            <input type="hidden" name="status" value="<?=$key;?>">
                            <button type="submit" <?=($key == $info['status']) ? 'disabled' : '';?> class="bg-2"><?=$value;?></button>
                        </form>
					<?php endforeach; ?>
                </div>

                <!--
                <div class="mv-1">
                    <div class="c-b-50">Изменить статус:</div>
                </div>
                <div class="mv-1">
		            <?php foreach ($states as $key => $value): ?>
                        <form method="post" class="d-ib">
                            <input type="hidden" name="request" value="taskChangeState">
                            <input type="hidden" name="id" value="<?=$this->data['id'];?>">
                            <input type="hidden" name="state" value="<?=$key;?>">
                            <button type="submit" <?=($key == $info['state']) ? 'disabled' : '';?> class="bg-2"><?=$value;?></button>
                        </form>
		            <?php endforeach; ?>
                </div>
                -->

                <div class="mv-1">
                    <div class="c-b-50">Изменить статус:</div>
                </div>
                <div class="mv-1">
		            <?php foreach ($states as $key => $value): ?>
                        <a href="<?=APP_PREFIX.'/task-state-'.$this->data['id'].'?state='.$key;?>" class="btn <?=$key == $info['state'] ? 'bg-1' : 'bg-2';?>"><?=$value;?></a>
		            <?php endforeach; ?>
                </div>



            </div>
		</div>
	</div>
</section>
