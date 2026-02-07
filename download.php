<?php

declare(strict_types=1);

$text = isset($_GET['text']) ? (string) $_GET['text'] : 'Текст по умолчанию';

header('Content-Type: text/plain; charset=UTF-8');
header('Content-Disposition: attachment; filename="text.txt"');
header('Content-Length: ' . strlen($text));

echo $text;
