<?php
    
    namespace App\Domain\Entry\Model;
    
    enum EntryTypeEnum: string
    {
        case TYPE_FORECAST = 'type-forecast';
        case TYPE_SPENT    = 'type-spent';
    }
