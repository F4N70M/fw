
<a href="<?= APP_PREFIX; ?>"><button>Все проекты</button></a>

<form method="post" style="display: grid; grid-gap: 1rem;">
	<input type="hidden" name="request" value="projectCreate">
	<input type="hidden" name="user" value="<?= $this->Fw->Account->getCurrent()['id']; ?>">
	<input type="hidden" name="redirect" value="/lk">

	<input type="text" name="name" placeholder="Название" required>
	<input type="text" name="link" placeholder="Ссылка (если существует)">
	<textarea name="description" rows="3" placeholder="Описание"></textarea>
	<button type="submit">Создать</button>
</form>