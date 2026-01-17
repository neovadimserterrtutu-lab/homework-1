<?php

echo 'Текущий файл: ' . __FILE__ . '<br>';
echo 'Текущая строка: ' . __LINE__ . '<br><br>';

$multiLine = <<<TEXT
Это многострочная строка.
Вторая строка.
Третья строка.
TEXT;

echo nl2br($multiLine) . '<br><br>';

$fish = 'Рыба';
$human = 'человек';

$fishStem = mb_substr($fish, 0, -1, 'UTF-8');
$fishInstrumental = mb_strtolower($fishStem, 'UTF-8') . 'ою';
$humanInstrumental = $human . 'ом';

echo $fish . ' ' . $fishInstrumental . ' сыта, а ' . $human . ' ' . $humanInstrumental;
