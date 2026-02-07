<?php

declare(strict_types=1);

session_start();

if (!isset($_SESSION['page3_counter'])) {
    $_SESSION['page3_counter'] = 0;
}

$_SESSION['page3_counter']++;
$counter = (int) $_SESSION['page3_counter'];

if ($counter % 3 === 0) {
    header('Location: page4.php');
    exit;
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Третья страница</title>
</head>
<body>
    <h1>Третья страница</h1>
    <p>Эта страница была открыта <?= $counter ?> раз(а).</p>
    <p>Каждый третий заход произойдёт редирект на четвёртую страницу.</p>
    <p><a href="page3.php">Открыть ещё раз</a></p>
    <p><a href="index.php">На главную</a></p>
</body>
</html>
