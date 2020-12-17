<?php
//$clients = $this->Fw->ClientManager->get(['type'=>'client']);
$clients = $this->Fw->Db->select()->from('users')->where(['type'=>'client'])->orderBy(['name'])->result();
$projects = $this->Fw->Db->select()->from('objects')->where(['type'=>'project'])->result();
$clientsProjects = [];
foreach ($projects as $project)
{
    $clientsProjects[$project['user']][] = $project;
}
ksort($clientsProjects);
?>

<section>
	<div class="limit part">
		<div class="part-min">
			<h1 class="ta-c">Клиенты</h1>
		</div>
        <div class="part-min">
            <a href="<?= APP_PREFIX; ?>/client-new" class="btn bg-g-50 bdrs-1">Новый клиент</a>
        </div>
		<div class="part-min">
			<?php
			?>
			<div class="d-g gg-1">
				<?php foreach ($clients as $client): ?>
					<a href="<?= APP_PREFIX; ?>/client-<?= $client['id']; ?>" class="bg-w bsh-05 p-1 d-g gg-1 gtc-2">
                        <div class="d-g ac-c">
                            <div><?= $client['name']; ?></div>
                        </div>
                        <div class="d-g ac-c">
	                        <?php
//	                        $projects = $this->Fw->Client($client['id'])->projectList();
//                          $projects = $this->Fw->Db->select(['type','title','user'])->from('objects')->where(['type'=>'project','user'=>$client['id']])->result();
	                        $clientProjects = isset($clientsProjects[$client['id']]) && is_array($clientsProjects[$client['id']]) ? $clientsProjects[$client['id']] : [];
	                        if(count($clientProjects) > 0) :
		                        ?>
                                <ul>
			                        <?php foreach ($clientProjects as $key => $project): ?>
                                        <li><?=$project['title'];?></li>
			                        <?php endforeach; ?>
                                </ul>
	                        <?php endif; ?>
                        </div>

                    </a>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>