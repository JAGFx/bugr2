<?php
    
    namespace App\Shared\Utils;
    
    class Statistics
    {
        public static function filterBy(array $statistics, string $property, mixed $value): array {
            return array_filter(
                $statistics,
                fn( array $statistic ): bool => $statistic[$property] === $value
            );
        }
        
        public static function sumOf(array $statistics, string $property): float
        {
            return array_reduce(
                $statistics,
                fn(float $currentSum, array $item): float => $currentSum + $item[ $property ],
                0.0
            );
        }
    }