<!doctype html>
<html lang="en">
<head>
    <?=$head;?>
</head>
<body>
    <?php
    foreach ($assets[0] as $key => $item)
    { echo $item; }
    ?>
    <header>
        <?=$header;?>
    </header>
    <main>
        <?=$main;?>
    </main>
    <footer>
        <?=$footer;?>
    </footer>
    <?php
    foreach ($assets[1] as $key => $item)
    { echo $item; }
    ?>
</body>
</html>