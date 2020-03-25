<?php
function getUrlParameters(array $params=[])
{
	$result = [];
	$get = $_GET;
	foreach ($params as $key => $value)
	{
		$get[$key] = $value;
	}
	foreach ($get as $key => $value)
	{
		$result[] = $key.'='.$value;
	}
	return '?'.implode('&',$result);
}

$months = ['01'=>'яневаря','02'=>'февраля','03'=>'марта','04'=>'апреля','05'=>'мая','06'=>'июня','07'=>'июля','08'=>'августа','09'=>'сентября','10'=>'октября','11'=>'ноября','12'=>'декабря'];

$states = [
    'relevant'=>'Актуальные',
    'complete'=>'Выполненные',
    'confirm'=>'Принятые',
    'payment'=>'К оплате',
    'bonus'=>'Бонус',
    'archive'=>'Архив'
];
$currentState = isset($_GET['state']) && isset($states[$_GET['state']]) ? $_GET['state'] : 'relevant';

$executors = $this->Fw->UserManager->get(['type'=>'admin']);
$tmp = [];
foreach ($executors as $executor)
{
    $tmp[$executor['id']] = $executor;
}
$executors = $tmp;
unset($tmp);

$currentExecutor = isset($_GET['executor']) && isset($executors[$_GET['executor']]) ? $_GET['executor'] : $this->Fw->Account->getCurrentId();

?>
<section class="bg-b-5">
	<div class="limit">
		<div class="part">
			<div class="part-min">
				<div class="d-g gg-2" style="grid-template-columns: auto 1fr;">
					<div>
						<a href="<?= APP_PREFIX; ?>/new-task" class="btn">Добавить задачу</a>
					</div>
					<div class="d-g gaf-c jc-sb gg-2 bg-2 ph-1 ac-c">
						<div class="d-g gg-1 gaf-c jc-l">
                            <?php foreach ($states as $stateKey => $stateName): ?>
                                <a href="<?=getUrlParameters(['state'=>$stateKey]);?>" class="<?=$currentState == $stateKey ? 'td-u' : '';?>"><?=$stateName;?></a>
                            <?php endforeach; ?>
						</div>
						<div>
							<div class="d-g gaf-c gg-1">
                                <?php foreach ($executors as $key => $executor): ?>
                                    <a href="<?=getUrlParameters(['executor'=>$executor['id']]);?>" class="<?=$currentExecutor == $executor['id'] ? 'td-u' : '';?>"><?=$executor['name'];?></a>
                                <?php endforeach; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="part-min">
				<?php
				$tasks = $this->Fw->TaskManager->get(['state'=>$currentState,'executor'=>$currentExecutor]);

				$projects = [];
				$users = [];
				?>
				<div class="d-g gg-2 gtc-2">
					<?php foreach ($tasks as $key => $task): ?>
						<a href="<?=APP_PREFIX;?>/task-<?=$task['id'];?>" class="bg-w p-1">
							<div class="d-g gg-1">
								<div class="d-g gg-1 gaf-c jc-sb">
									<div>
                                        <?php
                                        $time = time($task['date']);
                                        $date = explode('.',date('d.m.Y',$time));
                                        echo $date[0] . ' ' . $months[$date[1]] . ' ' . $date[2];
                                        ?>
                                    </div>
									<div>
										<?php
										    switch ($task['status'])
										    {
											    case "pause" :
											    	echo "Пауза";
													break;
											    case "quickly" :
												    echo "Срочно";
													break;
											    default :
												    echo "Не срочно";
											    	break;
										    }
										?>
									</div>
								</div>
								<div class="fw-b"><?=$task['title'];?></div>
								<div><?=$task['content'];?></div>
                                <div class="d-g gg-1 gaf-c jc-sb">
                                    <div>
                                        <?php
                                        if (!isset($projects[$task['project']])) $projects[$task['project']] = $this->Fw->Project($task['project'])->info();
                                        $project = $projects[$task['project']];
                                        if (!isset($users[$project['user']])) $users[$project['user']] = $this->Fw->User($project['user'])->info();
                                        $user = $users[$project['user']];
                                        echo $user['name'];?>/<?=$project['title'];
                                        ?>
                                    </div>
                                    <div>
                                        <?php
                                        if (!isset($users[$task['executor']])) $users[$task['executor']] = $this->Fw->User($task['executor'])->info();
                                        $user = $users[$task['executor']];
                                        echo $user['name'];
                                        ?>
                                    </div>
                                </div>
							</div>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</section>
