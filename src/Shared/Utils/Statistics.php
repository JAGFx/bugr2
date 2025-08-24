<?php

namespace App\Shared\Utils;

class Statistics
{
    /**
     * @param array<string, mixed> $statistics
     *
     * @return array<string, mixed>
     */
    public static function filterBy(array $statistics, string $property, mixed $value, bool $negate = false): array
    {
        return array_filter(
            $statistics,
            static fn (array $statistic): bool => ($negate) // @phpstan-ignore-line
                ? $statistic[$property] !== $value
                : $statistic[$property] === $value
        );
    }

    /**
     * @param array<string, mixed> $statistics
     */
    public static function sumOf(array $statistics, string $property): float
    {
        return array_reduce(
            $statistics,
            static function (float $currentSum, array $item) use ($property): float {
                /** @var array<float> $currentItem */
                $currentItem = $item;

                return $currentSum + ($currentItem[$property] ?? 0.0);
            },
            0.0
        );
    }
}
