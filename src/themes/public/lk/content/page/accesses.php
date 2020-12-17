<?php
/**
 * User: F4N70M
 * Version: 0.1
 * Date: 16.03.2020
 */
?>

<?php
$types = [
	"provider"  => "Хостинг-провайдер",
	"ftp"   => "FTP",
	"sql"   => "MySQL",
	"ssl"   => "SSL",
	"mail"  => "Почта",
	"other"  => "Другое"
];


$user = $this->Fw->Account->getCurrent();

$otherProjects = $this->Fw->Db->select()->from('objects')->where(['user'=>$user['id'], 'type'=>'project'])->result();
//debug($otherProjects);

$projects = [];
$projectIds = [];
foreach ($otherProjects as $otherProject)
{
    $projects[$otherProject['id']] = $otherProject;
    $projectIds[] = $otherProject['id'];
}
//$AccessManager = $this->Fw->AccessManager();
//$accesses = $AccessManager->get(['project'=>$this->data['id']]);

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

$accesses = [];
foreach ($relations as $relation)
{
    $access = $this->Fw->Db->select()->from('objects')->where(['type'=>'access', 'id'=>$relation['child_id']])->result();
    if ($access)
        $accesses[$relation['parent_id']][$access[0]['id']] = $access[0];
}



?>

<section>
	<div class="limit">
		<div class="part">
			<div class="part-min">
				<a href="<?= APP_PREFIX; ?>/new-access" class="btn">добавить доступ</a>
			</div>
            <div class="part-min">
                <h1>Доступы</h1>
            </div>
            <div class="part-min">
                <form>
                    <select name="project" onchange="this.form.submit()">
                        <option value="" <?=!(array_key_exists('project', $_GET) && array_key_exists($_GET['project'], $accesses)) ? 'selected' : null;?>>Все проекты</option>
                        <?php foreach ($projects as $projectId => $project): ?>
                            <option
                                value="<?=$project['id'];?>"
                                <?=array_key_exists('project', $_GET) && $project['id']==$_GET['project']?'selected':null;?>
                            ><?=$project['title'];?></option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
			<div class="part-min">
                <?php
                $currentAccesses = null;
                if (array_key_exists('project', $_GET) && in_array($_GET['project'], $projectIds))
                {
                        $currentAccesses = (array_key_exists($_GET['project'], $accesses)) ? $accesses[$_GET['project']] : [];

                }
                else
                {
                    $currentAccesses = [];
                    foreach ($accesses as $projectAccesses)
                    {
                        foreach ($projectAccesses as $access)
                        {
                            $currentAccesses[$access['id']] = $access;
                        }
                    }
                }
//                    $currentAccesses = array_shift($accesses);
                ?>
                <div class="d-g gg-1 gtc-2">
                    <?php if (is_array($currentAccesses)) : ?>
                        <?php foreach ($currentAccesses as $key => $access): ?>
                            <div id="<?=$access['id'];?>" class="p-1 bg-g-50">
                            <?php
                            switch ($access['access_type']) {
                                case "provider":
                                    ?>
                                    <div class="d-g gg-1 jc-sb gtc-2" sty-le="grid-template-columns: auto auto">
                                        <div>Тип</div><div class="fw-b"><?=$types[$access['access_type']];?></div>
                                        <div>Ссылка</div><div><a href="//<?=$access['link'];?>" target="_blank"><?=$access['link'];?></a></div>
                                        <div>Логин</div><div class="copyToClipboard"><?=$access['login'];?></div>
                                        <div>Пароль</div><div class="copyToClipboard"><?=$access['password'];?></div>
                                    </div>
                                    <?php
                                    break;
                                case "ftp":
                                    ?>
                                    <div class="d-g gg-1 jc-sb gtc-2" sty-le="grid-template-columns: auto auto">
                                        <div>Тип</div><div class="fw-b"><?=$types[$access['access_type']];?></div>
                                        <div>Сервер</div><div class="copyToClipboard"><?=$access['host'];?></div>
                                        <?php if(!empty($access['port'])): ?>
                                            <div>Порт</div><div class="copyToClipboard"><?=$access['port'];?></div>
                                        <?php endif; ?>
                                        <div>Логин</div><div class="copyToClipboard"><?=$access['login'];?></div>
                                        <div>Пароль</div><div class="copyToClipboard"><?=$access['password'];?></div>
                                    </div>
                                    <?php
                                    break;
                                case "sql":
                                    ?>
                                    <div class="d-g gg-1 jc-sb gtc-2" sty-le="grid-template-columns: auto auto">
                                        <div>Тип</div><div class="fw-b"><?=$types[$access['access_type']];?></div>
                                        <div>phpMyAdmin (ссылка)</div><div><a href="<?=$access['link'];?>" target="_blank"><?=$access['link'];?></a></div>
                                        <div>Сервер</div><div class="copyToClipboard"><?=$access['host'];?></div>
                                        <div>Логин</div><div class="copyToClipboard"><?=$access['login'];?></div>
                                        <div>Пароль</div><div class="copyToClipboard"><?=$access['password'];?></div>
                                        <div>База данных</div><div class="copyToClipboard"><?=$access['database'];?></div>
                                    </div>
                                    <?php
                                    break;
                                case "mail":
                                    ?>
                                    <div class="d-g gg-1 jc-sb gtc-2" sty-le="grid-template-columns: auto auto">
                                        <div>Тип</div><div class="fw-b"><?=$types[$access['access_type']];?></div>
                                        <div>Cсылка</div><div><a href="//<?=$access['link'];?>" target="_blank"><?=$access['link'];?></a></div>
                                        <div>Email</div><div class="copyToClipboard"><?=$access['login'];?></div>
                                        <div>Пароль</div><div class="copyToClipboard"><?=$access['password'];?></div>
                                    </div>
                                    <?php
                                    break;

                                default:
                                    ?>
                                    <div class="d-g gg-1 jc-sb gtc-2" sty-le="grid-template-columns: auto auto">
                                        <div>Тип</div><div class="fw-b">Другое</div>
                                        <div>Cсылка</div><div><a href="//<?=$access['link'];?>" target="_blank"><?=$access['link'];?></a></div>
                                        <div>Логин</div><div class="copyToClipboard"><?=$access['login'];?></div>
                                        <div>Пароль</div><div class="copyToClipboard"><?=$access['password'];?></div>
                                    </div>
                                    <?php
                                    break;
                            }
                            ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

			</div>
		</div>
	</div>
</section>