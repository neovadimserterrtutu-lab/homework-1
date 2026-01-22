<?php

mb_internal_encoding('UTF-8');

// В реальном приложении сюда пришли бы данные от пользователя (форма и т.п.)
$firstName = 'иван';
$lastName = 'иванов';
$patronymic = 'иванович';

// --- Проверки с использованием составных логических операторов ---

// Проверка: есть ли хотя бы одно пустое поле (используем ИЛИ: ||)
$isAnyEmpty =
    $firstName === '' ||
    $lastName === '' ||
    $patronymic === '';

if ($isAnyEmpty) {
    echo 'Заполните имя, фамилию и отчество';
    exit;
}

// Проверка: все ли поля состоят только из букв кириллицы и дефиса (используем И: &&)
$pattern = '/^[\p{Cyrillic}-]+$/u';

$allMatchPattern =
    preg_match($pattern, $firstName) &&
    preg_match($pattern, $lastName) &&
    preg_match($pattern, $patronymic);

if (!$allMatchPattern) {
    echo 'Используйте только буквы кириллицы и дефис';
    exit;
}

// --- Функции и основная логика форматирования ФИО ---

/**
 * Делает первую букву строки заглавной, остальные — строчными (для кириллицы).
 */
function mbUcfirst(string $string): string
{
    $firstChar = mb_substr($string, 0, 1);
    $rest = mb_substr($string, 1);

    return mb_strtoupper($firstChar) . mb_strtolower($rest);
}

// Приводим каждую часть ФИО к виду: Первая буква — заглавная, остальные — строчные
$firstName = mbUcfirst($firstName);
$lastName = mbUcfirst($lastName);
$patronymic = mbUcfirst($patronymic);

// Полное имя: Фамилия Имя Отчество
$fullName = $lastName . ' ' . $firstName . ' ' . $patronymic;

// Фамилия и инициалы: Фамилия И.О.
$surnameAndInitials =
    $lastName . ' ' .
    mb_substr($firstName, 0, 1) . '.' .
    mb_substr($patronymic, 0, 1) . '.';

// Аббревиатура: ФИО (первые буквы)
$fio =
    mb_substr($lastName, 0, 1) .
    mb_substr($firstName, 0, 1) .
    mb_substr($patronymic, 0, 1);

echo "Полное имя: '$fullName'" . PHP_EOL;
echo "Фамилия и инициалы: '$surnameAndInitials'" . PHP_EOL;
echo "Аббревиатура: '$fio'" . PHP_EOL;
