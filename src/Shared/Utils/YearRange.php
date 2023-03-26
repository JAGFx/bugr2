<?php

namespace App\Shared\Utils;

use DateTimeImmutable;

class YearRange
{
    public static function current(): int
    {
        return (int) date('Y');
    }

    /**
     * @return int[]
     */
    public static function range(int $from, int $to): array
    {
        return range($from, $to);
    }

    /**
     * @return int[]
     */
    public static function offset(?int $from = null, int $offset = 0): array
    {
        $from ??= self::current();

        return $offset > 0
            ? self::range($from, $from + $offset)
            : self::range($from + $offset, $from);
    }

    public static function firstDayOf(?int $year = null): DateTimeImmutable
    {
        $year ??= self::current();

        return (new DateTimeImmutable())
            ->setDate($year, 1, 1)
            ->setTime(0, 0);
    }

    public static function lastDayOf(?int $year = null): DateTimeImmutable
    {
        $year ??= self::current();

        return (new DateTimeImmutable())
            ->setDate($year, 12, 31)
            ->setTime(23, 59, 59);
    }
}
