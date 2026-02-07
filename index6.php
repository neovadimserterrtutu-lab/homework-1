<?php

declare(strict_types=1);

/**
 * Генерирует расписание работы на несколько месяцев подряд.
 *
 * Первый «плановый» рабочий день — первое число стартового месяца.
 * Если он выпадает на выходные, рабочий день переносится на первый понедельник,
 * после каждого рабочего дня — два выходных (сутки через двое).
 *
 * @return array<string, array<int, bool>> Массив вида ['YYYY-MM' => [day => isWork]]
 */
function generateScheduleForMonths(int $startYear, int $startMonth, int $monthsCount): array
{
    $schedule = [];

    $startDate = new DateTimeImmutable(sprintf('%04d-%02d-01', $startYear, $startMonth));
    $endDate = $startDate
        ->modify('+' . $monthsCount . ' month')
        ->modify('-1 day');

    // Первый плановый рабочий день — первое число стартового месяца
    $candidate = $startDate;

    while (true) {
        // Если плановый день попал на выходные — сдвигаем до понедельника
        while ((int) $candidate->format('N') >= 6) { // 6 = Сб, 7 = Вс
            $candidate = $candidate->modify('+1 day');
        }

        if ($candidate > $endDate) {
            break;
        }

        $yearMonthKey = $candidate->format('Y-m');
        $day = (int) $candidate->format('j');

        if (!isset($schedule[$yearMonthKey])) {
            $schedule[$yearMonthKey] = [];
        }

        $schedule[$yearMonthKey][$day] = true;

        // Следующий плановый рабочий день — через 3 дня (1 рабочий + 2 выходных)
        $candidate = $candidate->modify('+3 day');
    }

    return $schedule;
}

/**
 * Печатает расписание для одного месяца.
 *
 * @param array<int, bool> $workDaysForMonth [day => isWork]
 */
function printScheduleForMonth(int $year, int $month, array $workDaysForMonth): void
{
    $monthNames = [
        1  => 'Январь',
        2  => 'Февраль',
        3  => 'Март',
        4  => 'Апрель',
        5  => 'Май',
        6  => 'Июнь',
        7  => 'Июль',
        8  => 'Август',
        9  => 'Сентябрь',
        10 => 'Октябрь',
        11 => 'Ноябрь',
        12 => 'Декабрь',
    ];

    $weekdayShort = [
        1 => 'Пн',
        2 => 'Вт',
        3 => 'Ср',
        4 => 'Чт',
        5 => 'Пт',
        6 => 'Сб',
        7 => 'Вс',
    ];

    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    $monthName = $monthNames[$month] ?? '';
    echo $monthName . ' ' . $year . PHP_EOL;

    for ($day = 1; $day <= $daysInMonth; $day++) {
        $date = new DateTimeImmutable(sprintf('%04d-%02d-%02d', $year, $month, $day));
        $weekdayNumber = (int) $date->format('N');

        $label = sprintf('%2d %s', $day, $weekdayShort[$weekdayNumber]);

        $isWork = $workDaysForMonth[$day] ?? false;
        if ($isWork) {
            // Отметка рабочего дня
            $label .= ' +';

            // Если хочешь зелёный цвет — раскомментируй:
            // $label = "\033[32m" . $label . "\033[0m";
        }

        echo $label . PHP_EOL;
    }

    echo PHP_EOL;
}

// -------------------- Основной код --------------------

// Базовый вариант: ничего не принимаем, берём текущий год и месяц
$year = (int) date('Y');
$month = (int) date('n');
$monthsCount = 1;

// Дополнительно: если переданы аргументы из консоли — используем их
// php index.php 2025 3 4  => с марта 2025, на 4 месяца вперёд
if (PHP_SAPI === 'cli') {
    global $argc, $argv;

    if ($argc >= 3) {
        $year = (int) $argv[1];
        $month = (int) $argv[2];
    }

    if ($argc >= 4) {
        $monthsCount = max(1, (int) $argv[3]);
    }
}

// Генерируем сквозное расписание
$schedule = generateScheduleForMonths($year, $month, $monthsCount);

// Печатаем по месяцам
$startDate = new DateTimeImmutable(sprintf('%04d-%02d-01', $year, $month));

for ($i = 0; $i < $monthsCount; $i++) {
    $current = $startDate->modify('+' . $i . ' month');
    $currentYear = (int) $current->format('Y');
    $currentMonth = (int) $current->format('n');
    $yearMonthKey = $current->format('Y-m');

    $workDaysForMonth = $schedule[$yearMonthKey] ?? [];
    printScheduleForMonth($currentYear, $currentMonth, $workDaysForMonth);
}
