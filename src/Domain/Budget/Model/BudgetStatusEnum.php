<?php
    
    namespace App\Domain\Budget\Model;
    
    enum BudgetStatusEnum: string
    {
        case STATUS_HAS_MORE_SAVE = 'has-more-save';
        case STATUS_IS_IN_PROGRESS = 'is-in-progress';
        case STATUS_IS_SOON_FULL = 'is-soon-full';
        case STATUS_IS_FULL = 'is-full';
        case STATUS_IS_OVER = 'is-over';
        
        public static function statusByProgress( int $relativeProgress ): self {
            if( $relativeProgress === 0 ){
                return self::STATUS_HAS_MORE_SAVE;
            }
    
            if( $relativeProgress >= 80 && $relativeProgress < 100 ){
                return self::STATUS_IS_SOON_FULL;
            }
    
            if( $relativeProgress === 100 ){
                return self::STATUS_IS_FULL;
            }
    
            if( $relativeProgress > 100 ){
                return self::STATUS_IS_OVER;
            }
    
            return self::STATUS_IS_IN_PROGRESS;
        }
    }
