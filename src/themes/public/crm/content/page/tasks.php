<?php
function getUrlParameters(array $params=[])
{
	$result = [];
	$get = $_GET;
	foreach ($params as $key => $value)
	{
	    if (!empty($value))
		    $get[$key] = $value;
	    else
	        unset($get[$key]);
	}
	foreach ($get as $key => $value)
	{
		$result[] = $key.'='.$value;
	}
	return '?'.implode('&',$result);
}




$months = [
    '01'=>'января','02'=>'февраля','03'=>'марта',
    '04'=>'апреля','05'=>'мая','06'=>'июня',
    '07'=>'июля','08'=>'августа','09'=>'сентября',
    '10'=>'октября','11'=>'ноября','12'=>'декабря'
];

$states = [
	'new'=>'Новые',
	'relevant'=>'Актуальные',
    'complete'=>'Выполненные',
    'confirm'=>'Принятые',
    'payment'=>'К оплате',
    'bonus'=>'Бонус',
    'archive'=>'Архив'
];

$statuses = [
	'quickly'   => [
		'color' => '1',
		'title'  => 'Срочно'
	],
	'default'   => [
		'color' => '2',
        'title'  => 'Не срочно'
	],
	'pause'   => [
		'color' => 'g',
		'title'  => 'Пауза'
	]
];

$currentState =
    isset(
        $states[$currentState = $this->Fw->Cookie->has('state')
            ? $this->Fw->Cookie->get('state')
            : null]
    )
        ? $currentState
        : 'new';
//$currentState = isset($states[$currentState]) ? $currentState : 'new';

$executors = $this->Fw->Db->select()->from('users')->where(['type'=>'admin'])->result();
$tmp = [];
foreach ($executors as $executor)
{
    $tmp[$executor['id']] = $executor;
}
$executors = $tmp;
unset($tmp);
$currentExecutor = $this->Fw->Cookie->has('executor') ? $this->Fw->Cookie->get('executor') : null;
$currentExecutor = isset($executors[$currentExecutor]) ? $currentExecutor : null;
//debug($currentExecutor);

$projectUsers = [];


$query = ['state'=>$currentState,'type'=>'task'];
if ($currentExecutor) $query[] = ['executor'=>null, ['or','executor'=>''], ['or','executor'=>$currentExecutor]];
//				$tasks = $this->Fw->TaskManager->get($query);
$tasks = $this->Fw->Db
    ->select(['id','type','title','date','spent_time','project','executor','status','state'])
    ->from('objects')
    ->where($query)
    ->result();
//debug($tasks);


$messages = $this->Fw->Db->select(['task','type'])->from('objects')->where(['type'=>'message'])->result();
$messagesAll = [];
foreach ($messages as $key => $item)
{
    $messagesAll[$item['task']][] = $item;
}
//debug($messagesAll);



$users = $this->Fw->Db
    ->select(['id','name'])
    ->from('users')
    ->where(['type'=>'client'])
    ->result('id');

?>
<section class="c-b">
	<div class="limit">
        <div class="part">
            <h1 class="ta-c">Задачи</h1>
        </div>
		<div class="part">
			<div class="part-min">
				<div class="d-g gg-1 gaf-c jc-sb">
					<div>
						<a href="<?= APP_PREFIX; ?>/new-task" class="btn bg-g-50 bdrs-1 popup-link">Добавить задачу</a>
					</div>
                    <div class="d-g gaf-c jc-l ac-c bg-g-50 c-b bdrs-1 ov-h">
                        <?php foreach ($states as $stateKey => $stateName): ?>
                            <form method="post">
                                <input type="hidden" name="request" value="tasksChangeState">
                                <input type="hidden" name="redirect" value="<?=$_SERVER['REQUEST_URI'];?>">
                                <button type="submit" name="state" value="<?=$stateKey;?>" class="p-05 bg-t <?=$currentState == $stateKey ? 'c-1' : 'c-b';?>"><?=$stateName;?></button>
                            </form>
                        <?php endforeach; ?>
                    </div>

                    <div>
                        <form method="post">
                            <input type="hidden" name="request" value="tasksChangeExecutor">
                            <select name="executor" onchange="this.form.submit()" class="bg-g-50 bdrs-1 bdw-0">
                                <option value="all" <?=!$currentExecutor ? 'selected' : '';?> >Исполнитель</option>
								<?php foreach ($executors as $key => $executor): ?>
                                    <option value="<?=$executor['id'];?>" <?=$currentExecutor == $executor['id'] ? 'selected' : '';?> ><?=$executor['name'];?></option>
								<?php endforeach; ?>
                            </select>
                        </form>
                    </div>
				</div>
			</div>
			<div class="part-min">
				<?php
