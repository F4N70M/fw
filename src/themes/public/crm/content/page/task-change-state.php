<?php
//$task = $this->Fw->Task($this->data['id']);
$task =
    (
        $tasks = $this->Fw->Db
            ->select()
            ->from('objects')
            ->where(['type'=>'task', 'id'=>$this->data['id']])
            ->result()
    )
        ? $tasks[0]
        : false;

$state = $_GET['state'];

$arr = [
	'new'   =>  [],
	'relevant'  =>  [
        'project'  => true,
		'executor'  => true,
        'spent_time'    =>  true
	],
	'complete'  =>  [
		'spent_time'    =>  true
	],
	'confirm'   =>  [],
	'payment'   =>  [],
	'bonus'   =>  [],
	'archive'   =>  []
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
?>


<?php
$spentT = (int) $task['spent_time'];
$spentH = intdiv($spentT, 3600);
$spentM = intdiv($spentT - $spentH * 3600, 60);
?>

<section>
	<div class="limit">
		<div class="part">
			<div class="part-min">
				<h1 class="ta-c">Перенос задачи в "<?=$states[$state];?>"</h1>
			</div>
			<div class="part-min">
				<div>
					<form method="post" class="d-g gg-1" enctype="multipart/form-data">
						<input type="hidden" name="request" value="taskChangeState">
						<input type="hidden" name="id" value="<?=$this->data['id'];?>">
						<input type="hidden" name="state" value="<?=$state;?>">
<!--						<input type="hidden" name="redirect" value="--><?//=APP_PREFIX.'/task-'.$this->data['id'];?><!--">-->

                        <?php if (isset($arr[$state]['project'])) : ?>
                            <?php
                            $list = $this->Fw->Db->select()->from('objects')->where(['type'=>'project'])->result();
                            $projects = [];
                            foreach ($list as $item)
                            {
                                $projects[$item['user']][$item['id']] = $item;
                            }
                            $clients = $this->Fw->Db->select()->from('users')->where(['type'=>'client'])->result();
                            ?>
                            <select name="project" class="w-100p">
                                <option value="" style="display: none">Проект</option>
                                <?php
                                //                            $clients = $this->Fw->ClientManager->get(['type'=>'client']);
                                //                            debug($clients);
                                ?>
                                <?php foreach ($clients as $client): ?>
                                    <optgroup label="<?=$client['name'];?>">
                                        <?php foreach ($projects[$client['id']] as $item): ?>
                                            <option value="<?=$item['id'];?>" <?=$item['id']==$task['project'] ? 'selected' : null;?>><?=$item['title'];?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>

                        <?php endif; ?>

                        <?php if (isset($arr[$state]['executor'])) : ?>
                            <?php
//							$executors = $this->Fw->UserManager->get(['type'=>'admin']);
                            $executors = $this->Fw->Db
                                ->select(['id','type','name'])
                                ->from('users')
                                ->where(['type'=>'admin'])
                                ->result();

                            ?>
                            <div class="ta-c">
                                <div>Исполнитель</div>
                                <select name="executor" class="w-100p" required>
                                    <option value="" style="display: none">Исполнитель *</option>
                                    <?php foreach ($executors as $key => $executor): ?>
                                        <option value="<?=$executor['id'];?>" <?=$task['executor'] == $executor['id'] ? 'selected' : '';?>><?=$executor['name'];?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                        <?php endif; ?>

                        <?php if (isset($arr[$state]['spent_time'])) : ?>
                            <div class="ta-c">Затраченное время</div>
                            <div class="d-g gtc-2 gg-1">
                                <div>
                                    <div class="ta-c">Часы</div>
                                    <input type="number" class="w-100p" name="spent_hours" min="0" step="1" value="<?=$spentH;?>">
                                </div>
                                <div>
                                    <div class="ta-c">Минуты</div>
                                    <input type="number" class="w-100p" name="spent_minutes" min="0" max="59" step="5" value="<?=$spentM;?>">
                                </div>
                            </div>

                        <?php endif; ?>


						<button type="submit">Перенести</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
