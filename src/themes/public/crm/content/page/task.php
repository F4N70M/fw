<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 25.03.2020
 */
?>
<?php
//$task = $this->Fw->Task($this->data['id']);
$task = (
    $tasks = $this->Fw->Db
        ->select()
        ->from('objects')
        ->where(['type'=>'task', 'id'=>$this->data['id']])
        ->result()
) ? $tasks[0] : false;
//$info = $task->info();

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

$users = [];

$status = array_key_exists($task['status'], $statuses) ? $task['status'] : 'default';

$time = time($task['date']);
$date = explode('.',date('d.m.Y',$time));
$date =  $date[0] . ' ' . $months[$date[1]] . ' ' . $date[2];
$executorId = $task['executor'];
//debug($task);
//$executor = $this->Fw->User($executorId);
$executor = (
        $response = $this->Fw->Db
            ->select(['id','name'])
            ->from('users')
            ->where(['id'=>$executorId])
            ->result()
    ) ? $response[0] : false;
if ($executor) $users[$executor['id']] = $executor;
//$project = $this->Fw->Project($task['project']);
$project = $this->Fw->Db
    ->select(['id','type','user','title'])
    ->from('objects')
    ->where(['type'=>'project','id'=>$task['project']])
    ->result()[0];

//$client = $this->Fw->User($project->get('user'));
$client = (
        $response = $this->Fw->Db
            ->select(['id','name'])
            ->from('users')
            ->where(['id'=>$project['user']])
            ->result()
    ) ? $response[0] : false;
$users[$client['id']] = $client;

//$messages = $this->Fw->MessageManager->get(['task'=>$this->data['id']]);
$messages = $this->Fw->Db
    ->select(['id','task','content','date','user'])
    ->from('objects')
    ->where(['task'=>$this->data['id']])
    ->result();

