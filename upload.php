<?php

declare(strict_types=1);

// Если имя файла не передано — редирект обратно на форму
if (empty($_POST['file_name'])) {
    header('Location: form.html');
    exit;
}

// Если файл не передан или есть ошибка загрузки — редирект обратно
if (
    !isset($_FILES['content']) ||
    $_FILES['content']['error'] !== UPLOAD_ERR_OK
) {
    header('Location: form.html');
    exit;
}

$fileName = (string) $_POST['file_name'];
$tmpPath = $_FILES['content']['tmp_name'];

// Папка для сохранения
$uploadDir = __DIR__ . '/upload';

// Создаём папку, если её нет
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Полный путь к сохраняемому файлу
$destinationPath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;

// Перемещаем загруженный файл из временной директории
if (!move_uploaded_file($tmpPath, $destinationPath)) {
    echo 'Ошибка сохранения файла.';
    exit;
}

// Получаем полный путь и размер файла
$realPath = realpath($destinationPath);
$fileSize = filesize($destinationPath);

echo 'Файл успешно сохранён.' . '<br>';
echo 'Полный путь: ' . htmlspecialchars((string) $realPath, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '<br>';
echo 'Размер файла: ' . $fileSize . ' байт';
