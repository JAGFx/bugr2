<?php
    
    namespace App\Domain\Entry\ValueObject;
    
    class EntryBalance
    {
    
        public function __construct(
            private readonly float $total,
            private readonly float $totalSpent,
            private readonly float $totalForecast,
        ) {}
    
        public function getTotal(): float
        {
            return $this->total;
        }
    
        public function getTotalSpent(): float
        {
            return $this->totalSpent;
        }
    
        public function getTotalForecast(): float
        {
            return $this->totalForecast;
        }
    }