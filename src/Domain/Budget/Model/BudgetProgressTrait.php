<?php
    
    namespace App\Domain\Budget\Model;
    
    trait BudgetProgressTrait
    {
        abstract public function getProgress(): float;
        abstract public function getAmount(): float;
        
        public function getRelativeProgress(): int
        {
            if ($this->getProgress() <= 0 || $this->getAmount() === 0.0) {
                return 0;
            }
            
            return min($this->getProgress() * 100 / $this->getAmount(), 100);
        }
        
        public function getStatus(): BudgetStatusEnum
        {
            $relativeProgress = $this->getRelativeProgress();
            
            return BudgetStatusEnum::statusByProgress($relativeProgress);
        }
    }