//				debug($query, $tasks);


				$projects = [];
				$users = [];
				?>
                <?php if($tasks && count($tasks) > 0) : ?>
                    <?php
                    usort($tasks, function ($a, $b) {
                        $priorities = [
		                    'quickly'   => 1,
		                    'default'   => 0,
		                    'pause'     => -1
	                    ];
	                    $aS = array_key_exists($a['status'],$priorities) ? $a['status'] : 'default';
	                    $bS = array_key_exists($b['status'],$priorities) ? $b['status'] : 'default';
	                    $aP = $priorities[$aS];
	                    $bP = $priorities[$bS];
                        if ($aP == $bP) return 0;
                        return $aP > $bP ? -1 : 1;
                    });
                    ?>
			    	<div class="d-g gg-2 gtc-2">
					<?php foreach ($tasks as $key => $task): ?>
                        <?php
						$status = array_key_exists($task['status'],$statuses) ? $task['status'] : 'default';
						?>
						<a href="<?=APP_PREFIX;?>/task-<?=$task['id'];?>" class="popup-link p-1 bg-w bsh-05"
                           style="border-left: solid .25rem rgb(var(--c-<?=$statuses[$status]['color'];?>))"
                        >
							<div class="d-g gg-1">
								<div class="d-g gg-1 gaf-c jc-sb">
									<div>
                                        <?php
                                        $date = explode('.',date('d.m.Y', strtotime($task['date'])));
                                        echo $date[0] . ' ' . $months[$date[1]] . ' ' . $date[2];
                                        ?>
                                    </div>
									<div>

										<?=$statuses[$status]['title'];?>
									</div>
								</div>
								<div class="fw-b"><?=$task['title'];?></div>
<!--								<div>--><?//=mb_substr($task['content'],0,100).'...';?><!--</div>-->
                                <div class="d-g gg-1 gaf-c jc-sb">
                                    <?php /*<div>Файлов: <?=$files;?></div>*/ ?>
                                    <div>Сообщений: <?=isset($messagesAll[$task['id']]) ? count($messagesAll[$task['id']]) : 0;?></div>

	                                <?php if (!empty($task['spent_time'])) : ?>
                                        <div class="c-b-50">
                                            <?php
                                            $spentT = (int) $task['spent_time'];
                                            $spentH = intdiv($spentT, 3600);
                                            $spentM = intdiv($spentT - $spentH * 3600, 60);
                                            echo (!empty($spentH) ? $spentH.'ч': '') . ' ' . (!empty($spentM) ? $spentM.'мин': '');
                                            ?>
                                        </div>
	                                <?php endif; ?>
                                </div>

                                <div class="d-g gg-1 gaf-c jc-sb">
                                    <div>
                                        <?php
                                        if (!isset($projects[$task['project']]))
                                        {

	                                        $projects[$task['project']] =
                                                is_array(
                                                    $tmpResults = $this->Fw->Db
                                                        ->select(['id','title','type','user'])
                                                        ->from('objects')
                                                        ->where(['type'=>'project', 'id'=>$task['project']])
                                                        ->result()
                                                ) && isset($tmpResults[0])
                                                    ? $tmpResults[0]
                                                    : false;
//                                          $projects[$task['project']] = $this->Fw->Project($task['project'])->info();
                                        }
                                        $project = $projects[$task['project']];
                                        if (!isset($users[$project['user']]))
                                        {
	                                        $users[$project['user']] =
                                                is_array(
                                                    $tmpResults = $this->Fw->Db
                                                        ->select(['id','name'])
                                                        ->from('users')
                                                        ->where(['id' => $project['user']])
                                                        ->result()
                                                ) && isset($tmpResults[0])
                                                    ? $tmpResults[0]
                                                    : false;
//	                                        debug($tmpResults);
//	                                        $users[$project['user']] = $this->Fw->User($project['user'])->info();
                                        }
                                        $user = $users[$project['user']];
                                        echo $user['name'];?> / <?=$project['title'];
                                        ?>
                                    </div>
                                    <div>
                                        <?php
                                        if ((string) (int) $task['executor'] === (string) $task['executor'])
                                        {
                                            if (!isset($users[$task['executor']]))
                                            {
                                                $users[$task['executor']] =
	                                                is_array(
                                                        $tmpResults = $this->Fw->Db
                                                            ->select(['id','name'])
                                                            ->from('users')
                                                            ->where(['id' => $task['executor']])
                                                            ->result()
                                                    )
                                                        ? $tmpResults[0]
                                                        : false;
//                                                $users[$task['executor']] = $this->Fw->User($task['executor'])->info();
                                            }
                                            $user = $users[$task['executor']];
                                            echo $user['name'];
                                        }
                                        else
                                        {
                                            echo '–';
                                        }
                                        ?>
                                    </div>
                                </div>
							</div>
						</a>
					<?php endforeach; ?>
				</div>
                <?php else: ?>
                    <div class="c-g ta-c">Нет задач</div>
                <?php endif; ?>
			</div>
		</div>
	</div>
</section>