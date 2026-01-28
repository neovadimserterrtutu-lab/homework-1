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
 * Читает строку из STDIN с выводом приглашения.
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
 * Выводит список покупок.
 */
function printItems(array $items): void
{
    if (count($items) > 0) {
        echo 'Ваш список покупок: ' . PHP_EOL;
        echo implode(PHP_EOL, $items) . PHP_EOL;
    } else {
        echo 'Ваш список покупок пуст.' . PHP_EOL;
    }
}

/**
 * Показывает список, меню операций и возвращает корректный номер операции.
 */
function askOperation(array $items, array $operations): int
{
    do {
        system('clear');
        // system('cls'); // для Windows

        printItems($items);

        echo 'Выберите операцию для выполнения: ' . PHP_EOL;
        // (если хочешь, можешь здесь дополнительно убрать пункт удаления,
        // когда список пуст — сейчас оставлено как в исходном варианте)
        echo implode(PHP_EOL, $operations) . PHP_EOL;

        $operationInput = readLine('> ');
        $operationNumber = (int) $operationInput;

        if (!array_key_exists($operationNumber, $operations)) {
            system('clear');
            // system('cls');

            echo '!!! Неизвестный номер операции, повторите попытку.' . PHP_EOL;
            echo 'Нажмите enter для продолжения';
            fgets(STDIN);
        }
    } while (!array_key_exists($operationNumber, $operations));

    return $operationNumber;
}

/**
 * Реализация операции добавления товара.
 */
function addItem(array &$items): void
{
    $itemName = readLine("Введение название товара для добавления в список:\n> ");
    if ($itemName === '') {
        echo 'Пустое название товара не будет добавлено.' . PHP_EOL;
        echo 'Нажмите enter для продолжения';
        fgets(STDIN);
        return;
    }

    $items[] = $itemName;
}

/**
 * Реализация операции удаления товара.
 */
function deleteItem(array &$items): void
{
    if (count($items) === 0) {
        echo 'Список покупок пуст, удалять нечего.' . PHP_EOL;
        echo 'Нажмите enter для продолжения';
        fgets(STDIN);
        return;
    }

    echo 'Текущий список покупок:' . PHP_EOL;
    echo 'Список покупок: ' . PHP_EOL;
    echo implode(PHP_EOL, $items) . PHP_EOL;

    $itemName = readLine('Введение название товара для удаления из списка:' . PHP_EOL . '> ');

    if (in_array($itemName, $items, true) !== false) {
        while (($key = array_search($itemName, $items, true)) !== false) {
            unset($items[$key]);
        }
        echo 'Товар удалён.' . PHP_EOL;
    } else {
        echo 'Товар с таким названием не найден.' . PHP_EOL;
    }

    echo 'Нажмите enter для продолжения';
    fgets(STDIN);
}

/**
 * Реализация операции печати списка с подсчётом позиций и паузой.
 */
function printItemsWithSummary(array $items): void
{
    echo 'Ваш список покупок: ' . PHP_EOL;
    echo implode(PHP_EOL, $items) . PHP_EOL;
    echo 'Всего ' . count($items) . ' позиций. ' . PHP_EOL;
    echo 'Нажмите enter для продолжения';
    fgets(STDIN);
}

// ---------------- ОСНОВНОЙ КОД ----------------

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

    echo PHP_EOL . ' ----- ' . PHP_EOL;
} while ($operationNumber > OPERATION_EXIT);

echo 'Программа завершена' . PHP_EOL;
