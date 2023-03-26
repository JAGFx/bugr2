<?php

namespace App\Infrastructure\LeagueCsv;

use League\Csv\Reader;

final class CsvExtract
{
    public static function extractRecords(string $path): array
    {
        $reader = Reader::createFromPath($path);

        $reader->setHeaderOffset(0);

        $header  = $reader->getHeader();
        $records = [];

        foreach ($reader->getRecords($header) as $record) {
            $records[] = $record;
        }

        return $records;
    }
}
