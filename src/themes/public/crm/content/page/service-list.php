<?php
$types = [
	"hosting"  => "Хостинг",
	"domen"   => "Домен",
	"sql"   => "SSL сертификат",
	"other"  => "Другое"
];


$otherProjects = $this->Fw->Db
    ->select()
    ->from('objects')
    ->where([
        'user'=>$this->data['id'],
        'type'=>'project'
    ])
    ->result();
//debug($otherProjects);

$projects = [];
$projectIds = [];
foreach ($otherProjects as $otherProject)
{
    $projects[$otherProject['id']] = $otherProject;
    $projectIds[] = $otherProject['id'];
}
$relations = [];
if (!empty($projectIds))
{
    $relations = $this->Fw->Db
        ->select()
        ->from('relations')
        ->where([
            'parent_table' => 'objects',
            'parent_id' => ['in', $projectIds],
            'child_table' => 'objects'
        ])
        ->result();
}

$rels = [];
foreach ($relations as $relation)
{
    $relID = $relation['child_id'];
    $rels[$relID] = $relID;
}
//$services = $this->Fw->Db->select()->from('objects')->where(['type'=>'service', 'id'=>['in',$rels]])->result();
$services = $this->Fw->Db->select()->from('objects')->where(['type'=>'service', 'value'=>['in',$rels]])->result();


$accs = [];
foreach ($services as $service)
{
    $accID = $service['value'];
    $accs[$accID] = $accID;
}
$accesses = $this->Fw->Db->select()->from('objects')->where(['type'=>'access', 'id'=>['in',$accs]])->result('id');
//debug(key($accesses));


?>

<section>
	<div class="limit">
		<div class="part">
			<div class="part-min">
				<a href="<?= APP_PREFIX; ?>/new-service<?=$this->data['id']?'-'.$this->data['id']:'';?>" class="btn bg-g-50">добавить услугу</a>
			</div>
			<div class="part-min">
				<h1>Услуги</h1>
			</div>
			<div class="part-min">
				<div class="d-g gg-1 gtc-2">
					<?php foreach ($services as $key => $item): ?>
                        <?php
                        $paymentRelations = $this->Fw->Db
                                                ->select()
                                                ->from('relations')
                                                ->where([
                                                    'parent_table'=>'objects',
                                                    'parent_id'=>$item['id'],
                                                    'child_table'=>'objects'
                                                ])
                                                ->result();
                        $paymentRels = [];
                        foreach ($paymentRelations as $relation)
                        {
                            $relID = $relation['child_id'];
                            $paymentRels[$relID] = $relID;
                        }
                        $now = date('Y-m-d H:i:s');
                        $payment = (
                            $payments = $this->Fw->Db
                                ->select()
                                ->from('objects')
                                ->where([
                                    'type'=>'payment',
                                    'id'=>['in',$paymentRels],
//                                    'date_start'=>['<',$now],
//                                    'date_end'=>['>',$now]
                                ])
                                ->orderBy(['date_end'=>false])
                                ->limit(1)
                                ->result()
                        )
                            ? $payments[0]
                            : false;

                        /*$accesses = $this->Fw->Db
                            ->select()
                            ->from('objects')
                            ->where([
                                'type'=>'access',
                                'id'=>['in',$paymentRels],
                                'date_start'=>['<',$now],
                                'date_end'=>['>',$now]
                            ])
                            ->orderBy(['date_end'=>false])
                            ->limit(1)
                            ->result();*/
                        ?>
                        <?php
                        if ($payment['date_end'] < $now)
                        {
                            $serviceColor = 'b';
                        }
                        elseif ($payment['date_end'] < date('Y-m-d H:i:s', time()+604800))
                        {
                            $serviceColor = '1';
                        }
                        else
                        {
                            $serviceColor = '3';
                        }
                        ?>
						<div id="<?=$item['id'];?>" class="p-1 bg-w bsh-05" style="border-left: solid .25rem rgb(var(--c-<?=$serviceColor;?>))">
                            <div class="d-g gg-1 gtc-2">
                                <div><?php
                                    switch ($item['class']) {
                                        case "hosting": case "domen": case "sql":
                                        ?>
                                        <?=$types[$item['class']];?>
                                        <?php
                                        break;

                                        default:
                                            ?>
                                            <?=$types['other'];?>
                                            <?php
                                            break;
                                    }
                                ?></div><div><?=$item['title'];?></div>

                                <?php if ($payment) : ?>
<!--                                    <div>Оплачен с</div><div>--><?//=date('d.m.Y', strtotime($payment['date_start']));?><!--</div>-->
                                    <div>Оплачен до</div><div><?=date('d.m.Y', strtotime($payment['date_end']));?></div>
                                <?php else:?>
                                    <div>Оплачен до</div><div>Не оплачен</div>
                                <?php endif; ?>
                                <?php
                                $access = $accesses[$item['value']];
//                                debug($item['value'], $accesses[$item['value']]);
                                ?>
                                <div>Ссылка</div><a href="//<?=$access['link'];?>" target="_blank" class="externalLink"><?=$access['link'];?></a>
                                <div>Логин</div><div class="copyToClipboard"><?=$access['login'];?></div>
                                <div>Пароль</div><div class="copyToClipboard"><?=$access['password'];?></div>
                            </div>
                            <?php
                            ?>
						</div>
					<?php endforeach; ?>
				</div>

			</div>
		</div>
	</div>
</section>