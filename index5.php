<?php

declare(strict_types=1);

const OPERATION_EXIT = 0;
const OPERATION_ADD = 1;
const OPERATION_DELETE = 2;
const OPERATION_PRINT = 3;

$operations = [
    OPERATION_EXIT => OPERATION_EXIT . '. Завершить программу.',
    OPERATION_ADD => OPERATION_ADD . '. Добавить товар в список покупок.',
    OPERATION_DELETE => OPERATION_DELETE . '. Удалить товар из списка покупок.',
    OPERATION_PRINT => OPERATION_PRINT . '. Отобразить список покупок.',
];

/**
 * Выводит список покупок.
 * Если список пуст — выводит сообщение об этом.
 */
function printItems(array $items): void
{
    if ($items === []) {
        echo 'Ваш список покупок пуст.' . PHP_EOL;
        return;
    }

    echo implode(PHP_EOL, $items) . PHP_EOL;
}

/**
 * Запрашивает строку у пользователя.
 */
function readLine(string $prompt): string
{
    echo $prompt;
    $line = fgets(STDIN);

    if ($line === false) {
        return '';
    }

    return trim($line);
}

/**
 * Выводит текущий список покупок, меню операций и возвращает выбранный номер операции.
 */
function askOperation(array $items, array $operations): int
{
    do {
        // Очистка экрана (*nix). Для Windows можно использовать 'cls'
        system('clear');
        // system('cls');

        if ($items !== []) {
            echo 'Ваш список покупок:' . PHP_EOL;
        }

        printItems($items);

        echo 'Выберите операцию для выполнения:' . PHP_EOL;
        echo implode(PHP_EOL, $operations) . PHP_EOL;

        $operationInput = readLine('> ');
        $operationNumber = (int) $operationInput;

        if (!array_key_exists($operationNumber, $operations)) {
            echo '!!! Неизвестный номер операции, повторите попытку.' . PHP_EOL;
            echo 'Нажмите Enter для продолжения';
            fgets(STDIN);
        }
    } while (!array_key_exists($operationNumber, $operations));

    return $operationNumber;
}

/**
 * Добавляет товар в список покупок.
 */
function addItem(array &$items): void
{
    $itemName = readLine('Введите название товара для добавления в список:' . PHP_EOL . '> ');

    if ($itemName === '') {
        echo 'Пустое название товара не будет добавлено.' . PHP_EOL;
        echo 'Нажмите Enter для продолжения';
        fgets(STDIN);
        return;
    }

    $items[] = $itemName;
}

/**
 * Удаляет товар из списка покупок.
 */
function deleteItem(array &$items): void
{
    if ($items === []) {
        echo 'Список покупок пуст, удалять нечего.' . PHP_EOL;
        echo 'Нажмите Enter для продолжения';
        fgets(STDIN);
        return;
    }

    echo 'Текущий список покупок:' . PHP_EOL;
    printItems($items);

    $itemName = readLine('Введите название товара для удаления из списка:' . PHP_EOL . '> ');

    if (!in_array($itemName, $items, true)) {
        echo 'Товар с таким названием не найден в списке.' . PHP_EOL;
        echo 'Нажмите Enter для продолжения';
        fgets(STDIN);
        return;
    }

    while (($key = array_search($itemName, $items, true)) !== false) {
        unset($items[$key]);
    }

    echo 'Товар удалён.' . PHP_EOL;
    echo 'Нажмите Enter для продолжения';
    fgets(STDIN);
}

/**
 * Выводит список покупок с количеством и ждёт нажатия Enter.
 */
function printItemsWithSummary(array $items): void
{
    echo 'Ваш список покупок:' . PHP_EOL;
    printItems($items);

    echo 'Всего ' . count($items) . ' позиций.' . PHP_EOL;
    echo 'Нажмите Enter для продолжения';
    fgets(STDIN);
}

// ---- Основной код ----

$items = [];

do {
    $operationNumber = askOperation($items, $operations);

    echo 'Выбрана операция: ' . $operations[$operationNumber] . PHP_EOL;

    switch ($operationNumber) {
        case OPERATION_ADD:
            addItem($items);
            break;

        case OPERATION_DELETE:
            deleteItem($items);
            break;

        case OPERATION_PRINT:
            printItemsWithSummary($items);
            break;
    }

    echo PHP_EOL . '-----' . PHP_EOL;
} while ($operationNumber > OPERATION_EXIT);

echo 'Программа завершена' . PHP_EOL;