$files = [];
foreach ($messages as $message)
{
    $tmp = [];
//    $messageFiles = $this->Fw->Relations->getList('objects',$message['id'],'files');
    $messageFiles = $this->Fw->Db
        ->select()
        ->from('relations')
        ->where(['parent_table'=>'objects','parent_id'=>$message['id'],'child_table'=>'files'])
        ->result();
    if(is_array($messageFiles))
    {
       // debug($messageFiles);
        foreach ($messageFiles as $fileId)
        {
            $tmpFile = $this->Fw->File($fileId['child_id']);
            if(!empty($tmpFile->info()))
                $tmp[] = $tmpFile;
        }
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
                                            <span class="p-05 bg-g-50"><?=$states[$task['state']];?></span>
                                        </div>
                                        <div>
                                            <span class="p-05 bg-g-50"><?=$statuses[$status];?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mv-1">
                                <div class="d-g gaf-c jc-l gg-1">
                                        <div class="c-b-50">Проект:</div>
                                        <div>
                                            <?=$client['name'];?>
                                            /
                                            <?=$project['title'];?>
                                        </div>
                                </div>
                            </div>
                            <div class="mv-1">
                                <div class="d-g gaf-c jc-l gg-1">
                                        <div class="c-b-50">Исполнитель:</div>
                                        <div><?=isset($executor['name']) ? $executor['name'] : null;?></div>
                                </div>
                            </div>
                        </div>
                        <div class="part-min">
                            <div class="mv-1">
                                <div class="c-b-50">Тема:</div>
                                <div class="fw-b"><?=$task['title'];?></div>
                            </div>
                            <div class="mv-1">
                                <div class="c-b-50">Задача:</div>
                                <?=$task['content'];?>
                            </div>
                        </div>
                        <div class="part-min">
                            <div class="d-g gg-1">
                                <div class="c-b-50">Прикрепленные файлы:</div>
                                <div class="maxh-10 ovy-a">
                                    <div class="d-g gg-1">
		                                <?php foreach ($files as $messageId => $messageFiles): ?>
			                                <?php foreach ($messageFiles as $file): ?>
				                                <?php
                                                $fileInfo = $file->info();
				                                ?>
                                                <a href="<?=$fileInfo['url'];?>" target="_blank" class="d-g gg-1" style="grid-template-columns: auto 1fr;">
                                                    <div class="w-3 h-3" style="background-image: url(<?=$file->icon();?>);"></div>
                                                    <div class="d-g ac-c">
                                                        <div><?=$fileInfo['title'].'.'.$fileInfo['extension'];?></div>
                                                    </div>
                                                </a>
			                                <?php endforeach; ?>
		                                <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if (!empty($task['spent_time'])) : ?>
                            <div class="part-min">
                                <div class="mv-1">
                                    <span class="c-b-50">Затраченное время:</span>
                                    <?php
                                    $spentT = (int) $task['spent_time'];
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
                    <div class="task__chat pos-r minh-20">
                        <div class=" pos-a w-100p h-100p">

                            <div class="d-g bg-g-25 maxh-100p" style="grid-template-rows: 1fr auto;">
                                <div class="pv-1">
                                    <div class="h-100p ovy-s ph-1">
                                        <div class="d-g gg-1">
                                            <?php foreach ($messages as $key => $message): ?>
                                                <div class="bg-g-25" style="overflow-x:auto;">
                                                    <div class="p-1 d-g gg-1">
                                                        <?php
                                                        $content = $message['content'];
                                                        $content = htmlspecialchars($content);
                                                        $content = implode('<br>', explode("\r\n", $content));
                                                        ?>
                                                        <div><?=$content;?></div>
                                                        <?php if (count($files[$message['id']]) > 0) : ?>
                                                            <div>
                                                                <?php foreach ($files[$message['id']] as $file): ?>
                                                                    <?php
                                                                    $fileInfo = $file->info();
                                                                    // debug($fileInfo);
                                                                    ?>
                                                                    <a href="<?=$fileInfo['url'];?>" target="_blank" class="td-u c-2">
                                                                        <small><?=$fileInfo['title'].'.'.$fileInfo['extension'];?></small>
                                                                    </a>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div class="d-g gaf-c jc-sb gg-1 c-b-50">
                                                            <?php
                                                            $user = isset($users[$message['user']])
                                                                ? $users[$message['user']]
                                                                : (($response = $this->Fw->Db
                                                                    ->select(['id','name'])
                                                                    ->from('users')
                                                                    ->where(['id'=>$project['user']])
                                                                    ->result()
                                                                ) ? ($users[$message['user']] = $response[0]) : false);
                                                            ?>
                                                            <small><?=$user['name'];?></small>
                                                            <?php /*<small><?=$this->Fw->User($message['user'])['name'];?></small>*/ ?>
                                                            <small><?=date('d/m/Y H:i', time($message['date']));?></small>
                                                        </div>
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
                                                    <input id="file" type="file" class="w-100p" id="files" name="files[]" title="Добавить файлы" multiple>
                                                    <label for="file" class="w-100p bg-g-50">Добавить файлы</label>
                                                </div>
                                                <button type="submit" class="bg-2">Отправить</button>
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
                            <button type="submit" <?=($key == $task['status']) ? 'disabled' : '';?> class="bg-g-50"><?=$value;?></button>
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
                            <button type="submit" <?=($key == $task['state']) ? 'disabled' : '';?> class="bg-2"><?=$value;?></button>
                        </form>
		            <?php endforeach; ?>
                </div>
                -->

                <div class="mv-1">
                    <div class="c-b-50">Изменить статус:</div>
                </div>
                <div class="mv-1">
		            <?php foreach ($states as $key => $value): ?>
                        <a href="<?=APP_PREFIX.'/task-state-'.$this->data['id'].'.popup?state='.$key;?>" class="popup-link btn <?=$key == $task['state'] ? 'bg-2' : 'bg-g-50';?>"><?=$value;?></a>
		            <?php endforeach; ?>
                </div>



            </div>
		</div>
	</div>
</section>
