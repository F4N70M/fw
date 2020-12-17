<?php
function generatePassword(
    int $length = 8,
    string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
)
{
    if ($length < 1)
    {
        throw new \RangeException("Length must be a positive integer");
    }
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i)
    {
        $random = random_int(0, $max);
        $pieces []= $keyspace[$random];
    }
    return implode('', $pieces);
}
?>

<section>
	<div class="limit">
		<div class="part">
			<div class="part-min">
				<h1 class="ta-c">Новый клиент</h1>
			</div>
			<div class="part-min">
				<div class="maxw-20 m-a">
					<form method="post" style="display: grid; grid-gap: 1rem;">
						<input type="hidden" name="request" value="clientCreate">
						<input type="hidden" name="user" value="<?= $this->Fw->Account->getCurrentId(); ?>">
						<input type="hidden" name="redirect" value="<?= APP_PREFIX; ?>">

						<input type="text" class="w-100p" name="login" placeholder="Логин (только латинские буквы)" pattern="^[a-z]+$" required>
						<input type="text" class="w-100p" name="name" placeholder="Имя" required>
						<input type="text" class="w-100p" name="password" placeholder="Пароль" value="<?=generatePassword();?>" required>
						<button type="submit">Создать</button>
					</form>
				</div>

			</div>
		</div>
	</div>
</section>