<?php
    
    namespace App\Domain\Budget\ValueObject;
    
    use App\Domain\Budget\Model\BudgetProgressTrait;

    final class BudgetValueObject
    {
        use BudgetProgressTrait;
        
        public function __construct(
            private readonly int $id,
            private readonly string $name,
            private readonly float $amount,
            private readonly bool $enable,
            private readonly float $progress,
        ) {}
        
        
        public function getId(): int
        {
            return $this->id;
        }
        
        public function getName(): string
        {
            return $this->name;
        }
        
        public function getAmount(): float
        {
            return $this->amount;
        }
        
        public function isEnable(): bool
        {
            return $this->enable;
        }
        
        public function getProgress(): float
        {
            return $this->progress;
        }
    }