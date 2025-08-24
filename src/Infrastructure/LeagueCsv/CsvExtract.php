<?php

namespace App\Infrastructure\LeagueCsv;

use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\SyntaxError;
use League\Csv\UnavailableStream;

final class CsvExtract
{
    /**
     * @return list<array>
     *
     * @throws UnavailableStream
     * @throws SyntaxError
     * @throws Exception
     */
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
