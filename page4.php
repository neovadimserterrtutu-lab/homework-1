<?php

declare(strict_types=1);

session_start();

$counter = isset($_SESSION['page3_counter'])
    ? (int) $_SESSION['page3_counter']
    : 0;
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Четвёртая страница</title>
</head>
<body>
    <h1>Четвёртая страница</h1>
    <p>Третья страница была открыта <?= $counter ?> раз(а).</p>
    <p><a href="page3.php">Вернуться на третью страницу</a></p>
    <p><a href="index.php">На главную</a></p>
</body>
</html>
