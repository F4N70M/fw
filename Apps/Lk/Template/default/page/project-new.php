<?php
$im = $data['id'] == $this->Fw->Account->getCurrentId();
$breadcrumbs = [
    '' => 'Проекты'
];
if (!$im)
{
	$user = $this->Fw->Client($data['id']);
    array_shift($breadcrumbs);
	$breadcrumbs = [
	        ''  => 'Клиенты',
			'/projects-'.$user->get('id') => $user->get('name')
        ]
        + $breadcrumbs;
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
                Новый проект
            </div>
            <div class="part-min">
                <h1 class="ta-c">Новый проект</h1>
            </div>
            <div class="part-min">
                <div class="maxw-20 m-a">
                    <form method="post" style="display: grid; grid-gap: 1rem;">
                        <input type="hidden" name="request" value="projectCreate">
                        <input type="hidden" name="user" value="<?=$data['id'];?>">
                        <input type="hidden" name="redirect" value="<?= APP_PREFIX; ?>">

                        <input type="text" class="w-100p" name="title" placeholder="Название" required>
                        <input type="text" class="w-100p" name="link" placeholder="Ссылка (если существует)">
                        <textarea name="description" rows="3" placeholder="Описание"></textarea>
                        <button type="submit">Создать</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>