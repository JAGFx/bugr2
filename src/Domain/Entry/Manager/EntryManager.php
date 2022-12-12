<?php
    
    namespace App\Domain\Entry\Manager;
    
    use App\Domain\Entry\Model\EntryTypeEnum;
    use App\Domain\Entry\Repository\EntryRepository;
    use App\Domain\Entry\ValueObject\EntryBalance;
    use App\Shared\Utils\Statistics;

    class EntryManager
    {
    
        public function __construct(
            private readonly EntryRepository $entryRepository
        ) {}
    
        public function balance(): EntryBalance {
            $data = $this->entryRepository
                ->balance()
                ->getQuery()
                ->getResult();
            
            $spentAmount = Statistics::filterBy( $data, 'type', EntryTypeEnum::TYPE_SPENT );
            $forecastAmount = Statistics::filterBy( $data, 'type', EntryTypeEnum::TYPE_FORECAST );
    
            $spentAmount = Statistics::sumOf( $spentAmount, 'sum' );
            $forecastAmount = Statistics::sumOf( $forecastAmount, 'sum' );
            
            return new EntryBalance($spentAmount + $forecastAmount, $spentAmount, $forecastAmount);
        }
    }