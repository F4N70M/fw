<form method="post">
    <input type="hidden" name="request" value="login">
    <input type="text" name="login" placeholder="login" value="<?=$_POST['login'];?>">
    <input type="password" name="password" placeholder="password" value="<?=$_POST['password'];?>">
    <button type="submit">submit</button>
    <button type="reset">reset</button>
</form>

<?php
debug(
    $this->Fw->Account->getList(),
    $this->Fw->Account->getCurrent()
);
?>