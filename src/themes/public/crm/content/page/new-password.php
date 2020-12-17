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
                <h1 class="ta-c"><?=$this->data['title'];?></h1>
            </div>
            <div class="part-min">
                <div class="maxw-20 mh-a">
                    <form method="post" class="d-g gg-1" enctype="multipart/form-data">
                        <input type="hidden" name="request" value="changePassword">
                        <input type="hidden" name="id" value="<?=$this->data['id'];?>">
                        <input type="hidden" name="redirect" value="<?=APP_PREFIX;?>/client-<?=$this->data['id'];?>">
                        <input type="text" class="w-100p" name="password" placeholder="Пароль" value="<?=generatePassword();?>" required>


                        <button type="submit">Сохранить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>