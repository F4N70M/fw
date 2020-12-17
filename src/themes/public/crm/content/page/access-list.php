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


//$AccessManager = $this->Fw->AccessManager();
//$accesses = $AccessManager->get(['project'=>$this->data['id']]);
$relations =
    (
        $resultRequest = $this->Fw->Db
            ->select()
            ->from('relations')
            ->where([
                'parent_table'  =>  'objects',
                'parent_id'     =>  $this->data['id'],
                'child_table'   =>  'objects'
            ])
            ->result()
    ) | [];
debug($relations);
$accesses = [];
foreach ($relations as $relation)
{
    $access = $this->Fw->Db->select()->from('objects')->where(['type'=>'access', 'id'=>$relation['child_id']])->result();
    if ($access)
        $accesses[] = $access[0];
}

$project =
    (
    $resultRequest = $this->Fw->Db->select()->from('objects')->where(['id'=>$this->data['id'], 'type'=>'project'])->result()
    ) && isset($resultRequest[0])
        ? $resultRequest[0]
        : null;

$im = ($project['user'] == $this->Fw->Account->getCurrentId());

?>

<section>
	<div class="limit">
		<div class="part">
			<div class="part-min">
				<a href="<?= APP_PREFIX; ?>/new-access<?=$this->data['id']?'-'.$this->data['id']:'';?>" class="btn">добавить доступ</a>
			</div>
			<div class="part-min">
				<h1>Доступы</h1>
			</div>
			<div class="part-min">
                <div class="d-g gg-2 gtc-3">
	                <?php foreach ($accesses as $key => $access): ?>
                        <div id="<?=$access['id'];?>" class="p-1 bg-w bsh-05">
		                <?php
		                switch ($access['access_type']) {
                            case "provider":
                                ?>
                                <div class="d-g gg-1 jc-sb" style="grid-template-columns: auto auto">
                                    <div>Тип</div><div class="fw-b"><?=$types[$access['access_type']];?></div>
                                    <div>Ссылка</div><div><a href="//<?=$access['link'];?>" target="_blank"><?=$access['link'];?></a></div>
                                    <div>Логин</div><div class="copyToClipboard"><?=$access['login'];?></div>
                                    <div>Пароль</div><div class="copyToClipboard"><?=$access['password'];?></div>
                                </div>
                                <?php
                                break;
                            case "ftp":
                                ?>
                                <div class="d-g gg-1 jc-sb" style="grid-template-columns: auto auto">
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
                                <div class="d-g gg-1 jc-sb" style="grid-template-columns: auto auto">
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
                                <div class="d-g gg-1 jc-sb" style="grid-template-columns: auto auto">
                                    <div>Тип</div><div class="fw-b"><?=$types[$access['access_type']];?></div>
                                    <div>Cсылка</div><div><a href="//<?=$access['link'];?>" target="_blank"><?=$access['link'];?></a></div>
                                    <div>Email</div><div class="copyToClipboard"><?=$access['login'];?></div>
                                    <div>Пароль</div><div class="copyToClipboard"><?=$access['password'];?></div>
                                </div>
                                <?php
                                break;

                            default:
                                ?>
                                <div class="d-g gg-1 jc-sb" style="grid-template-columns: auto auto">
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
                </div>

			</div>
		</div>
	</div>
</section>